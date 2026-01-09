<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .input-focus-effect:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        
        .btn-hover-effect:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4 animate-fade-in">
    <div class="max-w-6xl w-full flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12">
        
        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 max-w-md animate-slide-up" style="animation-delay: 0.2s">
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100">
                
                <!-- Header Form -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa fa-book text-white text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">Library Management System</h1>
                    <p class="text-gray-500 mt-2">Sign in to access your library</p>
                </div>
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Email Field -->
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
                    
                    <!-- Password Field -->
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
                            <button 
                                type="button" 
                                class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none"
                                onclick="togglePassword()"
                            >
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                            @error('password')
                                <div class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl btn-hover-effect"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                    
                    <!-- Demo Accounts Section -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-500 mb-4">
                                <i class="fas fa-vial mr-1"></i>Demo Accounts (for testing):
                            </h3>
                            
                            <div class="space-y-3">
                                <!-- Admin Account -->
                                <div 
                                    class="demo-account bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-3 cursor-pointer transition-all duration-300 hover:shadow-md"
                                    onclick="fillDemo('admin')"
                                >
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
                                        <i class="fas fa-mouse-pointer text-blue-500 text-sm"></i>
                                    </div>
                                </div>
                                
                                <!-- Member Account -->
                                <div 
                                    class="demo-account bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-3 cursor-pointer transition-all duration-300 hover:shadow-md"
                                    onclick="fillDemo('member')"
                                >
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <div class="text-left">
                                                <div class="font-medium text-gray-800">Member Account</div>
                                                <div class="text-xs text-gray-600">budi@example.com / password123</div>
                                            </div>
                                        </div>
                                        <i class="fas fa-mouse-pointer text-green-500 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <!-- JavaScript untuk interaksi -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Auto-fill demo accounts
        function fillDemo(type) {
            const accounts = {
                'admin': {
                    email: 'admin@perpustakaan.com',
                    password: 'password123'
                },
                'member': {
                    email: 'budi@example.com',
                    password: 'password123'
                }
            };
            
            const account = accounts[type];
            if (account) {
                document.getElementById('email').value = account.email;
                document.getElementById('password').value = account.password;
                
                // Animasi feedback
                const demoElement = event.currentTarget;
                demoElement.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    demoElement.style.transform = 'scale(1)';
                }, 150);
                
                // Focus pada submit button
                setTimeout(() => {
                    document.querySelector('button[type="submit"]').focus();
                }, 200);
            }
        }
        
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
</body>
</html>