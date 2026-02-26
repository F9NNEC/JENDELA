<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Buku') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('books.update', $book) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input name="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('title')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Author</label>
                        <input name="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('author')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input name="isbn" value="{{ old('isbn', $book->isbn) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('isbn')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Available</label>
                        <input type="number" name="available" min="0" value="{{ old('available', $book->available) }}" class="mt-1 block w-32 border-gray-300 rounded-md" required>
                        @error('available')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cover URL</label>
                        <input name="cover_url" value="{{ old('cover_url', $book->cover_url) }}" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="https://example.com/image.jpg">
                        @error('cover_url')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                        <a href="{{ route('books.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
