# Technology Stack

**Analysis Date:** 2026-01-26

## Languages

**Primary:**
- **TypeScript** 5.x - Frontend (Next.js application)
- **PHP** 8.2+ - Backend API (Laravel application)
- **JavaScript** (Node.js) - Build tooling and Next.js runtime

**Secondary:**
- **BASH/Shell** - Deployment scripts and automation
- **CSS** - Styling (via Tailwind CSS)

## Runtime

**Environment:**
- **Node.js** 20-alpine - Next.js frontend runtime (Docker image `node:20-alpine`)
- **PHP** 8.2-8.5 - Laravel backend (via Laravel Sail)

**Package Managers:**
- **npm** - Frontend dependencies
- **Composer** - Backend PHP dependencies

## Frameworks

**Frontend:**
- **Next.js** 16.0.5 - React-based full-stack framework (SSR/SSG capable)
  - Path alias: `@/*` maps to `./src/*`
  - Turbopack disabled (causes memory leaks)
  - Image optimization enabled (avif, webp formats)

**Frontend UI/Components:**
- **React** 19.2.0 - Component framework
- **Tailwind CSS** 4.x - Utility-first CSS framework via PostCSS
- **Framer Motion** 12.23.24 - Animation library
- **Tiptap** 3.15.3 - Rich text editor with extensions (color, font, highlight, link, text-align, underline)
- **dnd-kit** 6.3.1 - Drag-and-drop library
- **Lucide React** 0.562.0 - Icon library

**Backend:**
- **Laravel** 12.0 - PHP web framework
- **Filament** 4.0 - Admin panel framework
- **Livewire** 3.0 - Server-driven reactive components
- **Laravel Permission** 6.24 - Role-based access control

**Testing:**
- **Playwright** (browser-based E2E testing, configured for Chromium)
- **PHPUnit** 11.5.3 - Backend unit tests

## Key Dependencies

**Critical Frontend:**
- `@vercel/analytics` 1.5.0 - Performance analytics
- `framer-motion` 12.23.24 - Smooth animations and transitions
- `dompurify` 3.3.1 - Sanitize untrusted HTML (for rich text editor)
- `zod` 4.3.5 - TypeScript-first schema validation
- `clsx` 2.1.1 - Conditional className utility
- `nanoid` 5.1.6 - Tiny unique ID generator
- `use-debounce` 10.1.0 - Debounce hook for form inputs

**Critical Backend:**
- `intervention/image` 3.11 - Image processing and manipulation
- `spatie/laravel-permission` 6.24 - ACL/permission management

**Development:**
- `eslint` 9.x - JavaScript/TypeScript linting
- `eslint-config-next` 16.0.5 - Next.js ESLint rules
- `@tailwindcss/postcss` 4.x - PostCSS integration

## Configuration

**Environment Variables (Frontend):**
```
NEXT_PUBLIC_API_URL          # Backend API endpoint (http://localhost:8001/api or https://api.musikfuerfirmen.de/api)
NEXT_PUBLIC_EDITOR_PASSWORD  # Admin editor password (default: admin123)
NEXT_PUBLIC_SITE_URL         # Site domain for SEO
NODE_ENV                     # production/development
PORT                         # Port 3000 (default)
HOSTNAME                     # 0.0.0.0 (Docker)
```

**Environment Variables (Backend):**
```
APP_ENV                      # production/local
APP_DEBUG                    # true/false
APP_KEY                      # Encryption key
APP_URL                      # Backend URL
DB_CONNECTION                # pgsql
DB_HOST                      # PostgreSQL host (91.99.177.238)
DB_PORT                      # 5432
DB_DATABASE                  # musikfürfirmen.de
DB_USERNAME                  # Database user
DB_PASSWORD                  # Database password
MAIL_MAILER                  # smtp
MAIL_HOST                    # SMTP server
MAIL_PORT                    # 587
MAIL_USERNAME                # SMTP username
MAIL_PASSWORD                # SMTP password
MAIL_ENCRYPTION              # tls
MAIL_FROM_ADDRESS            # noreply@musikfürfirmen.de.de
MAIL_FROM_NAME               # musikfürfirmen.de
REDIS_HOST                   # redis (container name)
REDIS_PORT                   # 6379
```

**Build Configuration:**
- `next.config.ts` - Next.js configuration (Turbopack disabled, image optimization)
- `tsconfig.json` - TypeScript compiler options (ES2017 target, strict mode enabled)
- `eslint.config.mjs` - ESLint configuration (Next.js core-web-vitals + TypeScript rules)
- `postcss.config.mjs` - PostCSS configuration (Tailwind CSS plugin)
- `playwright.config.js` - E2E test configuration (Chromium browser, localhost:3000)

**Development:**
- `.env.local` - Local development environment variables
- `.env.local.example` - Template for local development

**Production:**
- `.env.production` - Production environment variables
- `next.config.production.ts` - Production-specific Next.js configuration

## Platform Requirements

**Development:**
- Node.js 20+ (recommended)
- npm or yarn package manager
- PHP 8.2+ (for backend development)
- Composer (for Laravel dependency management)
- Docker & Docker Compose (recommended for local backend)

**Production:**
- **Hosting:** Hetzner CX32 server (91.99.177.238)
- **Deployment:** Docker containers with Traefik reverse proxy
- **Container Runtime:** Docker Engine with docker-compose
- **Registry:** GitLab Container Registry (git.nickheymann.de:5050)
- **Reverse Proxy:** Traefik (with automatic SSL via Let's Encrypt)
- **Database:** PostgreSQL 13+ (shared on Hetzner)
- **Cache:** Redis 7-alpine
- **Webserver:** Nginx:alpine (backend proxy)

## Containerization

**Frontend (Next.js):**
- Multi-stage Docker build (`node:20-alpine`)
- Non-root user `nextjs` (uid:1001)
- Standalone build output with node_modules included
- Image size optimization with layer caching

**Backend (Laravel):**
- Laravel Sail runtime (PHP 8.2-8.5)
- Nginx reverse proxy container
- Redis container (7-alpine)
- Queue worker container (php artisan queue:work)
- Scheduler container (php artisan schedule:run)
- All services in docker-compose.yml orchestration

## Deployment Pipeline

**CI/CD:**
- **Platform:** GitLab CI/CD
- **Registry:** GitLab Container Registry at `git.nickheymann.de:5050`
- **Build:** Docker build with layer caching (30-50% faster builds)
- **Push:** Multi-tag push (commit SHA + latest)
- **Deploy:** SSH to Hetzner, `docker compose pull && docker compose up -d`
- **Triggers:** Main branch pushes only

---

*Stack analysis: 2026-01-26*
