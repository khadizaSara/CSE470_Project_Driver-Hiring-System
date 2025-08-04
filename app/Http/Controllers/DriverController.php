<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        // For now, adding driver data manually here
        $drivers = [
            [
                'name' => 'Azmayeen Aftab',
                'experience' => '5 years',
                'availability' => ['regular', 'urgent'],
                'reviews' => 12,
                'rating' => 4.5,
            ],
            [
                'name' => 'Hasibul Hasan',
                'experience' => '3 years',
                'availability' => ['regular'],
                'reviews' => 8,
                'rating' => 4.0,
            ],
            [
                'name' => 'Farhan Labib',
                'experience' => '10 years',
                'availability' => ['urgent'],
                'reviews' => 15,
                'rating' => 4.8,
            ],
            // Add more driver arrays as you want
        ];

        return view('drivers.index', compact('drivers'));
    }
}
