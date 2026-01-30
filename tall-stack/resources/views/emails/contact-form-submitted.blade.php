<x-mail::message>
# Neue Kontaktanfrage

Sie haben eine neue Kontaktanfrage über das Kontaktformular auf musikfürfirmen.de erhalten.

## Kontaktdaten

**Name:** {{ $submission->name }}
**E-Mail:** {{ $submission->email }}
**Telefon:** {{ $submission->phone ?? 'Nicht angegeben' }}
**Firma:** {{ $submission->company ?? 'Nicht angegeben' }}
**Anfrageart:** {{ ucfirst($submission->inquiry_type) }}

## Nachricht

{{ $submission->message }}

---

<x-mail::button :url="config('app.url') . '/admin/contact-submissions'">
Anfrage im Admin-Panel ansehen
</x-mail::button>

Viele Grüße,<br>
{{ config('app.name') }}
</x-mail::message>
