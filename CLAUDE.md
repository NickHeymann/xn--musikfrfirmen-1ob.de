# musikfürfirmen.de – LLM CONTEXT & RULES

> Globale Coding-Regeln siehe: `~/CLAUDE.md`

## Projektüberblick

Website für **musikfürfirmen.de** – Livebands, DJs und Technik für Firmenevents.

## Repo & Hosting
- **GitHub**: github.com/NickHeymann/musikfuerfirmen
- **Branch**: main

## Stack
Next.js | TypeScript | Tailwind CSS

## Projektstruktur
```
src/
├── app/           # Pages
├── components/    # React components
├── config/        # Site config
├── data/          # Static data
└── types/         # TypeScript interfaces
```

## Infrastruktur & Integration

- **Hosting**: Vercel (automatisches Deploy bei Push auf `main`)
- **Backend-Services** (Hetzner CX32):
  - n8n: Automation/Workflows (z.B. Kontaktformular, Anfragen)
  - Supabase: Self-Hosted (falls Datenbank benötigt)
- **Deployment**:
  - Frontend: Vercel (automatisch)
  - Backend-Services: Docker/Compose auf Hetzner
- **Secrets-Management**:
  - Vercel Environment Variables für Frontend
  - `.env` für lokale Entwicklung (in .gitignore)

## Safety-Regeln für Git-Operationen durch LLM

- Arbeite NIEMALS direkt auf dem Branch `main`, sondern immer auf Feature-/Fix-Branches (z.B. `feature/...`, `fix/...`, `refactor/...`).
- Führe KEIN `git reset --hard`, KEIN `git push --force` und KEIN Löschen von Branches oder Tags aus, außer es wird explizit und eindeutig vom Nutzer angeordnet.
- Vor größeren Refactorings oder riskanten Änderungen:
  - Erstelle einen neuen Branch (z.B. `refactor/<kurze-beschreibung>`).
  - Setze einen Snapshot-Tag (z.B. `snapshot-YYYYMMDD-HHMM`) auf den letzten stabilen Commit.
  - Pushe den aktuellen Stand des Branches auf `origin`.
- Beschreibe im Commit-Text klar, was geändert wurde (z.B. „refactor: split monolithic file into modules"), damit der Verlauf nachvollziehbar bleibt.
