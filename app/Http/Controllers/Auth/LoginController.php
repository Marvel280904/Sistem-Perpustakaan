<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek credentials
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect langsung ke admin dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // Jika login gagal
        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Kembali ke member dashboard
    }
    
}