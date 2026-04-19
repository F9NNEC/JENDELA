<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Reading: ') . $book->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4 p-3 rounded border border-gray-300 bg-yellow-50 text-gray-700">PDF file is limited to the 3-day borrowing period.</div>
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('borrows.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Back to My Borrows</a>
                </div>

                <!-- Custom PDF Viewer Toolbar -->
                <div class="mb-2 flex items-center gap-2 bg-gray-200 p-2 rounded">
                    <button id="prevBtn" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">← Previous</button>
                    <span id="pageInfo" class="text-sm text-gray-700 mx-2">Page <span id="currentPage">1</span> of <span id="totalPages">0</span></span>
                    <button id="nextBtn" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Next →</button>
                    <div class="ml-auto flex gap-2">
                        <button id="zoomOutBtn" class="px-2 py-1 bg-gray-400 text-white rounded text-sm hover:bg-gray-500">−</button>
                        <span id="zoomLevel" class="text-sm text-gray-700 px-2">100%</span>
                        <button id="zoomInBtn" class="px-2 py-1 bg-gray-400 text-white rounded text-sm hover:bg-gray-500">+</button>
                    </div>
                </div>

                <!-- PDF Canvas -->
                <div id="pdfViewer" class="border border-gray-300 bg-gray-100 flex justify-center overflow-auto" style="height: 750px;">
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
    </script>
</x-app-layout>