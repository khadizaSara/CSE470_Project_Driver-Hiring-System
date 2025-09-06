<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use App\Models\Review;

class UpdateDriverRatings extends Command
{
    protected $signature = 'ratings:update-drivers';

    protected $description = 'Recalculate and update average_rating for all drivers using Bayesian average, falling back to existing rating if available.';

    public function handle()
    {
        $this->info('Starting driver ratings update...');

        $globalAvg = Review::avg('rating') ?? 0;
        $minRatings = 10;

        $drivers = Driver::all();

        foreach ($drivers as $driver) {
            $count = Review::where('driver_id', $driver->id)->count();
            $driverAvg = Review::where('driver_id', $driver->id)->avg('rating');


            if ($count > 0) {
                $bayesianAvg = (($count * $driverAvg) + ($minRatings * $globalAvg)) / ($count + $minRatings);
                $driver->average_rating = round($bayesianAvg, 2);
            } elseif (!empty($driver->rating)) {
                $driver->average_rating = $driver->rating;
            } else {
                $driver->average_rating = null;
            }

            $driver->save();
            $this->info("Updated driver ID {$driver->id} with average rating {$driver->average_rating}");
        }

        $this->info('Driver ratings update completed successfully.');
        return Command::SUCCESS;
    }
}
