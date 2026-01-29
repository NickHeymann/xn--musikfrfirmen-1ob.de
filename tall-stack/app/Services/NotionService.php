<?php

namespace App\Services;

use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Facades\Log;

class NotionService
{
    private ?string $apiToken;

    private ?string $databaseId;

    public function __construct()
    {
        $this->apiToken = config('services.notion.api_token');
        $this->databaseId = config('services.notion.database_id');
    }

    /**
     * Create an event request entry in Notion database.
     *
     * @param array{
     *     name: string,
     *     email: string,
     *     phone: string,
     *     company: string,
     *     date: string,
     *     time: string,
     *     city: string,
     *     budget: string,
     *     guests: string,
     *     package: string,
     *     message: string,
     * } $data
     */
    public function createEventRequest(array $data): bool
    {
        if (empty($this->apiToken) || empty($this->databaseId)) {
            Log::warning('Notion API token or database ID not configured');

            return false;
        }

        $packageLabels = [
            'dj' => 'Nur DJ',
            'band' => 'Full Band',
            'band_dj' => 'Full Band + DJ',
        ];

        $guestLabels = [
            'lt100' => 'Unter 100',
            '100-300' => '100 - 300',
            '300-500' => '300 - 500',
            'gt500' => '>500',
        ];

        try {
            $notion = new Notion($this->apiToken);

            $notion->pages()->createInDatabase($this->databaseId, [
                // Title property (required for Notion databases)
                'Name' => [
                    'title' => [
                        [
                            'text' => ['content' => "{$data['city']} - {$data['name']}"],
                        ],
                    ],
                ],
                // Rich text properties
                'Kontakt' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['name']],
                        ],
                    ],
                ],
                'E-Mail' => [
                    'email' => $data['email'],
                ],
                'Telefon' => [
                    'phone_number' => $data['phone'],
                ],
                'Firma' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['company'] ?: '-'],
                        ],
                    ],
                ],
                // Date property
                'Event-Datum' => [
                    'date' => [
                        'start' => $data['date'],
                    ],
                ],
                // Select properties
                'Stadt' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['city']],
                        ],
                    ],
                ],
                'Paket' => [
                    'select' => [
                        'name' => $packageLabels[$data['package']] ?? $data['package'],
                    ],
                ],
                'Gäste' => [
                    'select' => [
                        'name' => $guestLabels[$data['guests']] ?? $data['guests'],
                    ],
                ],
                'Budget' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['budget'] ?: 'Nicht angegeben'],
                        ],
                    ],
                ],
                'Uhrzeit' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['time'] ?: 'Nicht angegeben'],
                        ],
                    ],
                ],
                'Nachricht' => [
                    'rich_text' => [
                        [
                            'text' => ['content' => $data['message'] ?: '-'],
                        ],
                    ],
                ],
                // Status property
                'Status' => [
                    'select' => [
                        'name' => 'Neu',
                    ],
                ],
            ]);

            Log::info('Notion event request created', ['email' => $data['email'], 'city' => $data['city']]);

            return true;

        } catch (\Exception $e) {
            Log::error('Notion API error', ['message' => $e->getMessage()]);

            return false;
        }
    }
}
