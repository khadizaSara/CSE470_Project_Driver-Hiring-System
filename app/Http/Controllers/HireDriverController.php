<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promocode;
use Illuminate\Support\Facades\Auth;

class HireDriverController extends Controller
{
    public function showForm()
    {
        $promocodes = Auth::user()->promocodes()->where('is_used', false)->get();
        return view('drivers.hire', compact('promocodes'));
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            
            'car_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'service_type' => 'required|in:regular,urgent',
            'car_type' => 'required|string',
            'fare' => 'required|integer',
            'promocode' => 'nullable|string',
        ]);
        dd($validated);
        $originalFare = $validated['fare'];
        $user = Auth::user();
        $inputPromoCode = strtoupper($validated['promocode'] ?? '');
        $fareAfterDiscount = $originalFare;

        if ($inputPromoCode) {
            $promo = Promocode::where('user_id', $user->id)
                ->where('code', $inputPromoCode)
                ->where('is_used', false)
                ->first();

            if ($promo) {
                $discountAmount = ($originalFare * $promo->discount_percentage) / 100;
                $fareAfterDiscount = $originalFare - $discountAmount;
            }
        }

        $validated['fare'] = $fareAfterDiscount;
        return redirect()->route('drivers.list')->withInput($validated);
    }
}
