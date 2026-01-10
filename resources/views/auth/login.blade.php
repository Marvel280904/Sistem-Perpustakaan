@extends('layout.app')

@section('title', 'Member Dashboard - Library Management System')
@section('body-class', 'bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen animate-fade-in')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
@endpush

@section('content')
    <div class="w-full flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12">
        
        <div class="w-full lg:w-1/2 max-w-md animate-slide-up">
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100">
                
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa fa-book text-white text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">Library Management System</h1>
                    <p class="text-gray-500 mt-2">Sign in to access your library</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="email">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 input-focus-effect"
                                placeholder="Enter your email"
                                required
                                autocomplete="off"
                                autofocus
                            >
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                            @error('email')
                                <div class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="password">
                            <i class="fas fa-key mr-2 text-blue-500"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 input-focus-effect"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            @error('password')
                                <div class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                    
                    <div class="mt-8 pt-2 border-t border-gray-200">
                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-500 mb-4">
                                <i class="fas fa-vial mr-1"></i>Demo Accounts (for testing):
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="demo-account bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-3 cursor-pointer transition-all duration-300 hover:shadow-md">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-crown text-white text-xs"></i>
                                            </div>
                                            <div class="text-left">
                                                <div class="font-medium text-gray-800">Admin Account</div>
                                                <div class="text-xs text-gray-600">admin@perpustakaan.com / password123</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>   
        // Form submission dengan animasi loading
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Animasi loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing in...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('hover:from-blue-700', 'hover:to-indigo-700');
            
            // Reset setelah 5 detik jika ada masalah
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.add('hover:from-blue-700', 'hover:to-indigo-700');
            }, 5000);
        });
    </script>
@endsection