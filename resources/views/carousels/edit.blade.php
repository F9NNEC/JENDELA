<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Carousel') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('carousels.update', $carousel) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Order Field -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan (Order)
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $carousel->order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('order')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title Field -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $carousel->title) }}" placeholder="Masukkan title carousel"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image URL Field -->
                    <div class="mb-6">
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Gambar <span class="text-red-600">*</span>
                        </label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $carousel->image_url) }}" 
                               placeholder="https://example.com/image.jpg" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-gray-500 text-sm mt-1">Format: Full URL (https://...)</p>
                        @error('image_url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Preview Image -->
                        <div class="mt-4">
                            <img id="image_preview" src="{{ $carousel->image_url }}" alt="Preview" class="max-w-xs h-auto rounded-lg border border-gray-300">
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi carousel"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $carousel->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Field -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" id="active" name="active" value="1" {{ old('active', $carousel->active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Aktif</span>
                        </label>
                        @error('active')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Perbarui
                        </button>
                        <a href="{{ route('carousels.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        const imageInput = document.getElementById('image_url');
        const imagePreview = document.getElementById('image_preview');

        imageInput.addEventListener('input', function() {
            if (this.value) {
                imagePreview.src = this.value;
                imagePreview.onerror = function() {
                    imagePreview.style.display = 'none';
                };
                imagePreview.onload = function() {
                    imagePreview.style.display = 'block';
                };
            }
        });
    </script>
</x-app-layout>
