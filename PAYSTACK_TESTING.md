# Paystack Testing Information

## Test Phone Numbers for M-Pesa

When using Paystack test API keys, you must use their designated test phone numbers:

### Kenya M-Pesa Test Numbers:
- **0710000000** (or +254710000000) - Paystack's official test number for M-Pesa
- No PIN/OTP required for test transactions

### How to Test M-Pesa Payments:

1. Go to the donation page
2. Select M-Pesa as payment method
3. Use one of the test phone numbers above (without the +254, just enter 0700000000)
4. Complete the donation

**Note:** With test mode, you won't receive an actual M-Pesa prompt on your phone. Paystack will simulate the payment based on the test number used.

### For Production:
- Use your real Paystack live keys (not test keys)
- Real phone numbers will work
- Actual M-Pesa prompts will be sent

## Important Before Deployment:

1. Remove `->withoutVerifying()` from line 136 in `app/Http/Controllers/PaystackController.php`
2. Update Paystack dashboard with your production callback URL
3. Switch to live API keys in your .env file