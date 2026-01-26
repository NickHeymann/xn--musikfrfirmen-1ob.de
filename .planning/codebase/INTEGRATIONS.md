# External Integrations

**Analysis Date:** 2026-01-26

## APIs & External Services

**Scheduling/Calendar:**
- **Cal.com** - Appointment scheduling
  - Integration: Embedded booking link in site config
  - URL: `https://cal.com/xn--musikfrfirmen-1ob.de/30min`
  - Implementation: `src/config/site.ts` (calComUrl)

**Geocoding & City Autocomplete:**
- **Komoot Photon API** - Open-source reverse geocoding
  - Service: City autocomplete for contact form
  - Endpoint: `https://photon.komoot.io/api/`
  - Parameters: German cities (bbox: 5.87,47.27,15.04,55.06)
  - Implementation: `src/components/contact/useContactForm.ts` (useCityAutocomplete hook)
  - Auth: None (open API)

**Email Marketing (Optional):**
- **Brevo** (formerly Sendinblue) - Email service provider
  - Purpose: Newsletter/email campaigns (if enabled)
  - API Key env var: `NEXT_PUBLIC_BREVO_API_KEY`
  - Status: Optional (not currently integrated in code)
  - Privacy: EU-based data storage

## Data Storage

**Databases:**
- **PostgreSQL** - Relational database
  - Provider: Hetzner shared database (91.99.177.238:5432)
  - Connection: Laravel Eloquent ORM
  - Credentials: Env vars (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
  - Client: Laravel Query Builder / Eloquent

**Cache:**
- **Redis** 7-alpine - In-memory cache & queue
  - Docker container: `musikfürfirmen.de-redis`
  - Host: `redis` (container network)
  - Port: 6379
  - Purpose: Session storage, job queue, cache
  - Persistence: AOF (appendonly) enabled

**File Storage:**
- **Local filesystem** - Project storage directory
  - Backend: `./storage` volume in Docker (`/var/www/storage`)
  - Frontend: Static assets in `public/` directory
  - No external cloud storage configured

## Authentication & Identity

**Auth Provider:**
- **Custom** - Manual authentication in admin area
  - Implementation: EditorAuthContext (`src/contexts/EditorAuthContext.tsx`)
  - Password: `NEXT_PUBLIC_EDITOR_PASSWORD` env var (default: admin123)
  - Session: Client-side state management
  - Security: Password protection for admin editor pages

**Permission System:**
- **Laravel Permission** (Spatie) - Role-based access control
  - Backend: Filament 4 admin panel
  - Implementation: `spatie/laravel-permission` 6.24
  - Features: Role and permission management

## Monitoring & Observability

**Error Tracking:**
- **Built-in error logging** - Custom implementation
  - Location: `src/components/ErrorLoggerInit.tsx` and `src/lib/error-logger.ts`
  - Backend endpoint: `POST /api/log-error`
  - Implementation: `src/app/api/log-error/route.ts`
  - Storage: Daily log files in `logs/errors-YYYY-MM-DD.log`
  - Memory: Last 50 errors kept in-memory for debug panel
  - Size limit: 10KB per entry (oversized entries skipped)
  - Access: Debug panel at `src/components/DebugPanel.tsx`

**Analytics:**
- **Vercel Analytics** 1.5.0 - Web Vitals tracking
  - Package: `@vercel/analytics`
  - Purpose: Core Web Vitals metrics
  - Privacy note: No Google Analytics (explicitly disabled per privacy policy)

**Logs:**
- **File-based** - Server-side logging
  - Daily log files: `logs/errors-YYYY-MM-DD.log`
  - Format: JSON entries with timestamp
  - Rotation: Daily (new file each day)
  - Retention: Manual cleanup (no auto-deletion configured)

## CI/CD & Deployment

**Hosting:**
- **Hetzner** (CPX32 server at 91.99.177.238)
  - Operating System: Linux
  - Container Runtime: Docker with docker-compose
  - Network: Reverse proxy via Traefik

**Container Registry:**
- **GitLab Container Registry** - Private Docker image registry
  - Registry: `git.nickheymann.de:5050`
  - Namespace: `nickheymann/musikfürfirmen.de`
  - Images tagged: commit SHA and `latest`
  - Credentials: CI/CD variables (CI_REGISTRY_USER, CI_REGISTRY_PASSWORD)

**CI Pipeline:**
- **GitLab CI/CD** - Automated build and deployment
  - Build stage: Docker build with layer caching
  - Deploy stage: SSH to Hetzner server, docker-compose pull & up
  - Triggers: Main branch only
  - Build time: ~1.5-2.5 min (with cache)

**Reverse Proxy:**
- **Traefik** - Reverse proxy with automatic SSL
  - SSL Provider: Let's Encrypt (automatic certificate provisioning)
  - Network: `traefik-net` (external Docker network)
  - HTTP to HTTPS: Automatic redirect via middleware
  - Host rules: `musikfürfirmen.de.de` and `www.musikfürfirmen.de.de`

## Environment Configuration

**Required Environment Variables:**

Frontend:
- `NEXT_PUBLIC_API_URL` - Backend API endpoint
- `NEXT_PUBLIC_EDITOR_PASSWORD` - Admin editor password
- `NEXT_PUBLIC_SITE_URL` - Site domain
- `NODE_ENV` - production/development
- `PORT` - Server port (3000)

Backend:
- `APP_KEY` - Laravel encryption key (required)
- `APP_ENV` - production/local
- `APP_DEBUG` - Debug mode flag
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
- `REDIS_HOST`, `REDIS_PORT`

**Secrets Location:**
- GitLab CI/CD Variables (encrypted)
- `.env.local` (local development, git-ignored)
- `.env.production` (Hetzner server, not in git)
- Docker environment variables in docker-compose.yml

## Webhooks & Callbacks

**Incoming:**
- **Error logging webhook** - Internal
  - Endpoint: `POST /api/log-error` (Next.js route)
  - Purpose: Client-side error logging
  - Payload: Error object with timestamp

**Outgoing:**
- **Contact form submission** - Email fallback
  - Method: `mailto:` protocol (client-side)
  - Recipient: `kontakt@musikfürfirmen.de`
  - Format: Pre-formatted email body with form data
  - Implementation: `src/components/contact/useContactForm.ts` (handleSubmit)
  - No automated webhook to external service (uses native email client)

## Backend Services (Laravel)

**Queue System:**
- **Laravel Queue** (Redis-backed)
  - Worker container: `musikfürfirmen.de-queue`
  - Command: `php artisan queue:work --tries=3 --timeout=90`
  - Storage: Redis (shared with cache)
  - Purpose: Async job processing (emails, notifications, etc.)

**Scheduler:**
- **Laravel Scheduler** - Cron job replacement
  - Scheduler container: `musikfürfirmen.de-scheduler`
  - Interval: Every 60 seconds
  - Command: `php artisan schedule:run --verbose --no-interaction`
  - Purpose: Automated tasks (cleanup, reports, maintenance)

**Mail System:**
- **SMTP-based** - External email delivery
  - Configuration: Env vars (MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD)
  - Encryption: TLS (MAIL_ENCRYPTION=tls)
  - From: `noreply@musikfürfirmen.de.de` or custom

---

*Integration audit: 2026-01-26*
