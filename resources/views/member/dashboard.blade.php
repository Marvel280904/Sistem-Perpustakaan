@extends('layout.app')

@section('title', 'Member Dashboard - Library Management System')
@section('body-class', 'bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen animate-fade-in')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
@endpush

{{-- Isi Dashboard --}}
@section('content')
    <main class="max-w-7xl mx-auto px-4 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 animate-slide-up">
            <div class="gradient-bg rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome! 📚</h2>
                        <p class="text-indigo-100 mt-2">Browse and borrow books from our library collection.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Input -->
        <div class="mb-8 animate-slide-up">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Find Books</h3>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="library-search"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                        placeholder="Search by title, author, or ISBN..." autocomplete="off">
                </div>
            </div>
        </div>
        
        <div class="animate-slide-up">
            
            <!-- Title -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Book Collection</h3>
                <p class="text-gray-600 mb-6">Browse and borrow books from our library</p>
            </div>
            
            <!-- Books Grid -->
            <div id="books-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($books as $book)
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 card-hover">
                        <div class="p-5">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-center line-clamp-1">{{ $book->title }}</h4>
                            <p class="text-sm text-gray-600 text-center mt-1">{{ $book->author }}</p>
                            <p class="text-xs text-gray-500 text-center mt-2"><i class="fas fa-barcode mr-1"></i>ISBN: {{ $book->isbn }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-sm font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $book->stock }} Available
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($books->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-600">No books found.</p>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Search Books Logic
        document.addEventListener('DOMContentLoaded', function() {
            const librarySearch = document.getElementById('library-search');
            
            if (librarySearch) {
                librarySearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    // Mengambil semua child div langsung dari #books-grid
                    const books = document.querySelectorAll('#books-grid > div');
                    
                    books.forEach(book => {
                        // Mencari elemen di dalam card buku
                        const title = book.querySelector('h4').textContent.toLowerCase();
                        const author = book.querySelector('p.text-sm').textContent.toLowerCase();
                        // Menggunakan selector yang lebih aman jika ISBN ada
                        const isbnEl = book.querySelector('p.text-xs'); 
                        const isbn = isbnEl ? isbnEl.textContent.toLowerCase() : '';
                        
                        if (title.includes(searchTerm) || author.includes(searchTerm) || isbn.includes(searchTerm)) {
                            book.style.display = 'block';
                        } else {
                            book.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endpush