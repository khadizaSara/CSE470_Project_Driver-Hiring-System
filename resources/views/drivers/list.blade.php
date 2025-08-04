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

        <h3 class="text-lg font-semibold mt-6 mb-4">Available Drivers</h3>

        @php
            $drivers = [
                ['id' => 1, 'name' => 'Ted Mosby', 'age' => 35, 'experience' => 5, 'rating' => 4.5, 'type' => 'both'],
                ['id' => 2, 'name' => 'Sheldon Cooper', 'age' => 29, 'experience' => 3, 'rating' => 4.0, 'type' => 'regular'],
                ['id' => 3, 'name' => 'Luke Danes', 'age' => 45, 'experience' => 10, 'rating' => 4.8, 'type' => 'urgent'],
                ['id' => 4, 'name' => 'Joey Tribbiani', 'age' => 32, 'experience' => 7, 'rating' => 4.2, 'type' => 'both'],
                ['id' => 5, 'name' => 'Tyrion Lannister', 'age' => 40, 'experience' => 12, 'rating' => 4.7, 'type' => 'regular'],
            ];
        @endphp

        @foreach ($drivers as $driver)
            <div class="border rounded p-4 mb-4 shadow-sm">
                <h4 class="font-bold text-xl">{{ $driver['name'] }}</h4>
                <p><strong>Age:</strong> {{ $driver['age'] }}</p>
                <p><strong>Experience:</strong> {{ $driver['experience'] }} years</p>
                <p><strong>Rating:</strong> {{ $driver['rating'] }} / 5</p>
                <p><strong>Driver Type:</strong> {{ ucfirst($driver['type']) }}</p>

                <form method="POST" action="{{ route('drivers.select') }}" style="display:inline;" class="mt-3">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{ $driver['id'] }}">
                    <input type="hidden" name="car_location" value="{{ $data['car_location'] }}">
                    <input type="hidden" name="destination" value="{{ $data['destination'] }}">
                    <input type="hidden" name="service_type" value="{{ $data['service_type'] }}">

                    <button type="submit"
                        style="
                            background: #2563eb;  /* Crisp blue */
                            color: #ffffff;
                            padding: 0.5rem 1rem;
                            border-radius: 0.375rem;
                            font-weight: 600;
                            border: none;
                            cursor: pointer;
                            font-size: 1rem;
                            transition: background 0.2s;"
                        onmouseover="this.style.background='#1e40af';"
                        onmouseout="this.style.background='#2563eb';">
                        Choose
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>
