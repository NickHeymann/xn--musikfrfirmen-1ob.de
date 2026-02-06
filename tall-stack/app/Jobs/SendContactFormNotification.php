<?php

namespace App\Jobs;

use App\Mail\ContactFormSubmitted;
use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactFormNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactSubmission $submission
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipients = config('services.event_request.recipients', 'kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de');
        $recipientList = array_map('trim', explode(',', $recipients));

        try {
            Mail::to($recipientList)->send(new ContactFormSubmitted($this->submission));
            Log::info('Contact form email sent', [
                'recipients' => $recipientList,
                'email' => $this->submission->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email', ['error' => $e->getMessage()]);
        }
    }
}
