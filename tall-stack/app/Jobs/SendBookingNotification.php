<?php

namespace App\Jobs;

use App\Mail\BookingRequestSubmitted;
use App\Services\CompanyResearchService;
use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $bookingData
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GoogleSheetsService $sheetsService, CompanyResearchService $researchService): void
    {
        // 1. Company research (non-blocking, optional)
        $companyResearch = null;
        $companyName = $this->bookingData['company'] ?? '';
        if (! empty($companyName)) {
            try {
                $companyResearch = $researchService->research($companyName);
            } catch (\Exception $e) {
                Log::warning('Company research failed for booking', ['error' => $e->getMessage()]);
            }
        }

        // 2. Send email notification
        $recipients = config('services.event_request.recipients', 'kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de');
        $recipientList = array_map('trim', explode(',', $recipients));

        try {
            Mail::to($recipientList)->send(new BookingRequestSubmitted($this->bookingData, $companyResearch));
            Log::info('Booking request email sent', [
                'recipients' => $recipientList,
                'date' => $this->bookingData['selectedDate'],
                'time' => $this->bookingData['selectedTime'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking request email', ['error' => $e->getMessage()]);
        }

        // 3. Add to Google Sheet
        try {
            $sheetsService->createBookingRequest($this->bookingData);
        } catch (\Exception $e) {
            Log::error('Failed to add booking to Google Sheets', ['error' => $e->getMessage()]);
        }
    }
}
