<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Track Your Driver
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
        <div id="map" style="height: 500px; width: 100%;"></div>

        <!-- Arrival Notification (hidden by default) -->
        <div id="arrivalNotification" style="display:none; position:fixed; top: 30%; left:50%; transform:translate(-50%,-50%); z-index:9999; background:#fff; border:2px solid #2563eb; padding:2rem 2.5rem; border-radius:1rem; box-shadow:0 10px 32px rgba(0,0,0,0.12); text-align:center;">
            <h3 style="font-size:1.5rem; margin-bottom:1rem;">Your driver has arrived!</h3>
            <button id="startTripBtn" style="background-color:#2563eb; color:white; padding:0.6rem 1.6rem; border:none; border-radius:0.5rem; font-size:1.1rem; font-weight:600; cursor:pointer;">
                Start your trip
            </button>
        </div>
    </div>

    @push('scripts')
        <!-- Load Google Maps JS API -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBW2cnlqruVIYZC7YgYC4j9XcVzeGshEOw"></script>
        <script>
            let map;
            let driverMarker;
            let bookingId = {{ $booking->id }};

            function initMap() {
                const baseLocation = { lat: 23.8103, lng: 90.4125 };

                map = new google.maps.Map(document.getElementById('map'), {
                    center: baseLocation,
                    zoom: 14
                });

                driverMarker = new google.maps.Marker({
                    position: baseLocation,
                    map: map,
                    title: 'Driver Location'
                });
            }

            function updateDriverLocation() {
                fetch(`/api/driver-location/${bookingId}`)
                    .then(response => response.json())
                    .then(data => {
                        const newPos = new google.maps.LatLng(data.lat, data.lng);
                        driverMarker.setPosition(newPos);
                    });
            }

            document.addEventListener('DOMContentLoaded', function () {
                initMap();
                setInterval(updateDriverLocation, 3000);

                // Show the arrival notification after 10 seconds
                setTimeout(() => {
                    document.getElementById('arrivalNotification').style.display = 'block';
                }, 10000);

                document.getElementById('startTripBtn').addEventListener('click', function() {
                    document.getElementById('arrivalNotification').style.display = 'none';
                    alert('Trip started!');
                });
            });
        </script>
    @endpush
</x-app-layout>
