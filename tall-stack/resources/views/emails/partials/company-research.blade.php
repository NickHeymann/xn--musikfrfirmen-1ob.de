@if($companyResearch)
<tr>
    <td style="padding: 0;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f0faf6; border-radius: 6px; margin-top: 8px;">
            <tr>
                <td style="padding: 18px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size: 12px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.8px; padding-bottom: 10px;">
                                FIRMEN-PROFIL: {{ strtoupper($companyName) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; color: #333; line-height: 1.7; padding-bottom: 6px;">
                                @if($companyResearch['industry'] ?? null){{ $companyResearch['industry'] }}@endif
                                @if($companyResearch['employee_count'] ?? null) &middot; ~{{ $companyResearch['employee_count'] }} MA @endif
                                @if($companyResearch['location'] ?? null) &middot; {{ $companyResearch['location'] }}@endif
                            </td>
                        </tr>
                        @if($companyResearch['description'] ?? null)
                        <tr>
                            <td style="font-size: 13px; color: #555; line-height: 1.7; padding-bottom: 4px;">
                                {{ $companyResearch['description'] }}
                            </td>
                        </tr>
                        @endif
                        @if($companyResearch['website'] ?? null)
                        <tr>
                            <td style="padding-top: 8px; padding-bottom: 2px;">
                                <a href="{{ $companyResearch['website'] }}" style="font-size: 13px; color: #A0C4B5; text-decoration: none;">&#x1F310; {{ preg_replace('#^https?://(www\.)?#', '', $companyResearch['website']) }}</a>
                            </td>
                        </tr>
                        @endif
                        @if($companyResearch['financials'] ?? null)
                        <tr>
                            <td style="padding-top: 14px; border-top: 1px solid #ddeee8;">
                                <span style="font-size: 12px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.8px;">FINANZEN</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #555; padding-top: 8px; line-height: 1.7;">
                                {{ $companyResearch['financials'] }}
                            </td>
                        </tr>
                        @endif
                        @if($companyResearch['call_prep'] ?? null)
                        <tr>
                            <td style="padding-top: 14px; border-top: 1px solid #ddeee8;">
                                <span style="font-size: 12px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.8px;">GESPRÄCHSVORBEREITUNG</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #555; padding-top: 8px; line-height: 1.8;">
                                {{ $companyResearch['call_prep'] }}
                            </td>
                        </tr>
                        @endif
                        @if(!empty($companyResearch['talking_points'] ?? []))
                        <tr>
                            <td style="padding-top: 14px; border-top: 1px solid #ddeee8;">
                                <span style="font-size: 12px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.8px;">FRAGEN FÜR DEN ANRUF</span>
                            </td>
                        </tr>
                        @foreach($companyResearch['talking_points'] as $point)
                        <tr>
                            <td style="font-size: 13px; color: #555; padding-top: 6px; padding-left: 10px; line-height: 1.7;">
                                &bull; {{ $point }}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @if($companyResearch['potential_needs'] ?? null)
                        <tr>
                            <td style="padding-top: 14px; border-top: 1px solid #ddeee8;">
                                <span style="font-size: 12px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.8px;">MÖGLICHE BEDÜRFNISSE</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #555; padding-top: 8px; line-height: 1.8;">
                                {{ $companyResearch['potential_needs'] }}
                            </td>
                        </tr>
                        @endif
                        @foreach(($companyResearch['recent_news'] ?? []) as $news)
                        <tr>
                            <td style="padding-top: 6px;">
                                <a href="{{ $news['url'] ?? '#' }}" style="font-size: 13px; color: #555; text-decoration: none; line-height: 1.6;">&#x1F4F0; {{ $news['title'] }} &#x2197;</a>
                            </td>
                        </tr>
                        @endforeach
                        @foreach(($companyResearch['past_events'] ?? []) as $event)
                        <tr>
                            <td style="padding-top: 6px;">
                                <a href="{{ $event['url'] ?? '#' }}" style="font-size: 13px; color: #555; text-decoration: none; line-height: 1.6;">&#x1F389; {{ $event['title'] }} &#x2197;</a>
                            </td>
                        </tr>
                        @endforeach
                        @if(! empty($companyResearch['sources'] ?? []))
                        <tr>
                            <td style="padding-top: 10px; font-size: 11px; color: #999; line-height: 1.6;">
                                Quellen: {{ collect($companyResearch['sources'])->map(fn($url) => preg_replace('#^https?://(www\.)?#', '', parse_url($url, PHP_URL_HOST) ?? $url))->implode(', ') }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endif
