<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationStatusController extends Controller
{
    /**
     * Check the status of a donation
     */
    public function checkStatus($donationNumber)
    {
        $donation = DB::table('donations')
            ->where('donation_number', $donationNumber)
            ->first();
            
        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'status' => $donation->status,
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'payment_method' => $donation->payment_method,
            'created_at' => $donation->created_at,
            'completed_at' => $donation->processed_at,
            'message' => $this->getStatusMessage($donation->status)
        ]);
    }
    
    /**
     * Get user-friendly status message
     */
    private function getStatusMessage($status)
    {
        return match($status) {
            'pending' => 'Your donation is pending processing.',
            'processing' => 'Your donation is being processed. Please complete the payment on your phone.',
            'completed' => 'Thank you! Your donation has been successfully received.',
            'failed' => 'The donation could not be processed. Please try again.',
            'cancelled' => 'The donation was cancelled.',
            default => 'Unknown status'
        };
    }
}