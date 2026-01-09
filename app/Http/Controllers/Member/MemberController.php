<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Check if user is member
     */
    private function checkMember()
    {
        if (!Auth::check() || Auth::user()->role !== 'member') {
            abort(403, 'Unauthorized access. Member only.');
        }
    }
    
    /**
     * Display member dashboard
     */
    public function dashboard(Request $request)
    {
        $this->checkMember();
        
        $search = $request->input('search');
        
        // Query books with search filter
        $books = Book::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->orderBy('title')
            ->paginate(12);
        
        return view('member.dashboard', compact('books', 'search'));
    }
    
    /**
     * Borrow a book
     */
    public function borrowBook(Request $request, $bookId)
    {
        $this->checkMember();
        
        $user = Auth::user();
        $book = Book::findOrFail($bookId);
        
        // Validation checks
        $errors = [];
        
        // 1. Cek stock buku
        if ($book->stock <= 0) {
            $errors[] = 'This book is currently out of stock.';
        }
        
        // 2. Cek apakah user sudah meminjam buku yang sama (active loan)
        $alreadyBorrowed = Loan::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->where('status', 'active')
            ->exists();
            
        if ($alreadyBorrowed) {
            $errors[] = 'You have already borrowed this book. Please return it first.';
        }
        
        // 3. Cek maksimal peminjaman (misal maksimal 3 buku)
        $activeLoansCount = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
            
        if ($activeLoansCount >= 3) {
            $errors[] = 'You have reached the maximum borrowing limit (3 books). Please return some books first.';
        }
        
        // Jika ada error, kembalikan dengan error
        if (!empty($errors)) {
            return redirect()->route('member.dashboard')
                ->with('error', implode(' ', $errors));
        }
        
        // Mulai transaction untuk menjaga konsistensi data
        DB::beginTransaction();
        
        try {
            // 1. Buat record peminjaman
            $loan = Loan::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
                'loan_date' => now(),
                'due_date' => now()->addDays(7), // 7 hari dari sekarang
                'status' => 'active',
            ]);
            
            // 2. Kurangi stock buku
            $book->decrement('stock');
            
            // 3. Commit transaction
            DB::commit();
            
            return redirect()->route('member.dashboard')
                ->with('success', "Successfully borrowed '{$book->title}'. Due date: " . $loan->due_date->format('Y-m-d'));
                
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();
            
            return redirect()->route('member.dashboard')
                ->with('error', 'Failed to borrow book. Please try again.');
        }
    }
    
    /**
     * Show user's loan history
     */
    public function myLoans()
    {
        $this->checkMember();
        
        $user = Auth::user();
        
        $loans = Loan::with('book')
            ->where('user_id', $user->id)
            ->orderBy('loan_date', 'desc')
            ->paginate(10);
        
        return view('member.my-loans', compact('loans'));
    }
    
    /**
     * Return a book
     */
    public function returnBook($loanId)
    {
        $this->checkMember();
        
        $user = Auth::user();
        $loan = Loan::where('id', $loanId)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // 1. Update loan status
            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);
            
            // 2. Tambah stock buku
            $loan->book->increment('stock');
            
            DB::commit();
            
            return redirect()->route('member.my-loans')
                ->with('success', "Book '{$loan->book->title}' has been returned successfully.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('member.my-loans')
                ->with('error', 'Failed to return book. Please try again.');
        }
    }
}