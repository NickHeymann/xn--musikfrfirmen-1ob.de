<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Event-Anfrage</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #B2EAD8 0%, #7dc9b1 100%); padding: 30px; border-radius: 10px 10px 0 0;">
        <h1 style="color: #1a1a1a; margin: 0; font-size: 24px;">Neue Event-Anfrage</h1>
        <p style="color: #292929; margin: 10px 0 0 0;">{{ $data['city'] }} am {{ \Carbon\Carbon::parse($data['date'])->format('d.m.Y') }}</p>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; border-top: none;">
        {{-- Contact Details --}}
        <h2 style="color: #1a1a1a; font-size: 18px; margin-top: 0; border-bottom: 2px solid #B2EAD8; padding-bottom: 10px;">Kontaktdaten</h2>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px 0; color: #666; width: 120px;">Name:</td>
                <td style="padding: 8px 0; font-weight: bold;">{{ $data['name'] }}</td>
            </tr>
            @if($data['company'])
            <tr>
                <td style="padding: 8px 0; color: #666;">Firma:</td>
                <td style="padding: 8px 0;">{{ $data['company'] }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #666;">E-Mail:</td>
                <td style="padding: 8px 0;"><a href="mailto:{{ $data['email'] }}" style="color: #7dc9b1;">{{ $data['email'] }}</a></td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;">Telefon:</td>
                <td style="padding: 8px 0;"><a href="tel:{{ $data['phone'] }}" style="color: #7dc9b1;">{{ $data['phone'] }}</a></td>
            </tr>
        </table>

        {{-- Event Details --}}
        <h2 style="color: #1a1a1a; font-size: 18px; border-bottom: 2px solid #B2EAD8; padding-bottom: 10px;">Event-Details</h2>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px 0; color: #666; width: 120px;">Datum:</td>
                <td style="padding: 8px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($data['date'])->format('d.m.Y') }}</td>
            </tr>
            @if($data['time'])
            <tr>
                <td style="padding: 8px 0; color: #666;">Uhrzeit:</td>
                <td style="padding: 8px 0;">{{ $data['time'] }} Uhr</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #666;">Stadt:</td>
                <td style="padding: 8px 0;">{{ $data['city'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;">Gäste:</td>
                <td style="padding: 8px 0;">{{ $guestLabels[$data['guests']] ?? $data['guests'] }}</td>
            </tr>
            @if($data['budget'])
            <tr>
                <td style="padding: 8px 0; color: #666;">Budget:</td>
                <td style="padding: 8px 0;">{{ $data['budget'] }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #666;">Paket:</td>
                <td style="padding: 8px 0; font-weight: bold;">{{ $packageLabels[$data['package']] ?? $data['package'] }}</td>
            </tr>
        </table>

        {{-- Message --}}
        @if($data['message'])
        <h2 style="color: #1a1a1a; font-size: 18px; border-bottom: 2px solid #B2EAD8; padding-bottom: 10px;">Nachricht</h2>
        <div style="background: #f8f8f8; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ $data['message'] }}
        </div>
        @endif

        {{-- Quick Actions --}}
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <a href="mailto:{{ $data['email'] }}?subject=Re: Ihre Anfrage für {{ $data['city'] }}" style="display: inline-block; background: #B2EAD8; color: #292929; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-right: 10px;">Antworten</a>
            <a href="tel:{{ $data['phone'] }}" style="display: inline-block; background: #1a1a1a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;">Anrufen</a>
        </div>
    </div>

    <div style="background: #f8f8f8; padding: 20px; border-radius: 0 0 10px 10px; text-align: center; color: #666; font-size: 12px;">
        <p style="margin: 0;">Diese E-Mail wurde automatisch von musikfürfirmen.de generiert.</p>
    </div>
</body>
</html>
