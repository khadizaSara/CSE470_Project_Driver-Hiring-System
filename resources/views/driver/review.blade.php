<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            Rate Driver: {{ $driver->name }}
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto p-8 bg-white rounded-xl shadow-lg mt-8 border border-gray-200">
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-700">{{ $driver->name }}</h3>
            <p class="text-gray-500">{{ $driver->experience }} years experience</p>
        </div>

        <form action="{{ route('driver.saveReview', ['driverId' => $driver->id]) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="rating" class="block text-gray-700 font-semibold text-lg mb-2">Rating (1-5):</label>
                <select name="rating" id="rating" required
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 text-lg">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="review" class="block text-gray-700 font-semibold text-lg mb-2">Review (optional, max 50 words):</label>
                <textarea name="review" id="review" rows="5" maxlength="255" placeholder="Write your review here..."
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 resize-none text-base"></textarea>
                <p id="charCount" class="text-sm text-gray-400 mt-1">0 / 255 characters</p>
                @error('review')
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                    class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Submit Review</span>
                </button>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        const reviewTextarea = document.getElementById('review');
        const charCountEl = document.getElementById('charCount');

        reviewTextarea.addEventListener('input', () => {
            const length = reviewTextarea.value.length;
            charCountEl.textContent = `${length} / 255 characters`;
        });
    </script>
</x-app-layout>
