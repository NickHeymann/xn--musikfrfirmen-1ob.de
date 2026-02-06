<?php

namespace App\Livewire;

use App\Jobs\SendContactFormNotification;
use App\Models\ContactSubmission;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('nullable|string')]
    public $phone = '';

    #[Validate('nullable|string|max:255')]
    public $company = '';

    #[Validate('required|in:general,booking,partnership,other')]
    public $inquiry_type = 'general';

    #[Validate('required|string')]
    public $message = '';

    public function submit()
    {
        $validated = $this->validate();

        $submission = ContactSubmission::create([
            ...$validated,
            'status' => 'new',
        ]);

        // Dispatch async: email notification
        SendContactFormNotification::dispatch($submission);

        session()->flash('message', 'Vielen Dank! Wir melden uns innerhalb von 24 Stunden bei Ihnen.');

        // Clear stored form data after successful submission
        $this->dispatch('clear-contact-storage');

        $this->reset();
        $this->inquiry_type = 'general';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
