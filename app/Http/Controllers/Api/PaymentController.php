<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function updatePaymentStatus(Request $request, $rideId)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,processing,completed,failed,refunded',
            'payment_gateway' => 'nullable|string|in:stripe,paypal,razorpay,wallet,cash',
            'transaction_id' => 'nullable|string',
            'gateway_response' => 'nullable|array'
        ]);

        $user = $request->user();
        $ride = Ride::with(['rider.user', 'driver.user'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        // Check authorization - rider or driver can update payment
        $canUpdate = false;
        if ($user->user_type === 'rider' && $ride->rider->user_id === $user->id) {
            $canUpdate = true;
        } elseif ($user->user_type === 'driver' && $ride->driver && $ride->driver->user_id === $user->id) {
            $canUpdate = true;
        }

        if (!$canUpdate) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update payment for this ride'
            ], 403);
        }

        // Update payment status
        $updateData = [
            'payment_status' => $request->payment_status,
            'payment_updated_at' => now()
        ];

        if ($request->payment_gateway) {
            $updateData['payment_gateway'] = $request->payment_gateway;
        }

        if ($request->transaction_id) {
            $updateData['transaction_id'] = $request->transaction_id;
        }

        if ($request->gateway_response) {
            $updateData['gateway_response'] = json_encode($request->gateway_response);
        }

        $ride->update($updateData);

        // If payment completed, update wallet balances
        if ($request->payment_status === 'completed' && $ride->payment_method === 'wallet') {
            $this->processWalletPayment($ride);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment status updated successfully',
            'data' => [
                'ride_id' => $ride->id,
                'payment_status' => $ride->payment_status,
                'payment_method' => $ride->payment_method,
                'payment_gateway' => $ride->payment_gateway,
                'estimated_fare' => $ride->estimated_fare,
                'actual_fare' => $ride->actual_fare,
                'transaction_id' => $ride->transaction_id,
                'payment_updated_at' => $ride->payment_updated_at
            ]
        ]);
    }

    public function processPayment(Request $request, $rideId)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,wallet,card,stripe,paypal',
            'amount' => 'required|numeric|min:0',
            'card_token' => 'nullable|string', // For Stripe/card payments
            'wallet_pin' => 'nullable|string' // For wallet payments
        ]);

        $user = $request->user();
        $ride = Ride::with(['rider.user', 'driver.user'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        // Only rider can process payment
        if ($user->user_type !== 'rider' || $ride->rider->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only the rider can process payment'
            ], 403);
        }

        if ($ride->status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment can only be processed for completed rides'
            ], 400);
        }

        if ($ride->payment_status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment already completed for this ride'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $paymentResult = [];

            switch ($request->payment_method) {
                case 'wallet':
                    $paymentResult = $this->processWalletPayment($ride, $request->wallet_pin);
                    break;
                
                case 'stripe':
                case 'card':
                    $paymentResult = $this->processStripePayment($ride, $request->card_token);
                    break;
                
                case 'paypal':
                    $paymentResult = $this->processPayPalPayment($ride);
                    break;
                
                case 'cash':
                    $paymentResult = $this->processCashPayment($ride);
                    break;
            }

            if ($paymentResult['success']) {
                $ride->update([
                    'payment_status' => 'completed',
                    'payment_method' => $request->payment_method,
                    'actual_fare' => $request->amount,
                    'transaction_id' => $paymentResult['transaction_id'] ?? null,
                    'payment_gateway' => $request->payment_method,
                    'gateway_response' => json_encode($paymentResult['response'] ?? []),
                    'payment_updated_at' => now()
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully',
                    'data' => [
                        'ride_id' => $ride->id,
                        'payment_status' => 'completed',
                        'payment_method' => $request->payment_method,
                        'amount_paid' => $request->amount,
                        'transaction_id' => $paymentResult['transaction_id'] ?? null,
                        'payment_timestamp' => now()
                    ]
                ]);
            } else {
                DB::rollback();
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed: ' . $paymentResult['error'],
                    'error_code' => $paymentResult['error_code'] ?? 'PAYMENT_FAILED'
                ], 400);
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentHistory(Request $request)
    {
        $user = $request->user();
        
        if ($user->user_type === 'rider') {
            $rides = Ride::whereHas('rider', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNotNull('payment_status')
              ->orderBy('payment_updated_at', 'desc')
              ->paginate(10);
        } elseif ($user->user_type === 'driver') {
            $rides = Ride::whereHas('driver', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNotNull('payment_status')
              ->orderBy('payment_updated_at', 'desc')
              ->paginate(10);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid user type'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'payments' => $rides->map(function ($ride) {
                    return [
                        'ride_id' => $ride->id,
                        'ride_reference' => $ride->ride_id,
                        'payment_status' => $ride->payment_status,
                        'payment_method' => $ride->payment_method,
                        'payment_gateway' => $ride->payment_gateway,
                        'estimated_fare' => $ride->estimated_fare,
                        'actual_fare' => $ride->actual_fare,
                        'transaction_id' => $ride->transaction_id,
                        'pickup_address' => $ride->pickup_address,
                        'drop_address' => $ride->drop_address,
                        'ride_date' => $ride->created_at,
                        'payment_date' => $ride->payment_updated_at
                    ];
                }),
                'pagination' => [
                    'current_page' => $rides->currentPage(),
                    'total' => $rides->total(),
                    'per_page' => $rides->perPage(),
                    'last_page' => $rides->lastPage()
                ]
            ]
        ]);
    }

    private function processWalletPayment($ride, $pin = null)
    {
        // Mock wallet payment processing
        $rider = $ride->rider;
        $amount = $ride->estimated_fare;

        if ($rider && $rider->wallet_balance < $amount) {
            return [
                'success' => false,
                'error' => 'Insufficient wallet balance',
                'error_code' => 'INSUFFICIENT_BALANCE'
            ];
        }

        // Mock successful payment
        return [
            'success' => true,
            'transaction_id' => 'WLT' . time() . rand(1000, 9999),
            'response' => [
                'amount_deducted' => $amount,
                'remaining_balance' => $rider ? $rider->wallet_balance - $amount : 0
            ]
        ];
    }

    private function processStripePayment($ride, $cardToken)
    {
        // Mock Stripe payment processing
        // In production, integrate with Stripe SDK
        return [
            'success' => true,
            'transaction_id' => 'stripe_' . time() . '_' . rand(10000, 99999),
            'response' => [
                'status' => 'succeeded',
                'amount' => $ride->estimated_fare * 100, // Stripe uses cents
                'currency' => 'usd'
            ]
        ];
    }

    private function processPayPalPayment($ride)
    {
        // Mock PayPal payment processing
        // In production, integrate with PayPal SDK
        return [
            'success' => true,
            'transaction_id' => 'paypal_' . time() . '_' . rand(10000, 99999),
            'response' => [
                'status' => 'COMPLETED',
                'amount' => $ride->estimated_fare,
                'currency' => 'USD'
            ]
        ];
    }

    private function processCashPayment($ride)
    {
        // Cash payment is processed offline
        return [
            'success' => true,
            'transaction_id' => 'cash_' . time() . '_' . rand(1000, 9999),
            'response' => [
                'status' => 'cash_collected',
                'amount' => $ride->estimated_fare
            ]
        ];
    }

    public function refundPayment(Request $request, $rideId)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:500',
            'refund_amount' => 'nullable|numeric|min:0'
        ]);

        $user = $request->user();
        $ride = Ride::with(['rider.user', 'driver.user'])->find($rideId);

        if (!$ride) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ride not found'
            ], 404);
        }

        if ($ride->payment_status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Can only refund completed payments'
            ], 400);
        }

        $refundAmount = $request->refund_amount ?? $ride->actual_fare;

        try {
            DB::beginTransaction();

            // Process refund based on original payment method
            $refundResult = $this->processRefund($ride, $refundAmount, $request->refund_reason);

            if ($refundResult['success']) {
                $ride->update([
                    'payment_status' => 'refunded',
                    'refund_amount' => $refundAmount,
                    'refund_reason' => $request->refund_reason,
                    'refunded_at' => now(),
                    'refund_transaction_id' => $refundResult['refund_transaction_id']
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Refund processed successfully',
                    'data' => [
                        'ride_id' => $ride->id,
                        'refund_amount' => $refundAmount,
                        'refund_transaction_id' => $refundResult['refund_transaction_id'],
                        'refund_status' => 'completed'
                    ]
                ]);
            } else {
                DB::rollback();
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Refund failed: ' . $refundResult['error']
                ], 400);
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Refund processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processRefund($ride, $amount, $reason)
    {
        // Mock refund processing
        return [
            'success' => true,
            'refund_transaction_id' => 'refund_' . time() . '_' . rand(10000, 99999)
        ];
    }
}
