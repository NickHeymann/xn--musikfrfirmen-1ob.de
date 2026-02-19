<?php

namespace App\Livewire;

use App\Jobs\SendEventRequestNotification;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EventRequestModal extends Component
{
    public int $step = 1;

    public bool $showModal = false;

    public string $submitStatus = 'idle'; // idle, success, error

    // Step 1: Event Details
    #[Validate('required|date|after_or_equal:today', message: 'Bitte wähle ein Datum das in der Zukunft liegt')]
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
    #[Validate('required|string|min:2', message: 'Bitte gib deinen Vornamen an')]
    public string $firstname = '';

    public string $lastname = '';

    #[Validate('required|string|min:2', message: 'Bitte gib deine Firma an')]
    public string $company = '';

    #[Validate('required|email', message: 'Bitte gib eine gültige E-Mail an')]
    public string $email = '';

    // Phone is optional - no validation attribute
    public string $phone = '';

    public string $message = '';

    #[Validate('accepted', message: 'Bitte akzeptiere die Datenschutzerklärung')]
    public bool $privacy = false;

    // Optional: User consent for localStorage (GDPR compliance)
    public bool $storageConsent = false;

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

        // DO NOT reset data - keep it for next time modal opens
        // Data persists in Livewire session + localStorage (via Alpine.js)
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate([
                'date' => 'required|date|after_or_equal:today',
                'city' => 'required|string|min:2',
                'guests' => 'required|string',
            ], [
                'date.required' => 'Bitte wähle ein Datum das in der Zukunft liegt',
                'date.after_or_equal' => 'Bitte wähle ein Datum das in der Zukunft liegt',
                'city.required' => 'Bitte gib eine Stadt an',
                'city.min' => 'Bitte gib eine Stadt an',
                'guests.required' => 'Bitte wähle eine Gästeanzahl',
            ]);
            $this->step = 2;
        } elseif ($this->step === 2) {
            $this->validate([
                'package' => 'required|string',
            ], [
                'package.required' => 'Bitte wähle ein Paket',
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

    public function selectGuests(string $value): void
    {
        $this->guests = $value;

        // No auto-advance - user must click "Weiter" button manually
        $this->dispatch('guests-selected');
    }

    public function submit(): void
    {
        $key = 'event-request:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Zu viele Anfragen. Bitte versuchen Sie es später erneut.');
            return;
        }
        RateLimiter::hit($key, 3600);

        $this->validate([
            'firstname' => 'required|string|min:2',
            'company' => 'required|string|min:2',
            'email' => 'required|email',
            // phone is optional
            'privacy' => 'accepted',
        ], [
            'firstname.required' => 'Bitte gib deinen Vornamen an',
            'firstname.min' => 'Bitte gib deinen Vornamen an',
            'company.required' => 'Bitte gib deine Firma an',
            'company.min' => 'Bitte gib deine Firma an',
            'email.required' => 'Bitte gib eine gültige E-Mail an',
            'email.email' => 'Bitte gib eine gültige E-Mail an',
            'privacy.accepted' => 'Bitte akzeptiere die Datenschutzerklärung',
        ]);

        $fullName = trim($this->firstname.' '.$this->lastname);

        $data = [
            'name' => $fullName,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
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

        // Dispatch async: email + Google Sheets
        SendEventRequestNotification::dispatch($data);

        $this->submitStatus = 'success';

        // Clear stored form data after successful submission
        $this->dispatch('clear-calculator-storage');
    }

    public function openCalCom(): void
    {
        $this->dispatch('open-calcom', url: 'https://cal.com/xn--musikfrfirmen-1ob.de/30min');
    }

    private function resetAfterClose(): void
    {
        $this->reset([
            'step', 'submitStatus', 'date', 'time', 'city', 'budget', 'guests',
            'package', 'firstname', 'lastname', 'company', 'email', 'phone', 'message', 'privacy',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.event-request-modal');
    }
}
