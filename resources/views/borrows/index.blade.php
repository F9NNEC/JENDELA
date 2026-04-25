<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- My Borrows Section -->
            <div class="mb-12">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Active Borrowing</h3>
                    <p class="text-gray-600 mb-6">Books you currently have borrowed</p>
                </div>

                @if($myBorrows->count() > 0)
                    <div class="grid grid-cols-5 gap-6">
                        @foreach($myBorrows as $b)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 {{ $b->is_overdue ? 'border-l-4 border-l-red-500' : 'border-l-4 border-l-green-500' }}">
                                <!-- Book Cover -->
                                <div class="relative w-full aspect-[3/4] bg-gradient-to-br from-slate-200 to-slate-300 overflow-hidden">
                                    @if($b->book->cover_url)
                                        <img src="{{ $b->book->cover_url }}" alt="{{ $b->book->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-purple-600">
                                            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <!-- Status Badge -->
                                    <div class="absolute top-3 right-3">
                                        @if($b->is_overdue)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 backdrop-blur-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 backdrop-blur-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                Active
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Book Info -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <h4 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2 leading-tight h-9">{{ $b->book->title }}</h4>
                                    <p class="text-xs text-gray-600 mb-4 line-clamp-1 flex-grow">by {{ $b->book->author }}</p>

                                    <!-- Dates Info -->
                                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-xs text-gray-700 space-y-1">
                                        <p><span class="font-semibold">Borrowed:</span> {{ $b->borrowed_at->format('M d, Y') }}</p>
                                        <p><span class="font-semibold">Due:</span> {{ $b->due_at->format('M d, Y') }}</p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto flex gap-2">
                                        @if($b->book->pdf_path)
                                            <a href="{{ route('books.read', $b->book) }}" class="flex-1 px-3 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 text-xs font-semibold text-center shadow-md hover:shadow-lg transform hover:scale-105">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"/></svg>
                                                Read
                                            </a>
                                        @endif
                                        <form action="{{ route('borrows.return', $b) }}" method="POST" class="@if($b->book->pdf_path) flex-1 @else w-full @endif">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 text-xs font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Return
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-12 text-center border border-gray-200">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"></path>
                        </svg>
                        <p class="text-gray-600 text-lg font-medium">You haven't borrowed any books yet</p>
                        <p class="text-gray-500 text-sm mt-2">Start exploring our collection today!</p>
                    </div>
                @endif
            </div>

            <!-- History Section -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-gray-100">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Borrowing History</h3>
                    <p class="text-gray-600">Your past borrowing records</p>
                </div>

                @if($history->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Book Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Borrow Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Returned Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($history as $h)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4"><span class="text-gray-900 font-medium">{{ $h->book->title }}</span></td>
                                    <td class="px-6 py-4 text-gray-700">{{ $h->book->author }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $h->borrowed_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $h->returned_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($h->returned_late)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                                                Returned Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2"></span>
                                                Returned On Time
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">No borrowing history yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
