<x-app-layout>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <div class="ms-auto">
                            <a href="{{ route('carousels.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                                Manage Carousels
                            </a>
                </div>
            </div>
            </x-slot>
        @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Carousel Section -->
            @if($carousels->count() > 0)
                <div class="mb-12">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="relative w-full h-96 bg-gray-200 rounded-lg overflow-hidden group">
                            <!-- Carousel Container -->
                            <div class="relative w-full h-full">
                                <!-- Carousel Images -->
                                @foreach($carousels as $index => $carousel)
                                    <div class="carousel-item absolute w-full h-full transition-transform duration-700 ease-in-out"
                                         style="transform: @if($index === 0) translateX(0) @else translateX(100%) @endif;">
                                        <img src="{{ $carousel->image_url }}" alt="{{ $carousel->title }}" class="w-full h-full object-cover">
                                        @if($carousel->title || $carousel->description)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6 text-white">
                                                @if($carousel->title)
                                                    <h3 class="text-2xl font-bold mb-2">{{ $carousel->title }}</h3>
                                                @endif
                                                @if($carousel->description)
                                                    <p class="text-sm">{{ $carousel->description }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Navigation Buttons -->
                            @if($carousels->count() > 1)
                                <button onclick="previousSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/75 text-white p-2 rounded-full transition z-10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/75 text-white p-2 rounded-full transition z-10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>

                                <!-- Indicators -->
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                    @foreach($carousels as $index => $carousel)
                                        <button onclick="goToSlide({{ $index }})" 
                                                class="indicator w-3 h-3 rounded-full transition @if($index === 0) bg-white @else bg-white/50 @endif"
                                                data-index="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Bar Section -->
            <div class="mb-8 flex justify-center w-full">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center max-w-lg w-full space-x-2">
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
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center shrink-0 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg w-10 h-10 focus:outline-none transition">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="sr-only">Clear</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Books Grid Section -->
            <div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">Book List</h3>
                @if($books->count() > 0)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($books as $book)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                                <!-- Book Cover -->
                                <div class="w-40 h-56 bg-gray-200 overflow-hidden mx-auto">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
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
                                    <h4 class="font-semibold text-xs text-gray-800 mb-1 line-clamp-2">{{ $book->title }}</h4>
                                    <p class="text-xs text-gray-600 mb-2 line-clamp-1">{{ $book->author }}</p>
                                    
                                    <!-- Availability Badge -->
                                    <div class="mb-2">
                                        @if($book->available > 0)
                                            <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Available</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Out of Stock</span>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('books.edit', $book) }}" class="block w-full px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition text-xs font-semibold text-center">
                                            Edit
                                        </a>
                                    @elseif(auth()->check() && auth()->user()->role === 'user')
                                        @php
                                            $userBorrow = $book->borrows()
                                                ->where('user_id', auth()->id())
                                                ->whereNull('returned_at')
                                                ->first();
                                        @endphp
                                        @if($userBorrow)
                                            <button disabled class="w-full px-2 py-1 bg-green-600 text-white rounded text-xs font-semibold cursor-default">
                                                Borrowed
                                            </button>
                                        @elseif($book->available > 0)
                                            <form action="{{ route('books.borrow', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs font-semibold">
                                                    Borrow
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-full px-2 py-1 bg-gray-300 text-gray-600 rounded text-xs font-semibold cursor-not-allowed">
                                                Out of Stock
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition text-xs font-semibold text-center">
                                            Login
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-600">No books available yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const totalSlides = slides.length;
        let autoRotateInterval;

        function startAutoRotate() {
            if (totalSlides > 1) {
                autoRotateInterval = setInterval(nextSlide, 5000);
            }
        }

        function resetAutoRotate() {
            clearInterval(autoRotateInterval);
            startAutoRotate();
        }

        // Start initial auto-rotate
        startAutoRotate();

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.transform = 'translateX(0)';
                } else if (i < index) {
                    slide.style.transform = 'translateX(-100%)';
                } else {
                    slide.style.transform = 'translateX(100%)';
                }
            });

            // Update indicators
            document.querySelectorAll('.indicator').forEach((indicator, i) => {
                indicator.classList.toggle('bg-white', i === index);
                indicator.classList.toggle('bg-white/50', i !== index);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
            resetAutoRotate();
        }

        function goToSlide(index) {
            currentSlide = index;
            showSlide(currentSlide);
            resetAutoRotate();
        }

        // Reset auto-rotate when user clicks next button
        document.querySelectorAll('button[onclick*="nextSlide"]').forEach(btn => {
            btn.addEventListener('click', resetAutoRotate);
        });
    </script>
</x-app-layout>
