# musikfürfirmen.de - Project Guidelines

## Project Overview
Website für **musikfürfirmen.de** – Livebands, DJs und Technik für Firmenevents.

## Code Conventions

### File Size
- Target: 100-200 lines per file
- Soft limit: 300 lines
- Split large files into smaller modules

### Livewire Components
- Use Livewire 4 Islands for expensive components when upgrading
- Alpine.js only for client-side interactions (animations, modals)
- Single-File Components preferred for simple components

### Filament Admin
- All content editing via Filament at `/admin`
- Use existing Resources as templates for new ones
- Follow existing naming conventions

### Testing
- PHPUnit for now (project standard)
- Run `php artisan test --compact --filter=testName` after changes
- Feature tests preferred over unit tests

### Naming
- Livewire components: PascalCase (e.g., `ServiceCards.php`)
- Blade views: kebab-case (e.g., `service-cards.blade.php`)
- Routes: kebab-case (e.g., `contact-form`)

## Architecture

```
app/
├── Filament/Resources/    # Admin panel (7 resources)
├── Models/                # Eloquent models
├── View/Components/       # Livewire components
resources/views/
├── components/            # Blade components
├── livewire/              # Livewire views
└── layouts/               # Layout templates
```

## Git Rules
- Never commit directly to `main`
- Create feature branches: `feature/description`
- Snapshot tags before refactoring: `snapshot-YYYYMMDD-HHMM`

## Deployment
- GitHub Actions → Docker → Hetzner
- PostgreSQL database on Hetzner
- Domain: musikfürfirmen.de
