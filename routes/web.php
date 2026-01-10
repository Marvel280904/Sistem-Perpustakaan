<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Member\MemberController;

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
    
        // Loan Management
        Route::get('/loans/create', [AdminController::class, 'createLoan'])->name('loans.create');
        Route::post('/loans', [AdminController::class, 'storeLoan'])->name('loans.store');
        Route::post('/loans/{loan}/return', [AdminController::class, 'returnLoan'])->name('loans.return');
        Route::delete('/loans/{loan}', [AdminController::class, 'deleteLoan'])->name('loans.delete');
    });
    
    // Member Routes  
    Route::prefix('member')->name('member.')->group(function () {
        // Dashboard - Book Collection
        Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    });
});