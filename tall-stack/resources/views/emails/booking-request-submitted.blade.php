<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neues Erstgespräch</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto;">
        {{-- Header --}}
        <tr>
            <td style="background: #B0D4C5; padding: 20px 28px; border-radius: 8px 8px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size: 18px; font-weight: 700; color: #1a1a1a; letter-spacing: 0.3px;">
                            NEUES ERSTGESPRÄCH
                        </td>
                        <td align="right" style="font-size: 14px; color: #292929;">
                            {{ \Carbon\Carbon::parse($bookingData['selectedDate'])->locale('de')->isoFormat('dd, D.MM.YYYY') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Two-Column: Contact + Appointment --}}
        <tr>
            <td style="background: #ffffff; padding: 24px 28px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        {{-- Left: Contact --}}
                        <td width="50%" valign="top" style="padding-right: 20px;">
                            <div style="font-size: 11px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.8px; padding-bottom: 10px;">KONTAKT</div>
                            <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; padding-bottom: 5px; line-height: 1.4;">{{ $bookingData['name'] }}</div>
                            @if(! empty($bookingData['company']))
                            <div style="font-size: 14px; color: #555; padding-bottom: 6px; line-height: 1.5;">{{ $bookingData['company'] }}</div>
                            @endif
                            <div style="padding-bottom: 5px;">
                                <a href="mailto:{{ $bookingData['email'] }}" style="font-size: 14px; color: #A0C4B5; text-decoration: none; line-height: 1.5;">{{ $bookingData['email'] }} &#x2197;</a>
                            </div>
                            <div>
                                <a href="tel:{{ $bookingData['phone'] }}" style="font-size: 14px; color: #A0C4B5; text-decoration: none; line-height: 1.5;">{{ $bookingData['phone'] }} &#x2197;</a>
                            </div>
                        </td>

                        {{-- Right: Appointment --}}
                        <td width="50%" valign="top" style="padding-left: 20px; border-left: 1px solid #eee;">
                            <div style="font-size: 11px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.8px; padding-bottom: 10px;">TERMIN</div>
                            <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; padding-bottom: 5px; line-height: 1.4;">
                                {{ \Carbon\Carbon::parse($bookingData['selectedDate'])->format('d.m.Y') }}, {{ $bookingData['selectedTime'] }} Uhr
                            </div>
                            <div style="font-size: 14px; color: #555; line-height: 1.5;">Dauer: 30 Min</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Message --}}
        @if(! empty($bookingData['message']))
        <tr>
            <td style="background: #ffffff; padding: 0 28px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="background: #f8f8f8; padding: 14px 18px; border-radius: 6px; font-size: 14px; color: #333; line-height: 1.7;">
                            &#x1F4AC; "{{ $bookingData['message'] }}"
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif

        {{-- Company Research --}}
        <tr>
            <td style="background: #ffffff; padding: 0 28px 8px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    @include('emails.partials.company-research', ['companyResearch' => $companyResearch, 'companyName' => $bookingData['company'] ?? ''])
                </table>
            </td>
        </tr>

        {{-- Action Buttons --}}
        <tr>
            <td style="background: #ffffff; padding: 16px 28px 24px;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-right: 10px;">
                            <a href="mailto:{{ $bookingData['email'] }}?subject={{ rawurlencode('Re: Dein Erstgespräch am ' . \Carbon\Carbon::parse($bookingData['selectedDate'])->format('d.m.Y')) }}" style="display: inline-block; background: #B0D4C5; color: #1a1a1a; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">&#x2709; Antworten</a>
                        </td>
                        <td>
                            <a href="tel:{{ $bookingData['phone'] }}" style="display: inline-block; background: #1a1a1a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">&#x1F4DE; Anrufen</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td style="background: #f8f8f8; padding: 14px 28px; border-radius: 0 0 8px 8px; text-align: center;">
                <span style="font-size: 12px; color: #999;">musikfürfirmen.de &middot; automatisch generiert</span>
            </td>
        </tr>
    </table>
</body>
</html>
