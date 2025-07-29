<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Ride;

class DriverController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'driver' => $driver
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'nullable|in:bike,auto,sedan,suv',
            'vehicle_number' => 'nullable|string|max:20',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            $driver = Driver::create([
                'user_id' => $user->id,
                'referral_code' => 'D' . strtoupper(substr(md5($user->id . time()), 0, 8)),
                'status' => 'offline'
            ]);
        }

        $driver->update($request->only([
            'vehicle_type', 'vehicle_number', 'vehicle_model', 
            'vehicle_color', 'license_number'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $driver
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:online,offline,busy'
        ]);

        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        // Map status to boolean fields
        $isOnline = in_array($request->status, ['online', 'busy']);
        $isAvailable = $request->status === 'online';

        $driver->update([
            'is_online' => $isOnline,
            'is_available' => $isAvailable
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'data' => [
                'is_online' => $driver->is_online,
                'is_available' => $driver->is_available,
                'status' => $request->status
            ]
        ]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $driver->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
            'last_location_update' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated successfully'
        ]);
    }

    public function getEarnings(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        // Mock earnings data
        $todayEarnings = 450.00;
        $weekEarnings = 2800.00;
        $monthEarnings = 12500.00;
        $totalRides = 156;

        return response()->json([
            'status' => 'success',
            'data' => [
                'today_earnings' => $todayEarnings,
                'week_earnings' => $weekEarnings,
                'month_earnings' => $monthEarnings,
                'total_rides' => $totalRides,
                'current_wallet_balance' => $driver->wallet_balance ?? 0
            ]
        ]);
    }

    public function getRideHistory(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        $rides = Ride::where('driver_id', $driver->id)
                    ->with(['rider.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $rides
        ]);
    }

    public function acceptRide(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id'
        ]);

        $user = $request->user();
        $driver = $user->driver;
        $ride = Ride::find($request->ride_id);

        if ($ride->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride is not available'
            ], 400);
        }

        $ride->update([
            'driver_id' => $driver->id,
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        $driver->update(['status' => 'busy']);

        return response()->json([
            'status' => 'success',
            'message' => 'Ride accepted successfully',
            'data' => $ride
        ]);
    }

    public function updateRideStatus(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'status' => 'required|in:started,completed,cancelled'
        ]);

        $user = $request->user();
        $driver = $user->driver;
        $ride = Ride::where('id', $request->ride_id)
                   ->where('driver_id', $driver->id)
                   ->first();

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found or unauthorized'
            ], 404);
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'started') {
            $updateData['started_at'] = now();
        } elseif ($request->status === 'completed') {
            $updateData['completed_at'] = now();
            $driver->update(['status' => 'online']);
        } elseif ($request->status === 'cancelled') {
            $updateData['cancelled_at'] = now();
            $driver->update(['status' => 'online']);
        }

        $ride->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Ride status updated successfully',
            'data' => $ride
        ]);
    }
}
