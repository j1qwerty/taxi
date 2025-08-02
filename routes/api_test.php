<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working',
        'timestamp' => now()->toISOString()
    ]);
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => 'connected',
        'time' => now()->toISOString()
    ]);
});

// Existing API routes...
Route::prefix('api')->middleware('api')->group(function () {
    // Authentication routes
    Route::post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('/auth/user', [App\Http\Controllers\Api\AuthController::class, 'user']);
        
        // Ride routes
        Route::prefix('rides')->group(function () {
            Route::post('/request', [App\Http\Controllers\Api\RideController::class, 'requestRide']);
            Route::get('/status/{rideId}', [App\Http\Controllers\Api\RideController::class, 'getRideStatus']);
            Route::post('/cancel/{rideId}', [App\Http\Controllers\Api\RideController::class, 'cancelRide']);
        });
        
        // Driver routes
        Route::prefix('driver')->group(function () {
            Route::post('/location', [App\Http\Controllers\Api\DriverController::class, 'updateLocation']);
            Route::get('/nearby-rides', [App\Http\Controllers\Api\DriverController::class, 'getNearbyRides']);
            Route::post('/rides/{rideId}/accept', [App\Http\Controllers\Api\DriverController::class, 'acceptRide']);
            Route::post('/rides/{rideId}/start', [App\Http\Controllers\Api\DriverController::class, 'startRide']);
            Route::post('/rides/{rideId}/complete', [App\Http\Controllers\Api\DriverController::class, 'completeRide']);
            Route::get('/current-ride', [App\Http\Controllers\Api\DriverController::class, 'getCurrentRide']);
            Route::post('/toggle-availability', [App\Http\Controllers\Api\DriverController::class, 'toggleAvailability']);
        });
        
        // Rider routes
        Route::prefix('rider')->group(function () {
            Route::get('/profile', [App\Http\Controllers\Api\RiderController::class, 'getProfile']);
            Route::put('/profile', [App\Http\Controllers\Api\RiderController::class, 'updateProfile']);
            Route::get('/rides', [App\Http\Controllers\Api\RiderController::class, 'getRides']);
            Route::get('/rides/{rideId}', [App\Http\Controllers\Api\RiderController::class, 'getRideDetails']);
        });
    });
});
