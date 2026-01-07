# musikfürfirmen.de – LLM CONTEXT & RULES

> Globale Coding-Regeln siehe: `~/CLAUDE.md`

## Projektüberblick

Website für **musikfürfirmen.de** – Livebands, DJs und Technik für Firmenevents.

## Repo & Hosting
- **GitHub**: github.com/NickHeymann/musikfuerfirmen
- **Branch**: main

## Stack
Next.js 16 | TypeScript | Tailwind CSS 4

## Projektstruktur
```
src/
├── app/              # Pages/Routes
├── components/       # React Components
│   ├── contact/      # Contact Modal (modular)
│   └── icons/        # Icon Components
├── config/           # Site Config (Single Source of Truth)
│   └── site.ts
├── data/             # Static Data
│   ├── faq.ts
│   ├── team.ts
│   ├── services.ts
│   └── jsonLd.ts
└── types/            # TypeScript Interfaces
    └── index.ts
```

## Quick Reference

| Ändern... | Datei |
|-----------|-------|
| Site Config (Name, Email, Phone) | `src/config/site.ts` |
| Navigation Links | `src/config/site.ts` |
| FAQ Daten | `src/data/faq.ts` |
| Team Daten | `src/data/team.ts` |
| Service Steps | `src/data/services.ts` |
| TypeScript Types | `src/types/index.ts` |
| Icons | `src/components/icons/index.tsx` |
| Contact Modal | `src/components/contact/` |

## Commands
```bash
npm run dev    # Development Server
npm run build  # Production Build
npm run lint   # ESLint
```

## Infrastruktur & Integration

- **Hosting**: Vercel (automatisches Deploy bei Push auf `main`)
- **Backend-Services** (Hetzner CPX42):
  - n8n: Automation/Workflows (z.B. Kontaktformular, Anfragen)
  - Supabase: Self-Hosted (falls Datenbank benötigt)
- **Deployment**:
  - Frontend: Vercel (automatisch)
  - Backend-Services: Docker/Compose auf Hetzner
- **Secrets-Management**:
  - Vercel Environment Variables für Frontend
  - `.env` für lokale Entwicklung (in .gitignore)

## Safety-Regeln für Git-Operationen durch LLM

- Arbeite NIEMALS direkt auf dem Branch `main`, sondern immer auf Feature-/Fix-Branches.
- Führe KEIN `git reset --hard`, KEIN `git push --force` und KEIN Löschen von Branches/Tags aus.
- Vor größeren Refactorings:
  - Erstelle einen neuen Branch (z.B. `refactor/<beschreibung>`).
  - Setze einen Snapshot-Tag (z.B. `snapshot-YYYYMMDD-HHMM`).
  - Pushe den aktuellen Stand auf `origin`.
