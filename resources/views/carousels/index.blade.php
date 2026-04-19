<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="ms-auto">
                <a href="{{ route('carousels.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                    Add Carousel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                @if($carousels->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($carousels as $carousel)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $carousel->order }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $carousel->image_url }}" alt="{{ $carousel->title }}" class="h-12 w-12 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $carousel->title ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($carousel->description, 30) ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($carousel->active)
                                                <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                                            @else
                                                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('carousels.edit', $carousel) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                            <form action="{{ route('carousels.destroy', $carousel) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $carousels->links() }}
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-600">No carousel items yet. <a href="{{ route('carousels.create') }}" class="text-blue-600 hover:text-blue-900">Create one</a></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
