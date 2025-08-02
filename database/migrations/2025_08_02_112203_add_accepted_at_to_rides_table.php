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
            if (!Schema::hasColumn('rides', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('rides', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('accepted_at');
            }
            if (!Schema::hasColumn('rides', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['accepted_at', 'started_at', 'completed_at']);
        });
    }
};
