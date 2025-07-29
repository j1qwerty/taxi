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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('profile_photo')->nullable();
            $table->string('driving_license')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('vehicle_insurance')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('vehicle_rc')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('police_verification')->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->enum('vehicle_type', ['bike', 'auto', 'sedan', 'suv'])->nullable();
            $table->string('vehicle_number', 20)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('vehicle_color', 50)->nullable();
            $table->boolean('is_online')->default(false);
            $table->boolean('is_available')->default(true);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_rides')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->decimal('commission_rate', 5, 2)->default(20.00);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approval_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('referral_code', 20)->unique();
            $table->integer('referred_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
