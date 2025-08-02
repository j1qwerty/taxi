<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Ride;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class RideService
{
    /**
     * Find nearby drivers using spatial queries
     */
    public function findNearbyDrivers($pickupLat, $pickupLng, $vehicleType, $radius = 10)
    {
        // Use spatial query for efficient driver search
        return Driver::join('users', 'drivers.user_id', '=', 'users.id')
            ->where('drivers.vehicle_type', $vehicleType)
            ->where('drivers.is_online', true)
            ->where('drivers.is_available', true)
            ->where('drivers.approval_status', 'approved')
            ->whereNotNull('drivers.current_latitude')
            ->whereNotNull('drivers.current_longitude')
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(drivers.current_longitude, drivers.current_latitude),
                    POINT(?, ?)
                ) <= ? * 1000
            ", [$pickupLng, $pickupLat, $radius])
            ->select('drivers.*', 'users.name', 'users.phone')
            ->selectRaw("
                ST_Distance_Sphere(
                    POINT(drivers.current_longitude, drivers.current_latitude),
                    POINT(?, ?)
                ) / 1000 as distance_km
            ", [$pickupLng, $pickupLat])
            ->orderBy('distance_km', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Calculate ride price with dynamic factors
     */
    public function calculatePrice($distance, $duration, $vehicleType, $isNightTime = false, $surgeFactor = 1.0)
    {
        $rates = [
            'bike' => ['base' => 25, 'per_km' => 8, 'per_minute' => 1.0],
            'auto' => ['base' => 35, 'per_km' => 10, 'per_minute' => 1.5],
            'sedan' => ['base' => 50, 'per_km' => 12, 'per_minute' => 2.0],
            'suv' => ['base' => 70, 'per_km' => 15, 'per_minute' => 2.5]
        ];

        $rate = $rates[$vehicleType];
        $baseFare = $rate['base'];
        $distanceFare = $distance * $rate['per_km'];
        $timeFare = $duration * $rate['per_minute'];
        
        $subtotal = $baseFare + $distanceFare + $timeFare;
        
        // Apply surge pricing
        $subtotal *= $surgeFactor;
        
        // Night charges (10 PM to 6 AM)
        $nightCharge = 0;
        if ($isNightTime) {
            $nightCharge = $subtotal * 0.25; // 25% night surcharge
        }
        
        return [
            'base_fare' => $baseFare,
            'distance_fare' => $distanceFare,
            'time_fare' => $timeFare,
            'night_charges' => $nightCharge,
            'surge_multiplier' => $surgeFactor,
            'subtotal' => $subtotal,
            'total_fare' => $subtotal + $nightCharge
        ];
    }

    /**
     * Calculate distance and duration using Haversine formula (mock implementation)
     */
    public function calculateDistanceAndDuration($pickupLat, $pickupLng, $dropLat, $dropLng)
    {
        // Haversine formula for distance calculation
        $earthRadius = 6371; // km
        
        $latDelta = deg2rad($dropLat - $pickupLat);
        $lngDelta = deg2rad($dropLng - $pickupLng);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($pickupLat)) * cos(deg2rad($dropLat)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        
        // Mock duration calculation (assuming 30 km/h average speed)
        $duration = ($distance / 30) * 60; // minutes
        
        return [
            'distance' => round($distance, 2),
            'duration' => round($duration, 0)
        ];
    }

    /**
     * Get current surge factor based on demand/supply
     */
    public function getCurrentSurgeFactor($pickupLat, $pickupLng, $vehicleType)
    {
        // Get area demand (number of pending rides in 5km radius)
        $demand = Ride::where('status', 'searching')
            ->where('vehicle_type', $vehicleType)
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(pickup_longitude, pickup_latitude),
                    POINT(?, ?)
                ) <= 5000
            ", [$pickupLng, $pickupLat])
            ->count();

        // Get area supply (available drivers in 5km radius)
        $supply = $this->findNearbyDrivers($pickupLat, $pickupLng, $vehicleType, 5)->count();

        if ($supply == 0) return 2.0; // High surge when no drivers
        
        $ratio = $demand / $supply;
        
        if ($ratio >= 3) return 2.0;
        if ($ratio >= 2) return 1.5;
        if ($ratio >= 1.5) return 1.3;
        
        return 1.0; // Normal pricing
    }

    /**
     * Update driver location in both database and Redis
     */
    public function updateDriverLocation($driverId, $lat, $lng)
    {
        // Update database
        Driver::where('id', $driverId)->update([
            'current_latitude' => $lat,
            'current_longitude' => $lng,
            'last_location_update' => now()
        ]);

        // Update spatial column
        DB::statement("
            UPDATE drivers 
            SET location = POINT(?, ?) 
            WHERE id = ?
        ", [$lng, $lat, $driverId]);

        // Cache in Redis for real-time queries
        Redis::geoadd('drivers:locations', $lng, $lat, $driverId);
        Redis::hset("driver:$driverId:info", [
            'lat' => $lat,
            'lng' => $lng,
            'updated_at' => now()->timestamp
        ]);
    }

    /**
     * Track ride progress with GPS points
     */
    public function trackRideProgress($rideId, $lat, $lng, $speed = null, $bearing = null)
    {
        $ride = Ride::find($rideId);
        
        DB::table('ride_tracking')->insert([
            'ride_id' => $rideId,
            'latitude' => $lat,
            'longitude' => $lng,
            'speed' => $speed,
            'bearing' => $bearing,
            'status' => $ride->status,
            'recorded_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Update spatial column
        DB::statement("
            UPDATE ride_tracking 
            SET location = POINT(?, ?) 
            WHERE ride_id = ? AND recorded_at = ?
        ", [$lng, $lat, $rideId, now()]);

        // Broadcast location update via WebSocket (temporarily disabled for testing)
        // broadcast(new \App\Events\RideLocationUpdated($rideId, $lat, $lng, $speed, $bearing));
    }
}
