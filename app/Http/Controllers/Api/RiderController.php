<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rider;
use App\Models\Driver;

class RiderController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $rider = $user->rider;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'rider' => $rider
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'home_address' => 'nullable|string',
            'work_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $user = $request->user();
        $rider = $user->rider;

        if (!$rider) {
            $rider = Rider::create([
                'user_id' => $user->id,
                'referral_code' => 'R' . strtoupper(substr(md5($user->id . time()), 0, 8))
            ]);
        }

        $rider->update($request->only(['home_address', 'work_address', 'emergency_contact']));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $rider
        ]);
    }

    public function nearbyDrivers(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:50'
        ]);

        $radius = $request->radius ?? 10; // Default 10km radius

        // For now, return mock data
        $mockDrivers = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'vehicle_type' => 'sedan',
                'vehicle_number' => 'ABC123',
                'rating' => 4.8,
                'distance' => 2.5,
                'eta' => 8,
                'current_latitude' => $request->latitude + 0.01,
                'current_longitude' => $request->longitude + 0.01
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'vehicle_type' => 'suv',
                'vehicle_number' => 'XYZ789',
                'rating' => 4.9,
                'distance' => 1.8,
                'eta' => 5,
                'current_latitude' => $request->latitude - 0.005,
                'current_longitude' => $request->longitude + 0.008
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'drivers' => $mockDrivers,
                'search_radius' => $radius
            ]
        ]);
    }

    public function estimateFare(Request $request)
    {
        $request->validate([
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'drop_latitude' => 'required|numeric',
            'drop_longitude' => 'required|numeric',
            'vehicle_type' => 'nullable|in:bike,auto,sedan,suv'
        ]);

        // Calculate fare based on vehicle type
        $fareRates = [
            'bike' => ['base' => 25, 'per_km' => 8],
            'auto' => ['base' => 35, 'per_km' => 10],
            'sedan' => ['base' => 50, 'per_km' => 12],
            'suv' => ['base' => 70, 'per_km' => 15]
        ];

        $vehicleType = $request->vehicle_type ?? 'sedan'; // Default to sedan if not provided
        $baseFare = $fareRates[$vehicleType]['base'];
        $perKmRate = $fareRates[$vehicleType]['per_km'];
        $estimatedDistance = 5.5; // Mock distance in km - in production, calculate using mapping API
        $estimatedFare = $baseFare + ($estimatedDistance * $perKmRate);

        return response()->json([
            'status' => 'success',
            'data' => [
                'base_fare' => $baseFare,
                'per_km_rate' => $perKmRate,
                'estimated_distance' => $estimatedDistance,
                'estimated_fare' => $estimatedFare,
                'surge_multiplier' => 1.0,
                'final_fare' => $estimatedFare
            ]
        ]);
    }
}
