<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TripController extends Controller
{
    public function showArrival(Booking $booking)
    {
        return view('trip.arrival', compact('booking'));
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

                $user->trip_count = 0;
                $user->save();

                \Log::info('Promo used and trip count reset for user: ' . $user->id);
            }
        } else {
            $user->trip_count++;

            \Log::info('Trip count incremented to ' . $user->trip_count . ' for user: ' . $user->id);

            if ($user->trip_count % 3 == 0) {
                $existingPromo = Promocode::where('user_id', $user->id)
                    ->where('is_used', false)
                    ->first();

                if (!$existingPromo) {
                    Promocode::create([
                        'user_id' => $user->id,
                        'code' => strtoupper(Str::random(8)),
                        'discount_percentage' => rand(15, 30),
                        'is_used' => false,
                    ]);

                    \Log::info('New promo code generated for user ' . $user->id);
                } else {
                    \Log::info('Existing unused promo found for user ' . $user->id);
                }
            }

            $user->save();
        }

        return redirect()->route('trip.rateDriver', ['booking' => $booking->id]);
    }

    public function rateDriver(Booking $booking)
    {
        $driver = $booking->driver;
        return view('trip.rate', compact('booking', 'driver'));
    }
}
