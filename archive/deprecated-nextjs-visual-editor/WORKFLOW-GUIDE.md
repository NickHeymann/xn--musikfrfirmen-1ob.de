# Ultimate Debugging & GUI-Design Workflow für Claude Code
> Von Nick Heymann - 31. Dezember 2024

## Problem das gelöst wird

**VORHER:**
- Bugs finden aber Claude kann sie nicht lösen (weil Kontext fehlt)
- Viele random Versuche beim Debuggen
- GUI-Design mit Claude ist schwierig (Blind raten)
- Keine User zum Testen → alle Bugs selbst finden

**NACHHER:**
- Bugs werden automatisch mit vollem Kontext erfasst
- "Copy for Claude" Button → Claude weiß EXAKT wo Fehler ist
- GUI-Design in Storybook → Claude sieht Components live
- UX-Pilot prüft vor Deploy → keine User-Beschwerden

---

## Tool-Suite

### 1. Development Helper Panel ✅
- **Was**: Schwebendes Debug-Panel in Browser
- **Wann**: Läuft IMMER automatisch (nur localhost)
- **Interface**: GUI (in Ihrer App)
- **Nutzen**: Fängt alle Errors mit Stack Trace + User Actions

### 2. Storybook ⏳
- **Was**: Component Library & Design System
- **Wann**: Beim GUI-Design mit Claude
- **Interface**: Web UI (localhost:6006)
- **Nutzen**: Components isoliert testen, live sehen

### 3. UX-Pilot ✅
- **Was**: Accessibility/UX Testing (lokal, kostenlos)
- **Wann**: Vor Deploy
- **Interface**: Konsole + HTML Reports
- **Nutzen**: WCAG-Compliance, Ollama Vision-Analyse

### 4. Sentry (optional)
- **Was**: Production Error Tracking
- **Wann**: Nach Deploy (für my-second-brain)
- **Interface**: Web Dashboard
- **Nutzen**: User-Errors in Production

---

## Daily Workflow

### MORGENS - Setup

```bash
# Terminal 1: Projekt starten
cd ~/Desktop/Mein\ Business/Programmierprojekte/musikfürfirmen.de
npm run dev
# → http://localhost:3000 (Debug Panel aktiv)

# Terminal 2: Storybook starten
npm run storybook
# → http://localhost:6006 (Component Library)
```

**Browser Tabs:**
- Tab 1: localhost:3000 (Ihre App + Debug Panel)
- Tab 2: localhost:6006 (Storybook)
- Tab 3: Claude Code

---

### BUG FINDEN & FIXEN

**1. Bug passiert**
```
Browser → Debug Panel erscheint
→ Zeigt: "ContactModal.tsx:45 - TypeError: Cannot read 'email' of undefined"
→ Breadcrumbs: User klickte Button → Form submit
→ Network: POST /api/contact → 200 OK
→ Klick: [📋 Copy for Claude]
```

**2. Zu Claude**
```
Claude Code → Paste

Report enthält:
- Datei: ContactModal.tsx:45
- Error: TypeError
- Stack Trace
- User Actions (Breadcrumbs)
- Network Requests
- Browser Info

→ Claude: "Ah! In Zeile 45 greifst du auf formData.email zu,
           aber formData ist undefined. Fix:"
→ Claude ändert Code
```

**3. Automatisch**
```
Browser → Hot Reload
→ Bug ist weg
→ Debug Panel: ✅ Keine Errors
```

**Ergebnis:** Bug in 2 Minuten gefixt statt 30 Minuten raten!

---

### GUI-DESIGN MIT CLAUDE

**1. Component in Storybook öffnen**
```
localhost:6006
→ Sidebar: Components → ContactModal
→ Sehen: Modal isoliert
→ Screenshot: Cmd+Shift+4
```

**2. Zu Claude**
```
"Mach den Submit-Button größer und grüner"
*Screenshot anhängen*

Claude ändert:
→ src/components/contact/ContactModal.tsx
→ className="btn-lg bg-green-600 hover:bg-green-700"
```

**3. Storybook Hot Reload**
```
→ Button ist SOFORT größer & grün
→ Sie sehen Änderung live
→ Keine Random-Versuche mehr!
```

**Ergebnis:** GUI-Design in 1 Iteration statt 10!

---

### UX/ACCESSIBILITY PRÜFEN (vor Deploy)

```bash
# Terminal 3
cd ~/Projects/ux-pilot
npm run scan http://localhost:3000 -- --ollama --html

# → Läuft 20 Sekunden
# → Öffnet HTML Report automatisch
```

**Report zeigt:**
```
UX-Pilot Report für http://localhost:3000
Overall Score: 92/100
Accessibility: 95/100

Issues:
⚠️ SERIOUS: Missing alt text on logo
   File: src/components/Header.tsx:12
   Fix: Add alt="Company Logo"

⚠️ MODERATE: No skip-to-content link
   File: src/app/layout.tsx:15
   Fix: Add <a href="#main" class="skip-link">Skip to content</a>

ℹ️  MINOR: Low contrast footer links
   Element: footer a
   Current: #999 (3.2:1)
   Required: 4.5:1
   Fix: Change to #666
```

**Zu Claude:**
```
*Copy kompletten Report*
→ Paste zu Claude Code

Claude:
→ Header.tsx:12 + alt="Company Logo"
→ layout.tsx:15 + skip-link
→ Footer CSS: #999 → #666

Erneut testen:
npm run scan http://localhost:3000

→ Overall: 100/100 ✅
```

**Ergebnis:** Barrierefreiheit BEVOR User sich beschweren!

---

## Keyboard Shortcuts

| Shortcut | Funktion |
|----------|----------|
| `Cmd/Ctrl + Shift + D` | Debug Panel öffnen/schließen |
| `F12` | Browser DevTools |
| `Cmd + Shift + 4` | Screenshot (Mac) |
| `Cmd + K` (in Claude Code) | Neue Konversation |

---

## Console Commands

### Debug Helper Panel
```javascript
// Im Browser Console:
window.DebugHelper.getState()      // Aktueller State
window.DebugHelper.clear()         // Alle Errors löschen
window.DebugHelper.copyForClaude() // Report kopieren
```

### UX-Pilot
```bash
# Single URL
npm run scan https://example.com

# Mit Ollama Vision (lokal, kostenlos)
npm run scan https://example.com -- --ollama

# HTML Report
npm run scan https://example.com -- --html

# Multi-URL
npm run scan https://a.com https://b.com https://c.com -- --html

# Sitemap
npm run scan -- --sitemap https://example.com/sitemap.xml
```

### Storybook
```bash
npm run storybook              # Starten
npm run build-storybook        # Build für Deploy
npx chromatic --project-token=... # Visual Regression Testing
```

---

## Installation

### musikfürfirmen.de (Next.js)

**Debug Helper Panel:**
```typescript
// src/app/layout.tsx
import { DebugHelper } from '@/components/DebugHelper';

export default function RootLayout({ children }) {
  return (
    <html lang="de">
      <body>
        {children}
        <DebugHelper />
      </body>
    </html>
  );
}
```

**Storybook:**
```bash
# Bereits installiert! ✅
npm run storybook
```

---

### kathrin-coaching (Vanilla JS)

**Debug Helper Panel:**
```html
<!-- In index.html vor </head>: -->
<script src="../_shared/debug-helper.js"></script>
```

**Storybook:** NICHT nötig (keine React Components)

---

### my-second-brain (Electron)

**Sentry (optional - für Production Errors):**
```javascript
// In main.js (Zeile 1-5):
import * as Sentry from '@sentry/electron/main';

Sentry.init({
  dsn: process.env.SENTRY_DSN,
  environment: process.env.NODE_ENV || 'development',
});

// In renderer/app.js (Zeile 1-5):
import * as Sentry from '@sentry/electron/renderer';
Sentry.init({});
```

---

## UX-Pilot Features (bereits gebaut!)

### Fix-Prompt Generator
```bash
npm run scan http://localhost:3000

→ Erstellt automatisch:
  reports/localhost-fix-prompt-2025-12-31.md

→ Enthält Claude-optimiertes Format:
  - Issues mit Severity
  - Betroffene Dateien:Zeilen
  - Konkrete Fix-Anweisungen
  - Screenshots
```

**Dann einfach zu Claude:** *Paste kompletten Markdown*

### Ollama Vision (lokal, kostenlos)
```bash
# Einmalig:
brew install ollama
ollama pull llava:13b  # oder qwen2-vl:7b (kleiner)

# Dann für immer kostenlos:
npm run scan http://localhost:3000 -- --ollama

→ Analysiert visuell:
  ✓ Farbkontraste
  ✓ Button-Größen (Touch Targets)
  ✓ Layout-Hierarchie
  ✓ Lesbarkeit

→ Alles lokal, keine Cloud, keine Kosten!
```

---

## Troubleshooting

### Debug Panel erscheint nicht
```javascript
// Browser Console:
window.DebugHelper
// → Sollte Object sein

// Falls undefined:
// 1. Prüfe ob Script geladen: Network Tab → debug-helper.js
// 2. Prüfe ob localhost (nur dort aktiv!)
// 3. Hard Reload: Cmd+Shift+R
```

### Storybook startet nicht
```bash
# Node Version prüfen:
node --version  # Sollte >= 20 sein

# Neu installieren:
rm -rf node_modules package-lock.json
npm install
npm run storybook
```

### UX-Pilot Ollama Fehler
```bash
# Ollama läuft?
ollama list  # Zeigt installierte Modelle

# Ollama starten:
ollama serve

# Model fehlt?
ollama pull llava:13b
```

---

## Best Practices

### 1. Debug Panel
- ✅ Lasse es immer laufen (localhost only)
- ✅ Nutze "Copy for Claude" bei jedem Error
- ✅ Schaue Breadcrumbs an (User Actions)
- ❌ Ignoriere nicht die Network Logs

### 2. Storybook
- ✅ Teste Components BEVOR Integration
- ✅ Nutze Accessibility Addon (auto-integriert)
- ✅ Mach Screenshots für Claude
- ❌ Ändere nicht direkt in Story-Files

### 3. UX-Pilot
- ✅ Teste VOR jedem Deploy
- ✅ Nutze --ollama für visuelle Checks
- ✅ Paste kompletten Report zu Claude
- ❌ Ignoriere nicht "moderate" Issues

### 4. Mit Claude arbeiten
- ✅ Gebe IMMER Kontext (Debug Reports)
- ✅ Screenshots bei GUI-Fragen
- ✅ Lass Claude in Storybook testen
- ❌ Sage nicht nur "Button geht nicht"

---

## Erweiterte Workflows

### Visual Regression Testing (Chromatic)
```bash
# Einmalig:
npx chromatic --project-token=YOUR_TOKEN

# Bei jedem PR:
# → GitHub Action läuft automatisch
# → Vergleicht Screenshots
# → Zeigt Diff in PR
```

### Multi-Project UX Scan
```bash
# Alle Projekte auf einmal:
cd ~/Projects/ux-pilot

npm run scan \
  http://localhost:3000 \
  http://localhost:8765 \
  https://kathrin-coaching.de \
  -- --ollama --html

→ Ein kombinierter Report für alle!
```

### Sentry Self-Hosted (Hetzner)
```yaml
# /opt/docker/docker-compose.yml
sentry:
  image: sentry:latest
  environment:
    - DATABASE_URL=postgres://...
  labels:
    - "traefik.enable=true"
    - "traefik.http.routers.sentry.rule=Host(`sentry.91.99.177.238.nip.io`)"
```

---

## FAQ

**Q: Muss Storybook in JEDEM Projekt sein?**
A: Nein! Nur in React/Vue/Angular Projekten. Vanilla JS braucht kein Storybook.

**Q: Ist UX-Pilot kostenlos?**
A: Ja! 100% Open Source, läuft lokal, nutzt Ollama (auch lokal & kostenlos).

**Q: Können andere User mein Debug Panel sehen?**
A: Nein! Es läuft nur bei localhost. In Production ist es automatisch deaktiviert.

**Q: Brauche ich alle Tools?**
A: Minimum: Debug Panel + UX-Pilot. Storybook nur wenn React/Next.js.

---

## Nächste Schritte

1. ✅ **JETZT TESTEN:**
   ```bash
   cd musikfürfirmen.de
   npm run dev
   # → Öffne localhost:3000
   # → Drücke Cmd+Shift+D
   # → Debug Panel sollte erscheinen!
   ```

2. ✅ **Storybook testen:**
   ```bash
   npm run storybook
   # → Öffne localhost:6006
   # → Siehe Components
   ```

3. ✅ **UX-Pilot testen:**
   ```bash
   cd ~/Projects/ux-pilot
   npm run scan http://localhost:3000 -- --html
   # → Report öffnet automatisch
   ```

4. ⏳ **Ersten Bug mit Claude fixen:**
   - Provoziere einen Error
   - Debug Panel → Copy for Claude
   - Paste zu Claude
   - Siehe wie schnell es geht!

---

## Support & Updates

- **Debug Helper Panel:** `/Users/nickheymann/Desktop/Mein Business/Programmierprojekte/_shared/debug-helper.js`
- **UX-Pilot:** `/Users/nickheymann/Projects/ux-pilot`
- **Workflow Guide:** Diese Datei

Bei Problemen: Frage Claude Code direkt!

---

**Happy Debugging! 🎯**
