<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'service_type' => 'required|in:regular,urgent',
            'car_type' => 'required|string',
            'fare' => 'required|integer',
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'driver_id' => $validated['driver_id'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'service_type' => $validated['service_type'],
            'car_type' => $validated['car_type'],
            'fare' => $validated['fare'],
        ]);

        return redirect()->route('booking.track', $booking->id);
    }

    public function track(Booking $booking)
    {
        return view('booking.track', compact('booking'));
    }

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
