@if($companyResearch)
<tr>
    <td style="padding: 0;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f0faf6; border-radius: 6px; margin-top: 8px;">
            <tr>
                <td style="padding: 12px 16px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size: 11px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 8px;">
                                FIRMEN-PROFIL: {{ $companyName }}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #333; line-height: 1.5;">
                                @if($companyResearch['industry'] ?? null){{ $companyResearch['industry'] }}@endif
                                @if($companyResearch['employee_count'] ?? null) &middot; ~{{ $companyResearch['employee_count'] }} MA @endif
                                @if($companyResearch['location'] ?? null) &middot; {{ $companyResearch['location'] }}@endif
                            </td>
                        </tr>
                        @if($companyResearch['description'] ?? null)
                        <tr>
                            <td style="font-size: 12px; color: #555; padding-top: 4px;">
                                {{ $companyResearch['description'] }}
                            </td>
                        </tr>
                        @endif
                        @if($companyResearch['website'] ?? null)
                        <tr>
                            <td style="padding-top: 6px;">
                                <a href="{{ $companyResearch['website'] }}" style="font-size: 12px; color: #7dc9b1; text-decoration: none;">&#x1F310; {{ preg_replace('#^https?://(www\.)?#', '', $companyResearch['website']) }}</a>
                            </td>
                        </tr>
                        @endif
                        @foreach(($companyResearch['recent_news'] ?? []) as $news)
                        <tr>
                            <td style="padding-top: 4px;">
                                <a href="{{ $news['url'] ?? '#' }}" style="font-size: 12px; color: #555; text-decoration: none;">&#x1F4F0; {{ $news['title'] }} &#x2197;</a>
                            </td>
                        </tr>
                        @endforeach
                        @foreach(($companyResearch['past_events'] ?? []) as $event)
                        <tr>
                            <td style="padding-top: 4px;">
                                <a href="{{ $event['url'] ?? '#' }}" style="font-size: 12px; color: #555; text-decoration: none;">&#x1F389; {{ $event['title'] }} &#x2197;</a>
                            </td>
                        </tr>
                        @endforeach
                        @if(! empty($companyResearch['sources'] ?? []))
                        <tr>
                            <td style="padding-top: 6px; font-size: 10px; color: #999;">
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
