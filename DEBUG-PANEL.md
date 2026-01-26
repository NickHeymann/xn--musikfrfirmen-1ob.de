# 🐛 Debug Panel & Error Logging

**Date:** 2026-01-17
**Status:** ✅ Active (Development Only)

---

## Was wurde eingebaut?

### 1. **Error Logger** (`src/lib/error-logger.ts`)
- Fängt ALLE JavaScript-Fehler automatisch ab
- Loggt React-Fehler, Console-Errors, unhandled Promises
- Filtert Noise-Errors automatisch raus
- Sendet Logs an `/api/log-error` Endpoint

### 2. **Debug Panel** (`src/components/DebugPanel.tsx`)
- **Roter Button** unten rechts: "🐛 Errors (X)"
- Zeigt alle Fehler in Echtzeit
- Filter nach: All / Error / Warning / Info
- Export-Funktion für Logs als JSON
- Stack Traces & Component Stacks klickbar

### 3. **Server Logging** (`src/app/api/log-error/route.ts`)
- Speichert Fehler in `logs/errors-YYYY-MM-DD.log`
- Hält letzte 50 Errors im Memory
- API: `GET /api/log-error` für aktuelle Logs

---

## 🎯 Wie du es nutzt

### Schritt 1: Öffne die Seite
```
http://localhost:3000/admin/pages
```

### Schritt 2: Klicke auf den roten Button
Unten rechts siehst du:
```
🐛 Errors (X)
```
- **(X)** = Anzahl der Fehler seit Seite geladen wurde
- Klick öffnet das Debug-Panel

### Schritt 3: Teste die Editor-Funktionen
- Gehe auf "Edit" bei einer Page
- Versuche Komponenten zu ziehen
- Editiere Properties
- **ALLE Fehler werden automatisch geloggt!**

### Schritt 4: Sieh dir die Fehler an
Im Debug-Panel siehst du:
- **Zeitstempel** - Wann der Fehler passiert ist
- **Error Type** - ERROR / WARNING / INFO
- **Message** - Fehlermeldung
- **Metadata** - Zusätzliche Infos (klickbar)
- **Stack Trace** - Wo im Code (klickbar)

### Schritt 5: Export Logs
Klicke auf "Export" im Debug-Panel:
- Lädt `error-logs-[timestamp].json` herunter
- Kannst du mir schicken für Analyse

---

## 🔍 Was wurde gefixt

### Problem 1: `schema._def.shape is not a function`
**Ursache:** Zod v3.x hat die API geändert
**Fix:** Kompatibilitäts-Check in `PropertiesPanel.tsx`:
```typescript
const schemaShape = typeof schema._def.shape === 'function'
  ? schema._def.shape()
  : schema._def.shape;
```

### Problem 2: Hydration-Fehler
**Ursache:** Browser-Extension (Chrome) modifiziert das HTML
**Workaround:** Wird jetzt geloggt, aber nicht mehr als kritisch behandelt
**Lösung:** Deaktiviere temporär Browser-Extensions beim Testen

---

## 📊 Live-Monitoring

### Während du testest:
1. **Debug-Panel bleibt offen** - siehst du Fehler in Echtzeit
2. **Logs werden gespeichert** - in `logs/errors-[date].log`
3. **Ich bekomme Zugriff** - durch Export-Funktion

### Was du mir schickst:
- Screenshot vom Debug-Panel
- Oder: Export-JSON-Datei
- Oder: Kopiere die Error-Message

---

## 🧪 Test-Szenarien

### Test 1: Komponente ziehen
1. Öffne `/admin/editor/home`
2. Ziehe "Hero" Component zum Canvas
3. **Erwartung:** Keine Errors (außer Hydration-Warnung)

### Test 2: Properties editieren
1. Klicke auf eine Component im Canvas
2. Ändere Text im Properties-Panel
3. **Erwartung:** Keine Errors, Auto-Save funktioniert

### Test 3: Image Upload
1. Wähle Component mit Image-Property
2. Klicke "Choose File"
3. Lade ein Bild hoch
4. **Erwartung:** Bild wird hochgeladen, keine Errors

### Test 4: Komponente neu ordnen
1. Ziehe Component an eine andere Position
2. **Erwartung:** Reorder funktioniert, keine Errors

---

## 📝 Bekannte Warnungen (OK)

Diese Warnings sind **normal** und können ignoriert werden:

### 1. Hydration Mismatch (Browser Extension)
```
A tree hydrated but some attributes didn't match
- src="chrome-extension://..."
```
**Grund:** Chrome Extension injiziert Code
**Lösung:** Ignorieren oder Extension deaktivieren
**Impact:** Keine - funktioniert trotzdem

### 2. React DevTools
```
Download the React DevTools
```
**Grund:** Next.js Message
**Lösung:** Ignorieren
**Impact:** Keine

---

## 🚨 Echte Fehler (zu fixen)

Diese Errors **sollten nicht** auftreten:

### TypeError: Cannot read property 'X' of undefined
- **Bedeutung:** Component hat fehlende Daten
- **Action:** Screenshot machen & schicken

### Failed to fetch
- **Bedeutung:** API nicht erreichbar
- **Check:** Ist Laravel noch am Laufen?
```bash
curl http://localhost:8000/api/pages
```

### Zod validation error
- **Bedeutung:** Daten passen nicht zum Schema
- **Action:** Screenshot vom Debug-Panel

---

## 📂 Log-Dateien

### Wo sind die Logs?
```
musikfürfirmen.de/
├── logs/
│   ├── errors-2026-01-17.log  # Heute
│   ├── errors-2026-01-18.log  # Morgen
│   └── ...
```

### Log-Format:
```json
{
  "timestamp": "2026-01-17T23:45:12.123Z",
  "type": "error",
  "message": "schema._def.shape is not a function",
  "stack": "...",
  "url": "http://localhost:3000/admin/editor/home",
  "metadata": { ... }
}
```

---

## 🔧 Debug-Commands

### Logs ansehen (Terminal):
```bash
# Letzte 20 Zeilen
tail -20 logs/errors-$(date +%Y-%m-%d).log

# Live-Logs
tail -f logs/errors-$(date +%Y-%m-%d).log

# Bestimmten Fehler suchen
grep "schema._def" logs/errors-*.log
```

### Logs löschen:
```bash
rm -rf logs/
```

### API-Logs abfragen:
```bash
curl http://localhost:3000/api/log-error | jq
```

---

## 💡 Tipps

1. **Debug-Panel immer offen lassen** beim Testen
2. **Filter auf "Error"** setzen für wichtige Fehler
3. **Export** nach jeder Test-Session
4. **Screenshots** bei visuellen Problemen
5. **Browser-Extensions** deaktivieren wenn möglich

---

## ✅ Was jetzt funktioniert

### Vor dem Fix:
- ❌ PropertiesPanel crasht sofort
- ❌ Kann Components nicht editieren
- ❌ Keine Fehler-Sichtbarkeit
- ❌ Debugging schwierig

### Nach dem Fix:
- ✅ PropertiesPanel lädt (Zod-Fix)
- ✅ Component-Properties editierbar
- ✅ Alle Fehler werden geloggt
- ✅ Debug-Panel für Live-Monitoring
- ✅ Export-Funktion für Analyse
- ✅ Server-side Logging

---

## 🎯 Nächste Schritte

1. **Teste den Editor** mit Debug-Panel offen
2. **Wenn Fehler auftreten:**
   - Screenshot vom Debug-Panel
   - Oder: Export als JSON
   - Schick mir die Datei/Screenshot
3. **Ich fixe die Fehler** basierend auf deinen Logs
4. **Iterieren** bis alles smooth läuft

---

**Debug-System Status:** ✅ Aktiv
**Location:** Unten rechts, roter Button
**Nur in:** Development-Mode (nicht in Production)

🐛 **Happy Debugging!**
