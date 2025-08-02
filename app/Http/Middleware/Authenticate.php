<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API routes, always return null (no redirect)
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }
        
        // For web routes, redirect to login (if route exists)
        if (Route::has('login')) {
            return route('login');
        }
        
        return null;
    }
}
