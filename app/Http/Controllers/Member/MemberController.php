<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Display member dashboard (Book Catalog Only)
     */
    public function dashboard(Request $request)
    {
        $this->checkMember();
        
        $user = Auth::user();
        $search = $request->input('search');
        
        // Query books with search filter
        $books = Book::orderBy('title', 'asc')->get();
        
        return view('member.dashboard', compact('books', 'search', 'user'));
    }
}