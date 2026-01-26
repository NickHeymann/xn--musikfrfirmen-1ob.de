# Laravel Backend Deployment Guide

**Project:** musikfürfirmen.de Visual Editor API
**Date:** 2026-01-17

---

## Prerequisites

- Laravel 11+ project (new or existing)
- PostgreSQL database
- PHP 8.2+
- Composer

---

## Step 1: Install Dependencies

```bash
# Navigate to your Laravel project
cd /opt/laravel-backend  # Or wherever your Laravel project is

# Install required packages
composer require intervention/image
composer require fruitcake/laravel-cors  # If not already installed
```

---

## Step 2: Copy Files

Copy the following files from `laravel-backend-files/` to your Laravel project:

```bash
# From musikfürfirmen.de/laravel-backend-files/ to your Laravel project

# Models
cp app/Models/Page.php /opt/laravel-backend/app/Models/

# Controllers
cp app/Http/Controllers/PageController.php /opt/laravel-backend/app/Http/Controllers/
cp app/Http/Controllers/MediaController.php /opt/laravel-backend/app/Http/Controllers/

# Migration
cp database/migrations/2026_01_17_create_pages_table.php /opt/laravel-backend/database/migrations/

# Seeder
cp database/seeders/ConvertHomepageSeeder.php /opt/laravel-backend/database/seeders/

# Routes
cp routes/api.php /opt/laravel-backend/routes/api.php
# OR append the contents to your existing api.php

# Config
cp config/cors.php /opt/laravel-backend/config/cors.php
```

---

## Step 3: Configure Environment

Edit `/opt/laravel-backend/.env`:

```bash
# Database (PostgreSQL required for JSONB)
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=musikfürfirmen.de
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://musikfürfirmen.de.de

# File Storage
FILESYSTEM_DISK=public

# App URL
APP_URL=http://localhost:8000
```

---

## Step 4: Create Storage Symlink

```bash
# Create symbolic link for public storage
php artisan storage:link

# Create editor-images directory
mkdir -p storage/app/public/editor-images
chmod 755 storage/app/public/editor-images
```

---

## Step 5: Run Migrations

```bash
# Run migration to create pages table
php artisan migrate

# Seed initial data (optional)
php artisan db:seed --class=ConvertHomepageSeeder
```

---

## Step 6: Test API Endpoints

### Start Laravel Server

```bash
# Development
php artisan serve --host=0.0.0.0 --port=8000

# Production (use Nginx/Apache instead)
```

### Test with curl

```bash
# List all pages
curl http://localhost:8000/api/pages

# Get a single page
curl http://localhost:8000/api/pages/home

# Create a page
curl -X POST http://localhost:8000/api/pages \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Page",
    "content": {
      "version": "1.0",
      "type": "page",
      "blocks": []
    }
  }'

# Update a page
curl -X PUT http://localhost:8000/api/pages/test-page \
  -H "Content-Type: application/json" \
  -d '{
    "content": {
      "version": "1.0",
      "type": "page",
      "blocks": [
        {
          "id": "hero-1",
          "type": "Hero",
          "props": {
            "sliderContent": ["Musik", "Events"]
          }
        }
      ]
    }
  }'

# Delete a page
curl -X DELETE http://localhost:8000/api/pages/test-page

# Upload an image
curl -X POST http://localhost:8000/api/pages/media \
  -F "image=@/path/to/image.jpg"
```

---

## Step 7: Configure Next.js

In your Next.js project (`musikfürfirmen.de/`):

```bash
# Create .env.local
cp .env.local.example .env.local

# Edit .env.local
NEXT_PUBLIC_API_URL=http://localhost:8000/api  # Development
# NEXT_PUBLIC_API_URL=https://api.musikfürfirmen.de.de/api  # Production
```

---

## Step 8: Test Full Stack

### 1. Start Laravel Backend

```bash
cd /opt/laravel-backend
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Start Next.js Frontend

```bash
cd /path/to/musikfürfirmen.de
npm run dev
```

### 3. Access Visual Editor

Open browser:
```
http://localhost:3000/admin/pages
```

### 4. Test Workflow

1. Click "New Page"
2. Enter title (e.g., "Test Page")
3. Drag components from left sidebar
4. Edit properties in right sidebar
5. Watch auto-save indicator
6. Click "Preview" to view page
7. Verify page at `http://localhost:3000/test-page`

---

## Production Deployment

### Option A: Same Server (Hetzner)

```bash
# 1. Deploy Laravel to Hetzner
ssh hetzner
cd /opt
git clone <your-laravel-repo>
cd laravel-backend
composer install --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# 2. Configure Nginx
# Add API proxy to Nginx config:

server {
    listen 80;
    server_name api.musikfürfirmen.de.de;

    root /opt/laravel-backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}

# 3. Update Next.js .env.production
NEXT_PUBLIC_API_URL=https://api.musikfürfirmen.de.de/api
```

### Option B: Separate Services

```bash
# Backend: Deploy to Hetzner (same as above)
# Frontend: Deploy to Vercel (automatic via GitHub)

# Update CORS in Laravel .env:
CORS_ALLOWED_ORIGINS=https://musikfürfirmen.de.de,https://musikfürfirmen.de.vercel.app
```

---

## Troubleshooting

### Issue: "CORS error"

**Solution:**
```bash
# Check Laravel .env
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Clear config cache
php artisan config:clear
```

### Issue: "SQLSTATE[42P01]: Undefined table: pages"

**Solution:**
```bash
# Run migrations
php artisan migrate

# If migration already ran, rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Issue: "Class 'Intervention\Image\Facades\Image' not found"

**Solution:**
```bash
# Install Intervention/Image
composer require intervention/image

# Publish config (optional)
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProvider"
```

### Issue: "Storage not found"

**Solution:**
```bash
# Create storage symlink
php artisan storage:link

# Create editor-images directory
mkdir -p storage/app/public/editor-images
chmod 755 storage/app/public/editor-images
```

### Issue: "Failed to upload image"

**Solution:**
```bash
# Check permissions
chmod -R 755 storage/
chown -R www-data:www-data storage/  # On production

# Check GD/Imagick extension
php -m | grep -E "gd|imagick"

# If missing, install:
sudo apt-get install php8.2-gd  # or php8.2-imagick
```

---

## Maintenance

### Database Backups

```bash
# Backup pages table
pg_dump -U your_db_user -t pages musikfürfirmen.de > pages_backup.sql

# Restore
psql -U your_db_user musikfürfirmen.de < pages_backup.sql
```

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Update Dependencies

```bash
composer update
php artisan migrate
```

---

## API Documentation

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/pages` | List all pages |
| GET | `/api/pages/{slug}` | Get page by slug |
| POST | `/api/pages` | Create new page |
| PUT | `/api/pages/{slug}` | Update page |
| DELETE | `/api/pages/{slug}` | Delete page |
| POST | `/api/pages/media` | Upload image |

### Request/Response Examples

See Step 6 above for curl examples.

---

## Security Checklist

- [ ] Configure CORS to only allow your domains
- [ ] Use HTTPS in production
- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Use strong database passwords
- [ ] Limit file upload sizes (max 10MB)
- [ ] Add rate limiting to API routes (optional)

---

**Last Updated:** 2026-01-17
**Status:** Ready for deployment
