# M-Pesa Payment Integration Fix

## Problem
M-Pesa payments were being initiated but not showing any feedback to users. After submitting the donation form with M-Pesa as the payment method, nothing happened on the frontend even though the backend was processing the payment correctly.

## Solution Implemented

### 1. Frontend Modal System
- Added a processing modal that displays when M-Pesa payment is initiated
- Shows loading spinner initially, then displays STK push instructions
- Includes important reminder for users to check their phone and enter PIN

### 2. Status Polling
- Implemented automatic status checking every 3 seconds
- Polls `/donations/status/{donation_number}` endpoint
- Automatically updates UI when payment is completed or fails
- Stops polling after 30 seconds or when payment status changes

### 3. Backend Improvements
- PaystackController now returns donation number with flash messages
- Added DonationStatusController for checking payment status
- Webhook endpoint properly configured with CSRF exemption
- Returns appropriate messages for different payment states

### 4. Files Modified
- `/resources/js/Pages/Donate/Index.vue` - Added M-Pesa modal and status polling
- `/app/Http/Controllers/PaystackController.php` - Added donation_number to response
- `/app/Http/Controllers/DonationStatusController.php` - New controller for status checks
- `/app/Http/Middleware/HandleInertiaRequests.php` - Added flash messages and donation_number sharing
- `/bootstrap/app.php` - Added CSRF exemption for webhook
- `/routes/web.php` - Added status check route

## Testing Instructions

### For Testing (Use Paystack Test Number)
1. Go to the donation page
2. Select M-Pesa as payment method
3. Use phone number: 0710000000 (Paystack's test M-Pesa number)
4. Submit the form
5. You should see the M-Pesa modal with instructions

### For Production
1. Ensure Paystack webhook URL is configured in dashboard: `https://yourdomain.com/paystack/webhook`
2. Mobile money must be enabled in Paystack account
3. Use real phone numbers with format: 07XXXXXXXX or 254XXXXXXXX
4. Users will receive actual STK push on their phones

## How It Works

1. User submits donation form with M-Pesa selected
2. Backend creates donation record with 'pending' status
3. PaystackController calls Paystack Charge API for M-Pesa
4. Paystack returns 'pay_offline' status with STK push sent to phone
5. Frontend shows modal with instructions
6. Frontend polls for status updates every 3 seconds
7. When user completes payment on phone, Paystack sends webhook
8. Webhook updates donation status to 'completed'
9. Status polling detects completion and shows success message
10. Page reloads to show final success state

## Important Notes

- M-Pesa payments are asynchronous (offline) - they require user action on their phone
- Card payments are synchronous - they redirect to Paystack checkout immediately
- Always use the test phone number (0710000000) in development
- Webhook must be accessible from internet for production
- Payment status updates come via webhook, not direct API response