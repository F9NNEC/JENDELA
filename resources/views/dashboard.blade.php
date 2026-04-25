<x-app-layout>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Dashboard</h2>
                <a href="{{ route('carousels.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Manage Carousels
                </a>
            </div>
        </x-slot>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Carousel Section -->
            @if($carousels->count() > 0)
                <div class="mb-16">
                    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                        <div class="relative w-full h-96 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl overflow-hidden group">
                            <!-- Carousel Container -->
                            <div class="relative w-full h-full">
                                <!-- Carousel Images -->
                                @foreach($carousels as $index => $carousel)
                                    <div class="carousel-item absolute w-full h-full transition-transform duration-700 ease-in-out"
                                         style="transform: @if($index === 0) translateX(0) @else translateX(100%) @endif;">
                                        <img src="{{ $carousel->image_url }}" alt="{{ $carousel->title }}" class="w-full h-full object-cover">
                                        @if($carousel->title || $carousel->description)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-8 text-white">
                                                @if($carousel->title)
                                                    <h3 class="text-3xl font-bold mb-2">{{ $carousel->title }}</h3>
                                                @endif
                                                @if($carousel->description)
                                                    <p class="text-sm text-gray-100">{{ $carousel->description }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Navigation Buttons -->
                            @if($carousels->count() > 1)
                                <button onclick="previousSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-3 rounded-full transition-all duration-300 z-10 backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-3 rounded-full transition-all duration-300 z-10 backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>

                                <!-- Indicators -->
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2.5 z-10">
                                    @foreach($carousels as $index => $carousel)
                                        <button onclick="goToSlide({{ $index }})" 
                                                class="indicator w-2.5 h-2.5 rounded-full transition-all @if($index === 0) bg-white w-8 @else bg-white/50 hover:bg-white/70 @endif"
                                                data-index="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Bar Section -->
            <div class="mb-12 flex justify-center w-full">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center max-w-2xl w-full space-x-3">
                    <label for="book-search" class="sr-only">Search books</label>
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-600 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="book-search" name="search" class="px-4 py-3 ps-11 bg-white border-2 border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-0 focus:border-blue-600 block w-full placeholder:text-gray-500 transition-all shadow-sm" placeholder="Search ISBN, author, or title..." value="{{ request('search') }}" />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center shrink-0 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 rounded-xl w-11 h-11 focus:outline-none transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2.5" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center shrink-0 text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 rounded-xl w-11 h-11 focus:outline-none transition-all shadow-sm">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="sr-only">Clear</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Books Grid Section -->
            <div class="mb-12">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Book Collection</h2>
                        <p class="mt-2 text-gray-600">Explore our complete collection</p>
                    </div>
                </div>
                @if($books->count() > 0)
                    <div class="grid grid-cols-5 gap-6">
                        @foreach($books as $book)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                                <!-- Book Cover -->
                                <div class="relative w-full aspect-[3/4] bg-gradient-to-br from-slate-200 to-slate-300 overflow-hidden">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-purple-600">
                                            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <!-- Availability Badge -->
                                    <div class="absolute top-3 right-3">
                                        @if($book->available > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 backdrop-blur-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 backdrop-blur-sm">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Book Info -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <h4 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2 leading-tight h-9">{{ $book->title }}</h4>
                                    <p class="text-xs text-gray-600 mb-4 line-clamp-1 flex-grow">by {{ $book->author }}</p>
                                    
                                    <!-- Action Button -->
                                    <div class="mt-auto">
                                        @if(auth()->check() && auth()->user()->role === 'admin')
                                            <a href="{{ route('books.edit', $book) }}" class="block w-full px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg hover:from-amber-600 hover:to-amber-700 transition-all duration-300 text-xs font-semibold text-center shadow-md hover:shadow-lg transform hover:scale-105">
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
                                                <button disabled class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg text-xs font-semibold cursor-default shadow-md flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Borrowed
                                                </button>
                                            @elseif($book->available > 0)
                                                <form action="{{ route('books.borrow', $book) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 text-xs font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
                                                        Borrow
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled class="w-full px-4 py-2.5 bg-gray-200 text-gray-500 rounded-lg text-xs font-semibold cursor-not-allowed">
                                                    Out of Stock
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="block w-full px-4 py-2.5 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-lg hover:from-gray-800 hover:to-gray-900 transition-all duration-300 text-xs font-semibold text-center shadow-md hover:shadow-lg">
                                                Login
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center border border-gray-200">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747m0-13c5.5 0 10 4.745 10 10.747M9 9h1.646a1 1 0 00.707-.293l2.914-2.914a1 1 0 01.707-.293h1.414a1 1 0 01.707.293l2.914 2.914a1 1 0 00.707.293H15m-6-6v6m6-6v6"></path>
                        </svg>
                        <p class="text-gray-600 text-lg">No books available yet.</p>
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
