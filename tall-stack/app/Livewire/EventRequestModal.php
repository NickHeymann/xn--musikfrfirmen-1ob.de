<?php

namespace App\Livewire;

use App\Mail\EventRequestNotification;
use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EventRequestModal extends Component
{
    public int $step = 1;

    public bool $showModal = false;

    public string $submitStatus = 'idle'; // idle, success, error

    // Step 1: Event Details
    #[Validate('required|date|after_or_equal:today', message: 'Bitte wähle ein Datum in der Zukunft')]
    public string $date = '';

    public string $time = '';

    #[Validate('required|string|min:2', message: 'Bitte gib eine Stadt an')]
    public string $city = '';

    public string $budget = '';

    #[Validate('required|string', message: 'Bitte wähle eine Gästeanzahl')]
    public string $guests = '';

    // Step 2: Package Selection
    #[Validate('required|string', message: 'Bitte wähle ein Paket')]
    public string $package = '';

    // Step 3: Contact Details
    #[Validate('required|string|min:2', message: 'Bitte gib deinen Namen an')]
    public string $name = '';

    public string $company = '';

    #[Validate('required|email', message: 'Bitte gib eine gültige E-Mail an')]
    public string $email = '';

    #[Validate('required|string', message: 'Bitte gib eine Telefonnummer an')]
    public string $phone = '';

    public string $message = '';

    #[Validate('accepted', message: 'Bitte akzeptiere die Datenschutzerklärung')]
    public bool $privacy = false;

    public array $packageOptions = [
        ['value' => 'dj', 'label' => 'Nur DJ'],
        ['value' => 'band', 'label' => 'Full Band'],
        ['value' => 'band_dj', 'label' => 'Full Band + DJ'],
    ];

    public array $guestOptions = [
        ['value' => 'lt100', 'label' => 'Unter 100'],
        ['value' => '100-300', 'label' => '100 - 300'],
        ['value' => '300-500', 'label' => '300 - 500'],
        ['value' => 'gt500', 'label' => '>500'],
    ];

    #[On('openMFFCalculator')]
    public function openModal(): void
    {
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('modal-closed');
        $this->resetAfterClose();
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate([
                'date' => 'required|date|after_or_equal:today',
                'city' => 'required|string|min:2',
                'guests' => 'required|string',
            ]);
            $this->step = 2;
        } elseif ($this->step === 2) {
            $this->validate([
                'package' => 'required|string',
            ]);
            $this->step = 3;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submit(GoogleSheetsService $sheetsService): void
    {
        $this->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'phone' => 'required|string',
            'privacy' => 'accepted',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'date' => $this->date,
            'time' => $this->time,
            'city' => $this->city,
            'budget' => $this->budget,
            'guests' => $this->guests,
            'package' => $this->package,
            'message' => $this->message,
        ];

        // 1. Add to Google Sheet
        $sheetsService->createEventRequest($data);

        // 2. Send email notification to team
        $this->sendEmailNotification($data);

        $this->submitStatus = 'success';
    }

    private function sendEmailNotification(array $data): void
    {
        $recipients = config('services.event_request.recipients', 'moin@nickheymann.de,moin@jonasglamann.de');
        $recipientList = array_map('trim', explode(',', $recipients));

        try {
            Mail::to($recipientList)
                ->send(new EventRequestNotification($data));

            Log::info('Event request email sent', ['recipients' => $recipientList, 'city' => $data['city']]);
        } catch (\Exception $e) {
            Log::error('Failed to send event request email', ['error' => $e->getMessage()]);
        }
    }

    public function openCalCom(): void
    {
        $this->dispatch('open-calcom', url: 'https://cal.com/xn--musikfrfirmen-1ob.de/30min');
    }

    private function resetAfterClose(): void
    {
        $this->reset([
            'step', 'submitStatus', 'date', 'time', 'city', 'budget', 'guests',
            'package', 'name', 'company', 'email', 'phone', 'message', 'privacy',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.event-request-modal');
    }
}
