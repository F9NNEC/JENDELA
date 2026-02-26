<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Buku') }}</h2>
    </x-slot>

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
                    <div class="text-lg font-medium">Daftar Buku</div>

                    @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Tambah Buku</a>
                    @endif
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

                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus buku ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block px-3 py-1 text-sm bg-red-600 text-white rounded">Delete</button>
                                        </form>
                                    @else
                                        @if(in_array($book->id, $userBorrowedBookIds ?? []))
                                            <span class="inline-block px-3 py-1 text-sm bg-gray-300 text-gray-700 rounded">Borrowed</span>
                                        @else
                                            <form action="{{ route('books.borrow', $book) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex px-3 py-1 text-sm bg-gray-300 text-blue-700 rounded" @if($book->available < 1) disabled @endif>
                                                    {{ $book->available > 0 ? 'Borrow' : 'Out' }}
                                                </button>
                                            </form>
                                        @endif
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
