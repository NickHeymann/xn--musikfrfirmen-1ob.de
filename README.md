# musikfürfirmen.de

Website for **musikfürfirmen.de** – Live bands, DJs, and technical equipment for corporate events.

## Stack

**TALL Stack (Laravel + Livewire + Alpine.js + Tailwind CSS)**

- Laravel 12 + Filament 4 Admin Panel
- Livewire 3 for reactive components
- Alpine.js for client-side interactivity
- Tailwind CSS 4 for styling
- PostgreSQL database
- Docker deployment to Hetzner

## Getting Started

```bash
# Navigate to the TALL stack directory
cd tall-stack

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed data
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) to see the site.

## Admin Panel

Access the Filament admin panel at [http://localhost:8000/admin](http://localhost:8000/admin)

**Features:**
- Services management
- Team members management
- FAQs management
- Pages management (Impressum, Datenschutz, etc.)
- Events management
- Booking management
- Contact submissions

See `tall-stack/ADMIN_GUIDE.md` for detailed usage instructions.

## Deployment

Deployed via GitHub Actions to Hetzner:

```
Push to main → GitHub Actions → Docker build → Hetzner deployment
```

See `tall-stack/DEPLOYMENT.md` for detailed deployment instructions.

## Documentation

- `tall-stack/MIGRATION_SUMMARY.md` - Migration from Next.js to TALL Stack
- `tall-stack/ADMIN_GUIDE.md` - Admin panel user guide
- `tall-stack/DEPLOYMENT.md` - Deployment instructions
- `CLAUDE.md` - AI assistant context

## Archive

The `archive/` directory contains deprecated code:
- `deprecated-nextjs-frontend/` - Original Next.js frontend (replaced 2026-01-26)
- `deprecated-nextjs-visual-editor/` - Custom visual editor (replaced by Filament)

**Do not modify archived code** – it's kept for reference only.
