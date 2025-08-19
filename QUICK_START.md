# Quick Start Guide

## ✅ Setup Complete!

Your Ustawi Wa Jamii application is now ready to use with PostgreSQL.

## 🚀 Start the Application

```bash
# In terminal 1 - Start the Laravel server
php artisan serve

# In terminal 2 - Start the Vite dev server (for hot-reloading during development)
npm run dev
```

## 📍 Access Points

- **Application**: http://localhost:8000
- **Admin Login**: http://localhost:8000/login
  - Email: `admin@ustawiwajamii.org`
  - Password: `password123`
- **Mailhog (Email Testing)**: http://localhost:8025
- **MinIO Console (File Storage)**: http://localhost:9001
  - Username: `minioadmin`
  - Password: `minioadmin`

## 🐳 Docker Services Status

Check if all services are running:
```bash
sudo docker ps
```

You should see:
- `ustawi_postgres` - PostgreSQL database
- `ustawi_redis` - Redis cache
- `ustawi_mailhog` - Email testing
- `ustawi_minio` - S3-compatible storage

## 🔧 Common Commands

```bash
# Stop Docker services
sudo docker compose down

# Start Docker services
sudo docker compose up -d

# View logs
sudo docker compose logs -f

# Run migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Build production assets
npm run build
```

## 📝 Important Notes

1. **First Time Setup**: The admin user has been created for you.
2. **Email Testing**: All emails will be caught by Mailhog at http://localhost:8025
3. **File Uploads**: Files will be stored in MinIO (accessible at http://localhost:9001)
4. **Database**: PostgreSQL is running on port 5432

## 🛠 Troubleshooting

If you encounter any issues:

1. **Application won't start**: 
   - Make sure Docker services are running: `sudo docker ps`
   - Check `.env` file has correct database credentials

2. **Can't connect to database**:
   - Verify PostgreSQL is running: `sudo docker compose ps`
   - Check logs: `sudo docker compose logs postgres`

3. **Assets not loading**:
   - Run: `npm run build`
   - Or for development: `npm run dev`

## 🎯 Next Steps

1. Explore the admin dashboard at http://localhost:8000/admin/dashboard
2. Create blog posts, manage settings, and configure the site
3. Test email functionality using Mailhog
4. Review the codebase structure in the project documentation

---

**Need Help?** Check the `DOCKER_SETUP.md` for detailed Docker configuration or the `SRS.md` for application features.