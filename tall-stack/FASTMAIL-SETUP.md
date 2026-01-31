# Fastmail Setup für musikfürfirmen.de

## Email-Routing Einrichten

### Schritt 1: Mailadressen-Aliase konfigurieren

**Ziel:** `kontakt@musikfürfirmen.de` soll an mehrere Empfänger weiterleiten

**In Fastmail Web-Interface:**

1. Login: https://www.fastmail.com
   - User: `nick@nickheymann.de`
   - Password: (siehe `~/.claude/security/credentials/MASTER-CREDENTIALS.md`)

2. Settings → Mail → Rules → **New Rule**

3. Rule erstellen:
   ```
   Name: musikfürfirmen.de Routing

   When:
   - Message is received
   - To: kontakt@musikfürfirmen.de (oder xn--musikfrfirmen-1ob.de mit Punycode)

   Do this:
   - Forward to: moin@nickheymann.de
   - Forward to: moin@jonasglamann.de
   - Keep a copy in inbox (optional)
   ```

4. **Save Rule**

### Alternative: Multiple Aliases

Falls Routing-Rules nicht funktionieren:

1. Settings → Mail → Aliases
2. Add Alias: `kontakt@musikfürfirmen.de`
3. Settings → Mail → Forwarding
4. Add forwarding:
   - From: `kontakt@musikfürfirmen.de`
   - To: `moin@nickheymann.de, moin@jonasglamann.de`

---

## Kalender-Sync mit Google Calendar

### Schritt 1: CalDAV in Fastmail aktivieren

1. Settings → Calendars → Calendar Access
2. CalDAV URL notieren: `https://caldav.fastmail.com/dav/calendars/user/nick@nickheymann.de/`

### Schritt 2: Google Calendar Sync

**Option A: CalDAV Sync (empfohlen)**

1. In Google Calendar (musikfuerfirmen@gmail.com):
   - Settings → Add calendar → From URL
   - CalDAV URL eingeben: `https://caldav.fastmail.com/dav/calendars/user/nick@nickheymann.de/[calendar-id]`
   - Username: `nick@nickheymann.de`
   - Password: (App Password erstellen in Fastmail)

**Option B: iCal Feed (read-only)**

1. In Fastmail:
   - Settings → Calendars → [Calendar] → Sharing
   - Enable "Subscribe via iCal Feed"
   - Copy iCal URL

2. In Google Calendar:
   - Settings → Add calendar → From URL
   - Paste iCal URL

### Schritt 3: Buchungen in Calendar speichern

**Laravel Code Update nötig:**

Die Buchungsanfragen sollten automatisch in den Kalender eingetragen werden:

```php
// In BookingCalendarModal.php
public function submitBooking()
{
    // ... existing validation ...

    // Create calendar event via CalDAV
    $this->createCalendarEvent([
        'summary' => 'Erstgespräch: ' . $this->name,
        'description' => $this->message,
        'start' => $this->selectedDate . 'T' . $this->selectedTime,
        'end' => // +30 minutes
        'attendees' => [$this->email, 'musikfuerfirmen@gmail.com']
    ]);
}
```

**CalDAV Integration für Laravel:**
```bash
composer require sabre/dav
```

---

## Environment Variables

Füge zu `.env` hinzu:

```env
CALDAV_URL=https://caldav.fastmail.com/dav/calendars/user/nick@nickheymann.de/
CALDAV_USERNAME=nick@nickheymann.de
CALDAV_PASSWORD=<app-password-aus-fastmail>
GOOGLE_CALENDAR_EMAIL=musikfuerfirmen@gmail.com
```

---

## Testing

### Email-Routing testen:

```bash
# Testmail senden
echo "Test email routing" | mail -s "Test" kontakt@xn--musikfrfirmen-1ob.de
```

Prüfen ob Mail bei beiden ankommt:
- moin@nickheymann.de
- moin@jonasglamann.de

### Kalender-Sync testen:

1. Event in Fastmail Calendar erstellen
2. Prüfen ob es in Google Calendar (musikfuerfirmen@gmail.com) erscheint
3. Umgekehrt testen

---

## Punycode Domain

`musikfürfirmen.de` = `xn--musikfrfirmen-1ob.de` (Punycode)

Fastmail sollte beide Varianten akzeptieren. Falls nicht, nutze die Punycode-Version in den Regeln.

---

**Status:** ⚠️ Manuelle Konfiguration erforderlich
**Priorität:** Hoch (für Buchungsfunktion)
**Nächster Schritt:** Fastmail Web-Interface öffnen und Rules einrichten
