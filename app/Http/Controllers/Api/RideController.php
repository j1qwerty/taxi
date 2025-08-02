<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\Driver;
use App\Services\RideService;
use App\Events\RideStatusUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RideController extends Controller
{
    protected $rideService;

    public function __construct(RideService $rideService)
    {
        $this->rideService = $rideService;
    }

    public function requestRide(Request $request)
    {
        $request->validate([
            'pickup_latitude' => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
            'pickup_address' => 'required|string|max:500',
            'drop_latitude' => 'required|numeric|between:-90,90',
            'drop_longitude' => 'required|numeric|between:-180,180',
            'drop_address' => 'required|string|max:500',
            'vehicle_type' => 'required|in:bike,auto,sedan,suv',
            'payment_method' => 'required|in:cash,wallet,card',
            'notes' => 'nullable|string|max:1000'
        ]);

        $user = $request->user();
        $rider = $user->rider;

        if (!$rider) {
            // Auto-create rider profile if it doesn't exist
            $rider = \App\Models\Rider::create([
                'user_id' => $user->id,
                'phone' => $user->phone ?? '0000000000',
                'emergency_contact' => '0000000000',
                'referral_code' => 'REF' . $user->id . rand(100, 999)
            ]);
        }

        // Check if user has any active ride
        $activeRide = Ride::where('rider_id', $rider->id)
            ->whereIn('status', ['searching', 'confirmed', 'started'])
            ->first();

        if ($activeRide) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have an active ride',
                'active_ride' => [
                    'id' => $activeRide->id,
                    'status' => $activeRide->status,
                    'pickup_address' => $activeRide->pickup_address,
                    'drop_address' => $activeRide->drop_address,
                    'created_at' => $activeRide->created_at
                ]
            ], 400);
        }

        // Check for nearby drivers using spatial service
        $nearbyDrivers = $this->rideService->findNearbyDrivers(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->vehicle_type
        );

        if ($nearbyDrivers->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No drivers available in your area',
                'suggestion' => 'Please try again in a few minutes or select a different vehicle type'
            ], 404);
        }

        // Calculate distance, duration and dynamic pricing
        $routeData = $this->rideService->calculateDistanceAndDuration(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->drop_latitude,
            $request->drop_longitude
        );

        // Check if it's night time (10 PM to 6 AM)
        $isNightTime = Carbon::now()->hour >= 22 || Carbon::now()->hour < 6;

        // Get current surge factor
        $surgeFactor = $this->rideService->getCurrentSurgeFactor(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->vehicle_type
        );

        // Calculate dynamic pricing
        $pricingData = $this->rideService->calculatePrice(
            $routeData['distance'],
            $routeData['duration'],
            $request->vehicle_type,
            $isNightTime,
            $surgeFactor
        );

        // Generate unique ride ID
        $rideId = 'TXI' . time() . rand(1000, 9999);

        // Create ride record with enhanced data
        $ride = Ride::create([
            'ride_id' => $rideId,
            'rider_id' => $rider->id,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'pickup_address' => $request->pickup_address,
            'drop_latitude' => $request->drop_latitude,
            'drop_longitude' => $request->drop_longitude,
            'drop_address' => $request->drop_address,
            'vehicle_type' => $request->vehicle_type,
            'payment_method' => $request->payment_method,
            'distance' => $routeData['distance'],
            'estimated_duration' => $routeData['duration'],
            'estimated_fare' => $pricingData['total_fare'],
            'base_fare' => $pricingData['base_fare'],
            'distance_fare' => $pricingData['distance_fare'],
            'time_fare' => $pricingData['time_fare'],
            'night_charges' => $pricingData['night_charges'],
            'surge_multiplier' => $pricingData['surge_multiplier'],
            'per_km_charge' => $pricingData['distance_fare'] / $routeData['distance'],
            'status' => 'searching',
            'notes' => $request->notes,
            'otp' => rand(1000, 9999)
        ]);

        // Update spatial columns
        DB::statement("
            UPDATE rides 
            SET pickup_location = POINT(?, ?), 
                drop_location = POINT(?, ?)
            WHERE id = ?
        ", [
            $request->pickup_longitude, $request->pickup_latitude,
            $request->drop_longitude, $request->drop_latitude,
            $ride->id
        ]);

        // Broadcast ride request to nearby drivers
        broadcast(new RideStatusUpdated($ride->id, 'searching', null, null));

        return response()->json([
            'status' => 'success',
            'message' => 'Ride requested successfully',
            'data' => [
                'ride' => [
                    'id' => $ride->id,
                    'ride_id' => $ride->ride_id,
                    'status' => $ride->status,
                    'pickup_address' => $ride->pickup_address,
                    'drop_address' => $ride->drop_address,
                    'vehicle_type' => $ride->vehicle_type,
                    'payment_method' => $ride->payment_method,
                    'estimated_fare' => $ride->estimated_fare,
                    'distance' => $ride->distance,
                    'estimated_duration' => $ride->estimated_duration,
                    'otp' => $ride->otp,
                    'created_at' => $ride->created_at
                ],
                'nearby_drivers_count' => $nearbyDrivers->count(),
                'pricing_breakdown' => $pricingData,
                'estimated_arrival' => $routeData['duration'] . ' minutes',
                'surge_active' => $surgeFactor > 1.0,
                'night_charges_applied' => $isNightTime
            ]
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
            'data' => [
                'id' => $ride->id,
                'ride_id' => $ride->ride_id,
                'status' => $ride->status,
                'pickup_address' => $ride->pickup_address,
                'drop_address' => $ride->drop_address,
                'vehicle_type' => $ride->vehicle_type,
                'payment_method' => $ride->payment_method,
                'estimated_fare' => $ride->estimated_fare,
                'actual_fare' => $ride->actual_fare,
                'distance' => $ride->distance,
                'estimated_duration' => $ride->estimated_duration,
                'otp' => $ride->otp,
                'driver' => $ride->driver ? [
                    'name' => $ride->driver->user->name,
                    'phone' => $ride->driver->phone,
                    'vehicle_number' => $ride->driver->vehicle_number
                ] : null,
                'created_at' => $ride->created_at,
                'accepted_at' => $ride->accepted_at,
                'completed_at' => $ride->completed_at
            ]
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

        if (!in_array($ride->status, ['searching', 'pending', 'accepted'])) {
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
            'data' => [
                'id' => $ride->id,
                'ride_id' => $ride->ride_id,
                'status' => $ride->status,
                'cancelled_at' => $ride->cancelled_at,
                'cancelled_by' => $ride->cancelled_by,
                'cancellation_reason' => $ride->cancellation_reason
            ]
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
            'data' => [
                'id' => $ride->id,
                'ride_id' => $ride->ride_id,
                'rider_rating' => $ride->rider_rating,
                'rider_review' => $ride->rider_review,
                'driver_rating' => $ride->driver_rating,
                'driver_review' => $ride->driver_review
            ]
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
            'data' => [
                'rides' => $rides->map(function ($ride) {
                    return [
                        'id' => $ride->id,
                        'ride_id' => $ride->ride_id,
                        'status' => $ride->status,
                        'pickup_address' => $ride->pickup_address,
                        'drop_address' => $ride->drop_address,
                        'vehicle_type' => $ride->vehicle_type,
                        'estimated_fare' => $ride->estimated_fare,
                        'actual_fare' => $ride->actual_fare,
                        'distance' => $ride->distance,
                        'created_at' => $ride->created_at,
                        'completed_at' => $ride->completed_at,
                        'driver' => $ride->driver ? [
                            'name' => $ride->driver->user->name ?? 'Unknown',
                            'vehicle_number' => $ride->driver->vehicle_number
                        ] : null
                    ];
                }),
                'pagination' => [
                    'current_page' => $rides->currentPage(),
                    'total' => $rides->total(),
                    'per_page' => $rides->perPage(),
                    'last_page' => $rides->lastPage()
                ]
            ]
        ]);
    }
}
