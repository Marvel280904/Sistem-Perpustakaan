<div id="add-loan-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Add New Loan</h3>
                        <p class="text-gray-600 mt-1">Record multiple book loans for one borrower</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <!-- Form -->
                <form method="POST" action="{{ route('admin.loans.store') }}" id="loanForm">
                    @csrf
                    
                    <!-- Borrower Information -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Borrower Information
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    Borrower Name *
                                </label>
                                <input type="text" name="member_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                    placeholder="Wajib"
                                    required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    Phone Number *
                                </label>
                                <input type="text" name="borrower_phone"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Wajib"
                                    required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    Email Address *
                                </label>
                                <input type="email" name="borrower_email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Wajib"
                                    required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book Selection -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-book mr-2 text-green-500"></i>Select Books *
                        </h4>
                        
                        <!-- Books Grid -->
                        <div class="max-h-96 overflow-y-auto pr-2 scrollbar-thin">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="books-container">  
                                @foreach($bookCollection as $book)
                                <div class="book-item border border-gray-200 rounded-xl p-4 transition-colors duration-200 {{ $book->stock > 0 ? 'hover:bg-gray-50' : 'bg-gray-50 opacity-70' }}">
                                    <div class="flex items-start">
                                        <input type="checkbox" 
                                               name="book_ids[]" 
                                               value="{{ $book->id }}" 
                                               id="book-{{ $book->id }}"
                                               class="mt-1 mr-3 h-5 w-5 text-blue-600 rounded focus:ring-blue-500 {{ $book->stock == 0 ? 'cursor-not-allowed opacity-50' : '' }}"
                                               onchange="updateSelectedCount()"
                                               {{ $book->stock == 0 ? 'disabled' : '' }}>
                                        <div class="flex-1">
                                            <label for="book-{{ $book->id }}" class="cursor-pointer {{ $book->stock == 0 ? 'cursor-not-allowed' : '' }}">
                                                <div class="font-medium {{ $book->stock == 0 ? 'text-gray-500' : 'text-gray-800' }}">
                                                    {{ $book->title }}
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($bookCollection->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-600">No books found in the database.</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Selected Count -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">
                                    <span id="selected-count">0</span> book(s) selected
                                </span>
                                <span id="no-selection-warning" class="text-sm text-red-600 hidden">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Please select at least one book
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan Details -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Loan Details
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    Loan Date *
                                </label>
                                <input type="date" name="loan_date" required
                                    value="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    Due Date * (7 days from loan)
                                </label>
                                <input type="date" name="due_date" required
                                    value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-300">
                            Cancel
                        </button>
                        <button type="submit" id="submit-btn"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>Record Loans
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function closeModal() {
        document.getElementById('add-loan-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('input[name="book_ids[]"]:checked').length;
        document.getElementById('selected-count').textContent = selected;
        
        const warning = document.getElementById('no-selection-warning');
        if (selected === 0) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }
    
    // Form Validation
    document.getElementById('loanForm').addEventListener('submit', function(e) {
        const selectedBooks = document.querySelectorAll('input[name="book_ids[]"]:checked').length;
        
        if (selectedBooks === 0) {
            e.preventDefault();
            alert('Please select at least one book.');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        submitBtn.disabled = true;
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Update date inputs min values
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="loan_date"]').min = today;
        document.querySelector('input[name="due_date"]').min = today;
        
        // Auto-set due date to 7 days from loan date
        document.querySelector('input[name="loan_date"]').addEventListener('change', function() {
            const loanDate = new Date(this.value);
            const dueDate = new Date(loanDate);
            dueDate.setDate(dueDate.getDate() + 7);
            
            const dueDateInput = document.querySelector('input[name="due_date"]');
            dueDateInput.value = dueDate.toISOString().split('T')[0];
            dueDateInput.min = this.value;
        });
        
        // Update selected count when modal opens
        const modal = document.getElementById('add-loan-modal');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (!modal.classList.contains('hidden')) {
                        updateSelectedCount();
                    }
                }
            });
        });
        
        if (modal) {
            observer.observe(modal, { attributes: true });
        }
    });
</script>

<style>
    #add-loan-modal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .book-item:hover {
        border-color: #3b82f6;
        background-color: #f0f9ff;
    }
    
    .book-item input[type="checkbox"]:disabled + div label {
        cursor: not-allowed;
    }
</style>