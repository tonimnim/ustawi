# Immediate Fix for Paystack Testing

## Quick Solution (No Ngrok Needed)

### Step 1: Update Paystack Dashboard
In your Paystack dashboard, use these placeholder URLs:
- **Test Callback URL**: `https://ustawi-test.com/donations/callback`
- **Test Webhook URL**: `https://ustawi-test.com/paystack/webhook`

Save the changes in Paystack dashboard.

### Step 2: Test the Payment Flow

1. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Start the server**:
   ```bash
   php artisan serve
   ```

3. **Check Laravel logs** while testing:
   ```bash
   # In another terminal
   tail -f storage/logs/laravel.log
   ```

4. **Visit**: http://localhost:8000/donate

### Step 3: What Happens During Testing

1. Fill the donation form and submit
2. You'll be redirected to Paystack payment page
3. Complete the payment with test card
4. **IMPORTANT**: After payment, Paystack will try to redirect to the fake URL
5. **Manual Check**: Copy the payment reference from the URL
6. Visit: http://localhost:8000/donations/callback?reference=YOUR_REFERENCE

### Test Payment Details

**For Card:**
- Number: 4084 0840 8408 4081
- CVV: 408
- Expiry: 12/25
- PIN: 0000

**For M-Pesa (Test Mode):**
- Phone: 0700000000
- No actual M-Pesa prompt appears in test mode

## Debugging Tips

1. **Check if payment is reaching Paystack**:
   - Look in Laravel logs for "Initializing Paystack payment"
   - Check for any error messages

2. **Common Issues**:
   - "Invalid key" = Check your .env keys
   - "Currency not supported" = Make sure currency is KES
   - "Channel not available" = M-Pesa might need merchant account approval

3. **If nothing happens when clicking pay**:
   - Check browser console for JavaScript errors
   - Check network tab for failed requests
   - Check Laravel logs for PHP errors

## Alternative: Test with Postman

You can test the API directly:

```bash
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
```

If this works, the issue is in the Laravel implementation.
If this fails, the issue is with Paystack configuration/keys.