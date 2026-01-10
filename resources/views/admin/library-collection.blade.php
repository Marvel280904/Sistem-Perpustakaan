<div class="animate-slide-up">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Book Collection</h3>
            <p class="text-gray-600">Manage and view all books in the library</p>
        </div>
        
        <!-- Search in Library Tab -->
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="library-search" 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                placeholder="Search books...">
        </div>
    </div>
    
    <!-- Book Collection Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="books-grid">
        @foreach($bookCollection as $book)
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 card-hover">
            <div class="p-5">
                
                <!-- Book Icon with Stock Indicator -->
                <div class="w-12 h-12 {{ $book->stock > 0 ? 'bg-gradient-to-r from-blue-500 to-indigo-600' : 'bg-gradient-to-r from-gray-400 to-gray-600' }} rounded-lg flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-book text-white text-xl"></i>
                </div>
                
                <!-- Book Info -->
                <h4 class="font-bold text-gray-800 text-center line-clamp-1" title="{{ $book->title }}">{{ $book->title }}</h4>
                <p class="text-sm text-gray-600 text-center mt-1 line-clamp-1" title="{{ $book->author }}">{{ $book->author }}</p>
                <p class="text-xs text-gray-500 text-center mt-2">
                    <i class="fas fa-barcode mr-1"></i>{{ $book->isbn }}
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
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    // Search in library tab
    document.addEventListener('DOMContentLoaded', function() {
        const librarySearch = document.getElementById('library-search');
        if (librarySearch) {
            librarySearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const books = document.querySelectorAll('#books-grid > div');
                
                books.forEach(book => {
                    const title = book.querySelector('h4').textContent.toLowerCase();
                    const author = book.querySelector('p.text-sm').textContent.toLowerCase();
                    const isbn = book.querySelector('p.text-xs').textContent.toLowerCase();
                    
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