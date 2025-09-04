<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    // Apply auth middleware to all methods in this controller
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show arrival and payment page
    public function showArrival(Booking $booking)
    {
        // Ensure booking belongs to authenticated user for security
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('trip.arrival', compact('booking'));
    }

    // Handle payment confirmation
    public function confirmPayment(Request $request, Booking $booking)
    {
        // Ensure booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized payment attempt.');
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,bkash,cash',
        ]);

        // Save payment info and mark trip as completed
        $booking->payment_method = $request->payment_method;
        $booking->payment_status = 'paid'; // You can also use boolean or enum as you set
        $booking->trip_status = 'completed';
        $booking->save();

        // Increment user's trip count safely (handle null trips_count)
        $user = Auth::user();
        $user->trips_count = ($user->trips_count ?? 0) + 1;
        $user->save();

        // Redirect to dashboard with success notification
        return redirect()->route('dashboard')->with('success', 'Payment confirmed. Thank you for riding with us!');
    }
}
