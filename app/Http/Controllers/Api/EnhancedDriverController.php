<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Ride;
use App\Services\RideService;
use App\Events\RideLocationUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DriverController extends Controller
{
    protected $rideService;

    public function __construct(RideService $rideService)
    {
        $this->rideService = $rideService;
    }

    /**
     * Update driver's current location with enhanced tracking
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'bearing' => 'nullable|numeric|between:0,360'
        ]);

        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        // Update location using RideService
        $this->rideService->updateDriverLocation(
            $driver->id,
            $request->latitude,
            $request->longitude
        );

        // If driver has active ride, track the progress
        $activeRide = Ride::where('driver_id', $driver->id)
            ->whereIn('status', ['confirmed', 'started'])
            ->first();

        if ($activeRide) {
            $this->rideService->trackRideProgress(
                $activeRide->id,
                $request->latitude,
                $request->longitude,
                $request->speed,
                $request->bearing
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated successfully',
            'data' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Get nearby ride requests for driver
     */
    public function getNearbyRides(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        if (!$driver->current_latitude || !$driver->current_longitude) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please update your location first'
            ], 400);
        }

        // Find nearby ride requests using spatial query
        $nearbyRides = Ride::with(['rider.user'])
            ->where('vehicle_type', $driver->vehicle_type)
            ->where('status', 'searching')
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(pickup_longitude, pickup_latitude),
                    POINT(?, ?)
                ) <= 10000
            ", [$driver->current_longitude, $driver->current_latitude])
            ->selectRaw("
                *,
                ST_Distance_Sphere(
                    POINT(pickup_longitude, pickup_latitude),
                    POINT(?, ?)
                ) / 1000 as distance_km
            ", [$driver->current_longitude, $driver->current_latitude])
            ->orderBy('distance_km', 'asc')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Nearby rides fetched successfully',
            'data' => [
                'rides' => $nearbyRides,
                'count' => $nearbyRides->count()
            ]
        ]);
    }

    /**
     * Accept a ride request
     */
    public function acceptRide(Request $request, $rideId)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        // Check if driver is available
        if (!$driver->is_available || !$driver->is_online) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver is not available'
            ], 400);
        }

        $ride = Ride::with(['rider.user'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        if ($ride->status !== 'searching') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride is no longer available'
            ], 400);
        }

        // Check if ride matches driver's vehicle type
        if ($ride->vehicle_type !== $driver->vehicle_type) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vehicle type mismatch'
            ], 400);
        }

        // Update ride and driver status
        DB::transaction(function () use ($ride, $driver) {
            $ride->update([
                'driver_id' => $driver->id,
                'status' => 'confirmed',
                'accepted_at' => now()
            ]);

            $driver->update([
                'is_available' => false
            ]);
        });

        // Calculate ETA to pickup location
        $pickupDistance = $this->rideService->calculateDistanceAndDuration(
            $driver->current_latitude,
            $driver->current_longitude,
            $ride->pickup_latitude,
            $ride->pickup_longitude
        );

        $driverInfo = [
            'id' => $driver->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'vehicle_number' => $driver->vehicle_number,
            'vehicle_type' => $driver->vehicle_type,
            'rating' => $driver->rating,
            'current_latitude' => $driver->current_latitude,
            'current_longitude' => $driver->current_longitude
        ];

        // Broadcast ride status update
        broadcast(new \App\Events\RideStatusUpdated(
            $ride->id,
            'confirmed',
            $driverInfo,
            $pickupDistance['duration'] . ' minutes'
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Ride accepted successfully',
            'data' => [
                'ride' => $ride->fresh(['rider.user']),
                'eta_to_pickup' => $pickupDistance['duration'] . ' minutes',
                'pickup_distance' => $pickupDistance['distance'] . ' km'
            ]
        ]);
    }

    /**
     * Start the ride (driver has reached pickup)
     */
    public function startRide(Request $request, $rideId)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:4'
        ]);

        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $ride = Ride::with(['rider.user'])->find($rideId);

        if (!$ride || $ride->driver_id !== $driver->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found or unauthorized'
            ], 404);
        }

        if ($ride->status !== 'confirmed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride cannot be started'
            ], 400);
        }

        // Verify OTP
        if ($ride->otp != $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Start the ride
        $ride->update([
            'status' => 'started',
            'started_at' => now()
        ]);

        // Broadcast ride status update
        broadcast(new \App\Events\RideStatusUpdated($ride->id, 'started', null, null));

        return response()->json([
            'status' => 'success',
            'message' => 'Ride started successfully',
            'data' => [
                'ride' => $ride->fresh(['rider.user'])
            ]
        ]);
    }

    /**
     * Complete the ride
     */
    public function completeRide(Request $request, $rideId)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $ride = Ride::with(['rider.user'])->find($rideId);

        if (!$ride || $ride->driver_id !== $driver->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found or unauthorized'
            ], 404);
        }

        if ($ride->status !== 'started') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride is not in progress'
            ], 400);
        }

        // Calculate actual fare if needed (based on actual distance/time)
        $actualDistance = $ride->distance; // In production, calculate from GPS tracking
        $finalFare = $ride->estimated_fare; // Use estimated for now

        // Complete the ride
        DB::transaction(function () use ($ride, $driver, $finalFare, $actualDistance) {
            $ride->update([
                'status' => 'completed',
                'completed_at' => now(),
                'final_fare' => $finalFare,
                'actual_distance' => $actualDistance
            ]);

            $driver->update([
                'is_available' => true
            ]);
        });

        // Broadcast ride completion
        broadcast(new \App\Events\RideStatusUpdated($ride->id, 'completed', null, null));

        return response()->json([
            'status' => 'success',
            'message' => 'Ride completed successfully',
            'data' => [
                'ride' => $ride->fresh(['rider.user']),
                'final_fare' => $finalFare,
                'payment_method' => $ride->payment_method
            ]
        ]);
    }

    /**
     * Get driver's current ride
     */
    public function getCurrentRide(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $currentRide = Ride::with(['rider.user'])
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['confirmed', 'started'])
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_ride' => $currentRide
            ]
        ]);
    }

    /**
     * Toggle driver availability
     */
    public function toggleAvailability(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $newStatus = !$driver->is_online;
        
        $driver->update([
            'is_online' => $newStatus,
            'is_available' => $newStatus // If going offline, also set unavailable
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Availability updated successfully',
            'data' => [
                'is_online' => $newStatus,
                'is_available' => $driver->is_available
            ]
        ]);
    }
}
