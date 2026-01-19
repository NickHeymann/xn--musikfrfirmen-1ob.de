# Visual Editor - Quick Start Guide

**5-Minute Setup** | musikfuerfirmen.de

---

## 🚀 Quick Start (Local Development)

### Step 1: Setup Laravel Backend (2 min)

```bash
# Navigate to your Laravel project (or create new one)
cd /opt/laravel-backend

# Copy backend files
cp -r /path/to/musikfuerfirmen/laravel-backend-files/* .

# Install dependencies
composer require intervention/image

# Configure database
# Edit .env:
DB_CONNECTION=pgsql
DB_DATABASE=musikfuerfirmen
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Run migrations
php artisan migrate
php artisan db:seed --class=ConvertHomepageSeeder
php artisan storage:link

# Start server
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 2: Setup Next.js Frontend (1 min)

```bash
cd musikfuerfirmen

# Create environment file
cp .env.local.example .env.local

# Start development server
npm run dev
```

### Step 3: Access Editor (1 min)

```bash
# Open browser
open http://localhost:3000/admin/pages

# Create your first page:
# 1. Click "New Page"
# 2. Enter title
# 3. Drag components from left
# 4. Edit in right panel
# 5. Auto-saves every 2 seconds
# 6. Click "Preview"
```

---

## 📋 Files Created

**Frontend (12 files):**
- `src/types/visual-editor.ts`
- `src/lib/api/client.ts`
- `src/visual-editor/registry.ts`
- `src/visual-editor/PageRenderer.tsx`
- `src/visual-editor/context/EditorContext.tsx`
- `src/visual-editor/components/*.tsx` (6 files)
- `src/app/admin/pages/page.tsx`
- `src/app/admin/editor/[slug]/page.tsx`

**Backend (8 files):**
- `laravel-backend-files/app/Models/Page.php`
- `laravel-backend-files/app/Http/Controllers/PageController.php`
- `laravel-backend-files/app/Http/Controllers/MediaController.php`
- `laravel-backend-files/database/migrations/2026_01_17_create_pages_table.php`
- `laravel-backend-files/database/seeders/ConvertHomepageSeeder.php`
- `laravel-backend-files/routes/api.php`
- `laravel-backend-files/config/cors.php`
- `laravel-backend-files/DEPLOYMENT.md`

---

## ✅ Checklist

- [ ] Laravel backend running (`http://localhost:8000`)
- [ ] Next.js frontend running (`http://localhost:3000`)
- [ ] Database migrated
- [ ] `.env.local` configured
- [ ] Test API: `curl http://localhost:8000/api/pages`
- [ ] Access admin: `http://localhost:3000/admin/pages`
- [ ] Create test page
- [ ] Verify auto-save works
- [ ] Test image upload
- [ ] Preview page

---

## 🔧 Troubleshooting

**CORS Error?**
```bash
# Laravel .env
CORS_ALLOWED_ORIGINS=http://localhost:3000
php artisan config:clear
```

**API Not Found?**
```bash
# Check Laravel is running
curl http://localhost:8000/api/pages

# Check .env.local
cat .env.local | grep API_URL
```

**Components Not Showing?**
```bash
# Check registry
cat src/visual-editor/registry.ts
```

---

## 📚 Full Documentation

- `docs/VISUAL-EDITOR-COMPLETE.md` - Complete overview
- `laravel-backend-files/DEPLOYMENT.md` - Deployment guide
- `laravel-backend-files/README.md` - Backend details
- `docs/visual-editor-implementation-status.md` - Implementation tracking

---

**Status:** ✅ Complete
**Time to Setup:** ~5 minutes
**Difficulty:** Easy

🎉 **You're ready to build!**
