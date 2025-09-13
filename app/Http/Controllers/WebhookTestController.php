<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookTestController extends Controller
{
    /**
     * Test webhook endpoint to verify it's accessible
     */
    public function test(Request $request)
    {
        Log::info('Webhook test endpoint hit', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook endpoint is accessible',
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Manually verify a payment status with Paystack
     */
    public function verifyPayment($reference)
    {
        try {
            $secretKey = config('paystack.secretKey');
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $secretKey,
                    "Cache-Control: no-cache",
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            if ($err) {
                Log::error('Paystack verification error', ['error' => $err]);
                return response()->json(['error' => 'Verification failed'], 500);
            }
            
            $result = json_decode($response, true);
            
            Log::info('Paystack verification result', $result);
            
            // If payment is successful, update the donation
            if ($result['status'] && $result['data']['status'] === 'success') {
                $donation = \DB::table('donations')
                    ->where('donation_number', $reference)
                    ->first();
                    
                if ($donation && $donation->status !== 'completed') {
                    \DB::table('donations')
                        ->where('id', $donation->id)
                        ->update([
                            'status' => 'completed',
                            'gateway_response' => json_encode($result['data']),
                            'processed_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                    Log::info('Donation updated via manual verification', [
                        'donation_id' => $donation->id,
                        'reference' => $reference
                    ]);
                }
            }
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Manual verification error', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            
            return response()->json([
                'error' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }
}