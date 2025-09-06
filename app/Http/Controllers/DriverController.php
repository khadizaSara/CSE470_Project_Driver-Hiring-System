<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('reviews')->get();
        foreach ($drivers as $driver) {
            $driver->average_rating = $driver->reviews->avg('rating');
        }
        
        return view('drivers.index', compact('drivers'));
    }

    public function reviewForm($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        return view('driver.review', compact('driver'));
    }

    public function saveReview(Request $request, $driverId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
            'booking_id' => 'required|integer|exists:bookings,id',
        ]);

        Review::create([
            'driver_id' => $driverId,
            'user_id' => Auth::id(),
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        $driver = Driver::findOrFail($driverId);
        $averageRating = Review::where('driver_id', $driver->id)->avg('rating');
        $driver->rating = $averageRating;
        $driver->save();

        return redirect()->route('drivers.list')->with('success', 'Review submitted.');
    }
}
