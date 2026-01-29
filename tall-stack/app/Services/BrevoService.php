<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    private string $apiKey;

    private int $listId;

    private string $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key', '');
        $this->listId = (int) config('services.brevo.list_id', 2);
    }

    /**
     * Create or update a contact in Brevo.
     *
     * @param array{
     *     email: string,
     *     name: string,
     *     phone?: string,
     *     company?: string,
     *     date?: string,
     *     time?: string,
     *     city?: string,
     *     budget?: string,
     *     guests?: string,
     *     package?: string,
     *     message?: string,
     * } $data
     */
    public function createContact(array $data): bool
    {
        if (empty($this->apiKey)) {
            Log::warning('Brevo API key not configured');

            return false;
        }

        $nameParts = $this->splitName($data['name'] ?? '');

        $attributes = [
            'FIRSTNAME' => $nameParts['firstName'],
            'LASTNAME' => $nameParts['lastName'],
            'PHONE' => $data['phone'] ?? '',
            'COMPANY' => $data['company'] ?? '',
            'EVENT_DATE' => $data['date'] ?? '',
            'EVENT_TIME' => $data['time'] ?? '',
            'EVENT_CITY' => $data['city'] ?? '',
            'EVENT_BUDGET' => $data['budget'] ?? '',
            'EVENT_GUESTS' => $data['guests'] ?? '',
            'EVENT_PACKAGE' => $data['package'] ?? '',
            'EVENT_MESSAGE' => $data['message'] ?? '',
        ];

        $payload = [
            'email' => $data['email'],
            'attributes' => $attributes,
            'listIds' => [$this->listId],
            'updateEnabled' => true,
        ];

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/contacts", $payload);

            if ($response->successful()) {
                Log::info('Brevo contact created', ['email' => $data['email']]);

                return true;
            }

            Log::error('Brevo API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Brevo API exception', ['message' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Split a full name into first and last name.
     *
     * @return array{firstName: string, lastName: string}
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            'firstName' => $parts[0] ?? '',
            'lastName' => $parts[1] ?? '',
        ];
    }
}
