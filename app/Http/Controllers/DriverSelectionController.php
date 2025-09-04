<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverSelectionController extends Controller
{
    public function showList(Request $request)
    {
        $data = $request->validate([
            'car_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'service_type' => 'required|in:regular,urgent',
            'car_type' => 'required|string',
            'fare' => 'required|integer',
        ]);

        $drivers = Driver::where('type', $data['service_type'])
            ->orWhere('type', 'both')
            ->get();

        return view('drivers.list', compact('drivers', 'data'));
    }

    public function select(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|integer',
            'car_location' => 'required|string',
            'destination' => 'required|string',
            'service_type' => 'required|string',
            'car_type' => 'required|string',
            'fare' => 'required|integer',
        ]);

        // TODO: Save booking & chosen driver info here if needed

        return redirect()->route('profile.edit')->with('success', 'You chose driver ID ' . $validated['driver_id']);
    }
}
