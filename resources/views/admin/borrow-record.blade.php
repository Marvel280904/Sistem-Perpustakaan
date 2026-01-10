<div class="animate-slide-up">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Borrowing Records</h3>
            <p class="text-gray-600">Track all borrowing activities and returns</p>
        </div>
        <!-- Search Bar -->
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
                type="text" 
                id="borrowing-search"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                placeholder="Search borrowing records..."
            >
        </div>
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
                            Borrower Info
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Borrow Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Return Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="loans-table-body">
                    @foreach($loans as $loan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150" data-status="{{ $loan->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 line-clamp-1" title="{{ $loan->book->title }}">{{ $loan->book->title }}</div>
                                    <div class="text-sm text-gray-500 line-clamp-1">{{ $loan->book->author }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $loan->member_name }}</div>
                                    @if($loan->borrower_phone)
                                    <div class="text-sm text-gray-500">{{ $loan->borrower_phone }}</div>
                                    @endif
                                    @if($loan->borrower_email)
                                    <div class="text-xs text-gray-400">{{ $loan->borrower_email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loan->loan_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $isOverdue = $loan->due_date < now() && $loan->status == 'active';
                            @endphp
                            <div class="text-sm font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $loan->due_date->format('Y-m-d') }}
                            </div>
                            @if($isOverdue)
                            <div class="text-xs text-red-500">Overdue</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $loan->return_date ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                            @if($loan->return_date)
                                {{ $loan->return_date->format('Y-m-d') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($loan->status == 'active')
                                <span class="status-badge status-active">
                                    <i class="fas fa-clock mr-1"></i>Active
                                </span>
                            @elseif($loan->status == 'returned')
                                <span class="status-badge status-returned">
                                    <i class="fas fa-check mr-1"></i>Returned
                                </span>
                            @else
                                <span class="status-badge status-overdue">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($loan->status == 'active')
                                <!-- Form untuk Mark as Returned -->
                                <form method="POST" action="{{ route('admin.loans.return', $loan->id) }}" class="inline" id="return-form-{{ $loan->id }}">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Mark this loan as returned?')"
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200 btn-hover-effect" 
                                            title="Mark as Returned">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Empty State -->
    @if($loans->isEmpty())
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exchange-alt text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No borrowing records found</h3>
        <p class="text-gray-600 max-w-md mx-auto">
            No books have been borrowed yet. Click "Add New Loan" to record a loan.
        </p>
    </div>
    @endif
</div>

<script>
    // Search loans
    document.addEventListener('DOMContentLoaded', function() {
        const borrowingSearch = document.getElementById('borrowing-search');
        if (borrowingSearch) {
            borrowingSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#loans-table-body tr');
                
                rows.forEach(row => {
                    const bookTitle = row.querySelector('td:first-child .font-medium').textContent.toLowerCase();
                    const author = row.querySelector('td:first-child .text-sm').textContent.toLowerCase();
                    const borrowerName = row.querySelector('td:nth-child(2) .font-medium').textContent.toLowerCase();
                    const borrowerPhone = row.querySelector('td:nth-child(2) .text-sm')?.textContent.toLowerCase() || '';
                    
                    if (bookTitle.includes(searchTerm) || 
                        author.includes(searchTerm) || 
                        borrowerName.includes(searchTerm) || 
                        borrowerPhone.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>