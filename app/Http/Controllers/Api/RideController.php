<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\Driver;

class RideController extends Controller
{
    public function requestRide(Request $request)
    {
        $request->validate([
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'pickup_address' => 'required|string',
            'drop_latitude' => 'required|numeric',
            'drop_longitude' => 'required|numeric',
            'drop_address' => 'required|string',
            'vehicle_type' => 'required|in:bike,auto,sedan,suv',
            'payment_method' => 'required|in:cash,wallet,card'
        ]);

        $user = $request->user();
        $rider = $user->rider;

        if (!$rider) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rider profile not found'
            ], 404);
        }

        // Create new ride request
        $ride = Ride::create([
            'rider_id' => $rider->id,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'pickup_address' => $request->pickup_address,
            'drop_latitude' => $request->drop_latitude,
            'drop_longitude' => $request->drop_longitude,
            'drop_address' => $request->drop_address,
            'vehicle_type' => $request->vehicle_type,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'booking_id' => 'TXI' . time() . rand(1000, 9999)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ride requested successfully',
            'data' => $ride
        ]);
    }

    public function getRideStatus(Request $request, $rideId)
    {
        $user = $request->user();
        $ride = Ride::with(['driver.user', 'rider.user'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        // Check if user is authorized to view this ride
        if ($user->user_type === 'rider' && $ride->rider->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($user->user_type === 'driver' && $ride->driver && $ride->driver->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ride
        ]);
    }

    public function cancelRide(Request $request, $rideId)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $ride = Ride::find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        // Check authorization
        $canCancel = false;
        if ($user->user_type === 'rider' && $ride->rider->user_id === $user->id) {
            $canCancel = true;
        } elseif ($user->user_type === 'driver' && $ride->driver && $ride->driver->user_id === $user->id) {
            $canCancel = true;
        }

        if (!$canCancel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to cancel this ride'
            ], 403);
        }

        if (!in_array($ride->status, ['pending', 'accepted'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride cannot be cancelled at this stage'
            ], 400);
        }

        $ride->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => $user->user_type,
            'cancellation_reason' => $request->cancellation_reason
        ]);

        // Update driver status if assigned
        if ($ride->driver) {
            $ride->driver->update(['status' => 'online']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ride cancelled successfully',
            'data' => $ride
        ]);
    }

    public function rateRide(Request $request, $rideId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        $user = $request->user();
        $ride = Ride::with(['driver', 'rider'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        if ($ride->status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Can only rate completed rides'
            ], 400);
        }

        // Check authorization and determine rating type
        if ($user->user_type === 'rider' && $ride->rider->user_id === $user->id) {
            $ride->update([
                'rider_rating' => $request->rating,
                'rider_review' => $request->review
            ]);
        } elseif ($user->user_type === 'driver' && $ride->driver && $ride->driver->user_id === $user->id) {
            $ride->update([
                'driver_rating' => $request->rating,
                'driver_review' => $request->review
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to rate this ride'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rating submitted successfully',
            'data' => $ride
        ]);
    }

    public function getRideHistory(Request $request)
    {
        $user = $request->user();
        
        if ($user->user_type === 'rider') {
            $rides = Ride::whereHas('rider', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['driver.user'])->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->user_type === 'driver') {
            $rides = Ride::whereHas('driver', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['rider.user'])->orderBy('created_at', 'desc')->paginate(10);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid user type'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $rides
        ]);
    }
}
