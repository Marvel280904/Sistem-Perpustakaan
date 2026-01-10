<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* Cek User Admin? */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin only.');
        }
    }

    /* Display Admin Dashboard */
    public function dashboard()
    {
        $this->checkAdmin();

        // Total Books
        $totalBooks = Book::count();

        // Current Borrowed 
        $currentlyBorrowed = Loan::where('status', 'active')->count();
        
        // Unique borrowers
        $activeMembers = Loan::where('loan_date', '>=', now()->subDays(30)) 
            ->select('member_name', 'borrower_email') 
            ->distinct() 
            ->get() 
            ->count();

        // Books for collection section
        $bookCollection = Book::orderBy('title', 'asc')->get();

        // Recent borrowing records with pagination
        $loans = Loan::with('book') 
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'currentlyBorrowed',
            'activeMembers',
            'bookCollection',
            'loans'
        ));
    }

    /* Show Add Loan Modal */
    public function createLoan()
    {
        $this->checkAdmin();
        
        $availableBooks = Book::where('stock', '>', 0)->get();
        
        return view('admin.create-loan', compact('availableBooks'));
    }

    /* Store multiple book loans for one borrower */
    public function storeLoan(Request $request)
    {
        $this->checkAdmin();
        
        $request->validate([
            'member_name' => 'required|string|max:255',
            'borrower_phone' => 'required|string|max:20', 
            'borrower_email' => 'required|email|max:255',
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
        ]);
        
        // Cek stock untuk setiap buku
        $errors = [];
        $selectedBooks = Book::whereIn('id', $request->book_ids)->get();
        
        foreach ($selectedBooks as $book) {
            if ($book->stock <= 0) {
                $errors[] = "Book '{$book->title}' is out of stock!";
            }
        }
        
        if (!empty($errors)) {
            return back()
                ->withErrors(['book_ids' => implode(' ', $errors)])
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $loansCreated = [];
            
            foreach ($request->book_ids as $bookId) {
                $book = Book::find($bookId);
                
                // Create loan record
                $loan = Loan::create([
                    'book_id' => $bookId,
                    'member_name' => $request->member_name,
                    'borrower_phone' => $request->borrower_phone,
                    'borrower_email' => $request->borrower_email,
                    'loan_date' => $request->loan_date,
                    'due_date' => $request->due_date,
                    'status' => 'active',
                ]);
                
                // Decrease book stock
                $book->decrement('stock');
                
                $loansCreated[] = $loan;
            }
            
            DB::commit();
            
            $bookCount = count($request->book_ids);
            $bookTitles = $selectedBooks->pluck('title')->implode(', ');
            
            return redirect()->route('admin.dashboard')
                ->with('success', "Successfully recorded {$bookCount} loan(s) for {$request->member_name}. Books: {$bookTitles}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to record loans: ' . $e->getMessage())
                ->withInput();
        }
    }

    /* Mark loan as returned */
    public function returnLoan($loanId)
    {
        $this->checkAdmin();
        
        try {
            $loan = Loan::with('book')
                ->where('id', $loanId)
                ->where('status', 'active')
                ->firstOrFail();
            
            DB::beginTransaction();
            
            // Update loan status
            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);
            
            // Increase book stock
            $loan->book->increment('stock');
            
            DB::commit();
            
            return redirect()->route('admin.dashboard')
                ->with('success', "Book '{$loan->book->title}' successfully marked as returned.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Kembalikan JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as returned: ' . $e->getMessage()
            ], 500);
        }
    }

}