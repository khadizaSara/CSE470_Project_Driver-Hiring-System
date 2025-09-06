<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            $validated = $request->validate([
                'driver_id' => 'required|exists:drivers,id',
                'pickup_location' => 'required|string',
                'destination' => 'required|string',
                'service_type' => 'required|in:regular,urgent',
                'car_type' => 'required|string',
                'promocode' => 'nullable|string',
            ]);
        } else {
            $validated = $bookingData;
        }

        $originalFare = $this->calculateFare(
            $validated['service_type'],
            $validated['car_type'],
            $validated['pickup_location'] ?? $validated['car_location'],
            $validated['destination']
        );

        $discountedFare = $originalFare;

        if (!empty($validated['promocode'])) {
            $promo = Promocode::where('code', $validated['promocode'])
                ->where('is_used', false)
                ->first();

            if ($promo) {
                $discountAmount = round($originalFare * ($promo->discount_percentage / 100));
                $discountedFare = $originalFare - $discountAmount;

                $promo->is_used = true;
                $promo->save();
            }
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'driver_id' => $validated['driver_id'],
            'pickup_location' => $validated['pickup_location'] ?? $validated['car_location'],
            'destination' => $validated['destination'],
            'service_type' => $validated['service_type'],
            'car_type' => $validated['car_type'],
            'fare' => $originalFare,
            'discounted_fare' => $discountedFare,
        ]);

        return redirect()->route('booking.track', $booking->id);
    }

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
            $baseFare = 300;
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

    public function completeTrip(Request $request)
    {
        $user = Auth::user();
        $user->trip_count++;
        if ($user->trip_count % 3 == 0) {
            Promocode::create([
                'user_id' => $user->id,
                'code' => strtoupper(Str::random(8)),
                'discount_percentage' => rand(15, 30),
                'is_used' => false,
            ]);
        }
        $user->save();
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $inputPromoCode = $request->input('promocode');

        if ($inputPromoCode) {
            $promo = Promocode::where('user_id', $user->id)
                ->where('code', $inputPromoCode)
                ->where('is_used', false)
                ->first();

            if ($promo) {
                $promo->is_used = true;
                $promo->save();
            }
        }

        return redirect()->route('dashboard')->with('success', 'Payment confirmed and promocode applied!');
    }
}
