# Ralph Guide für musikfürfirmen.de Block Templates

## Was Ralph machen wird

Ralph wird **automatisch 10 User Stories** implementieren:
1. TypeScript Types für Templates
2. Default Template Definitionen (5 vorgefertigte Templates)
3. TemplateLibrary UI Component
4. Template Insertion Logik
5. Toolbar Button & Modal
6. Custom Template Speichern
7. Preview Modal
8. Dokumentation
9. Tests
10. Integration Testing & Cleanup

**Geschätzte Zeit:** 1-2 Stunden (15 Iterationen)

---

## Option 1: Interaktiv (Empfohlen zum Lernen) 🔄

```bash
cd "/Users/nickheymann/Desktop/Mein Business/Programmierprojekte/musikfürfirmen.de"
claude
```

In der Claude Session:
```
/ralph-loop Implement block templates following scripts/ralph/prd.json --max-iterations 20 --completion-promise 'All stories complete'
```

**Vorteile:**
- ✅ Du siehst den Fortschritt live
- ✅ Kannst bei Problemen eingreifen
- ✅ Lernst wie Ralph arbeitet

**Nachteile:**
- ❌ Musst dabei bleiben (1-2 Stunden)
- ❌ Token-Akkumulation im gleichen Session

---

## Option 2: Background (Für Overnight/Away) 🤖

```bash
cd "/Users/nickheymann/Desktop/Mein Business/Programmierprojekte/musikfürfirmen.de"

# Starte Ralph im Hintergrund
~/.claude/scripts/ralph/ralph.sh 25 . &

# Optional: Live-Monitoring in separatem Terminal
watch -n 10 "~/.claude/scripts/ralph/check-progress.sh ."
```

**Vorteile:**
- ✅ Läuft ohne dich
- ✅ Token-effizient (fresh context)
- ✅ Perfekt für Overnight

**Nachteile:**
- ❌ Kannst nicht live zusehen
- ❌ Musst Ergebnis später reviewen

---

## Empfehlung für Block Templates

**Wähle Option 1 (Plugin), weil:**
- Feature ist überschaubar (10 Stories)
- Gute Lernmöglichkeit
- Du kannst UI sofort checken
- Erste Ralph-Erfahrung

**Oder wähle Option 2 (Script), wenn:**
- Du jetzt weg musst
- Du token-effizient arbeiten willst
- Du schlafen gehen willst (overnight)

---

## Monitoring (für Option 2)

```bash
# Status checken
~/.claude/scripts/ralph/check-progress.sh .

# Git Commits ansehen
git log --oneline -15

# Welche Stories sind fertig?
cat scripts/ralph/prd.json | jq '.userStories[] | {id, title, passes}'

# Was hat Ralph gelernt?
cat scripts/ralph/progress.txt
```

---

## Nach Ralph Fertig ist

1. **Branch checken:**
   ```bash
   git status
   git log --oneline -15
   ```

2. **Testen:**
   ```bash
   npm run typecheck
   npm run build
   npm run dev
   # Browser: http://localhost:3000/admin/pages/[id]/edit
   ```

3. **PR erstellen:**
   ```bash
   git push -u origin ralph/block-templates
   gh pr create --title "feat: Add block templates system" --body "$(cat <<'EOF'
   ## Summary
   - Block templates system with 5 default templates
   - Template library UI with search/filter
   - Custom template saving to localStorage
   - Preview modal
   - Full documentation

   ## Stories Completed
   All 10 user stories (US-001 to US-010)

   ## Test Plan
   - [x] npm run typecheck passes
   - [x] npm run build succeeds
   - [x] npm test passes
   - [ ] Manual E2E test
   - [ ] Visual QA

   🤖 Implemented by Ralph
   EOF
   )"
   ```

---

## Wenn Ralph stecken bleibt

**Symptom:** Ralph macht keine Fortschritte mehr

**Check:**
```bash
# Was ist der letzte Commit?
git log -1

# Welche Story ist aktuell?
cat scripts/ralph/prd.json | jq '.userStories[] | select(.passes == false) | .id' | head -1
```

**Fix:**
```bash
# Option A: Manuell weitermachen
git status
# Fix das Problem selbst
git add . && git commit -m "fix: Manual fix for US-XXX"

# Update prd.json: Story als passes: true markieren
# Dann Ralph neu starten

# Option B: Mit Plugin weitermachen
claude
/ralph-loop Continue from US-XXX following prd.json --max-iterations 10
```

---

## Ready to Start? 🚀

**Interaktiv (jetzt mit Live-Output):**
```bash
cd "/Users/nickheymann/Desktop/Mein Business/Programmierprojekte/musikfürfirmen.de"
claude
/ralph-loop Implement block templates following scripts/ralph/prd.json --max-iterations 20
```

**Background (läuft ohne dich):**
```bash
cd "/Users/nickheymann/Desktop/Mein Business/Programmierprojekte/musikfürfirmen.de"
~/.claude/scripts/ralph/ralph.sh 25 .
```

**Viel Erfolg! Ralph wird ein großartiges Block Templates Feature bauen. 🎉**
