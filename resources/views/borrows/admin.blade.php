<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin - All Borrows') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned at</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($borrows as $b)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->user->name }} ({{ $b->user->email }})</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->book->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->book->author }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->borrowed_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->due_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $b->returned_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($b->returned_at)
                                        @if($b->returned_late)
                                            <span class="text-red-600 font-semibold">Returned Overdue</span>
                                        @else
                                            <span class="text-green-600 font-semibold">Returned</span>
                                        @endif
                                    @elseif($b->is_overdue)
                                        <span class="text-red-600 font-semibold">Overdue</span>
                                    @else
                                        <span class="text-yellow-600 font-semibold">Borrowed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if(!$b->returned_at)
                                        <form action="{{ route('borrows.return', $b) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded">Mark as returned</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $borrows->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
