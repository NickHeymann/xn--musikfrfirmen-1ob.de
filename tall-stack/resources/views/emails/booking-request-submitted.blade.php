<x-mail::message>
# Neue Erstgesprächs-Anfrage

Sie haben eine neue Erstgesprächs-Anfrage über den Kalender auf musikfürfirmen.de erhalten.

## Termin-Details

**Datum:** {{ \Carbon\Carbon::parse($bookingData['selectedDate'])->locale('de')->isoFormat('dddd, D. MMMM YYYY') }}
**Uhrzeit:** {{ $bookingData['selectedTime'] }} Uhr
**Dauer:** 30 Minuten

## Kontaktdaten

**Name:** {{ $bookingData['name'] }}
**E-Mail:** {{ $bookingData['email'] }}
**Telefon:** {{ $bookingData['phone'] }}

@if(!empty($bookingData['message']))
## Nachricht

{{ $bookingData['message'] }}
@endif

---

<x-mail::button :url="'mailto:' . $bookingData['email']">
Auf E-Mail antworten
</x-mail::button>

**Nächste Schritte:**
1. Termin im Kalender eintragen
2. Bestätigungs-E-Mail an {{ $bookingData['email'] }} senden
3. Gesprächsnotizen vorbereiten

Viele Grüße,<br>
{{ config('app.name') }}
</x-mail::message>
