# Paystack Setup - Complete Guide

## ✅ What We've Fixed

1. **M-Pesa Integration**: Now uses the correct `/charge` API endpoint for mobile money
2. **Phone Number Validation**: Required for M-Pesa payments
3. **Proper Error Handling**: Better error messages and logging
4. **Webhook Support**: Ready to receive payment notifications

## 🚀 Quick Setup for Testing

### Step 1: Update Paystack Dashboard

Since Paystack doesn't accept localhost URLs, use these placeholder URLs in your dashboard:

1. Go to https://dashboard.paystack.com/#/settings/developer
2. Set these URLs:
   - **Test Callback URL**: `https://yourdomain.com/donations/callback`
   - **Test Webhook URL**: `https://yourdomain.com/paystack/webhook`
3. Save the settings

### Step 2: Clear Cache and Start Server

```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

### Step 3: Test the Payment Flow

1. Visit: http://localhost:8000/donate
2. Fill the form:
   - Select amount
   - Choose payment method (Card or M-Pesa)
   - For M-Pesa: Enter phone number (e.g., 0700000000)
   - Fill donor details

### Step 4: Test Credentials

**For Card Payments:**
- Card Number: `4084 0840 8408 4081`
- CVV: `408`
- Expiry: `12/25`
- PIN: `0000`

**For M-Pesa (Test Mode):**
- Phone: `0700000000` (any valid format)
- No actual M-Pesa prompt in test mode
- You'll see a success message to check your phone

## 🔍 How It Works Now

### Card Payment Flow:
1. User submits donation form
2. System creates donation record
3. Redirects to Paystack payment page
4. User completes payment
5. Paystack redirects back (will fail due to localhost)
6. Webhook updates payment status

### M-Pesa Payment Flow:
1. User submits donation form with phone number
2. System creates donation record
3. Sends charge request to Paystack
4. Shows "Check your phone" message
5. Webhook updates payment status when complete

## 🛠️ Troubleshooting

### If payments aren't working:

1. **Check Laravel Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Browser Console**:
   - Look for JavaScript errors
   - Check network tab for failed requests

3. **Common Issues**:
   - "Invalid key" → Check .env file has correct keys
   - "Phone required" → M-Pesa needs phone number
   - "Amount too small" → Minimum is KES 50

### Test API Directly:

```bash
# Test Card Payment
curl https://api.paystack.co/transaction/initialize \
  -H "Authorization: Bearer sk_test_3e5d7f4b81986add03e441b4580eeca5819a1f1e" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "amount": "10000",
    "currency": "KES",
    "channels": ["card"]
  }' \
  -X POST

# Test M-Pesa Payment
curl https://api.paystack.co/charge \
  -H "Authorization: Bearer sk_test_3e5d7f4b81986add03e441b4580eeca5819a1f1e" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "amount": "10000",
    "currency": "KES",
    "mobile_money": {
      "phone": "+254700000000",
      "provider": "mpesa"
    }
  }' \
  -X POST
```

## 📱 For Production

When deploying to production:

1. Update Paystack dashboard with real URLs:
   - Callback: `https://yourdomain.com/donations/callback`
   - Webhook: `https://yourdomain.com/paystack/webhook`

2. Update .env with live keys:
   ```env
   PAYSTACK_PUBLIC_KEY=pk_live_xxxxx
   PAYSTACK_SECRET_KEY=sk_live_xxxxx
   ```

3. Test with real payments (small amounts first!)

## 🎯 Next Steps

1. **For Local Testing**: Use the manual callback method described above
2. **For Better Testing**: Set up ngrok for real webhook testing
3. **For Production**: Deploy and use real domain URLs