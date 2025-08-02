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
            if (!Schema::hasColumn('rides', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('rides', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('payment_gateway');
            }
            if (!Schema::hasColumn('rides', 'payment_updated_at')) {
                $table->timestamp('payment_updated_at')->nullable()->after('gateway_response');
            }
            if (!Schema::hasColumn('rides', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('payment_updated_at');
            }
            if (!Schema::hasColumn('rides', 'refund_reason')) {
                $table->string('refund_reason')->nullable()->after('refund_amount');
            }
            if (!Schema::hasColumn('rides', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            }
            if (!Schema::hasColumn('rides', 'refund_transaction_id')) {
                $table->string('refund_transaction_id')->nullable()->after('refunded_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'gateway_response', 
                'payment_updated_at',
                'refund_amount',
                'refund_reason',
                'refunded_at',
                'refund_transaction_id'
            ]);
        });
    }
};
