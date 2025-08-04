<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HireDriverController extends Controller
{
    // Show the form where user fills location, destination, and service type
    public function showForm()
    {
        return view('drivers.hire');
    }

    // We will add this later for form submission
    /*
    public function submitForm(Request $request)
    {
        // Validate and process form data
    }
    */
}
