<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RiderController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\RideController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Rider routes
    Route::prefix('rider')->group(function () {
        Route::get('/profile', [RiderController::class, 'profile']);
        Route::post('/profile', [RiderController::class, 'updateProfile']);
        Route::post('/nearby-drivers', [RiderController::class, 'nearbyDrivers']);
        Route::post('/estimate-fare', [RiderController::class, 'estimateFare']);
    });
    
    // Driver routes
    Route::prefix('driver')->group(function () {
        Route::get('/profile', [DriverController::class, 'profile']);
        Route::post('/profile', [DriverController::class, 'updateProfile']);
        Route::post('/status', [DriverController::class, 'updateStatus']);
        Route::post('/location', [DriverController::class, 'updateLocation']);
        Route::get('/earnings', [DriverController::class, 'getEarnings']);
        Route::get('/rides', [DriverController::class, 'getRideHistory']);
        Route::post('/accept-ride', [DriverController::class, 'acceptRide']);
        Route::post('/update-ride-status', [DriverController::class, 'updateRideStatus']);
    });
    
    // Ride routes
    Route::prefix('ride')->group(function () {
        Route::post('/request', [RideController::class, 'requestRide']);
        Route::get('/{ride}/status', [RideController::class, 'getRideStatus']);
        Route::post('/{ride}/cancel', [RideController::class, 'cancelRide']);
        Route::post('/{ride}/rate', [RideController::class, 'rateRide']);
        Route::get('/history', [RideController::class, 'getRideHistory']);
    });
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Taxi Booking API is working!',
        'version' => '1.0.0',
        'timestamp' => now(),
        'endpoints' => [
            'auth' => '/api/auth/*',
            'rider' => '/api/rider/*',
            'driver' => '/api/driver/*',
            'ride' => '/api/ride/*'
        ]
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
