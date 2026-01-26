# musikfürfirmen.de – LLM CONTEXT & RULES

> Globale Coding-Regeln siehe: `~/CLAUDE.md`

## Projektüberblick

Website für **musikfürfirmen.de** – Livebands, DJs und Technik für Firmenevents.

**Status:** TALL Stack (production-ready since 2026-01-14)

## Repo & Hosting
- **GitHub**: github.com/NickHeymann/musikfürfirmen.de
- **Branch**: main
- **Deployment**: Hetzner (Docker via GitHub Actions)

## Stack

**Production (tall-stack/):**
- Laravel 12 + Filament 4 Admin Panel
- Livewire 3 + Alpine.js + Tailwind CSS 4
- PostgreSQL (Hetzner)
- Docker deployment

**Deprecated (archive/):**
- Next.js 16 visual editor - archived 2026-01-26
- See `archive/deprecated-nextjs-frontend/README.md`

## Projektstruktur

```
tall-stack/                    # PRODUCTION APP
├── app/
│   ├── Filament/Resources/    # Admin panel (7 resources)
│   ├── Models/                # Eloquent models
│   └── View/Components/       # Livewire components
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Initial data
├── resources/views/           # Blade templates
└── config/                    # Laravel config

archive/                       # DEPRECATED CODE
├── deprecated-nextjs-frontend/
└── deprecated-nextjs-visual-editor/
```

## Quick Reference

| Ändern... | Datei |
|-----------|-------|
| Services | Filament Admin: `/admin/services` |
| Team Members | Filament Admin: `/admin/team-members` |
| FAQs | Filament Admin: `/admin/faqs` |
| Pages | Filament Admin: `/admin/pages` |
| Events | Filament Admin: `/admin/events` |
| Bookings | Filament Admin: `/admin/bookings` |
| Contact Submissions | Filament Admin: `/admin/contact-submissions` |

## Commands

```bash
# Development (in tall-stack/)
cd tall-stack
php artisan serve              # Start dev server
php artisan migrate            # Run migrations
php artisan db:seed            # Seed data

# Production
# Deployed via GitHub Actions to Hetzner
```

## Infrastruktur

- **Hosting**: Hetzner CPX42 (Docker)
- **Database**: PostgreSQL (shared on Hetzner)
- **Admin Panel**: Filament 4 at /admin
- **Deployment**: GitHub Actions → Docker → Hetzner

## Key Documentation

- `tall-stack/MIGRATION_SUMMARY.md` - Full migration details
- `tall-stack/ADMIN_GUIDE.md` - Admin panel user guide
- `tall-stack/DEPLOYMENT.md` - Deployment instructions
- `.github/workflows/deploy.yml` - CI/CD pipeline

## Safety-Regeln für Git-Operationen durch LLM

- Arbeite NIEMALS direkt auf dem Branch `main`, sondern immer auf Feature-/Fix-Branches.
- Führe KEIN `git reset --hard`, KEIN `git push --force` und KEIN Löschen von Branches/Tags aus.
- Vor größeren Refactorings:
  - Erstelle einen neuen Branch (z.B. `refactor/<beschreibung>`).
  - Setze einen Snapshot-Tag (z.B. `snapshot-YYYYMMDD-HHMM`).
  - Pushe den aktuellen Stand auf `origin`.

## Important Notes

- **DO NOT modify code in archive/** - it's deprecated
- **All development happens in tall-stack/**
- **Client content editing via Filament admin panel at /admin**
