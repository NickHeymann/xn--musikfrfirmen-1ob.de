<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Kontaktanfrage</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
        {{-- Header --}}
        <tr>
            <td style="background: #B0D4C5; padding: 14px 20px; border-radius: 8px 8px 0 0;">
                <span style="font-size: 16px; font-weight: 700; color: #1a1a1a;">NEUE KONTAKTANFRAGE</span>
            </td>
        </tr>

        {{-- Contact Info --}}
        <tr>
            <td style="background: #ffffff; padding: 14px 20px 10px;">
                <div style="font-size: 14px; font-weight: 600; color: #1a1a1a; padding-bottom: 4px;">
                    {{ $submission->name }} &middot; {{ $submission->company }}
                </div>
                <div style="padding-bottom: 4px;">
                    <a href="mailto:{{ $submission->email }}" style="font-size: 13px; color: #A0C4B5; text-decoration: none;">{{ $submission->email }} &#x2197;</a>
                    @if($submission->phone)
                    &middot;
                    <a href="tel:{{ $submission->phone }}" style="font-size: 13px; color: #A0C4B5; text-decoration: none;">{{ $submission->phone }} &#x2197;</a>
                    @endif
                </div>
                <div style="font-size: 12px; color: #999;">
                    Anfrageart: {{ ucfirst($submission->inquiry_type) }}
                </div>
            </td>
        </tr>

        {{-- Message --}}
        <tr>
            <td style="background: #ffffff; padding: 6px 20px 10px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="background: #f8f8f8; padding: 10px 14px; border-radius: 6px; font-size: 13px; color: #333; line-height: 1.5;">
                            &#x1F4AC; "{{ $submission->message }}"
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Company Research --}}
        <tr>
            <td style="background: #ffffff; padding: 4px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    @include('emails.partials.company-research', ['companyResearch' => $companyResearch, 'companyName' => $submission->company])
                </table>
            </td>
        </tr>

        {{-- Action Buttons --}}
        <tr>
            <td style="background: #ffffff; padding: 12px 20px 16px;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-right: 8px;">
                            <a href="mailto:{{ $submission->email }}?subject=Re: Ihre Kontaktanfrage" style="display: inline-block; background: #B0D4C5; color: #1a1a1a; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">&#x2709; Antworten</a>
                        </td>
                        @if($submission->phone)
                        <td style="padding-right: 8px;">
                            <a href="tel:{{ $submission->phone }}" style="display: inline-block; background: #1a1a1a; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">&#x1F4DE; Anrufen</a>
                        </td>
                        @endif
                        <td>
                            <a href="{{ config('app.url') }}/admin/contact-submissions" style="display: inline-block; background: #f0f0f0; color: #555; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">Admin &#x2197;</a>
                        </td>
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
