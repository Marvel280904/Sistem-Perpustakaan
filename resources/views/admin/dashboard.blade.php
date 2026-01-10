@extends('layout.page-layout')

@section('title', 'Admin Dashboard - Library Management System')
@section('body-class', 'bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen animate-fade-in')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

{{-- Badge Khusus Admin --}}
@section('role-badge')
    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
        <i class="fas fa-crown mr-1"></i>Admin
    </span>
@endsection

{{-- Isi Dashboard --}}
@section('page-content')

    <!-- Header -->
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
    
    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Books -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Books</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBooks }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-bookmark mr-1"></i> unique titles
                        </span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fa fa-book text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Current Borrow -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Currently Borrowed</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $currentlyBorrowed }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i> Active loans
                        </span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-amber-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Active Users -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover animate-slide-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Members</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $activeMembers }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-users mr-1"></i> Unique borrowers
                        </span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-friends text-emerald-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications -->
    @if(session('success'))
        <div class="mb-6 animate-slide-up">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 animate-slide-up">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Tab Sections -->
    <div class="mb-8 animate-slide-up">
        <div class="flex justify-between items-center border-b border-gray-200">
            <nav class="flex space-x-3">
                <button id="tab-library" class="tab-active py-3 px-1 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">
                    <i class="fas fa-book-open mr-2"></i>Library Collection
                </button>
                <button id="tab-borrowing" class="py-3 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-exchange-alt mr-2"></i>Borrowing Records
                </button>
            </nav>
            
            <button id="add-loan-btn" onclick="openAddLoanModal()" 
                    class="flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 text-sm font-medium py-2.5 px-5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg btn-hover-effect">
                <i class="fas fa-plus-circle mr-2 text-lg"></i>
                <span>Add New Loan</span>
            </button>
        </div>
    </div>
    
    <!-- Modal Tabs -->
    <div id="tab-content" class="animate-slide-up">
        <div id="library-content">
            @include('admin.library-collection')
        </div>
        <div id="borrowing-content" class="hidden">
            @include('admin.borrow-record')
        </div>
    </div>
@endsection

{{-- Add Loan Modal --}}
@section('modals')
    @include('admin.create-loan')
@endsection

@push('scripts')
    <script>
        // TAB SWITCHING FUNCTIONALITY
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
            
            // Auto-hide success/error messages after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
        

        // MODAL FUNCTIONS
        function openAddLoanModal() {
            document.getElementById('add-loan-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            updateSelectedCount();
        }
        
        function closeModal() {
            document.getElementById('add-loan-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Logout confirmation
        document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    </script>
@endpush