<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Member\MemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('member.dashboard');
        }
    })->name('dashboard');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Search borrowing records
        Route::get('/search-borrowings', [AdminController::class, 'searchBorrowings'])->name('search.borrowings');
        
        // Tambahkan route admin lainnya di sini
    });
    
    // Member Routes  
    Route::prefix('member')->name('member.')->group(function () {
        // Dashboard - Book Collection
        Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
        
        // Borrow a book
        Route::post('/borrow/{book}', [MemberController::class, 'borrowBook'])->name('borrow');
        
        // My Loans History
        Route::get('/my-loans', [MemberController::class, 'myLoans'])->name('my-loans');
        
        // Return a book
        Route::post('/return/{loan}', [MemberController::class, 'returnBook'])->name('return');
        
        // Tambahkan route member lainnya di sini
    });
});

// Demo info (for testing)
Route::get('/demo-info', [LoginController::class, 'showDemoInfo'])->name('demo.info');