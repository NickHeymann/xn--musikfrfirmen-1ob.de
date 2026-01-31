# Production Email Setup Guide

## Current Configuration (Working)

```env
# Queue: SYNC for immediate sending
QUEUE_CONNECTION=sync

# Fastmail SMTP (Laravel 12)
MAIL_MAILER=smtp
MAIL_HOST=smtp.fastmail.com
MAIL_PORT=465
MAIL_USERNAME=nick@nickheymann.de
MAIL_PASSWORD=8w7s8u5u6v9q9u38
MAIL_SCHEME=smtps  # Laravel 12: Use 'smtps' not 'MAIL_ENCRYPTION=ssl'
MAIL_FROM_ADDRESS="nick@nickheymann.de"
MAIL_FROM_NAME="musikfürfirmen.de (via Nick Heymann)"

# Recipients (Punycode for IDN)
EVENT_REQUEST_RECIPIENTS="kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de"
```

## Laravel 12 Mail Changes

**CRITICAL:** Laravel 12 changed SMTP encryption config:
- ❌ OLD: `MAIL_ENCRYPTION=ssl` or `tls` (ignored in Laravel 12)
- ✅ NEW: `MAIL_SCHEME=smtps` (SSL) or `smtp` (TLS/STARTTLS)

## Queue Configuration

**Development:**
```env
QUEUE_CONNECTION=sync  # Immediate sending
```

**Production (recommended):**
```env
QUEUE_CONNECTION=database  # Better performance
```

If using `database` queue, you MUST run a queue worker:
```bash
# One-time (testing)
php artisan queue:work

# Production (with Supervisor)
# See: https://laravel.com/docs/12.x/queues#supervisor-configuration
```

## Fastmail Setup

### Aliases Configuration
1. All aliases automatically support sending (no separate "Sending Identity" config needed)
2. Current aliases:
   - `kontakt@xn--musikfrfirmen-1ob.de` → nick@nickheymann.de
   - `moin@nickheymann.de` → nick@nickheymann.de
   - `moin@jonasglamann.de` → jonas (separate account)

### App Password
- Created in Fastmail: Settings → Password & Security → App Passwords
- Name: `musikfuerfirmen-laravel`
- Password: `8w7s8u5u6v9q9u38`

## Troubleshooting

### Emails not arriving?
1. Check `QUEUE_CONNECTION` - must be `sync` OR queue worker running
2. Check `MAIL_SCHEME` - must be `smtps` for port 465
3. Run `php artisan config:clear` after .env changes
4. Check Laravel logs: `tail -f storage/logs/laravel.log`

### Test email sending:
```bash
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('your@email.com')->subject('Test'));
```

## Production Deployment Checklist

- [ ] Set `QUEUE_CONNECTION=database` in production .env
- [ ] Configure Supervisor to run `php artisan queue:work`
- [ ] Test booking flow end-to-end
- [ ] Verify all 3 recipients receive emails
- [ ] Check Fastmail "Sent" folder for confirmation
- [ ] Monitor `storage/logs/laravel.log` for errors

## Last Updated
2026-01-31 - Initial configuration (development working)
