<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    private ?string $spreadsheetId;
    private ?string $bookingsSheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google_sheets.event_requests_id');
        $this->bookingsSheetId = config('services.google_sheets.bookings_id', $this->spreadsheetId);
    }

    /**
     * Append an event request to the Google Sheet.
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
        if (empty($this->spreadsheetId)) {
            Log::warning('Google Sheets spreadsheet ID not configured');

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
            $client = $this->getClient();
            $service = new Sheets($client);

            // Header: Datum, Name, Email, Telefon, Stadt, Event-Datum, Gästeanzahl, Event-Art, Musik-Stil, Budget, Nachricht, Status, Notizen
            // Prefix phone with ' to prevent formula interpretation
            $phone = $data['phone'] ? "'".$data['phone'] : '-';

            $row = [
                now()->format('Y-m-d H:i'),                              // A: Datum
                $data['name'],                                            // B: Name
                $data['email'],                                           // C: Email
                $phone,                                                   // D: Telefon
                $data['city'],                                            // E: Stadt
                $data['date'],                                            // F: Event-Datum
                $guestLabels[$data['guests']] ?? $data['guests'],         // G: Gästeanzahl
                $packageLabels[$data['package']] ?? $data['package'],     // H: Event-Art
                '-',                                                      // I: Musik-Stil (not collected in modal)
                $data['budget'] ?: 'Nicht angegeben',                     // J: Budget
                $data['message'] ?: '-',                                  // K: Nachricht
                'Neu',                                                    // L: Status
                $data['company'] ?: '-',                                  // M: Notizen (using for company)
            ];

            $body = new Sheets\ValueRange([
                'values' => [$row],
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED',
            ];

            $service->spreadsheets_values->append(
                $this->spreadsheetId,
                'A:M',
                $body,
                $params
            );

            Log::info('Event request added to Google Sheet', ['email' => $data['email'], 'city' => $data['city']]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets API error', ['message' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Append a booking request to the Google Sheet.
     *
     * @param array{
     *     selectedDate: string,
     *     selectedTime: string,
     *     name: string,
     *     email: string,
     *     phone: string,
     *     message: string|null,
     * } $data
     */
    public function createBookingRequest(array $data): bool
    {
        $sheetId = $this->bookingsSheetId ?: $this->spreadsheetId;

        if (empty($sheetId)) {
            Log::warning('Google Sheets spreadsheet ID not configured for bookings');
            return false;
        }

        try {
            $client = $this->getClient();
            $service = new Sheets($client);

            // Header: Datum, Name, Email, Telefon, Termin-Datum, Termin-Zeit, Nachricht, Status
            // Prefix phone with ' to prevent formula interpretation
            $phone = $data['phone'] ? "'" . $data['phone'] : '-';

            $row = [
                now()->format('Y-m-d H:i'),                    // A: Eingangsdatum
                $data['name'],                                  // B: Name
                $data['email'],                                 // C: Email
                $phone,                                         // D: Telefon
                $data['selectedDate'],                          // E: Termin-Datum
                $data['selectedTime'],                          // F: Termin-Zeit
                $data['message'] ?: '-',                        // G: Nachricht
                'Neu',                                          // H: Status
            ];

            $body = new Sheets\ValueRange([
                'values' => [$row],
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED',
            ];

            // Use different sheet name or range if needed
            $range = 'Bookings!A:H'; // Falls separates Sheet gewünscht, sonst 'A:H'

            $service->spreadsheets_values->append(
                $sheetId,
                $range,
                $body,
                $params
            );

            Log::info('Booking request added to Google Sheet', [
                'email' => $data['email'],
                'date' => $data['selectedDate'],
                'time' => $data['selectedTime']
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets API error (booking)', ['message' => $e->getMessage()]);
            return false;
        }
    }

    private function getClient(): Client
    {
        $client = new Client;
        $client->setApplicationName('musikfuerfirmen');
        $client->setScopes([Sheets::SPREADSHEETS]);

        $credentialsPath = config('services.google_sheets.credentials_path');

        if ($credentialsPath && file_exists($credentialsPath)) {
            $client->setAuthConfig($credentialsPath);
        } else {
            // Use individual credentials from env
            $client->setAuthConfig([
                'type' => 'service_account',
                'project_id' => config('services.google_sheets.project_id'),
                'private_key_id' => config('services.google_sheets.private_key_id'),
                'private_key' => str_replace('\\n', "\n", config('services.google_sheets.private_key')),
                'client_email' => config('services.google_sheets.client_email'),
                'client_id' => config('services.google_sheets.client_id'),
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
            ]);
        }

        return $client;
    }
}
