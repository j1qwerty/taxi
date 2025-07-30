<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->string('ride_id', 20)->unique();
            $table->foreignId('rider_id')->constrained('riders')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->text('pickup_address');
            $table->decimal('pickup_latitude', 10, 8);
            $table->decimal('pickup_longitude', 11, 8);
            $table->text('drop_address');
            $table->decimal('drop_latitude', 10, 8);
            $table->decimal('drop_longitude', 11, 8);
            $table->enum('vehicle_type', ['bike', 'auto', 'sedan', 'suv'])->default('sedan');
            $table->decimal('distance', 10, 2)->nullable();
            $table->decimal('estimated_fare', 10, 2);
            $table->decimal('actual_fare', 10, 2)->nullable();
            $table->decimal('base_fare', 10, 2);
            $table->decimal('per_km_charge', 10, 2);
            $table->decimal('surge_multiplier', 3, 2)->default(1.00);
            $table->enum('status', ['searching', 'accepted', 'arrived', 'started', 'completed', 'cancelled'])->default('searching');
            $table->string('otp', 6);
            $table->boolean('otp_verified')->default(false);
            $table->enum('payment_method', ['cash', 'wallet', 'online'])->default('cash');
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('driver_arrival_time')->nullable();
            $table->timestamp('ride_start_time')->nullable();
            $table->timestamp('ride_end_time')->nullable();
            $table->enum('cancelled_by', ['rider', 'driver', 'system'])->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('cancellation_charge', 10, 2)->default(0.00);
            $table->integer('rider_rating')->nullable();
            $table->text('rider_review')->nullable();
            $table->integer('driver_rating')->nullable();
            $table->text('driver_review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
