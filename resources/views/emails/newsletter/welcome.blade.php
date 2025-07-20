@component('mail::message')
# Welcome to {{ config('app.name') }} Newsletter!

Thank you for subscribing to our newsletter. We're excited to keep you updated on our latest programs, initiatives, and community impact.

## What to Expect

- **Monthly Updates**: Receive our monthly newsletter with highlights from our programs
- **Success Stories**: Read inspiring stories from the communities we serve
- **Event Announcements**: Be the first to know about upcoming events and volunteer opportunities
- **Impact Reports**: See how your support makes a difference

## Stay Connected

Follow us on social media for daily updates:
- Facebook: [Ustawi Wa Jamii](https://facebook.com/ustawiwajamii)
- Twitter: [@UstawiWaJamii](https://twitter.com/ustawiwajamii)
- Instagram: [@ustawiwajamii](https://instagram.com/ustawiwajamii)

@component('mail::button', ['url' => config('app.url')])
Visit Our Website
@endcomponent

If you ever wish to unsubscribe, you can do so by clicking the unsubscribe link at the bottom of any of our emails.

Thank you for being part of the Ustawi Wa Jamii community!

Best regards,<br>
The {{ config('app.name') }} Team
@endcomponent