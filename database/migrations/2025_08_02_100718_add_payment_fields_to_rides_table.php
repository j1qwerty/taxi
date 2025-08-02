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
            $table->string('payment_gateway')->nullable()->after('payment_status');
            $table->json('gateway_response')->nullable()->after('payment_gateway');
            $table->timestamp('payment_updated_at')->nullable()->after('gateway_response');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('payment_updated_at');
            $table->string('refund_reason')->nullable()->after('refund_amount');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            $table->string('refund_transaction_id')->nullable()->after('refunded_at');
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
