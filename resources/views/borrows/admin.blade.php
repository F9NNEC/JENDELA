<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">Borrow History</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-gray-100">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">All Borrowing Records</h1>
                    <p class="mt-2 text-gray-600">Monitor and manage user borrowing activity</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Book Title</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Borrow Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Returned</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($borrows as $b)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $b->user->name }}</div>
                                    <div class="text-xs text-gray-600">{{ $b->user->email }}</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-gray-900 font-medium">{{ $b->book->title }}</span></td>
                                <td class="px-6 py-4 text-gray-700">{{ $b->book->author }}</td>
                                <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $b->borrowed_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $b->due_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $b->returned_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($b->returned_at)
                                        @if($b->returned_late)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                                                Returned Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2"></span>
                                                Returned
                                            </span>
                                        @endif
                                    @elseif($b->is_overdue)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                                            Overdue
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                            Borrowed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $borrows->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
