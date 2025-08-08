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
                <select name="car_location" id="car_location" class="block w-full border rounded px-2 py-1" required>
                    <option value="">Select Area</option>
                    <option value="Gulshan, Dhaka">Gulshan, Dhaka</option>
                    <option value="Banani, Dhaka">Banani, Dhaka</option>
                    <option value="Dhanmondi, Dhaka">Dhanmondi, Dhaka</option>
                    <option value="Uttara, Dhaka">Uttara, Dhaka</option>
                    <option value="Mirpur, Dhaka">Mirpur, Dhaka</option>
                    <option value="Chittagong, Bangladesh">Chittagong, Bangladesh</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="destination" class="block font-semibold mb-1">Destination</label>
                <select name="destination" id="destination" class="block w-full border rounded px-2 py-1" required>
                    <option value="">Select Destination</option>
                    <option value="Gulshan, Dhaka">Gulshan, Dhaka</option>
                    <option value="Banani, Dhaka">Banani, Dhaka</option>
                    <option value="Dhanmondi, Dhaka">Dhanmondi, Dhaka</option>
                    <option value="Uttara, Dhaka">Uttara, Dhaka</option>
                    <option value="Mirpur, Dhaka">Mirpur, Dhaka</option>
                    <option value="Chittagong, Bangladesh">Chittagong, Bangladesh</option>
                </select>
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
    <div id="map" style="height: 400px; width: 100%;" class="mt-6"></div>

    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBW2cnlqruVIYZC7YgYC4j9XcVzeGshEOw"></script>
    <script>
        let map, directionsService, directionsRenderer;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 23.8103, lng: 90.4125 },
                zoom: 12,
            });
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
        }

        function calculateRoute() {
            const origin = document.getElementById("car_location").value;
            const destination = document.getElementById("destination").value;
            if (origin && destination) {
                directionsService.route(
                    {
                        origin,
                        destination,
                        travelMode: google.maps.TravelMode.DRIVING,
                    },
                    (response, status) => {
                        if (status === "OK") {
                            directionsRenderer.setDirections(response);
                        } else {
                            directionsRenderer.setDirections({routes: []});
                        }
                    }
                );
            } else {
                directionsRenderer.setDirections({routes: []});
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            initMap();
            document.getElementById("car_location").addEventListener("change", calculateRoute);
            document.getElementById("destination").addEventListener("change", calculateRoute);
        });
    </script>
</x-app-layout>