# Production Payment Setup Checklist

## CRITICAL: Before Going Live

### 1. Paystack Live API Keys
- [ ] Login to Paystack Dashboard
- [ ] Get your LIVE API keys (not test keys)
- [ ] Update production `.env` with:
  ```
  PAYSTACK_PUBLIC_KEY=pk_live_xxxxx
  PAYSTACK_SECRET_KEY=sk_live_xxxxx
  PAYSTACK_MERCHANT_EMAIL=stephenkiarie@ustawiwajamii.org
  PAYSTACK_CURRENCY=KES
  ```

### 2. Paystack Dashboard Settings
- [ ] Update Callback URL: `https://yourdomain.com/donations/callback`
- [ ] Update Webhook URL: `https://yourdomain.com/paystack/webhook`
- [ ] Enable both Card and Mobile Money (M-Pesa) channels
- [ ] Verify your business details are complete

### 3. Code Updates (Already Done)
- [x] SSL verification bypass commented out in PaystackController.php
- [x] Payment methods configured (Card and M-Pesa only)

### 4. Production Environment
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_URL=https://yourdomain.com` in `.env`

### 5. Testing with Live Keys
- [ ] Test small amount donation with M-Pesa
- [ ] Test small amount donation with Card
- [ ] Verify webhooks are received
- [ ] Check donation records in database

### 6. M-Pesa Production Notes
- Real phone numbers will work (no test numbers needed)
- Users will receive actual M-Pesa prompts
- Real money will be transferred

### 7. Security Reminders
- Never commit live API keys to git
- Keep production `.env` secure
- Monitor failed payment attempts
- Set up email notifications for successful donations

## Support Contacts
- Paystack Support: support@paystack.com
- Paystack Kenya: +254 20 520 1570