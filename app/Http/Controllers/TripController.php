<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show arrival and payment page
    public function showArrival(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('trip.arrival', compact('booking'));
    }

    // Handle payment confirmation
    public function confirmPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized payment attempt.');
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,bkash,cash',
        ]);

        // Save payment info and mark trip as completed
        $booking->payment_method = $request->payment_method;
        $booking->payment_status = 'paid';
        $booking->trip_status = 'completed';
        $booking->save();

        // Increment user's trip count safely (handle null trips_count)
        $user = Auth::user();
        $user->trips_count = ($user->trips_count ?? 0) + 1;
        $user->save();

        // Redirect to the driver rating page (Pass booking id)
        return redirect()->route('trip.rateDriver', ['booking' => $booking->id]);
    }

    // Show the rating and review form for driver
    public function rateDriver(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }
        $driver = $booking->driver; // Assuming you have driver relation in Booking model
        return view('trip.rate-driver', compact('booking', 'driver'));
    }
}
