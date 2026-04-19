<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <h3 class="text-lg font-medium mb-4">My Borrowing</h3>
                @if($myBorrows->count() > 0)
                    <div class="grid grid-cols-5 gap-3 mb-8">
                        @foreach($myBorrows as $b)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-b-4 @if($b->is_overdue) border-red-500 @else border-green-500 @endif">
                                <!-- Book Cover -->
                                <div class="w-40 h-56 bg-gray-200 overflow-hidden mx-auto">
                                    @if($b->book->cover_url)
                                        <img src="{{ $b->book->cover_url }}" alt="{{ $b->book->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-300 to-blue-600">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Book Info -->
                                <div class="p-2">
                                    <h4 class="font-semibold text-xs text-gray-800 mb-1 line-clamp-2">{{ $b->book->title }}</h4>
                                    <p class="text-xs text-gray-600 mb-2 line-clamp-1">{{ $b->book->author }}</p>

                                    <!-- Status Badge -->
                                    <div class="mb-2">
                                        @if($b->is_overdue)
                                            <span class="inline-block px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Overdue</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Borrowed</span>
                                        @endif
                                    </div>

                                    <!-- Dates Info -->
                                    <div class="text-xs text-gray-600 mb-2 space-y-0.5">
                                        <p><span class="font-semibold">Borrowed:</span> {{ $b->borrowed_at->format('Y-m-d') }}</p>
                                        <p><span class="font-semibold">Due:</span> {{ $b->due_at->format('Y-m-d') }}</p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-1">
                                        @if($b->book->pdf_path)
                                            <a href="{{ route('books.read', $b->book) }}" class="flex-1 px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-xs font-semibold text-center">
                                                Read
                                            </a>
                                        @endif
                                        <form action="{{ route('borrows.return', $b) }}" method="POST" class="@if($b->book->pdf_path) flex-1 @else w-full @endif">
                                            @csrf
                                            <button type="submit" class="w-full px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs font-semibold">
                                                Return
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center mb-8">
                        <p class="text-gray-600">You haven't borrowed any books yet.</p>
                    </div>
                @endif

                <h3 class="text-lg font-medium mb-4">History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($history as $h)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $h->book->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $h->book->author }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $h->borrowed_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $h->returned_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($h->returned_late)
                                        <span class="text-red-600 font-semibold">Returned Overdue</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Returned</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
