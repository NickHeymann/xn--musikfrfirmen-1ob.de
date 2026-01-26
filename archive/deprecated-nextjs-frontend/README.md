# Deprecated: Next.js Frontend

**Archived:** 2026-01-26
**Reason:** Replaced by TALL Stack (Laravel + Livewire + Alpine.js + Tailwind)

## What This Was

The original frontend for musikfürfirmen.de built with:
- Next.js 16 + React
- TypeScript
- Tailwind CSS 4
- Custom visual editor (also deprecated, see `../deprecated-nextjs-visual-editor/`)

## Why It Was Deprecated

1. **TALL Stack is the chosen architecture** - Laravel + Filament provides everything needed
2. **Simpler deployment** - Single Laravel app vs separate frontend/backend
3. **Better client experience** - Filament admin panel is more polished than custom visual editor
4. **Maintainability** - One codebase, one language (PHP), familiar patterns

## Replacement

The production system is now in `tall-stack/`:
- Laravel 12 + Filament 4 admin panel
- Livewire 3 frontend
- PostgreSQL database
- Deployed to Hetzner via Docker

See: `tall-stack/MIGRATION_SUMMARY.md` for full migration details.

## Contents

```
src/                    # React components, pages, config
public/                 # Static assets (images, videos)
next.config.ts          # Next.js configuration
package.json            # Node.js dependencies
tsconfig.json           # TypeScript configuration
Dockerfile              # Docker build for Next.js
tests/                  # Playwright tests
node_modules/           # Dependencies (if present)
.next/                  # Build output (if present)
```

## Do Not Use

This code is archived for reference only. All new development should happen in `tall-stack/`.

## Migration Reference

If you need to reference how things were implemented:
- Components: `src/components/`
- Pages/Routes: `src/app/`
- Site config: `src/config/site.ts`
- Data files: `src/data/`

The TALL stack equivalents are:
- Components: `tall-stack/resources/views/livewire/`
- Pages: `tall-stack/resources/views/`
- Site config: `tall-stack/config/site.php`
- Data: `tall-stack/app/Models/` + Filament admin
