<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Check if user is admin
     */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin only.');
        }
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $this->checkAdmin();

        // Total statistics
        $totalBooks = Book::sum('stock');
        $uniqueTitles = Book::count();
        
        $currentlyBorrowed = Loan::where('status', 'active')->count();
        
        // Unique members who have borrowed books (last 30 days)
        $activeMembers = Loan::where('loan_date', '>=', now()->subDays(30))
            ->distinct('user_id')
            ->count('user_id');

        // Recent books (limit 8 for cards)
        $recentBooks = Book::latest()->take(8)->get();

        // Recent borrowing records with pagination
        $loans = Loan::with(['book', 'user'])
            ->latest()
            ->paginate(10);

        // Books for collection section (for slider/cards)
        $bookCollection = Book::latest()->take(12)->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'uniqueTitles',
            'currentlyBorrowed',
            'activeMembers',
            'recentBooks',
            'loans',
            'bookCollection'
        ));
    }

    /**
     * Search borrowing records
     */
    public function searchBorrowings(Request $request)
    {
        $search = $request->input('search');
        
        $loans = Loan::with(['book', 'user'])
            ->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', [
            'loans' => $loans,
            'search' => $search,
            // Include other data needed for dashboard
            'totalBooks' => Book::sum('stock'),
            'uniqueTitles' => Book::count(),
            'currentlyBorrowed' => Loan::where('status', 'active')->count(),
            'activeMembers' => Loan::where('loan_date', '>=', now()->subDays(30))
                ->distinct('user_id')
                ->count('user_id'),
            'recentBooks' => Book::latest()->take(8)->get(),
            'bookCollection' => Book::latest()->take(12)->get(),
        ]);
    }

    /**
     * Get books by status (available/unavailable)
     */
    public function getBooksByStatus($status)
    {
        if ($status === 'available') {
            $books = Book::where('stock', '>', 0)->paginate(12);
        } elseif ($status === 'unavailable') {
            $books = Book::where('stock', '<=', 0)->paginate(12);
        } else {
            $books = Book::paginate(12);
        }

        return response()->json([
            'books' => $books,
            'status' => $status
        ]);
    }

    /**
     * Get borrowing statistics for chart
     */
    public function getBorrowingStats()
    {
        // Last 6 months borrowing statistics
        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Loan::whereYear('loan_date', $month->year)
                ->whereMonth('loan_date', $month->month)
                ->count();
            
            $stats[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        return response()->json($stats);
    }
}