<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Member\MemberController;

// Public Routes
// Redirect root langsung ke member dashboard (tanpa login)
Route::get('/', function () {
    return redirect()->route('member.dashboard');
});

// Public member dashboard (tanpa auth)
Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');

// Authentication Routes (hanya untuk admin)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected Routes (hanya untuk admin yang login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard admin langsung
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Loan Management
        Route::get('/loans/create', [AdminController::class, 'createLoan'])->name('loans.create');
        Route::post('/loans', [AdminController::class, 'storeLoan'])->name('loans.store');
        Route::post('/loans/{loan}/return', [AdminController::class, 'returnLoan'])->name('loans.return');
        Route::delete('/loans/{loan}', [AdminController::class, 'deleteLoan'])->name('loans.delete');
    });
});