<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome-new');
});

Route::get('/demo', function () {
    return response()->file(public_path('ride-flow-demo.html'));
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel is working!',
        'timestamp' => now(),
        'status' => 'success'
    ]);
});

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

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/riders', [AdminController::class, 'riders'])->name('riders');
        Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
        Route::get('/rides', [AdminController::class, 'rides'])->name('rides');
        Route::get('/rides/{ride}', [AdminController::class, 'rideDetails'])->name('ride.details');
        Route::get('/earnings', [AdminController::class, 'earnings'])->name('earnings');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings']);
        
        // AJAX Routes
        Route::post('/drivers/{driver}/approve', [AdminController::class, 'approveDriver'])->name('driver.approve');
        Route::post('/drivers/{driver}/reject', [AdminController::class, 'rejectDriver'])->name('driver.reject');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('user.toggle-status');
        
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});
