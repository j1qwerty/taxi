<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple test route without authentication
Route::get('/simple-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Simple test working',
        'time' => now()
    ]);
});

// Test database connection
Route::get('/db-test', function () {
    try {
        $userCount = DB::table('users')->count();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connected',
            'user_count' => $userCount
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }
});

// Test simple ride creation without auth
Route::post('/test-ride', function (Request $request) {
    try {
        $data = $request->all();
        return response()->json([
            'status' => 'success',
            'message' => 'Received data',
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});
