<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;  // Import the Driver model

class DriverSelectionController extends Controller
{
    public function showList(Request $request)
    {
        $data = $request->validate([
            'car_location' => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'service_type' => 'required|in:regular,urgent',
        ]);

        // Fetch drivers from database where type matches selected service_type or is 'both'
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
        ]);

        // TODO: Save booking & chosen driver info here

        return redirect()->route('profile.edit')->with('success', 'You chose driver ID ' . $validated['driver_id']);
    }
}
