<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yabacon\Paystack;
use Yabacon\Paystack\Exception\ApiException;
use Inertia\Inertia;

class PaystackController extends Controller
{
    protected $paystack;

    public function __construct()
    {
        $this->paystack = new Paystack(config('paystack.secretKey'));
    }

    /**
     * Initialize payment with Paystack
     */
    public function initializePayment($donationId)
    {
        $donation = DB::table('donations')->find($donationId);
        
        if (!$donation || $donation->status !== 'pending') {
            return redirect()->route('donate')
                ->with('error', 'Invalid donation request.');
        }

        try {
            $amount = $donation->amount * 100; // Convert to kobo/cents
            $email = $donation->donor_email ?: 'anonymous@ustawiwajamii.org';
            
            // Log payment initialization
            \Log::info('Initializing Paystack payment', [
                'donation_id' => $donationId,
                'amount' => $amount,
                'currency' => $donation->currency,
                'payment_method' => $donation->payment_method,
                'phone' => $donation->donor_phone
            ]);
            
            // For M-Pesa payments, use the charge API
            if ($donation->payment_method === 'mpesa') {
                return $this->initializeMobileMoney($donation, $amount, $email);
            }
            
            // For card payments, use the transaction initialize API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paystack.secretKey'),
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.paystack.co/transaction/initialize', [
                'amount' => $amount,
                'email' => $email,
                'currency' => $donation->currency,
                'reference' => $donation->donation_number,
                'callback_url' => route('donations.callback'),
                'metadata' => [
                    'donation_id' => $donationId,
                    'donor_name' => $donation->donor_name,
                    'project_designation' => $donation->project_designation,
                    'payment_method' => $donation->payment_method
                ],
                'channels' => ['card'],
            ]);

            $result = $response->json();
            
            \Log::info('Card payment initialization response', $result);
            
            if (!$response->successful() || !($result['status'] ?? false)) {
                throw new \Exception($result['message'] ?? 'Payment initialization failed');
            }

            // Update donation with gateway reference
            DB::table('donations')
                ->where('id', $donationId)
                ->update([
                    'gateway_transaction_id' => $result['data']['access_code'] ?? null,
                    'status' => 'processing',
                    'gateway_response' => json_encode($result),
                    'updated_at' => now()
                ]);

            // For Inertia.js, we need to return a response that forces external redirect
            return Inertia::location($result['data']['authorization_url']);

        } catch (ApiException $e) {
            \Log::error('Paystack API error', [
                'message' => $e->getMessage(),
                'response' => $e->getResponseObject(),
                'donation_id' => $donationId
            ]);
            return redirect()->route('donate')
                ->with('error', 'Payment initialization failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Payment error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'donation_id' => $donationId
            ]);
            return redirect()->route('donate')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Initialize Mobile Money payment (M-Pesa)
     */
    protected function initializeMobileMoney($donation, $amount, $email)
    {
        try {
            // Format phone number for M-Pesa (ensure it starts with +254)
            $phone = $donation->donor_phone;
            if (!$phone) {
                return redirect()->route('donate')
                    ->with('error', 'Phone number is required for M-Pesa payments.');
            }
            
            // Strip any non-numeric characters first
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            // Format phone number for Paystack (no + prefix)
            if (substr($phone, 0, 1) === '0') {
                $phone = '254' . substr($phone, 1);
            } elseif (substr($phone, 0, 3) !== '254') {
                $phone = '254' . $phone;
            }
            // If already starts with 254, use as is
            
            \Log::info('Initiating M-Pesa payment', [
                'phone' => $phone,
                'amount' => $amount,
                'reference' => $donation->donation_number
            ]);
            
            // Use Paystack Charge API for Mobile Money
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paystack.secretKey'),
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.paystack.co/charge', [
                'amount' => $amount,
                'email' => $email,
                'currency' => $donation->currency,
                'mobile_money' => [
                    'phone' => $phone,
                    'provider' => 'mpesa'
                ],
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_name' => $donation->donor_name,
                    'project_designation' => $donation->project_designation,
                    'custom_fields' => [
                        [
                            'display_name' => 'Donation ID',
                            'variable_name' => 'donation_id',
                            'value' => $donation->id
                        ]
                    ]
                ]
            ]);
            
            $result = $response->json();
            
            \Log::info('M-Pesa charge response', $result);
            
            // Always update donation with the response, regardless of status
            DB::table('donations')
                ->where('id', $donation->id)
                ->update([
                    'gateway_transaction_id' => $result['data']['reference'] ?? null,
                    'gateway_response' => json_encode($result),
                    'updated_at' => now()
                ]);
            
            // Check if the request was successful (HTTP level)
            if (!$response->successful()) {
                if ($response->status() === 400) {
                    throw new \Exception('Payment failed. Please enter a valid Kenyan phone number (254XXXXXXXXX)');
                }
                throw new \Exception('Payment initialization failed. Please try again.');
            }
            
            // Handle different response statuses
            $status = $result['data']['status'] ?? '';
            $message = $result['data']['message'] ?? $result['message'] ?? '';
            
            // If it's a test number error, provide clear instructions
            if (stripos($message, 'test mobile money number') !== false) {
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update(['status' => 'failed']);
                    
                return redirect()->route('donate')
                    ->with('error', 'For testing, please use the phone number: 0710000000 (Paystack test number for M-Pesa)');
            }
            
            // If charge failed for other reasons
            if ($status === 'failed' || !($result['status'] ?? false)) {
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update(['status' => 'failed']);
                    
                throw new \Exception($message ?: 'Mobile money charge failed');
            }
            
            // Update status to processing for valid responses
            DB::table('donations')
                ->where('id', $donation->id)
                ->update(['status' => 'processing']);
            
            // For M-Pesa, we expect pay_offline status
            if (($result['data']['status'] ?? '') === 'pay_offline') {
                // For Inertia requests, return with flash message and donation number
                if (request()->header('X-Inertia')) {
                    return redirect()->route('donate')
                        ->with('success', $result['data']['display_text'] ?? 'Please complete the payment authorization on your mobile phone. You will receive an M-Pesa prompt shortly.')
                        ->with('donation_number', $donation->donation_number);
                }
                // For regular requests, return JSON
                return response()->json([
                    'success' => true,
                    'message' => $result['data']['display_text'] ?? 'Please complete the payment authorization on your mobile phone.',
                    'status' => 'pay_offline'
                ]);
            }
            
            // If payment was processed immediately (unlikely for M-Pesa)
            if (($result['data']['status'] ?? '') === 'success') {
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                if (request()->header('X-Inertia')) {
                    return redirect()->route('donate')
                        ->with('success', 'Payment completed successfully!');
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully!',
                    'status' => 'success'
                ]);
            }
            
            // Default message
            if (request()->header('X-Inertia')) {
                return redirect()->route('donate')
                    ->with('info', 'Payment initiated. Please check your phone for the M-Pesa prompt.')
                    ->with('donation_number', $donation->donation_number);
            }
            return response()->json([
                'success' => true,
                'message' => 'Payment initiated. Please check your phone for the M-Pesa prompt.',
                'status' => 'pending'
            ]);
                
        } catch (\Exception $e) {
            \Log::error('M-Pesa payment error', [
                'message' => $e->getMessage(),
                'donation_id' => $donation->id
            ]);
            
            return redirect()->route('donate')
                ->with('error', 'M-Pesa payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment callback from Paystack
     */
    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('donate')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            // Verify transaction
            $tranx = $this->paystack->transaction->verify([
                'reference' => $reference
            ]);

            if (!$tranx->status) {
                throw new \Exception('Transaction verification failed');
            }

            $donation = DB::table('donations')
                ->where('donation_number', $reference)
                ->first();

            if (!$donation) {
                throw new \Exception('Donation record not found');
            }

            if ($tranx->data->status === 'success') {
                // Update donation status
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update([
                        'status' => 'completed',
                        'gateway_response' => json_encode($tranx->data),
                        'processed_at' => now(),
                        'updated_at' => now()
                    ]);

                // Record payment transaction
                DB::table('payment_transactions')->insert([
                    'donation_id' => $donation->id,
                    'transaction_type' => 'payment',
                    'gateway' => 'paystack',
                    'gateway_transaction_id' => $tranx->data->id,
                    'amount' => $tranx->data->amount / 100,
                    'currency' => $tranx->data->currency,
                    'status' => 'completed',
                    'gateway_data' => json_encode($tranx->data),
                    'processed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                return redirect()->route('donate')
                    ->with('success', 'Thank you for your donation! Your payment has been processed successfully.');
            } else {
                // Payment failed
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update([
                        'status' => 'failed',
                        'gateway_response' => json_encode($tranx->data),
                        'updated_at' => now()
                    ]);

                return redirect()->route('donate')
                    ->with('error', 'Payment was not successful. Please try again.');
            }

        } catch (\Exception $e) {
            \Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('donate')
                ->with('error', 'Unable to verify payment. Please contact support.');
        }
    }

    /**
     * Handle webhook from Paystack
     */
    public function webhook(Request $request)
    {
        // Log incoming webhook for debugging
        \Log::info('Paystack webhook received', [
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);

        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();
        $computed = hash_hmac('sha512', $payload, config('paystack.secretKey'));

        if ($signature !== $computed) {
            \Log::warning('Invalid Paystack webhook signature', [
                'received' => $signature,
                'computed' => $computed
            ]);
            return response('Unauthorized', 401);
        }

        $event = json_decode($payload);
        
        \Log::info('Paystack webhook event', [
            'event' => $event->event,
            'reference' => $event->data->reference ?? null,
            'amount' => $event->data->amount ?? null,
            'status' => $event->data->status ?? null
        ]);

        switch ($event->event) {
            case 'charge.success':
                $this->handleChargeSuccess($event->data);
                break;
                
            case 'charge.failed':
                $this->handleChargeFailed($event->data);
                break;
                
            default:
                \Log::info('Unhandled Paystack event', ['event' => $event->event]);
        }

        return response('OK', 200);
    }

    /**
     * Handle successful charge webhook
     */
    private function handleChargeSuccess($data)
    {
        \Log::info('Processing successful charge webhook', [
            'reference' => $data->reference,
            'amount' => $data->amount,
            'id' => $data->id
        ]);

        $donation = DB::table('donations')
            ->where('donation_number', $data->reference)
            ->first();

        if (!$donation) {
            \Log::error('Donation not found for successful charge', [
                'reference' => $data->reference
            ]);
            return;
        }

        if ($donation->status === 'completed') {
            \Log::info('Donation already marked as completed', [
                'donation_id' => $donation->id
            ]);
            return;
        }

        // Update donation status
        DB::table('donations')
            ->where('id', $donation->id)
            ->update([
                'status' => 'completed',
                'gateway_response' => json_encode($data),
                'processed_at' => now(),
                'updated_at' => now()
            ]);

        \Log::info('Donation marked as completed', [
            'donation_id' => $donation->id,
            'amount' => $donation->amount,
            'donor' => $donation->donor_name
        ]);

        // Record transaction if not already recorded
        $existingTransaction = DB::table('payment_transactions')
            ->where('gateway_transaction_id', $data->id)
            ->first();

        if (!$existingTransaction) {
            DB::table('payment_transactions')->insert([
                'donation_id' => $donation->id,
                'transaction_type' => 'payment',
                'gateway' => 'paystack',
                'gateway_transaction_id' => $data->id,
                'amount' => $data->amount / 100,
                'currency' => $data->currency,
                'status' => 'completed',
                'gateway_data' => json_encode($data),
                'processed_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            \Log::info('Payment transaction recorded', [
                'donation_id' => $donation->id,
                'transaction_id' => $data->id
            ]);
        }
    }

    /**
     * Handle failed charge webhook
     */
    private function handleChargeFailed($data)
    {
        \Log::info('Processing failed charge webhook', [
            'reference' => $data->reference ?? null,
            'message' => $data->message ?? null,
            'gateway_response' => $data->gateway_response ?? null
        ]);

        $donation = DB::table('donations')
            ->where('donation_number', $data->reference)
            ->first();

        if ($donation && $donation->status !== 'failed' && $donation->status !== 'cancelled') {
            // Determine if it was cancelled or failed
            $message = $data->message ?? '';
            $gatewayResponse = $data->gateway_response ?? '';
            
            // Check for cancellation indicators
            $isCancelled = (
                stripos($message, 'cancelled') !== false ||
                stripos($message, 'canceled') !== false ||
                stripos($message, 'user abort') !== false ||
                stripos($message, 'user declined') !== false ||
                stripos($gatewayResponse, 'cancelled') !== false ||
                stripos($gatewayResponse, 'user_cancelled') !== false
            );
            
            $status = $isCancelled ? 'cancelled' : 'failed';
            
            DB::table('donations')
                ->where('id', $donation->id)
                ->update([
                    'status' => $status,
                    'gateway_response' => json_encode($data),
                    'updated_at' => now()
                ]);
                
            \Log::info('Donation marked as ' . $status, [
                'donation_id' => $donation->id,
                'reference' => $data->reference
            ]);
        }
    }
}