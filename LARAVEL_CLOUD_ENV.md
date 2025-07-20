# Laravel Cloud Environment Variables

Add these custom environment variables to your Laravel Cloud dashboard:

```env
# Email Configuration (Zoho)
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=stephenkiarie@ustawiwajamii.org
MAIL_PASSWORD=your-actual-zoho-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=stephenkiarie@ustawiwajamii.org
MAIL_FROM_NAME="Ustawi Wa Jamii"

# Cache Configuration
CACHE_PREFIX=ustawi_

# Queue Configuration
QUEUE_CONNECTION=database

# Filesystem Configuration  
FILESYSTEM_DISK=public

# Storage URL (update with your actual URL)
ASSET_URL=https://ustawi-main-zqyxpx.laravel.cloud/storage
```

## Deploy Commands

Add this to your Laravel Cloud deploy commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan migrate --force
php artisan storage:link || true
php artisan storage:ensure-link || true
```

## Important Notes

1. **Storage Link**: Laravel Cloud might require special handling for storage links. The `storage:ensure-link` command will create it if possible.

2. **File Uploads**: All uploaded files are stored in `storage/app/public/` with subdirectories:
   - `homepage/` - Homepage carousel images
   - `media/` - General media files
   - `careers/` - CV uploads

3. **CSRF Token**: Already configured in the app, should work automatically.

4. **Database**: Using PostgreSQL provided by Laravel Cloud.