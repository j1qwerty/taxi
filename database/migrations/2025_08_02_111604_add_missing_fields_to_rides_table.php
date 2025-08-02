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
        Schema::table('rides', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('rides', 'distance_fare')) {
                $table->decimal('distance_fare', 10, 2)->default(0.00)->after('base_fare');
            }
            if (!Schema::hasColumn('rides', 'time_fare')) {
                $table->decimal('time_fare', 10, 2)->default(0.00)->after('distance_fare');
            }
            if (!Schema::hasColumn('rides', 'notes')) {
                $table->text('notes')->nullable()->after('otp_verified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            if (Schema::hasColumn('rides', 'distance_fare')) {
                $table->dropColumn('distance_fare');
            }
            if (Schema::hasColumn('rides', 'time_fare')) {
                $table->dropColumn('time_fare');
            }
            if (Schema::hasColumn('rides', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
