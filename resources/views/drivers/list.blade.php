<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Drivers Right Now
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
        <p><strong>Car Location:</strong> {{ $data['car_location'] }}</p>
        <p><strong>Destination:</strong> {{ $data['destination'] }}</p>
        <p><strong>Service Type:</strong> {{ ucfirst($data['service_type']) }}</p>
        <p><strong>Car Type:</strong> {{ $data['car_type'] }}</p>
        <p><strong>Fare:</strong> {{ $data['fare'] }} ৳</p>
        <p><strong>Promo Code:</strong> {{ $data['promocode'] ?? 'None' }}</p>

        <h3 class="text-lg font-semibold mt-6 mb-4">Available Drivers</h3>

        @foreach ($drivers as $driver)
            <div class="border rounded p-4 mb-4 shadow-sm">
                <h4 class="font-bold text-xl">{{ $driver->name }}</h4>
                <p><strong>Age:</strong> {{ $driver->age }}</p>
                <p><strong>Experience:</strong> {{ $driver->experience }} years</p>
                <p><strong>Average Rating:</strong> {{ number_format($driver->average_rating, 2) }} / 5</p>

                <div>
                    <button
                        type="button"
                        class="reviews-toggle-btn mt-2 px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
                        onclick="this.nextElementSibling.classList.toggle('hidden')"
                    >
                        Show Reviews ({{ $driver->reviews->count() }})
                    </button>
                    <div class="reviews-dropdown mt-2 hidden border p-2 rounded bg-gray-50 max-h-48 overflow-y-auto">
                        @forelse ($driver->reviews as $review)
                            <div class="mb-2 border-b pb-1">
                                <div><strong>{{ $review->rating }} stars</strong></div>
                                <div class="text-sm text-gray-700">{{ $review->review }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>

                <p><strong>Driver Type:</strong> {{ ucfirst($driver->type) }}</p>

                <button type="button"
                    class="choose-driver-btn mt-3"
                    style="
                        background: #2563eb;
                        color: #ffffff;
                        padding: 0.5rem 1rem;
                        border-radius: 0.375rem;
                        font-weight: 600;
                        border: none;
                        cursor: pointer;
                        font-size: 1rem;
                        transition: background 0.2s;"
                    onmouseover="this.style.background='#1e40af';"
                    onmouseout="this.style.background='#2563eb';"
                    data-driver-id="{{ $driver->id }}"
                >
                    Choose
                </button>
            </div>
        @endforeach
    </div>

    <!-- Confirmation Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="confirmModal">
        <div class="relative top-40 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Confirm Driver Selection</h3>
            <p class="mb-6">Are you sure you want to choose this driver?</p>
            <form method="POST" action="{{ route('booking.store') }}" id="confirmForm">
                @csrf
                <input type="hidden" name="driver_id" id="modalDriverId">
                <input type="hidden" name="pickup_location" value="{{ $data['car_location'] }}">
                <input type="hidden" name="destination" value="{{ $data['destination'] }}">
                <input type="hidden" name="service_type" value="{{ $data['service_type'] }}">
                <input type="hidden" name="car_type" value="{{ $data['car_type'] }}">
                <input type="hidden" name="fare" value="{{ $data['fare'] }}">
                <input type="hidden" name="promocode" value="{{ $data['promocode'] ?? '' }}">

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        style="background-color: #2563eb;
                               color: white;
                               padding: 0.5rem 1rem;
                               border-radius: 0.375rem;
                               font-weight: 600;
                               border: none;
                               cursor: pointer;
                               font-size: 1rem;"
                        onmouseover="this.style.backgroundColor='#1e40af';"
                        onmouseout="this.style.backgroundColor='#2563eb';"
                    >
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let modal = document.getElementById('confirmModal');
            let modalDriverIdInput = document.getElementById('modalDriverId');
            let cancelBtn = document.getElementById('cancelBtn');

            document.querySelectorAll('.choose-driver-btn').forEach(button => {
                button.addEventListener('click', () => {
                    let driverId = button.getAttribute('data-driver-id');
                    modalDriverIdInput.value = driverId;
                    modal.classList.remove('hidden');
                });
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
    @endpush
</x-app-layout>
