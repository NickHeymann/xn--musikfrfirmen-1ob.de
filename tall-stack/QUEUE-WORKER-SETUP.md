# Queue Worker Setup Guide

## Overview

The musikfürfirmen.de application uses Laravel's queue system to send emails asynchronously. This ensures contact form and booking calendar submissions don't block the user while emails are being sent.

## Current Configuration

- **Queue Driver:** `database` (reliable, survives restarts)
- **Mail Provider:** Fastmail SMTP (smtp.fastmail.com:465)
- **Recipients:** Configured via `EVENT_REQUEST_RECIPIENTS` in `.env`

## Email Configuration ✅

Your `.env` file is already configured:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.fastmail.com
MAIL_PORT=465
MAIL_USERNAME=nick@nickheymann.de
MAIL_PASSWORD=8w7s8u5u6v9q9u38
MAIL_SCHEME=smtps
MAIL_FROM_ADDRESS="kontakt@xn--musikfrfirmen-1ob.de"
MAIL_FROM_NAME="musikfürfirmen.de"
EVENT_REQUEST_RECIPIENTS="moin@nickheymann.de,moin@jonasglamann.de"
```

✅ **Email configuration verified and working!**

---

## Development (Local)

For local development, run the queue worker manually:

```bash
# Process all queued jobs (keeps running)
php artisan queue:work

# Process jobs with more verbose output
php artisan queue:work --verbose

# Process only one job (useful for testing)
php artisan queue:work --once

# Listen for new jobs and process them
php artisan queue:listen
```

**Tip:** Keep a terminal window open with `php artisan queue:work` running while developing.

---

## Production Setup

For production on Hetzner, you need a process manager to keep the queue worker running. Choose **one** of these options:

### Option 1: systemd (Recommended - Modern Linux)

systemd is built into modern Linux distributions and is the recommended approach.

**1. Copy the service file to systemd:**

```bash
sudo cp musikfuerfirmen-queue-worker.service /etc/systemd/system/
```

**2. Edit the file and update paths:**

```bash
sudo nano /etc/systemd/system/musikfuerfirmen-queue-worker.service
```

Replace `/path/to/tall-stack` with actual path (e.g., `/opt/apps/musikfuerfirmen-production`)

**3. Enable and start the service:**

```bash
sudo systemctl daemon-reload
sudo systemctl enable musikfuerfirmen-queue-worker
sudo systemctl start musikfuerfirmen-queue-worker
```

**4. Verify it's running:**

```bash
sudo systemctl status musikfuerfirmen-queue-worker
```

**5. View logs:**

```bash
# Follow logs in real-time
sudo journalctl -u musikfuerfirmen-queue-worker -f

# View recent logs
sudo journalctl -u musikfuerfirmen-queue-worker -n 50
```

**After deployments:**

```bash
sudo systemctl restart musikfuerfirmen-queue-worker
```

---

### Option 2: Supervisor (Alternative)

If your server uses Supervisor instead of systemd:

**1. Copy the config file:**

```bash
sudo cp queue-worker-supervisor.conf /etc/supervisor/conf.d/musikfuerfirmen-queue-worker.conf
```

**2. Edit and update paths:**

```bash
sudo nano /etc/supervisor/conf.d/musikfuerfirmen-queue-worker.conf
```

**3. Reload supervisor:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start musikfuerfirmen-queue-worker:*
```

**4. Check status:**

```bash
sudo supervisorctl status
```

**After deployments:**

```bash
sudo supervisorctl restart musikfuerfirmen-queue-worker:*
```

---

## Testing Email Delivery

### Test Contact Form

1. Go to `/contact` on your site
2. Fill out and submit the form
3. Check that email arrives at `moin@jonasglamann.de` and `moin@nickheymann.de`

### Test Booking Calendar

1. Go to `/erstgespraech`
2. Select date, time, and fill out contact form
3. Check that booking request email arrives

### Manual Test

Run this in `php artisan tinker`:

```php
use App\Mail\ContactFormSubmitted;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;

// Create test submission
$submission = ContactSubmission::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '+49 123 456789',
    'company' => 'Test Company',
    'inquiry_type' => 'general',
    'message' => 'This is a test email',
    'status' => 'new',
]);

// Send email
$recipients = explode(',', env('EVENT_REQUEST_RECIPIENTS'));
Mail::to($recipients)->send(new ContactFormSubmitted($submission));

echo "Test email queued! Check queue with: php artisan queue:work --once\n";
```

---

## Monitoring Queue Status

### Check pending jobs

```bash
php artisan queue:monitor
```

### View failed jobs

```bash
php artisan queue:failed
```

### Retry failed jobs

```bash
# Retry specific job
php artisan queue:retry {job-id}

# Retry all failed jobs
php artisan queue:retry all
```

### Clear all failed jobs

```bash
php artisan queue:flush
```

---

## Troubleshooting

### Emails not sending?

1. **Check queue worker is running:**
   ```bash
   sudo systemctl status musikfuerfirmen-queue-worker  # systemd
   # or
   sudo supervisorctl status  # supervisor
   ```

2. **Check for pending jobs:**
   ```bash
   php artisan tinker --execute="\DB::table('jobs')->count()"
   ```

3. **Check for failed jobs:**
   ```bash
   php artisan queue:failed
   ```

4. **View queue worker logs:**
   ```bash
   tail -f storage/logs/queue-worker.log
   ```

5. **Test SMTP connection manually:**
   ```bash
   telnet smtp.fastmail.com 465
   ```

### Common Issues

**Issue:** Queue worker dies after deployment
**Solution:** Restart the queue worker after every deployment (systemd does this automatically with `Restart=always`)

**Issue:** Emails stuck in queue
**Solution:** Check `storage/logs/laravel.log` for errors, ensure queue worker is running

**Issue:** Permission errors
**Solution:** Ensure queue worker user has write access to `storage/` directory

---

## Production Checklist

- [ ] systemd service file configured with correct paths
- [ ] Queue worker enabled and started
- [ ] Test email sent successfully
- [ ] Logs are being written to `storage/logs/`
- [ ] Failed jobs monitoring set up
- [ ] Deployment script restarts queue worker

---

## Quick Reference Commands

```bash
# Development
php artisan queue:work              # Run worker
php artisan queue:work --once       # Process one job

# Production (systemd)
sudo systemctl start musikfuerfirmen-queue-worker
sudo systemctl stop musikfuerfirmen-queue-worker
sudo systemctl restart musikfuerfirmen-queue-worker
sudo systemctl status musikfuerfirmen-queue-worker
sudo journalctl -u musikfuerfirmen-queue-worker -f

# Monitoring
php artisan queue:failed            # List failed jobs
php artisan queue:retry all         # Retry all failed
php artisan queue:flush             # Clear failed jobs
```

---

**Last Updated:** 2026-01-30
**Status:** ✅ Email configuration verified and working
