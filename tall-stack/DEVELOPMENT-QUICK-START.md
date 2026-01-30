# Development Quick Start Guide

## 🚀 Your Site is Running!

✅ **Laravel Server:** http://127.0.0.1:8000
✅ **Queue Worker:** Running in background
✅ **Email System:** Configured and ready

---

## Quick Access URLs

| Page | URL | Description |
|------|-----|-------------|
| **Homepage** | http://127.0.0.1:8000 | Main landing page with all animations |
| **Booking Calendar** | http://127.0.0.1:8000/erstgespraech | Cal.com-style booking flow |
| **Contact Form** | http://127.0.0.1:8000/contact | Contact submission form |
| **Admin Panel** | http://127.0.0.1:8000/admin | Filament admin (login required) |

---

## Running Processes

Check what's running:
```bash
ps aux | grep -E "artisan serve|queue:work" | grep -v grep
```

You should see:
- `php artisan serve --host=127.0.0.1 --port=8000`
- `php artisan queue:work --tries=3`

---

## Testing Email Delivery

### Option 1: Use the Booking Calendar

1. Visit: http://127.0.0.1:8000/erstgespraech
2. Select a date (click any weekday)
3. Select a time slot (e.g., 10:00)
4. Fill out contact form:
   - Name: Your Name
   - Email: your-email@example.com
   - Phone: +49 123 456789
   - Message: Test booking
5. Click "Termin anfragen"
6. **Check your email** at `moin@jonasglamann.de` and `moin@nickheymann.de`

### Option 2: Use the Contact Form

1. Visit: http://127.0.0.1:8000/contact
2. Fill out the form
3. Submit
4. **Check your email** at the configured recipients

### Option 3: Quick Test via Command Line

```bash
php artisan tinker --execute="
use Illuminate\Support\Facades\Mail;

Mail::raw('Test email from musikfürfirmen.de - Development', function(\$message) {
    \$message->to('moin@jonasglamann.de')
           ->subject('✅ Test: Email System Working');
});

echo 'Test email queued! It will be sent within a few seconds.\n';
"
```

Then check the queue worker output:
```bash
tail -f storage/logs/laravel.log
```

---

## Stopping & Starting Services

### Stop Everything

```bash
# Stop Laravel server
pkill -f "artisan serve"

# Stop queue worker
pkill -f "queue:work"
```

### Start Everything

```bash
# Start Laravel server
php artisan serve --host=127.0.0.1 --port=8000 &

# Start queue worker
php artisan queue:work --tries=3 &

# Build assets (if you made CSS/JS changes)
npm run build
```

---

## Viewing Logs

### Queue Worker Activity
```bash
tail -f storage/logs/laravel.log
```

### Failed Jobs
```bash
php artisan queue:failed
```

### Retry Failed Jobs
```bash
php artisan queue:retry all
```

---

## Making Changes

### After Editing Blade Templates
Just refresh the browser - no build needed!

### After Editing CSS/JS
```bash
npm run build
# Then refresh browser
```

### After Editing PHP Code
Just refresh - Laravel automatically reloads!

### After Changing .env
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Common Issues & Fixes

### Issue: Site can't be reached

**Solution:**
```bash
# Check if server is running
ps aux | grep "artisan serve"

# If not running, start it:
php artisan serve --host=127.0.0.1 --port=8000 &
```

### Issue: Emails not sending

**Solution:**
```bash
# Check queue worker is running
ps aux | grep "queue:work"

# If not running, start it:
php artisan queue:work --tries=3 &

# Check for failed jobs
php artisan queue:failed

# Check logs
tail -f storage/logs/laravel.log
```

### Issue: CSS changes not showing

**Solution:**
```bash
# Rebuild assets
npm run build

# Force refresh browser (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows)
```

### Issue: Port 8000 already in use

**Solution:**
```bash
# Use a different port
php artisan serve --port=8001 &
# Then visit: http://127.0.0.1:8001
```

---

## Database Management

### Run Migrations
```bash
php artisan migrate
```

### Reset Database (WARNING: Deletes all data!)
```bash
php artisan migrate:fresh --seed
```

### Access Database
```bash
php artisan tinker
# Then run queries like:
# DB::table('jobs')->count()
# DB::table('contact_submissions')->latest()->first()
```

---

## Git Workflow

### Check Status
```bash
git status
git log --oneline -10
```

### Create Feature Branch
```bash
git checkout -b feature/my-new-feature
# Make changes...
git add .
git commit -m "feat: add new feature"
git push origin feature/my-new-feature
```

### Deploy to Production
```bash
# Push to main branch
git push origin main

# GitHub Actions will automatically deploy to Hetzner
```

---

## Monitoring Queue

### Live Queue Monitor
```bash
php artisan queue:monitor
```

### Check Pending Jobs
```bash
php artisan tinker --execute="
echo 'Pending jobs: ' . \DB::table('jobs')->count() . PHP_EOL;
echo 'Failed jobs: ' . \DB::table('failed_jobs')->count() . PHP_EOL;
"
```

---

## Production Deployment Checklist

Before deploying to production:

- [ ] All tests passing: `php artisan test`
- [ ] Assets built: `npm run build`
- [ ] No sensitive data in commits
- [ ] .env configured on server
- [ ] Queue worker set up (systemd/supervisor)
- [ ] Test emails working
- [ ] Database migrated: `php artisan migrate --force`
- [ ] Cache cleared: `php artisan optimize:clear`

---

## Emergency Commands

### Clear Everything
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Reset Queue
```bash
# Clear all failed jobs
php artisan queue:flush

# Restart queue worker
pkill -f "queue:work"
php artisan queue:work --tries=3 &
```

---

## Current Setup Summary

✅ **Email Configuration:**
- Provider: Fastmail SMTP (smtp.fastmail.com:465)
- From: kontakt@musikfürfirmen.de
- Recipients: moin@nickheymann.de, moin@jonasglamann.de
- Status: ✅ Working

✅ **Features Implemented:**
- Hero video background
- Cal.com-style booking calendar
- Event photo gallery (swipeable)
- Team photo modals
- Dynamic header colors
- Email notifications (contact + booking)
- Queue system for async email delivery

✅ **Services Running:**
- Laravel dev server: http://127.0.0.1:8000
- Queue worker: Background process
- Build system: npm run build

---

**Need Help?**
- Check logs: `tail -f storage/logs/laravel.log`
- Queue worker guide: `QUEUE-WORKER-SETUP.md`
- Laravel docs: https://laravel.com/docs/12.x

**Last Updated:** 2026-01-30
