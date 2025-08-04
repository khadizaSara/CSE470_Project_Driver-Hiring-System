<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hire a Driver
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto p-6 bg-white rounded shadow">
        <form method="POST" action="{{ route('drivers.list') }}">
            @csrf
            <div class="mb-4">
                <label for="car_location" class="block font-semibold mb-1">Car Location</label>
                <input type="text" name="car_location" id="car_location" class="block w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label for="destination" class="block font-semibold mb-1">Destination</label>
                <input type="text" name="destination" id="destination" class="block w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label for="service_type" class="block font-semibold mb-1">Service Type</label>
                <select name="service_type" id="service_type" class="block w-full border rounded px-2 py-1" required>
                    <option value="regular">Regular</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <button type="submit"
                style="
                    background: #3b82f6;   /* A clear blue */
                    color: #ffffff;
                    padding: 0.5rem 1rem;
                    border-radius: 0.375rem;
                    font-weight: 600;
                    border: none;
                    cursor: pointer;
                    font-size: 1rem;
                ">
                Choose a Driver
            </button>
        </form>
    </div>
</x-app-layout>
