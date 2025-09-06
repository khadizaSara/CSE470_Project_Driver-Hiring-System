<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Review;
use App\Models\Booking;
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

        return redirect()->route('dashboard')->with('success', 'Review submitted successfully. Thank you!');
    }

    // New method to save a booking with discounted fare
    public function storeBooking(Request $request)
    {
        $request->validate([
            'car_location' => 'required|string',
            'destination' => 'required|string',
            'service_type' => 'required|string',
            'car_type' => 'required|string',
            'fare' => 'required|numeric',
            'promocode' => 'nullable|string',
        ]);

        // Optionally, you can store the original fare too if you want to show original price
        // Here let's re-calculate original fare ignoring promo for record
        $originalFare = $this->calculateOriginalFare(
            $request->car_location,
            $request->destination,
            $request->service_type,
            $request->car_type
        );

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'car_location' => $request->car_location,
            'destination' => $request->destination,
            'service_type' => $request->service_type,
            'car_type' => $request->car_type,
            'original_fare' => $originalFare,
            'fare' => $request->fare, // discounted fare passed from form
            'promo_code' => $request->promocode,
        ]);

        return redirect()->route('trip.completed', ['booking' => $booking->id]);
    }

    // Helper to calculate original fare (without promo discount logic)
    private function calculateOriginalFare($origin, $destination, $serviceType, $carType)
    {
        $baseFare = 0;
        if (($origin === 'Gulshan, Dhaka' && $destination === 'Uttara, Dhaka') ||
            ($origin === 'Uttara, Dhaka' && $destination === 'Gulshan, Dhaka')) {
            $baseFare = 500;
        } elseif (($origin === 'Dhanmondi, Dhaka' && $destination === 'Mirpur, Dhaka') ||
                  ($origin === 'Mirpur, Dhaka' && $destination === 'Dhanmondi, Dhaka')) {
            $baseFare = 400;
        } else {
            $baseFare = 300;
        }

        $carTypeSurcharge = 0;
        switch ($carType) {
            case 'sedan': $carTypeSurcharge = 100; break;
            case 'suv': $carTypeSurcharge = 150; break;
            case 'microbus': $carTypeSurcharge = 200; break;
            case 'hatchback': $carTypeSurcharge = 80; break;
        }

        $fare = $baseFare + $carTypeSurcharge;

        if ($serviceType === 'urgent') {
            $fare += 100;
        }

        return $fare;
    }
}
