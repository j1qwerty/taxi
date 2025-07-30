<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Rider;
use App\Models\Driver;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:rider,driver',
        ];

        // Additional validation for drivers
        if ($request->user_type === 'driver') {
            $rules['license_number'] = 'required|string|unique:drivers,driving_license';
            $rules['vehicle_number'] = 'required|string|unique:drivers,vehicle_number';
            $rules['vehicle_type'] = 'required|in:bike,auto,sedan,suv';
            $rules['vehicle_model'] = 'nullable|string|max:100';
            $rules['vehicle_color'] = 'nullable|string|max:50';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'status' => 'active',
            ]);

            // Create rider or driver profile
            if ($request->user_type === 'rider') {
                Rider::create([
                    'user_id' => $user->id,
                    'referral_code' => 'RIDER' . str_pad($user->id, 6, '0', STR_PAD_LEFT)
                ]);
            } else {
                Driver::create([
                    'user_id' => $user->id,
                    'driving_license' => $request->license_number,
                    'vehicle_number' => $request->vehicle_number,
                    'vehicle_type' => $request->vehicle_type,
                    'vehicle_model' => $request->vehicle_model,
                    'vehicle_color' => $request->vehicle_color,
                    'is_online' => false,
                    'is_available' => true,
                    'approval_status' => 'pending',
                    'referral_code' => 'DRIVER' . str_pad($user->id, 6, '0', STR_PAD_LEFT)
                ]);
            }

            DB::commit();

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }
}
