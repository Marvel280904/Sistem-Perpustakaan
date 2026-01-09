<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {  
        // Admin user
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Member users (3 orang)
        $members = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081111111111',
                'role' => 'member',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'phone' => '082222222222',
                'role' => 'member',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@example.com',
                'password' => Hash::make('password123'),
                'phone' => '083333333333',
                'role' => 'member',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($members as $member) {
            User::create($member);
        }

        // Books (10)
        $books = [
            [
                'title' => 'Laravel: Up and Running',
                'author' => 'Matt Stauffer',
                'isbn' => '978-1492041219',
                'stock' => 5,
            ],
            [
                'title' => 'Eloquent JavaScript',
                'author' => 'Marijn Haverbeke',
                'isbn' => '978-1593279509',
                'stock' => 3,
            ],
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'stock' => 4,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas & Andrew Hunt',
                'isbn' => '978-0201616224',
                'stock' => 2,
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma et al.',
                'isbn' => '978-0201633610',
                'stock' => 3,
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '978-9794331234',
                'stock' => 6,
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '978-9793062797',
                'stock' => 7,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '978-0735211292',
                'stock' => 4,
            ],
            [
                'title' => 'Deep Work: Rules for Focused Success in a Distracted World',
                'author' => 'Cal Newport',
                'isbn' => '978-1455586691',
                'stock' => 3,
            ],
            [
                'title' => 'The Psychology of Money',
                'author' => 'Morgan Housel',
                'isbn' => '978-0857197689',
                'stock' => 5,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }


        
        /* OPTIONAL: Sample Loans (peminjaman)
        \App\Models\Loan::create([
            'book_id' => 1,
            'user_id' => 2, // Budi meminjam
            'loan_date' => now()->subDays(3),
            'due_date' => now()->addDays(4), // 7 hari dari pinjam
            'status' => 'active',
        ]);

        \App\Models\Loan::create([
            'book_id' => 3,
            'user_id' => 3, // Siti meminjam
            'loan_date' => now()->subDays(10),
            'due_date' => now()->subDays(3), // sudah lewat
            'return_date' => now()->subDays(2),
            'status' => 'returned',
        ]);
        */
    }
}