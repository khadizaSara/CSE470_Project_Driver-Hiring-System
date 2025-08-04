<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverSelectionController extends Controller
{
    public function showList(Request $request)
    {
        $data = $request->validate([
            'car_location' => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'service_type' => 'required|in:regular,urgent',
        ]);

        $drivers = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'age' => 35,
                'experience' => 5,
                'rating' => 4.5,
                'type' => 'both',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'age' => 29,
                'experience' => 3,
                'rating' => 4.0,
                'type' => 'regular',
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson',
                'age' => 45,
                'experience' => 10,
                'rating' => 4.8,
                'type' => 'urgent',
            ],
        ];

        // Filter drivers by service type
        $drivers = array_filter($drivers, function ($driver) use ($data) {
            return $driver['type'] === $data['service_type'] || $driver['type'] === 'both';
        });

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
