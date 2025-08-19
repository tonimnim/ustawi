# Paystack Live Integration Setup

## Current Status

✅ **Completed:**
- Paystack integration code is implemented
- Support for both Card and M-Pesa payments
- Webhook handling for payment verification
- Public key configured: `pk_live_7504ac7158aaf3db6691bf2cf8d990a3321045cf`

⚠️ **Required:**
- **LIVE SECRET KEY** - You need to provide the live secret key from your Paystack dashboard

## Steps to Complete Live Setup

### 1. Add Secret Key

Update your `.env` file with the live secret key:

```env
PAYSTACK_SECRET_KEY=sk_live_YOUR_SECRET_KEY_HERE
```

### 2. Configure Webhook URL in Paystack Dashboard

1. Log into your [Paystack Dashboard](https://dashboard.paystack.com)
2. Go to **Settings** → **API Keys & Webhooks**
3. Add your webhook URL:
   ```
   https://yourdomain.com/paystack/webhook
   ```
4. Select the following events to listen for:
   - `charge.success`
   - `charge.failed`
   - `transfer.success` (if using transfers)

### 3. Configure Payment Channels

In your Paystack dashboard:
1. Go to **Settings** → **Preferences**
2. Enable the following channels:
   - ✅ Cards
   - ✅ Mobile Money (M-Pesa)
   - ✅ Bank Transfer (optional)

### 4. Test Live Payments

Before going fully live, test with small amounts:

#### Test Card Payment:
1. Use a real card with a small amount (e.g., KES 100)
2. Verify the payment appears in your Paystack dashboard
3. Check if the donation status updates in your database

#### Test M-Pesa Payment:
1. Use a real M-Pesa number
2. Complete the STK push prompt
3. Verify the payment in both Paystack and your database

### 5. Update Frontend Configuration

Make sure to rebuild your assets after updating the .env file:

```bash
# Clear config cache
php artisan config:clear

# Rebuild frontend assets
npm run build
```

### 6. Production Checklist

Before going live, ensure:

- [ ] Both public and secret keys are set in `.env`
- [ ] Webhook URL is configured in Paystack dashboard
- [ ] SSL certificate is active (HTTPS required)
- [ ] Email notifications are configured
- [ ] Database backups are scheduled
- [ ] Error logging is enabled
- [ ] Test transactions completed successfully

## Payment Flow Overview

### Card Payments:
1. User fills donation form
2. System creates pending donation record
3. User redirected to Paystack payment page
4. After payment, callback URL handles verification
5. Donation status updated to completed/failed
6. Webhook provides additional verification

### M-Pesa Payments:
1. User provides phone number
2. System initiates STK push via Paystack
3. User approves payment on phone
4. Webhook updates donation status
5. Success message displayed to user

## Important URLs

- **Callback URL**: `https://yourdomain.com/donations/callback`
- **Webhook URL**: `https://yourdomain.com/paystack/webhook`
- **Success Page**: `https://yourdomain.com/donate` (with success message)

## Security Considerations

1. **Webhook Verification**: The system verifies webhook signatures to prevent fraud
2. **Amount Validation**: Always verify the amount received matches the expected amount
3. **Status Checks**: Never trust client-side payment confirmations alone
4. **SSL Required**: Paystack requires HTTPS for production
5. **Key Security**: Never commit secret keys to version control

## Troubleshooting

### Common Issues:

1. **"Invalid Secret Key"**
   - Ensure you're using the live secret key, not test key
   - Check for extra spaces in the .env file

2. **M-Pesa Not Working**
   - Verify M-Pesa is enabled in your Paystack account
   - Check phone number format (+254...)
   - Ensure sufficient balance for testing

3. **Webhook Not Receiving Events**
   - Verify webhook URL is publicly accessible
   - Check SSL certificate is valid
   - Review webhook logs in Paystack dashboard

4. **Payment Successful but Status Not Updated**
   - Check database connection
   - Verify webhook signature validation
   - Review Laravel logs for errors

## Testing Commands

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Test webhook manually (replace with actual webhook data)
curl -X POST http://localhost:8000/paystack/webhook \
  -H "Content-Type: application/json" \
  -H "x-paystack-signature: YOUR_SIGNATURE" \
  -d '{"event":"charge.success","data":{...}}'

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Support Contacts

- **Paystack Support**: support@paystack.com
- **Paystack Documentation**: https://paystack.com/docs
- **API Reference**: https://paystack.com/docs/api

## Next Steps

1. Add your live secret key to `.env`
2. Configure webhook in Paystack dashboard
3. Run `php artisan config:clear`
4. Run `npm run build`
5. Test with a real payment
6. Monitor the first few live transactions closely

---

**Important**: Keep your secret key secure and never share it publicly!