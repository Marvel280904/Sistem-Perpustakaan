<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display member dashboard (Book Catalog Only) - PUBLIC ACCESS
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');
        
        // Query books with search filter
        $booksQuery = Book::query();
        
        if ($search) {
            $booksQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        $books = $booksQuery->orderBy('title', 'asc')->get();
        
        return view('member.dashboard', compact('books', 'search'));
    }
}