@extends('layout.app')

{{-- Teruskan section dari child ke parent (app.blade.php) --}}
@section('title')
    @yield('title')
@endsection

@section('body-class')
    @yield('body-class')
@endsection

@section('content')
    <header class="glass-effect sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h1>
                        @yield('role-badge')
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to logout?')" 
                                class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2 btn-hover-effect">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('page-content')
    </main>
    
    <footer class="mt-12 py-6 border-t border-gray-200 bg-gray-50">
        <div class="max-w-7xl mx-auto text-center items-center">
            <p>© {{ date('Y') }} Library Management System. @yield('footer-note', 'All rights reserved.')</p>
        </div>
    </footer>

    @yield('modals')

@endsection