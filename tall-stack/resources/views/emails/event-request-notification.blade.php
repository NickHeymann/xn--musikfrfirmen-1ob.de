<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Event-Anfrage</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
        {{-- Header --}}
        <tr>
            <td style="background: #B0D4C5; padding: 14px 20px; border-radius: 8px 8px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size: 16px; font-weight: 700; color: #1a1a1a;">
                            NEUE EVENT-ANFRAGE
                        </td>
                        <td align="right" style="font-size: 13px; color: #292929;">
                            {{ $data['city'] }}, {{ \Carbon\Carbon::parse($data['date'])->format('d.m.Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Two-Column: Contact + Event --}}
        <tr>
            <td style="background: #ffffff; padding: 14px 20px 10px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        {{-- Left: Contact --}}
                        <td width="50%" valign="top" style="padding-right: 12px;">
                            <div style="font-size: 10px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 6px;">KONTAKT</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1a1a1a; padding-bottom: 2px;">{{ $data['name'] }}</div>
                            <div style="font-size: 13px; color: #555; padding-bottom: 2px;">{{ $data['company'] }}</div>
                            <div style="padding-bottom: 2px;">
                                <a href="mailto:{{ $data['email'] }}" style="font-size: 13px; color: #A0C4B5; text-decoration: none;">{{ $data['email'] }} &#x2197;</a>
                            </div>
                            @if($data['phone'])
                            <div>
                                <a href="tel:{{ $data['phone'] }}" style="font-size: 13px; color: #A0C4B5; text-decoration: none;">{{ $data['phone'] }} &#x2197;</a>
                            </div>
                            @endif
                        </td>

                        {{-- Right: Event --}}
                        <td width="50%" valign="top" style="padding-left: 12px; border-left: 1px solid #eee;">
                            <div style="font-size: 10px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 6px;">EVENT</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1a1a1a; padding-bottom: 2px;">
                                {{ \Carbon\Carbon::parse($data['date'])->format('d.m.Y') }}@if($data['time']), {{ $data['time'] }} Uhr @endif
                            </div>
                            <div style="font-size: 13px; color: #555; padding-bottom: 2px;">{{ $data['city'] }}</div>
                            <div style="font-size: 13px; color: #555; padding-bottom: 2px;">{{ $guestLabels[$data['guests']] ?? $data['guests'] }} Gäste</div>
                            <div style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
                                {{ $packageLabels[$data['package']] ?? $data['package'] }}@if($data['budget']) &mdash; {{ $data['budget'] }}&euro;@endif
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Message --}}
        @if($data['message'])
        <tr>
            <td style="background: #ffffff; padding: 6px 20px 10px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="background: #f8f8f8; padding: 10px 14px; border-radius: 6px; font-size: 13px; color: #333; line-height: 1.5;">
                            &#x1F4AC; "{{ $data['message'] }}"
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif

        {{-- Company Research --}}
        <tr>
            <td style="background: #ffffff; padding: 4px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    @include('emails.partials.company-research', ['companyResearch' => $companyResearch, 'companyName' => $data['company']])
                </table>
            </td>
        </tr>

        {{-- Action Buttons --}}
        <tr>
            <td style="background: #ffffff; padding: 12px 20px 16px;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-right: 8px;">
                            <a href="mailto:{{ $data['email'] }}?subject=Re: Ihre Anfrage für {{ $data['city'] }}" style="display: inline-block; background: #B0D4C5; color: #1a1a1a; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">&#x2709; Antworten</a>
                        </td>
                        @if($data['phone'])
                        <td>
                            <a href="tel:{{ $data['phone'] }}" style="display: inline-block; background: #1a1a1a; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">&#x1F4DE; Anrufen</a>
                        </td>
                        @endif
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td style="background: #f8f8f8; padding: 10px 20px; border-radius: 0 0 8px 8px; text-align: center;">
                <span style="font-size: 11px; color: #999;">musikfürfirmen.de &middot; automatisch generiert</span>
            </td>
        </tr>
    </table>
</body>
</html>
