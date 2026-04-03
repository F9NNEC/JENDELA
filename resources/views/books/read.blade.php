<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Reading: ') . $book->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4 p-3 rounded border border-gray-300 bg-yellow-50 text-gray-700">Jika ini masih kosong, silakan klik tombol di bawah untuk menampilkan file PDF dalam tab baru tanpa embed.</div>
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('books.pdf', $book) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded">Open PDF in new tab</a>
                    <a href="{{ route('borrows.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Back to My Borrows</a>
                </div>

                <object data="data:application/pdf;base64,{{ $pdfBase64 }}" type="application/pdf" width="100%" height="800px">
                    <p>PDF tidak dapat ditampilkan secara inline. <a href="{{ route('books.pdf', $book) }}" target="_blank">Klik di sini untuk buka PDF</a></p>
                </object>
            </div>
        </div>
    </div>
</x-app-layout>