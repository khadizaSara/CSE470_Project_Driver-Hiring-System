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

    /**
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function showArrival(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('trip.arrival', compact('booking'));
    }

    /**
     *
     * @param Request $request
     * @param int $tripId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPayment(Request $request, $tripId)
    {
        $trip = Booking::findOrFail($tripId);

        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized payment attempt.');
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,bkash,cash',
        ]);

        $trip->payment_method = $request->payment_method;
        $trip->payment_status = 'paid';
        $trip->trip_status = 'completed';
        $trip->save();

        $user = Auth::user();
        $user->increment('trip_count');


        return redirect()->route('driver.review', ['driverId' => $trip->driver_id]);
    }

    /**
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function rateDriver(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }
        $driver = $booking->driver;
        return view('trip.rate-driver', compact('booking', 'driver'));
    }
}
