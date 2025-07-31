# Paystack Local Testing Solutions

## The Problem
Paystack doesn't accept `localhost` URLs for callbacks, which is why you're seeing "Test callback URL must be a valid URL" error.

## Solutions

### Solution 1: Use Ngrok (Recommended for Testing)

1. **Download Ngrok**: https://ngrok.com/download
2. **Install and run ngrok**:
   ```bash
   ngrok http 8000
   ```
3. **You'll get a public URL like**: `https://abc123.ngrok-free.app`
4. **Update your Paystack dashboard**:
   - Test Callback URL: `https://abc123.ngrok-free.app/donations/callback`
   - Test Webhook URL: `https://abc123.ngrok-free.app/paystack/webhook`

### Solution 2: Use a Temporary Domain (Quick Fix)

In your Paystack dashboard, you can use a placeholder URL for now:
- Test Callback URL: `https://ustawi-test.com/donations/callback`
- Test Webhook URL: `https://ustawi-test.com/paystack/webhook`

Then override the callback URL in your code temporarily.

### Solution 3: Skip Dashboard Configuration (Testing Only)

For immediate testing, you can:
1. Leave the Paystack dashboard URLs as placeholders
2. The payment will still work, but you'll need to manually check payment status

## Fix for M-Pesa Payment

**IMPORTANT**: Paystack M-Pesa for Kenya uses specific configuration. Update your PaystackController.php:

```php
// In initializePayment method, update the channels configuration:
'channels' => $donation->payment_method === 'mpesa' 
    ? ['mobile_money_kenya'] // Changed from 'mobile_money'
    : ['card'],
```

## Testing Steps After Fix

1. **For Card Payments**:
   - Card: 4084 0840 8408 4081
   - CVV: 408
   - Expiry: 12/25
   - PIN: 0000

2. **For M-Pesa Payments**:
   - Use test phone number: 0700000000
   - No actual M-Pesa prompt in test mode

## Temporary Code Fix (Optional)

If you want to override the callback URL in code while testing:

```php
// In PaystackController.php, temporarily add after line 40:
'callback_url' => 'https://your-ngrok-url.ngrok-free.app/donations/callback',
```

## Debug Checklist

- [ ] Cleared Laravel config cache: `php artisan config:clear`
- [ ] APP_URL in .env is correct
- [ ] Paystack keys are correct in .env
- [ ] Using the right channel names for payment methods
- [ ] Amount is multiplied by 100 (for kobo conversion)