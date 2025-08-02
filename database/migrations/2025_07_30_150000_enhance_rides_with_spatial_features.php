<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add spatial columns and indexes to existing tables
        Schema::table('drivers', function (Blueprint $table) {
            // Add spatial point column for location (NOT NULL with default)
            DB::statement('ALTER TABLE drivers ADD COLUMN location POINT NOT NULL DEFAULT (POINT(0, 0))');
            
            // Add duration tracking
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->decimal('max_search_radius', 5, 2)->default(10.00); // km
        });

        Schema::table('rides', function (Blueprint $table) {
            // Add spatial columns (NOT NULL with default)
            DB::statement('ALTER TABLE rides ADD COLUMN pickup_location POINT NOT NULL DEFAULT (POINT(0, 0))');
            DB::statement('ALTER TABLE rides ADD COLUMN drop_location POINT NOT NULL DEFAULT (POINT(0, 0))');
            
            // Add real-time tracking fields
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->decimal('actual_distance', 10, 2)->nullable();
            $table->integer('actual_duration')->nullable(); // in minutes
            $table->timestamp('driver_accepted_at')->nullable();
            $table->timestamp('driver_arrived_at')->nullable();
            $table->timestamp('ride_started_at')->nullable();
            $table->timestamp('ride_completed_at')->nullable();
            
            // Driver tracking during ride
            $table->json('route_tracking')->nullable(); // Store GPS points during ride
            $table->decimal('max_waiting_time', 5, 2)->default(10.00); // minutes
            
            // Enhanced pricing
            $table->decimal('waiting_charges', 8, 2)->default(0.00);
            $table->decimal('toll_charges', 8, 2)->default(0.00);
            $table->decimal('night_charges', 8, 2)->default(0.00);
        });

        // Add spatial indexes after tables are created
        DB::statement('ALTER TABLE drivers ADD SPATIAL INDEX idx_location (location)');
        DB::statement('ALTER TABLE rides ADD SPATIAL INDEX idx_pickup_location (pickup_location)');
        DB::statement('ALTER TABLE rides ADD SPATIAL INDEX idx_drop_location (drop_location)');

        // Create ride_tracking table for real-time GPS data
        Schema::create('ride_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable(); // km/h
            $table->decimal('bearing', 5, 2)->nullable(); // degrees
            $table->enum('status', ['pickup', 'enroute', 'arrived', 'started', 'completed']);
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            // Spatial column for tracking points
            $table->index(['ride_id', 'recorded_at']); // Regular index for queries
        });

        // Add spatial column separately for ride_tracking (nullable since updated dynamically)
        DB::statement('ALTER TABLE ride_tracking ADD COLUMN location POINT NULL');

        // Create driver_sessions table for online/offline tracking
        Schema::create('driver_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->timestamp('online_at');
            $table->timestamp('offline_at')->nullable();
            $table->decimal('session_earnings', 10, 2)->default(0.00);
            $table->integer('rides_completed')->default(0);
            $table->json('session_data')->nullable(); // Store session analytics
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_sessions');
        Schema::dropIfExists('ride_tracking');
        
        Schema::table('rides', function (Blueprint $table) {
            DB::statement('ALTER TABLE rides DROP COLUMN pickup_location');
            DB::statement('ALTER TABLE rides DROP COLUMN drop_location');
            $table->dropColumn([
                'estimated_duration', 'actual_distance', 'actual_duration',
                'driver_accepted_at', 'driver_arrived_at', 'ride_started_at', 
                'ride_completed_at', 'route_tracking', 'max_waiting_time',
                'waiting_charges', 'toll_charges', 'night_charges'
            ]);
        });
        
        Schema::table('drivers', function (Blueprint $table) {
            DB::statement('ALTER TABLE drivers DROP INDEX idx_location');
            DB::statement('ALTER TABLE drivers DROP COLUMN location');
            $table->dropColumn(['estimated_duration', 'max_search_radius']);
        });
    }
};
