<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Library Management System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
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
        
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen animate-fade-in">
    
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h1>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <i class="fas fa-user mr-1"></i>Member
                        </span>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- My Loans Button -->
                    <a href="{{ route('member.my-loans') }}" class="px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium rounded-xl transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-book-reader"></i>
                        <span>My Loans</span>
                        <span class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ Auth::user()->loans()->where('status', 'active')->count() }}
                        </span>
                    </a>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to logout?')" 
                                class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Banner -->
        <div class="mb-8 animate-slide-up">
            <div class="gradient-bg rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}! 📚</h2>
                        <p class="text-indigo-100 mt-2">Browse and borrow books from our library collection.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold">{{ Auth::user()->loans()->where('status', 'active')->count() }}</div>
                                <div class="text-sm text-indigo-200">Active Loans</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold">{{ Auth::user()->loans()->where('status', 'returned')->count() }}</div>
                                <div class="text-sm text-indigo-200">Books Returned</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-8 animate-slide-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Find Books</h3>
                <form method="GET" action="{{ route('member.dashboard') }}">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                            placeholder="Search by title, author, or ISBN..."
                        >
                        <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-arrow-right text-blue-600 hover:text-blue-800 transition-colors duration-200"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Book Collection -->
        <div class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Book Collection</h3>
                <p class="text-gray-600 mb-6">Browse and borrow books from our library</p>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <!-- Book Collection Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($books as $book)
                    @php
                        // Cek apakah user sudah meminjam buku ini (active loan)
                        $hasBorrowed = auth()->user()->loans()
                            ->where('book_id', $book->id)
                            ->where('status', 'active')
                            ->exists();
                            
                        // Cek apakah buku tersedia
                        $isAvailable = $book->stock > 0;
                        
                        // Tentukan apakah button disabled
                        $isDisabled = $hasBorrowed || !$isAvailable;
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 card-hover">
                        <div class="p-5">
                            <!-- Book Icon -->
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                            
                            <!-- Book Info -->
                            <h4 class="font-bold text-gray-800 text-center line-clamp-1">{{ $book->title }}</h4>
                            <p class="text-sm text-gray-600 text-center mt-1">{{ $book->author }}</p>
                            <p class="text-xs text-gray-500 text-center mt-2">
                                <i class="fas fa-barcode mr-1"></i>ISBN: {{ $book->isbn }}
                            </p>
                            
                            <!-- Stock Info -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $book->stock }} Available
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Loan Status -->
                            @if($hasBorrowed)
                                <div class="mt-3 bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs px-3 py-2 rounded-lg">
                                    <i class="fas fa-clock mr-1"></i>You have borrowed this book
                                </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 flex space-x-2">
                                <button class="flex-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium py-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                
                                <form method="POST" action="{{ route('member.borrow', $book->id) }}" class="flex-1">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        onclick="return confirm('Are you sure you want to borrow this book?')"
                                        class="w-full {{ $isDisabled ? 'btn-disabled bg-gray-100 text-gray-400' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }} text-sm font-medium py-2 rounded-lg transition-colors duration-200"
                                        {{ $isDisabled ? 'disabled' : '' }}
                                    >
                                        @if($hasBorrowed)
                                            <i class="fas fa-check mr-1"></i> Borrowed
                                        @elseif(!$isAvailable)
                                            <i class="fas fa-times mr-1"></i> Unavailable
                                        @else
                                            <i class="fas fa-book-reader mr-1"></i> Borrow
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($books->hasPages())
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @endif
            
            <!-- Empty State -->
            @if($books->isEmpty())
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        No books match your search criteria. Try searching with different keywords.
                    </p>
                </div>
            @endif
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="mt-12 py-6 border-t border-gray-200 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-600 text-sm">
                    <p>© 2024 Library Management System. Member Portal</p>
                </div>
                <div class="mt-4 md:mt-0 text-sm text-gray-500">
                    <p>Need help? Contact library admin at library@example.com</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Confirmation for borrowing
        document.querySelectorAll('form[action*="borrow"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                if (button.disabled) {
                    e.preventDefault();
                    alert('This book is not available for borrowing.');
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>