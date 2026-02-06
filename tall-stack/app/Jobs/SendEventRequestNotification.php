<?php

namespace App\Jobs;

use App\Mail\EventRequestNotification;
use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEventRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $eventData
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GoogleSheetsService $sheetsService): void
    {
        // 1. Send email notification
        $recipients = config('services.event_request.recipients', 'kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de');
        $recipientList = array_map('trim', explode(',', $recipients));

        try {
            Mail::to($recipientList)->send(new EventRequestNotification($this->eventData));
            Log::info('Event request email sent', [
                'recipients' => $recipientList,
                'city' => $this->eventData['city'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send event request email', ['error' => $e->getMessage()]);
        }

        // 2. Add to Google Sheet
        try {
            $sheetsService->createEventRequest($this->eventData);
        } catch (\Exception $e) {
            Log::error('Failed to add event request to Google Sheets', ['error' => $e->getMessage()]);
        }
    }
}
