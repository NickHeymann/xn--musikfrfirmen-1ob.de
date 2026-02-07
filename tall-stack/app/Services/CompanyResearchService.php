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

            if (empty($generalResults) && empty($eventResults)) {
                return null;
            }

            return $this->summarizeWithGroq(
                $companyName,
                $generalResults,
                $eventResults,
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
     */
    private function summarizeWithGroq(
        string $companyName,
        array $generalResults,
        array $eventResults,
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

        $model = config('services.groq.model', 'llama-3.3-70b-versatile');

        $response = Http::timeout(15)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'max_tokens' => 1500,
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Du bist ein Firmen-Recherche-Assistent. Extrahiere NUR verifizierte Fakten aus den Suchergebnissen. Wenn du dir bei einer Information unsicher bist, gib null für das Feld zurück. Antworte ausschließlich mit validem JSON.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Analysiere diese Suchergebnisse über '{$companyName}' und erstelle ein strukturiertes Firmenprofil.\n\n{$snippets}\n\nAntworte als JSON mit diesem Schema:\n{\"industry\": \"Branche oder null\", \"employee_count\": \"Anzahl oder null\", \"website\": \"URL oder null\", \"location\": \"Standort oder null\", \"description\": \"Kurze Beschreibung (1-2 Sätze) oder null\", \"call_prep\": \"2-3 Sätze Vorbereitung für den Anruf oder null\", \"talking_points\": [\"Frage 1\", \"Frage 2\"], \"potential_needs\": \"Mögliche Bedürfnisse basierend auf Branche/Größe oder null\", \"recent_news\": [{\"title\": \"...\", \"url\": \"...\"}], \"past_events\": [{\"title\": \"...\", \"url\": \"...\"}], \"sources\": [\"url1\", \"url2\"]}",
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
