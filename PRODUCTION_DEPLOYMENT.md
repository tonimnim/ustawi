# Production Deployment Commands

Run these commands after deploying to production:

## 1. Initial Setup (First Deployment)

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.production .env
php artisan key:generate

# Database setup
php artisan migrate --force

# Seed categories (run once)
php artisan db:seed --class=BlogCategorySeeder --force

# Setup storage
php artisan storage:setup
# OR if custom command doesn't work, use:
php artisan storage:link

# Clear and optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 2. Subsequent Deployments

```bash
# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 3. Fix Storage Issues

If images are not displaying (404 errors):

```bash
# Ensure storage link exists
php artisan storage:link

# Check if symlink exists
ls -la public/storage

# If symlink is broken, remove and recreate
rm public/storage
php artisan storage:link

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/storage
```

## 4. Troubleshooting

### Images not displaying
1. Check storage symlink: `ls -la public/storage`
2. Verify file exists: `ls storage/app/public/images/`
3. Check permissions: `ls -la storage/app/public/`

### 419 CSRF Token Errors
1. Clear browser cookies
2. Clear Laravel session: `php artisan session:clear`
3. Check session configuration in `.env`

### 500 Errors
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check PHP error logs
3. Ensure all migrations ran: `php artisan migrate:status`

## 5. Environment Variables

Ensure these are set in production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ustawiwajamii.org

SESSION_DRIVER=database
SESSION_LIFETIME=240
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Database configuration
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```