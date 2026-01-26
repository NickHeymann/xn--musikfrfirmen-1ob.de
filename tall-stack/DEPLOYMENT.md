# musikfürfirmen.de - TALL Stack Deployment Guide

## Overview

This guide covers deploying the musikfürfirmen.de TALL Stack application to production.

**Tech Stack:**
- Laravel 12
- Filament 4.5.1
- Livewire 3
- Alpine.js
- Tailwind CSS 4
- SQLite (dev) / MySQL (production recommended)

---

## Pre-Deployment Checklist

### 1. Server Requirements

**Minimum Requirements:**
- PHP 8.2 or higher
- Composer 2.x
- Node.js 20.x and npm
- MySQL 8.0+ or PostgreSQL 15+ (SQLite for testing only)
- Nginx or Apache with mod_rewrite
- SSL certificate (Let's Encrypt recommended)

**PHP Extensions Required:**
```bash
php -m | grep -E "pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|fileinfo"
```

### 2. Repository Access

```bash
# Clone the repository
git clone https://github.com/NickHeymann/musikfürfirmen.de.git
cd musikfürfirmen.de/tall-stack

# Or pull latest changes
git pull origin main
```

---

## Production Deployment Steps

### Step 1: Environment Configuration

```bash
# Copy production environment template
cp .env.production.example .env

# Generate application key
php artisan key:generate

# Edit .env with production values
nano .env
```

**Critical .env Settings:**
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://musikfürfirmen.de.de

# Database (MySQL recommended)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=musikfürfirmen.de_prod
DB_USERNAME=musikfürfirmen.de_user
DB_PASSWORD=your_secure_password

# Filesystem
FILESYSTEM_DISK=public

# Cache (Redis recommended for production)
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install and build frontend assets
npm install
npm run build
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (services, team members, FAQs)
php artisan db:seed --class=NextJsContentSeeder
```

### Step 4: File Storage Setup

```bash
# Create storage symlink
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 5: Optimization

```bash
# Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer dump-autoload --optimize
```

### Step 6: Create Admin User

The seeder creates a default admin user:
- **Email:** admin@musikfürfirmen.de.de
- **Password:** admin123

**⚠️ CRITICAL: Change this password immediately after first login!**

To create additional admin users:
```bash
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => Hash::make('secure_password'),
    'email_verified_at' => now(),
]);
```

---

## Web Server Configuration

### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name musikfürfirmen.de.de www.musikfürfirmen.de.de;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name musikfürfirmen.de.de www.musikfürfirmen.de.de;

    root /var/www/musikfürfirmen.de/tall-stack/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/musikfürfirmen.de.de/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/musikfürfirmen.de.de/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Gzip Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    # Logs
    access_log /var/log/nginx/musikfürfirmen.de-access.log;
    error_log /var/log/nginx/musikfürfirmen.de-error.log;

    # Rate Limiting (Admin Panel)
    limit_req_zone $binary_remote_addr zone=admin:10m rate=10r/m;
    
    location /admin {
        limit_req zone=admin burst=5 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Laravel Routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|woff|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Apache Configuration (.htaccess already included)

The Laravel .htaccess file in `public/` handles routing automatically.

---

## Post-Deployment Verification

### 1. Test Homepage

```bash
curl -I https://musikfürfirmen.de.de
# Expected: HTTP 200 OK
```

### 2. Test Admin Panel

```bash
# Visit: https://musikfürfirmen.de.de/admin
# Login with: admin@musikfürfirmen.de.de / admin123
# Change password immediately!
```

### 3. Verify Resources

- ✅ Services: https://musikfürfirmen.de.de/admin/services
- ✅ Team Members: https://musikfürfirmen.de.de/admin/team-members
- ✅ FAQs: https://musikfürfirmen.de.de/admin/faqs
- ✅ Pages: https://musikfürfirmen.de.de/admin/pages

### 4. Test File Uploads

1. Go to https://musikfürfirmen.de.de/admin/team-members/create
2. Upload a test image
3. Verify image appears at `/storage/team-members/filename.jpg`

### 5. Check Frontend

- Homepage displays team members from database
- FAQ section shows 7 questions
- All content is dynamic (not hardcoded)

---

## Maintenance & Updates

### Deploying Updates

```bash
# Pull latest code
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Run migrations (if any)
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Backups

**Daily Automated Backup (Cron):**
```bash
# Add to crontab
0 2 * * * cd /var/www/musikfürfirmen.de/tall-stack && mysqldump -u user -p'password' musikfürfirmen.de_prod > /backups/db-$(date +\%Y\%m\%d).sql
```

**Manual Backup:**
```bash
php artisan db:backup
```

### Log Monitoring

```bash
# Application logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/musikfürfirmen.de-error.log
```

---

## Security Best Practices

### 1. Admin Password

**Change default password immediately:**
```bash
php artisan tinker
```
```php
$admin = App\Models\User::where('email', 'admin@musikfürfirmen.de.de')->first();
$admin->password = Hash::make('new_secure_password');
$admin->save();
```

### 2. File Permissions

```bash
# Application files
chown -R www-data:www-data /var/www/musikfürfirmen.de
chmod -R 755 /var/www/musikfürfirmen.de

# Writable directories
chmod -R 775 storage bootstrap/cache
```

### 3. SSL/TLS

```bash
# Install Let's Encrypt certificate
certbot --nginx -d musikfürfirmen.de.de -d www.musikfürfirmen.de.de

# Auto-renewal (runs twice daily)
systemctl status certbot.timer
```

### 4. Rate Limiting

Admin panel is rate-limited in Nginx config:
- 10 requests per minute
- Burst of 5 allowed

### 5. Security Headers

Already configured in Nginx:
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Referrer-Policy

---

## Troubleshooting

### Issue: "500 Internal Server Error"

```bash
# Check permissions
chmod -R 775 storage bootstrap/cache

# Clear caches
php artisan optimize:clear

# Check logs
tail -f storage/logs/laravel.log
```

### Issue: "Storage link not working"

```bash
# Remove old link
rm public/storage

# Recreate link
php artisan storage:link

# Verify
ls -la public/storage
```

### Issue: "Admin panel not loading"

```bash
# Clear Filament cache
php artisan filament:cache-components

# Rebuild assets
npm run build

# Clear Laravel caches
php artisan optimize:clear
```

### Issue: "Database connection failed"

```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Check .env credentials
grep DB_ .env

# Test MySQL connection
mysql -u username -p database_name
```

---

## Performance Optimization

### 1. OPcache (Production)

```ini
; /etc/php/8.2/fpm/conf.d/10-opcache.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### 2. Redis Caching

```bash
# Install Redis
apt install redis-server

# Configure Laravel to use Redis
# Already set in .env.production.example
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. Queue Workers (Optional)

```bash
# Start queue worker
php artisan queue:work --daemon

# Supervisor configuration
[program:musikfürfirmen.de-queue]
command=php /var/www/musikfürfirmen.de/tall-stack/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/musikfürfirmen.de/tall-stack/storage/logs/worker.log
```

---

## Rollback Plan

### Quick Rollback

```bash
# Revert to previous git commit
git log --oneline -10
git checkout <commit-hash>

# Restore database backup
mysql -u user -p musikfürfirmen.de_prod < /backups/db-20260114.sql

# Clear caches
php artisan optimize:clear
php artisan config:cache
```

---

## Support & Contact

**Project Repository:** https://github.com/NickHeymann/musikfürfirmen.de  
**Laravel Documentation:** https://laravel.com/docs/12.x  
**Filament Documentation:** https://filamentphp.com/docs/4.x  
**Livewire Documentation:** https://livewire.laravel.com/docs/3.x

---

## Deployment Checklist

**Before Deployment:**
- [ ] .env configured with production values
- [ ] APP_ENV=production, APP_DEBUG=false
- [ ] Database credentials set
- [ ] APP_KEY generated
- [ ] SSL certificate installed
- [ ] Server requirements met

**During Deployment:**
- [ ] Dependencies installed (composer, npm)
- [ ] Database migrated and seeded
- [ ] Storage link created
- [ ] Caches built (config, routes, views)
- [ ] File permissions set

**After Deployment:**
- [ ] Admin password changed
- [ ] Homepage loads correctly
- [ ] Admin panel accessible
- [ ] File uploads working
- [ ] Database content displaying
- [ ] Backups configured
- [ ] Monitoring set up

---

**Deployment Date:** 2026-01-14  
**Version:** 1.0.0  
**Migration Status:** ✅ Complete (Next.js → TALL Stack)
