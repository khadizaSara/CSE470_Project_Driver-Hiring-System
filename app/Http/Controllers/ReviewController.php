<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $driver = Driver::findOrFail($request->driver_id);

        // Create new review
        $review = new Review();
        $review->driver_id = $driver->id;
        $review->user_id = Auth::id();
        $review->booking_id = $request->booking_id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        // Update the average rating in drivers table
        $averageRating = Review::where('driver_id', $driver->id)->avg('rating');
        $driver->average_rating = $averageRating;
        $driver->save();

        return redirect()->route('dashboard')->with('success', 'Thank you for rating the driver!');
    }
}
