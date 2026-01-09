<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management System</title>
    
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
        
        .animate-bounce-slow {
            animation: bounce 3s infinite;
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
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        
        .tab-active {
            border-bottom: 3px solid #4f46e5;
            color: #4f46e5;
            font-weight: 600;
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
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-returned {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                            <i class="fas fa-crown mr-1"></i>Admin
                        </span>
                    </div>
                </div>
                
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
    </header>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Banner -->
        <div class="mb-8 animate-slide-up">
            <div class="gradient-bg rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                        <p class="text-indigo-100 mt-2">Here's what's happening with your library today.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="text-sm bg-white/20 px-4 py-2 rounded-full">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ now()->format('l, F j, Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Books Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Books</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBooks }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-bookmark mr-1"></i>
                                {{ $uniqueTitles }} unique titles
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fa fa-book text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Currently Borrowed Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Currently Borrowed</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $currentlyBorrowed }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                Active loans
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-amber-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Active Members Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Active Members</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $activeMembers }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                Unique borrowers
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-friends text-emerald-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="mb-8 animate-slide-up">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button id="tab-library" class="tab-active py-3 px-1 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">
                        <i class="fas fa-book-open mr-2"></i>Library Collection
                    </button>
                    <button id="tab-borrowing" class="py-3 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-exchange-alt mr-2"></i>Borrowing Records
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Tab Content -->
        <div id="tab-content">
            
            <!-- Library Collection Tab -->
            <div id="library-content" class="animate-slide-up">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Book Collection</h3>
                    <p class="text-gray-600 mb-6">Manage and view all books in the library</p>
                </div>
                
                <!-- Book Collection Cards (4 per row on desktop) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($bookCollection as $book)
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
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 flex space-x-2">
                                <button class="flex-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium py-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                <button class="flex-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-sm font-medium py-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- View All Button -->
                <div class="text-center">
                    <button class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg">
                        <i class="fas fa-book-reader mr-2"></i>View All Books
                    </button>
                </div>
            </div>
            
            <!-- Borrowing Records Tab (Hidden by default) -->
            <div id="borrowing-content" class="hidden animate-slide-up">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Borrowing Records</h3>
                    <p class="text-gray-600 mb-6">Track all borrowing activities and returns</p>
                    
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.search.borrowings') }}" class="mb-6">
                        <div class="relative max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300"
                                placeholder="Search by book title or borrower name..."
                            >
                            <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-arrow-right text-indigo-600 hover:text-indigo-800 transition-colors duration-200"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Borrowing Table -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Book Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Borrower Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Borrow Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Due Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($loans as $loan)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                                <i class="fas fa-book text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $loan->book->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $loan->book->author }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-indigo-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $loan->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $loan->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $loan->loan_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $loan->due_date < now() && $loan->status == 'active' ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $loan->due_date->format('Y-m-d') }}
                                        </div>
                                        @if($loan->due_date < now() && $loan->status == 'active')
                                        <div class="text-xs text-red-500">Overdue</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($loan->status == 'active')
                                            <span class="status-badge status-active">Borrowed</span>
                                        @elseif($loan->status == 'returned')
                                            <span class="status-badge status-returned">Returned</span>
                                        @else
                                            <span class="status-badge status-overdue">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if($loan->status == 'active')
                                            <button onclick="markAsReturned({{ $loan->id }})" class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Mark as Returned">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @endif
                                            <button class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($loans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $loans->links() }}
                    </div>
                    @endif
                </div>
                
                <!-- Empty State -->
                @if($loans->isEmpty())
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-exchange-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No borrowing records found</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        No books have been borrowed yet. When members start borrowing books, their records will appear here.
                    </p>
                </div>
                @endif
            </div>
        
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="mt-12 py-6 border-t border-gray-200 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-600 text-sm">
                    <p>© 2026 Library Management System. All rights reserved.</p>
                </div>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Tab Switching
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = {
                'tab-library': 'library-content',
                'tab-borrowing': 'borrowing-content',
            };
            
            // Set default active tab
            setActiveTab('tab-library');
            
            // Add click events to tabs
            Object.keys(tabs).forEach(tabId => {
                document.getElementById(tabId).addEventListener('click', function() {
                    setActiveTab(tabId);
                });
            });
            
            function setActiveTab(activeTabId) {
                // Update tab buttons
                Object.keys(tabs).forEach(tabId => {
                    const tabBtn = document.getElementById(tabId);
                    const contentId = tabs[tabId];
                    
                    if (tabId === activeTabId) {
                        tabBtn.classList.add('tab-active');
                        tabBtn.classList.remove('text-gray-500', 'border-transparent');
                        tabBtn.classList.add('text-indigo-600', 'border-indigo-600');
                        
                        // Show content
                        document.getElementById(contentId).classList.remove('hidden');
                    } else {
                        tabBtn.classList.remove('tab-active', 'text-indigo-600', 'border-indigo-600');
                        tabBtn.classList.add('text-gray-500', 'border-transparent');
                        
                        // Hide content
                        document.getElementById(contentId).classList.add('hidden');
                    }
                });
            }
            
            // Mark loan as returned
            window.markAsReturned = function(loanId) {
                if (confirm('Mark this loan as returned?')) {
                    fetch(`/admin/loans/${loanId}/return`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            };
            
            // Search functionality
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value) {
                document.getElementById('tab-borrowing').click();
            }
            
            // Add animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-slide-up');
                    }
                });
            }, observerOptions);
            
            // Observe all cards and sections
            document.querySelectorAll('.card-hover, .animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
        
        // Logout confirmation
        document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>