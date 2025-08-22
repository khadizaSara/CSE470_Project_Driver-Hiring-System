<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run()
    {
        $drivers = [
            [
                'name' => 'John Doe',
                'age' => 35,
                'experience' => 5,
                'rating' => 4.5,
                'type' => 'both',
            ],
            [
                'name' => 'Jane Smith',
                'age' => 29,
                'experience' => 3,
                'rating' => 4.0,
                'type' => 'regular',
            ],
            [
                'name' => 'Bob Johnson',
                'age' => 45,
                'experience' => 10,
                'rating' => 4.8,
                'type' => 'urgent',
            ],
            [
                'name' => 'Alice Green',
                'age' => 32,
                'experience' => 7,
                'rating' => 4.2,
                'type' => 'both',
            ],
            [
                'name' => 'Sam Wilson',
                'age' => 40,
                'experience' => 12,
                'rating' => 4.7,
                'type' => 'regular',
            ],
            [
                'name' => 'Emma White',
                'age' => 27,
                'experience' => 4,
                'rating' => 4.3,
                'type' => 'urgent',
            ],
            [
                'name' => 'Michael Brown',
                'age' => 38,
                'experience' => 9,
                'rating' => 4.6,
                'type' => 'both',
            ],
            [
                'name' => 'Olivia Black',
                'age' => 31,
                'experience' => 6,
                'rating' => 4.4,
                'type' => 'regular',
            ],
            [
                'name' => 'David Lee',
                'age' => 42,
                'experience' => 11,
                'rating' => 4.9,
                'type' => 'urgent',
            ],
            [
                'name' => 'Sophia King',
                'age' => 30,
                'experience' => 7,
                'rating' => 4.5,
                'type' => 'both',
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
