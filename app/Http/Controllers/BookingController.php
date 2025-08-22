<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'service_type' => 'required|in:regular,urgent',
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'driver_id' => $request->driver_id,
            'pickup_location' => $request->pickup_location,
            'destination' => $request->destination,
            'service_type' => $request->service_type,
        ]);

        // Redirect to tracking page
        return redirect()->route('booking.track', $booking->id);
    }

    public function track(Booking $booking)
    {
        // Return a view for driver tracking
        return view('booking.track', compact('booking'));
    }

    public function driverLocation(Booking $booking)
    {
        // Base coordinates for simulation (example: Dhaka)
        $baseLat = 23.8103;
        $baseLng = 90.4125;

        // Random small offsets to simulate movement
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
