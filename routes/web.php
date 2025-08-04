<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\HireDriverController;
use App\Http\Controllers\DriverSelectionController;



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

    // Driver list page (displays list of drivers after form submission)
    Route::post('/drivers/list', [DriverSelectionController::class, 'showList'])->name('drivers.list');

    // Driver selection submission (handles user choosing a driver)
    Route::post('/drivers/select', [DriverSelectionController::class, 'select'])->name('drivers.select');


    Route::post('/test-route', function () {
    return 'Test route works';
    })->name('test.route');

});

require __DIR__.'/auth.php';
