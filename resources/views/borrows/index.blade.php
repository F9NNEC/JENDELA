<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My borrow and history') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <h3 class="text-lg font-medium mb-4">My borrow</h3>
                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($myBorrows as $b)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->book->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->book->author }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->borrowed_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($b->is_overdue)
                                        <span class="text-red-600 font-semibold">Overdue</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Borrowed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($b->book->pdf_path)
                                        <a href="{{ route('books.read', $b->book) }}" class="px-3 py-1 text-sm bg-green-600 text-white rounded mr-2">Read</a>
                                    @endif
                                    <form action="{{ route('borrows.return', $b) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded">Return</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

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
