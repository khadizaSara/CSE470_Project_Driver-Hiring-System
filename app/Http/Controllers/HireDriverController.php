<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HireDriverController extends Controller
{
    // Show the form where user fills location, destination, service type, and car type
    public function showForm()
    {
        return view('drivers.hire');
    }

    // Handle form submission, validate data and redirect to drivers list
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'car_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'service_type' => 'required|in:regular,urgent',
            'car_type' => 'required|string',
            'fare' => 'required|integer',
        ]);

        // Redirect with validated data to show the list of drivers matching criteria
        return redirect()->route('drivers.list')->withInput($validated);
    }
}
