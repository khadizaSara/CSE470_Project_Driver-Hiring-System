<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Store a new booking
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'service_type' => 'required|in:regular,urgent',
            'car_type' => 'required|string',
        ]);

        // Calculate fare on backend
        $fare = $this->calculateFare(
            $validated['service_type'],
            $validated['car_type'],
            $validated['pickup_location'],
            $validated['destination']
        );

        // Create booking record with validated details and current auth user
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'driver_id' => $validated['driver_id'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'service_type' => $validated['service_type'],
            'car_type' => $validated['car_type'],
            'fare' => $fare,  // Save calculated fare here
        ]);

        // Redirect user to track page of created booking
        return redirect()->route('booking.track', $booking->id);
    }

    // Calculate fare based on inputs
    private function calculateFare($serviceType, $carType, $pickup, $destination)
    {
        $baseFare = 0;
        if (
            ($pickup === 'Gulshan, Dhaka' && $destination === 'Uttara, Dhaka') ||
            ($pickup === 'Uttara, Dhaka' && $destination === 'Gulshan, Dhaka')
        ) {
            $baseFare = 500;
        } elseif (
            ($pickup === 'Dhanmondi, Dhaka' && $destination === 'Mirpur, Dhaka') ||
            ($pickup === 'Mirpur, Dhaka' && $destination === 'Dhanmondi, Dhaka')
        ) {
            $baseFare = 400;
        } else {
            $baseFare = 300; // default
        }

        $carTypeSurcharge = match($carType) {
            'sedan' => 100,
            'suv' => 150,
            'microbus' => 200,
            'hatchback' => 80,
            default => 0,
        };

        $fare = $baseFare + $carTypeSurcharge;
        if ($serviceType === 'urgent') {
            $fare += 100;
        }
        return $fare;
    }

    // Show tracking page for booking
    public function track(Booking $booking)
    {
        return view('booking.track', compact('booking'));
    }

    // Return simulated driver location as JSON for map updates
    public function driverLocation(Booking $booking)
    {
        $baseLat = 23.8103;
        $baseLng = 90.4125;

        $randomLatOffset = (mt_rand(-1000, 1000)) / 1000000;
        $randomLngOffset = (mt_rand(-1000, 1000)) / 1000000;

        $lat = $baseLat + $randomLatOffset;
        $lng = $baseLng + $randomLngOffset;

        return response()->json([
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }
}
