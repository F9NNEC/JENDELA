<x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 text-red-600">{{ session('error') }}</div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <div class="text-lg font-medium">Book List</div>

                    @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Add Book</a>
                    @endif
                </div>

                <!-- Search Bar Section -->
                <div class="mb-6 flex justify-center w-full">
                    <form action="{{ route('books.index') }}" method="GET" class="flex items-center max-w-2xl w-full space-x-2">
                        <label for="book-search" class="sr-only">Search books</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8v8m0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0a4 4 0 0 1-4 4h-1a3 3 0 0 0-3 3"/>
                                </svg>
                            </div>
                            <input type="text" id="book-search" name="search" class="px-3 py-2.5 bg-white border border-gray-300 rounded-lg ps-9 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full placeholder:text-gray-500" placeholder="Search ISBN or author..." value="{{ request('search') }}" />
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center shrink-0 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 rounded-lg w-10 h-10 focus:outline-none transition">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center shrink-0 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg w-10 h-10 focus:outline-none transition">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="sr-only">Clear</span>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($books as $book)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" alt="cover" class="h-16 w-auto object-contain">
                                    @else
                                        <span class="text-xs text-gray-400">no cover</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $book->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $book->author }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $book->isbn ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $book->available }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if(auth()->user() && auth()->user()->isAdmin())
                                        <a href="{{ route('books.edit', $book) }}" class="inline-block px-3 py-1 text-sm bg-yellow-400 text-black rounded">Edit</a>

                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this book?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block px-3 py-1 text-sm bg-red-600 text-white rounded">Delete</button>
                                        </form>
                                    @elseif(auth()->check())
                                        @if(in_array($book->id, $userBorrowedBookIds ?? []))
                                            <span class="inline-block px-3 py-1 text-sm bg-gray-300 text-gray-700 rounded">Borrowed</span>
                                        @else
                                            <form action="{{ route('books.borrow', $book) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700" @if($book->available < 1) disabled @endif>
                                                    {{ $book->available > 0 ? 'Borrow' : 'Out' }}
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">
                                            Login to Borrow
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
