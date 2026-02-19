<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CompanyResearchService
{
    /**
     * @return array{
     *     industry: ?string,
     *     employee_count: ?string,
     *     website: ?string,
     *     location: ?string,
     *     description: ?string,
     *     recent_news: array<int, array{title: string, url: string}>,
     *     past_events: array<int, array{title: string, url: string}>,
     *     sources: string[]
     * }|null
     */
    public function research(string $companyName): ?array
    {
        if (empty(trim($companyName))) {
            return null;
        }

        $tavilyKey = config('services.tavily.api_key');
        $groqKey = config('services.groq.api_key');

        if (empty($tavilyKey) || empty($groqKey)) {
            Log::info('Company research skipped: missing API keys');

            return null;
        }

        try {
            $timeout = (int) config('services.tavily.timeout', 8);

            $generalResults = $this->tavilySearch(
                "{$companyName} Unternehmen Branche Mitarbeiter",
                $tavilyKey,
                $timeout
            );

            $eventResults = $this->tavilySearch(
                "{$companyName} Firmenevent Sommerfest Weihnachtsfeier",
                $tavilyKey,
                $timeout
            );

            $financialResults = $this->tavilySearch(
                "{$companyName} Umsatz Jahresbericht Gewinn Finanzen",
                $tavilyKey,
                $timeout
            );

            if (empty($generalResults) && empty($eventResults) && empty($financialResults)) {
                return null;
            }

            return $this->summarizeWithGroq(
                $companyName,
                $generalResults,
                $eventResults,
                $financialResults,
                $groqKey
            );
        } catch (\Exception $e) {
            Log::warning('Company research failed', [
                'company' => $companyName,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @return array<int, array{title: string, url: string, content: string}>
     */
    private function tavilySearch(string $query, string $apiKey, int $timeout): array
    {
        $response = Http::timeout($timeout)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://api.tavily.com/search', [
                'api_key' => $apiKey,
                'query' => $query,
                'search_depth' => 'basic',
                'max_results' => 5,
                'include_answer' => true,
            ]);

        if (! $response->successful()) {
            Log::warning('Tavily search failed', [
                'status' => $response->status(),
                'query' => $query,
            ]);

            return [];
        }

        $data = $response->json();
        $results = [];

        foreach ($data['results'] ?? [] as $result) {
            $results[] = [
                'title' => $result['title'] ?? '',
                'url' => $result['url'] ?? '',
                'content' => $result['content'] ?? '',
            ];
        }

        return $results;
    }

    /**
     * @param  array<int, array{title: string, url: string, content: string}>  $generalResults
     * @param  array<int, array{title: string, url: string, content: string}>  $eventResults
     * @param  array<int, array{title: string, url: string, content: string}>  $financialResults
     */
    private function summarizeWithGroq(
        string $companyName,
        array $generalResults,
        array $eventResults,
        array $financialResults,
        string $apiKey
    ): ?array {
        $snippets = "## Allgemeine Suchergebnisse\n";
        foreach ($generalResults as $r) {
            $snippets .= "- [{$r['title']}]({$r['url']}): {$r['content']}\n";
        }
        $snippets .= "\n## Event-Suchergebnisse\n";
        foreach ($eventResults as $r) {
            $snippets .= "- [{$r['title']}]({$r['url']}): {$r['content']}\n";
        }
        $snippets .= "\n## Finanz-Suchergebnisse\n";
        foreach ($financialResults as $r) {
            $snippets .= "- [{$r['title']}]({$r['url']}): {$r['content']}\n";
        }

        $model = config('services.groq.model', 'llama-3.3-70b-versatile');

        $response = Http::timeout(15)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'max_tokens' => 1800,
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => <<<PROMPT
Du bereitest einen Verkäufer von musikfürfirmen.de auf ein Erstgespräch mit einem Firmenkunden vor.

musikfürfirmen.de bietet:
- Live-Bands (Jazz, Pop, Rock, Coverband, Akustik)
- DJs für Firmenevents
- Komplette Veranstaltungstechnik (Ton, Licht, Bühne)
- Kombinationspakete Band + DJ
- Für alle Unternehmensgrößen und Anlässe

Typische Anlässe: Sommerfeste, Weihnachtsfeiern, Firmenjubiläen, Produktlaunches, Teamevents, Kundenevents, Messen, Awards-Abende.

Deine Aufgabe: Hilf dem Verkäufer, den Kunden wirklich zu verstehen – seine Unternehmenskultur, typische Eventgrößen, Anlässe und Wünsche – damit er das richtige Angebot machen kann.

Extrahiere NUR verifizierte Fakten aus den Suchergebnissen. Antworte ausschließlich mit validem JSON.
PROMPT,
                    ],
                    [
                        'role' => 'user',
                        'content' => <<<PROMPT
Analysiere diese Suchergebnisse über '{$companyName}' und erstelle ein Event-Sales-Briefing.

{$snippets}

Antworte als JSON:
{
  "industry": "Branche in 2-3 Worten oder null",
  "employee_count": "Mitarbeiterzahl oder null",
  "website": "URL oder null",
  "location": "Hauptstandort oder null",
  "description": "1-2 Sätze: Wer ist das Unternehmen, welche Unternehmenskultur/Werte erkennbar? – aus Event-Perspektive relevant (z.B. modern, traditionell, international, familienorientiert)",
  "financials": "Nur wenn aus Suchergebnissen belegbar: Umsatz, Gewinn/Verlust, Marketingbudget und ob das Unternehmen finanziell gut aufgestellt ist. Beispiel: 'Umsatz 2024: ~42 Mrd. € · Gewinn: 2,1 Mrd. € · Solide Finanzlage laut Geschäftsbericht'. Wenn nichts Belastbares gefunden: null",
  "call_prep": "2-3 Sätze: Was sollte der Verkäufer über dieses Unternehmen wissen, bevor er anruft? Welche Anlässe für Events sind bei dieser Firmengröße/Branche typisch? Gibt es bekannte Events oder eine Event-Kultur?",
  "talking_points": [
    "Frage die hilft den konkreten Event-Bedarf zu verstehen (Anlass, Datum, Ort)",
    "Frage zu Erwartungen an Musik/Atmosphäre – was soll der Abend vermitteln?",
    "Frage zur Gästezahl oder ob es interne oder gemischte Veranstaltung ist"
  ],
  "potential_needs": "Welches musikfürfirmen.de-Paket passt wahrscheinlich und warum? (z.B. 'Bei ~5000 MA und internationalem Umfeld: eher coole Coverband + DJ-Übergang, weniger Volksmusik')",
  "recent_news": [{"title": "...", "url": "..."}],
  "past_events": [{"title": "...", "url": "..."}],
  "sources": ["url1", "url2"]
}

KRITISCH für "financials": Nur echte Zahlen aus den Suchergebnissen verwenden. Keine Schätzungen, keine Halluzinationen. Wenn keine Finanzdaten in den Ergebnissen vorhanden sind → null.
PROMPT,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            Log::warning('Groq API call failed', [
                'status' => $response->status(),
            ]);

            return null;
        }

        $content = $response->json('choices.0.message.content', '');

        // Extract JSON from response (LLM may wrap it in markdown code blocks)
        if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
            $parsed = json_decode($matches[0], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                return $parsed;
            }
        }

        Log::warning('Failed to parse Groq response', ['content' => $content]);

        return null;
    }
}
