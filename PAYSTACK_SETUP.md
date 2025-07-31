# Paystack Integration Setup

## Test Keys (Already configured in .env)
- **Test Secret Key**: sk_test_3e5d7f4b81986add03e441b4580eeca5819a1f1e
- **Test Public Key**: pk_test_2e6c4d563cff71607faeab7b6465b9458e6d13ee

## Callback & Webhook URLs

Based on your local development setup, here are the URLs you need to configure in your Paystack dashboard:

### For Local Development (http://localhost)
- **Test Callback URL**: `http://localhost/donations/callback`
- **Test Webhook URL**: `http://localhost/paystack/webhook`

### For Production (when deployed)
Replace `yourdomain.com` with your actual domain:
- **Callback URL**: `https://yourdomain.com/donations/callback`
- **Webhook URL**: `https://yourdomain.com/paystack/webhook`

## How to Configure in Paystack Dashboard

1. **Login to Paystack Dashboard**: https://dashboard.paystack.com/

2. **Navigate to Settings**:
   - Click on "Settings" in the left sidebar
   - Go to "API Keys & Webhooks" tab

3. **Set the Callback URL**:
   - Find the "Callback URL" field
   - Enter: `http://localhost/donations/callback` (for local testing)
   - This URL is where Paystack will redirect users after payment

4. **Set the Webhook URL**:
   - Find the "Webhook URL" field
   - Enter: `http://localhost/paystack/webhook`
   - This URL is where Paystack will send payment notifications

## Important Notes for Local Testing

### Option 1: Using Laravel's Built-in Server
If you're using `php artisan serve`, your URLs will be:
- **Callback URL**: `http://localhost:8000/donations/callback`
- **Webhook URL**: `http://localhost:8000/paystack/webhook`

### Option 2: Using Ngrok for Webhook Testing (Recommended)
Since Paystack can't reach your localhost directly for webhooks, you'll need to use a tunneling service:

1. Download and install [Ngrok](https://ngrok.com/)
2. Run: `ngrok http 8000` (or your port number)
3. Use the provided ngrok URL for webhooks:
   - Example: `https://abc123.ngrok.io/paystack/webhook`

### Option 3: Skip Webhook for Local Testing
For initial testing, you can:
- Just use the callback URL for testing payments
- Webhooks are mainly for production to handle payment notifications

## Testing the Integration

1. **Start your Laravel server**:
   ```bash
   php artisan serve
   ```

2. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

3. **Visit the donation page**: http://localhost:8000/donate

4. **Make a test donation**:
   - Use Paystack test card: 4084 0840 8408 4081
   - CVV: 408
   - Expiry: Any future date
   - PIN: 0000

## Flow Explanation

1. User fills donation form on `/donate`
2. Form submits to `/donate` (POST)
3. Donation record is created with "pending" status
4. User is redirected to `/donations/pay/{id}`
5. PaystackController initializes payment and redirects to Paystack
6. After payment, Paystack redirects back to `/donations/callback`
7. Callback verifies payment and updates donation status
8. Webhook (if configured) provides additional payment confirmation

## Troubleshooting

- **"Invalid key" error**: Check that your .env file has the correct keys
- **Callback not working**: Ensure APP_URL in .env matches your actual URL
- **Amount issues**: Paystack expects amount in kobo/pesewas (multiply by 100)