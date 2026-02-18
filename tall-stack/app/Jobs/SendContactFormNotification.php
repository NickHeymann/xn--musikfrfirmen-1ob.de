<?php

namespace App\Jobs;

use App\Mail\ContactFormSubmitted;
use App\Models\ContactSubmission;
use App\Services\CompanyResearchService;
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
    public function handle(CompanyResearchService $researchService): void
    {
        // 1. Company research (non-blocking, optional)
        $companyResearch = null;
        if (! empty($this->submission->company)) {
            try {
                $companyResearch = $researchService->research($this->submission->company);
                $this->submission->update(['company_research' => $companyResearch]);
            } catch (\Exception $e) {
                Log::warning('Company research failed for contact form', ['error' => $e->getMessage()]);
            }
        }

        // 2. Send email notification
        $recipients = config('services.event_request.recipients', 'kontakt@musikfuerfirmen.com');
        $recipientList = array_map('trim', explode(',', $recipients));

        try {
            Mail::to($recipientList)->send(new ContactFormSubmitted($this->submission, $companyResearch));
            Log::info('Contact form email sent', [
                'recipients' => $recipientList,
                'email' => $this->submission->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email', ['error' => $e->getMessage()]);
        }
    }
}
