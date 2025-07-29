<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Rider;
use App\Models\Driver;
use App\Models\Ride;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            $credentials['user_type'] = 'admin';

            if (Auth::attempt($credentials)) {
                return redirect()->route('admin.dashboard');
            }

            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        return view('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_riders' => Rider::count(),
            'total_drivers' => Driver::count(),
            'total_rides' => Ride::count(),
            'completed_rides' => Ride::where('status', 'completed')->count(),
            'active_rides' => Ride::whereIn('status', ['pending', 'accepted', 'started'])->count(),
            'total_earnings' => Ride::where('status', 'completed')->sum('actual_fare'),
            'drivers_online' => Driver::where('is_online', true)->count()
        ];

        $recent_rides = Ride::with(['rider.user', 'driver.user'])
                           ->orderBy('created_at', 'desc')
                           ->limit(10)
                           ->get();

        return view('admin.dashboard', compact('stats', 'recent_rides'));
    }

    public function users()
    {
        $users = User::with(['rider', 'driver'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function riders()
    {
        $riders = Rider::with('user')
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        return view('admin.riders', compact('riders'));
    }

    public function drivers()
    {
        $drivers = Driver::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('admin.drivers', compact('drivers'));
    }

    public function rides()
    {
        $rides = Ride::with(['rider.user', 'driver.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.rides', compact('rides'));
    }

    public function approveDriver(Request $request, $driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $driver->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Driver approved successfully'
        ]);
    }

    public function rejectDriver(Request $request, $driverId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $driver = Driver::findOrFail($driverId);
        $driver->update([
            'is_verified' => false,
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Driver rejected successfully'
        ]);
    }

    public function toggleUserStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User status updated successfully',
            'is_active' => $user->is_active
        ]);
    }

    public function rideDetails($rideId)
    {
        $ride = Ride::with(['rider.user', 'driver.user', 'rideLocations'])
                   ->findOrFail($rideId);

        return view('admin.ride-details', compact('ride'));
    }

    public function earnings()
    {
        $totalEarnings = Ride::where('status', 'completed')->sum('actual_fare');
        $todayEarnings = Ride::where('status', 'completed')
                           ->whereDate('ride_end_time', today())
                           ->sum('actual_fare');
        $monthEarnings = Ride::where('status', 'completed')
                           ->whereMonth('ride_end_time', now()->month)
                           ->whereYear('ride_end_time', now()->year)
                           ->sum('actual_fare');

        $earningsData = [
            'total' => $totalEarnings,
            'today' => $todayEarnings,
            'month' => $monthEarnings
        ];

        return view('admin.earnings', compact('earningsData'));
    }

    public function settings()
    {
        // Mock settings data
        $settings = [
            'app_name' => 'Taxi Booking App',
            'base_fare' => 50,
            'per_km_rate' => 12,
            'cancellation_fee' => 25,
            'commission_rate' => 20,
            'surge_multiplier' => 1.5,
            'max_search_radius' => 10
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
            'base_fare' => 'required|numeric|min:0',
            'per_km_rate' => 'required|numeric|min:0',
            'cancellation_fee' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'surge_multiplier' => 'required|numeric|min:1',
            'max_search_radius' => 'required|numeric|min:1|max:50'
        ]);

        // In a real app, you'd save these to a settings table or config
        return back()->with('success', 'Settings updated successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
