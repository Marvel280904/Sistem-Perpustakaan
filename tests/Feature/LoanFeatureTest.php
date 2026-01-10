<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanFeatureTest extends TestCase
{
    // Membersihkan database setiap kali test berjalan agar data selalu fresh
    use RefreshDatabase;

    /* Membuat Account untuk Login */
    private function loginAsUser()
    {
        // Account Admin
        $user = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        return $user;
    }


    /* Skenario 1: Test Membuat Peminjaman (Stok Berkurang) */
    public function test_can_create_loan_and_reduce_stock()
    {
        // Login
        $user = $this->loginAsUser();

        // Dummy Buku
        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Tester',
            'isbn' => 'TEST-001',
            'stock' => 5
        ]);

        // Data Input Form
        $formData = [
            'member_name' => 'Peminjam Test',
            'borrower_phone' => '08123456789',
            'borrower_email' => 'peminjam@mail.com',
            'book_ids' => [$book->id],
            'loan_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        // Eksekusi: User POST ke route simpan
        $response = $this->actingAs($user)
                         ->post(route('admin.loans.store'), $formData);

        // Cek Hasil
        // Harus redirect kembali ke dashboard
        $response->assertRedirect(route('admin.dashboard'));
        // Harus ada pesan sukses di session
        $response->assertSessionHas('success');

        // Cek Database: Stok buku harus jadi 4 (Berkurang 1)
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 4
        ]);

        // Cek Database: Data Loan harus tersimpan
        $this->assertDatabaseHas('loans', [
            'book_id' => $book->id,
            'member_name' => 'Peminjam Test',
            'status' => 'active'
        ]);
    }

    /* Skenario 2: Test Gagal Pinjam Jika Stok Kosong */
    public function test_cannot_create_loan_if_stock_is_empty()
    {
        $user = $this->loginAsUser();
        
        // Buat Buku Stok 0 (Habis)
        $book = Book::create([
            'title' => 'Empty Book',
            'author' => 'Tester',
            'isbn' => 'TEST-EMPTY',
            'stock' => 0
        ]);

        $formData = [
            'member_name' => 'Sad Member',
            'borrower_phone' => '08123456789',
            'borrower_email' => 'sad@mail.com',
            'book_ids' => [$book->id],
            'loan_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        // Eksekusi
        $response = $this->actingAs($user)
                         ->post(route('admin.loans.store'), $formData);

        // Cek Hasil:
        // Session harus punya error (karena stok habis)
        $response->assertSessionHasErrors(['book_ids']);
        
        // Cek Database: TIDAK boleh ada loan baru untuk user ini
        $this->assertDatabaseMissing('loans', [
            'member_name' => 'Sad Member'
        ]);
    }

    /* Skenario 3: Test Mengembalikan Buku (Return) & Stok Bertambah */
    public function test_can_return_loan_and_restore_stock()
    {
        $user = $this->loginAsUser();
        
        // Siapkan Buku Stok 2
        $book = Book::create([
            'title' => 'Return Book',
            'author' => 'Tester',
            'isbn' => 'TEST-RET',
            'stock' => 2
        ]);

        // Siapkan Data Peminjaman Aktif (Insert Manual ke DB)
        $loan = Loan::create([
            'book_id' => $book->id,
            'member_name' => 'Si Pengembali',
            'borrower_phone' => '08111',
            'borrower_email' => 'return@mail.com',
            'loan_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'active'
        ]);

        // Eksekusi: User menekan tombol Return (POST request)
        $response = $this->actingAs($user)
                         ->post(route('admin.loans.return', $loan->id));

        // Cek Hasil:
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        // Cek Database: Status loan harus berubah jadi 'returned'
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned'
        ]);

        // Cek Database: Stok buku harus nambah jadi 3 (2 + 1)
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 3
        ]);
    }
}