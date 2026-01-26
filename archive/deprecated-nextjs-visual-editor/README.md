# Deprecated: Next.js Visual Editor

**Archived:** 2026-01-26
**Reason:** Replaced by TALL Stack with Filament admin panel

## What This Was

A custom visual page editor built with:
- Next.js 16 + React
- Tiptap rich text editor
- dnd-kit drag-and-drop
- Custom block system

## Why It Was Deprecated

1. **Too complex** - Building a Squarespace-like editor from scratch was overkill
2. **Client needs simpler** - Clients just need to edit content, not design pages
3. **TALL Stack is better** - Filament provides a polished admin UI out of the box

## Replacement

The production system is now in `tall-stack/`:
- Laravel 12 + Filament 4 admin panel
- Livewire 3 frontend
- PostgreSQL database
- Clients can edit: Services, Team, FAQs, Pages, Events, Bookings

See: `tall-stack/MIGRATION_SUMMARY.md` for full migration details.

## Contents

```
visual-editor/           # The custom editor code
laravel-backend-files/   # Old API (replaced by tall-stack)
*.md                     # Related documentation
```

## Do Not Use

This code is archived for reference only. All new development should happen in `tall-stack/`.
