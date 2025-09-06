<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\HireDriverController;
use App\Http\Controllers\DriverSelectionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\DriverController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // User profile routes
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    // Hire a driver routes
    Route::get('/hire-driver', [HireDriverController::class, 'showForm'])->name('driver.hire.form');
    Route::post('/hire-driver', [HireDriverController::class, 'submitForm'])->name('driver.hire.submit');

    // GET fallback route to avoid MethodNotAllowedHttpException for /drivers/list
    Route::get('/drivers/list', function () {
        return redirect()->route('driver.hire.form')->with('error', 'Please fill out the hire driver form first.');
    })->name('drivers.list.get');

    // ONLY allow POST request on /drivers/list
    Route::post('/drivers/list', [DriverSelectionController::class, 'showList'])->name('drivers.list');

    // Driver selection submission
    Route::post('/drivers/select', [DriverSelectionController::class, 'select'])->name('drivers.select');

    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store')->middleware('auth');
    Route::get('/track-driver/{booking}', [BookingController::class, 'track'])->name('booking.track')->middleware('auth');

    Route::get('/api/driver-location/{booking}', [BookingController::class, 'driverLocation'])
        ->name('api.driver.location')
        ->middleware('auth');

    // Show arrival & payment page
    Route::get('/trip/arrival/{booking}', [TripController::class, 'showArrival'])->name('trip.arrival')->middleware('auth');

    // Handle payment confirmation
    Route::post('/trip/arrival/{booking}/pay', [TripController::class, 'confirmPayment'])->name('trip.payment.confirm')->middleware('auth');

    Route::middleware('auth')->group(function () {
    Route::get('/trip/{booking}/rate', [TripController::class, 'rateDriver'])->name('trip.rateDriver');
    });

    Route::middleware('auth')->post('/review/submit', [ReviewController::class, 'submit'])->name('review.submit');

    Route::post('/driver/{driverId}/review', [DriverController::class, 'saveReview'])->name('driver.saveReview');
    Route::get('/driver/{driverId}/review', [DriverController::class, 'reviewForm'])->name('driver.review');


    Route::post('/test-route', function () {
        return 'Test route works';
    })->name('test.route');
});


Route::get('/test-generate-promocode', function () {
    $user = Auth::user();
    $code = strtoupper(\Illuminate\Support\Str::random(8));
    \App\Models\Promocode::create([
        'user_id' => $user->id,
        'code' => $code,
        'discount_percentage' => rand(15, 30),
        'is_used' => false,
    ]);
    return "Promo $code created!";
})->middleware('auth');


require __DIR__.'/auth.php';
