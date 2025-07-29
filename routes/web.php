<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome-new');
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel is working!',
        'timestamp' => now(),
        'status' => 'success'
    ]);
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
