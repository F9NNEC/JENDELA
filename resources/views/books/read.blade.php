<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent leading-tight">{{ __('Reading: ') . $book->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">by {{ $book->author }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Card -->
            <div class="mb-4 p-4 rounded-xl border-l-4 border-amber-500 bg-gradient-to-r from-amber-50 to-yellow-50 text-gray-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium">PDF file is limited to the 3-day borrowing period.</p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-6 border border-gray-100">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('borrows.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to My Borrows
                    </a>
                </div>

                <!-- Custom PDF Viewer Toolbar -->
                <div class="mb-4 flex items-center justify-between gap-4 bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200 shadow-sm">
                    <!-- Left Controls -->
                    <div class="flex items-center gap-2">
                        <button id="prevBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg text-sm font-medium transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        <div class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm font-medium text-gray-700">
                            Page <span id="currentPage">1</span> of <span id="totalPages">0</span>
                        </div>
                        <button id="nextBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg text-sm font-medium transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-3 border-l border-gray-300 pl-4">
                        <button id="zoomOutBtn" class="p-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg border border-gray-200 transition duration-200" title="Zoom Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                            </svg>
                        </button>
                        <span id="zoomLevel" class="text-sm font-medium text-gray-700 w-12 text-center">100%</span>
                        <button id="zoomInBtn" class="p-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg border border-gray-200 transition duration-200" title="Zoom In">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                            </svg>
                        </button>
                        <span class="text-xs text-gray-500 border-l border-gray-300 pl-4">Use ← → keys to navigate</span>
                    </div>
                </div>

                <!-- PDF Canvas -->
                <div id="pdfViewer" class="border-2 border-gray-300 bg-gradient-to-b from-gray-50 to-gray-100 flex justify-center overflow-auto rounded-xl shadow-inner" style="height: 750px;">
                    <canvas id="pdfCanvas" class="border border-gray-200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Set up PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfBase64 = '{{ $pdfBase64 }}';
        const pdfBytes = atob(pdfBase64);
        const pdfArray = new Uint8Array(pdfBytes.length);
        for (let i = 0; i < pdfBytes.length; i++) {
            pdfArray[i] = pdfBytes.charCodeAt(i);
        }

        let pdfDoc = null;
        let currentPage = 1;
        let zoom = 1;

        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');

        // Load PDF
        pdfjsLib.getDocument({ data: pdfArray }).promise.then(pdf => {
            pdfDoc = pdf;
            document.getElementById('totalPages').textContent = pdf.numPages;
            renderPage(currentPage);
        });

        function renderPage(page) {
            if (!pdfDoc) return;

            pdfDoc.getPage(page).then(pdfPage => {
                const viewport = pdfPage.getViewport({ scale: zoom * 1.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport,
                };

                pdfPage.render(renderContext);
                document.getElementById('currentPage').textContent = page;
                document.getElementById('zoomLevel').textContent = Math.round(zoom * 100) + '%';

                // Update button states
                prevBtn.disabled = page === 1;
                nextBtn.disabled = page === pdfDoc.numPages;
            });
        }

        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < pdfDoc.numPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        zoomInBtn.addEventListener('click', () => {
            zoom += 0.2;
            renderPage(currentPage);
        });

        zoomOutBtn.addEventListener('click', () => {
            if (zoom > 0.5) {
                zoom -= 0.2;
                renderPage(currentPage);
            }
        });

        // Keyboard Navigation
        document.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    renderPage(currentPage);
                }
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                if (pdfDoc && currentPage < pdfDoc.numPages) {
                    currentPage++;
                    renderPage(currentPage);
                }
            }
        });
    </script>
</x-app-layout>