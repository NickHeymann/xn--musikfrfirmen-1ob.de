# Laravel Backend Files for Visual Editor

**Project:** musikfuerfirmen Visual Editor API
**Date:** 2026-01-17

---

## 📁 Contents

This directory contains all Laravel backend files needed for the visual editor:

```
laravel-backend-files/
├── app/
│   ├── Models/
│   │   └── Page.php                      # Page model with JSONB support
│   └── Http/Controllers/
│       ├── PageController.php            # CRUD operations for pages
│       └── MediaController.php           # Image upload & optimization
├── database/
│   ├── migrations/
│   │   └── 2026_01_17_create_pages_table.php  # Pages table migration
│   └── seeders/
│       └── ConvertHomepageSeeder.php     # Initial data seeder
├── routes/
│   └── api.php                           # API routes
├── config/
│   └── cors.php                          # CORS configuration
├── DEPLOYMENT.md                         # Complete deployment guide
└── README.md                             # This file
```

---

## 🚀 Quick Start

### 1. Copy Files to Laravel Project

```bash
# Replace /opt/laravel-backend with your Laravel project path
cp -r laravel-backend-files/* /opt/laravel-backend/
```

### 2. Install Dependencies

```bash
cd /opt/laravel-backend
composer require intervention/image
composer require fruitcake/laravel-cors
```

### 3. Configure Environment

```bash
# Edit .env
DB_CONNECTION=pgsql
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://musikfuerfirmen.de
```

### 4. Run Migration

```bash
php artisan migrate
php artisan db:seed --class=ConvertHomepageSeeder
```

### 5. Start Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 6. Test API

```bash
curl http://localhost:8000/api/pages
```

---

## 📋 File Descriptions

### Models

**`app/Models/Page.php`**
- Eloquent model for pages table
- JSONB content casting
- Auto-generates slug from title
- Timestamps enabled

### Controllers

**`app/Http/Controllers/PageController.php`**
- `index()` - List all pages
- `show($slug)` - Get single page
- `store()` - Create new page
- `update($slug)` - Update page
- `destroy($slug)` - Delete page

**`app/Http/Controllers/MediaController.php`**
- `upload()` - Upload & optimize images
- Auto-converts to WebP
- Resizes to max 2000px width
- Stores in `storage/app/public/editor-images/`

### Database

**`database/migrations/2026_01_17_create_pages_table.php`**
- Creates `pages` table with JSONB support
- Indexes JSONB content column (GIN)
- Requires PostgreSQL

**`database/seeders/ConvertHomepageSeeder.php`**
- Seeds initial homepage data
- Creates 3 example pages (home, services, about)
- Uses existing component structure

### Routes

**`routes/api.php`**
- `/api/pages` - CRUD endpoints
- `/api/pages/media` - Image upload

### Config

**`config/cors.php`**
- CORS configuration for Next.js
- Allows localhost:3000 by default
- Configure via `CORS_ALLOWED_ORIGINS` env var

---

## 🔧 Configuration

### Required Environment Variables

```bash
# Database (PostgreSQL required)
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=musikfuerfirmen
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://musikfuerfirmen.de

# Storage
FILESYSTEM_DISK=public
```

### Optional Settings

```bash
# Image optimization
IMAGE_MAX_WIDTH=2000
IMAGE_QUALITY=85
IMAGE_FORMAT=webp
```

---

## 📊 Database Schema

```sql
CREATE TABLE pages (
    id BIGSERIAL PRIMARY KEY,
    slug VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    content JSONB NOT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE INDEX idx_pages_content ON pages USING GIN (content);
```

### JSON Structure

```json
{
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
```

---

## 🧪 Testing

### Manual Testing

```bash
# List pages
curl http://localhost:8000/api/pages

# Get page
curl http://localhost:8000/api/pages/home

# Create page
curl -X POST http://localhost:8000/api/pages \
  -H "Content-Type: application/json" \
  -d '{"title": "Test", "content": {"version": "1.0", "type": "page", "blocks": []}}'

# Upload image
curl -X POST http://localhost:8000/api/pages/media \
  -F "image=@test.jpg"
```

### PHPUnit Tests (Optional)

Create tests in `tests/Feature/PageApiTest.php`:

```php
public function test_can_list_pages()
{
    $response = $this->get('/api/pages');
    $response->assertStatus(200);
}

public function test_can_create_page()
{
    $response = $this->post('/api/pages', [
        'title' => 'Test Page',
        'content' => [
            'version' => '1.0',
            'type' => 'page',
            'blocks' => []
        ]
    ]);
    $response->assertStatus(201);
}
```

---

## 🔒 Security

### Best Practices

- ✅ CORS configured to only allow specific origins
- ✅ File upload validation (max 10MB, images only)
- ✅ JSONB validation on create/update
- ✅ Slug uniqueness enforced
- ✅ Image optimization prevents huge files

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Use HTTPS
- [ ] Configure strict CORS
- [ ] Add rate limiting
- [ ] Regular database backups

---

## 📚 Dependencies

### Required

- `intervention/image` - Image manipulation & optimization
- `fruitcake/laravel-cors` - CORS middleware (Laravel 11 includes this)

### PHP Extensions

- `php-gd` or `php-imagick` - Required by Intervention/Image
- `php-pgsql` - PostgreSQL driver

---

## 🐛 Common Issues

### "Class 'Intervention\Image\Facades\Image' not found"

```bash
composer require intervention/image
php artisan config:clear
```

### "SQLSTATE[42P01]: Undefined table: pages"

```bash
php artisan migrate
```

### "CORS error"

```bash
# Check .env
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Clear cache
php artisan config:clear
```

### "Storage link not found"

```bash
php artisan storage:link
mkdir -p storage/app/public/editor-images
chmod 755 storage/app/public/editor-images
```

---

## 📖 Full Documentation

See `DEPLOYMENT.md` for complete deployment instructions.

---

**Created:** 2026-01-17
**Status:** Production-ready
**Laravel Version:** 11+
**PHP Version:** 8.2+
**Database:** PostgreSQL 15+
