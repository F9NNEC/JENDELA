<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Kelola Carousel') }}</h2>
            <a href="{{ route('carousels.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Tambah Carousel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(session('success'))
                    <div class="p-6 bg-green-50 border-l-4 border-green-400">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if($carousels->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($carousels as $carousel)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $carousel->order }}</td>
                                        <td class="px-6 py-4">
                                            <img src="{{ $carousel->image_url }}" alt="{{ $carousel->title }}" class="h-12 w-12 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $carousel->title ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($carousel->description, 30) ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($carousel->active)
                                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Aktif</span>
                                            @else
                                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm space-x-2">
                                            <a href="{{ route('carousels.edit', $carousel) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <form action="{{ route('carousels.destroy', $carousel) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6">
                        {{ $carousels->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-600">
                        <p>Belum ada carousel item. <a href="{{ route('carousels.create') }}" class="text-blue-600 hover:text-blue-900">Buat yang baru</a></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
