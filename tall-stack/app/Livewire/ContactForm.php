<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\Attributes\Validate;

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

        ContactSubmission::create([
            ...$validated,
            'status' => 'new',
        ]);

        session()->flash('message', 'Thank you! We\'ll get back to you within 24 hours.');

        $this->reset();
        $this->inquiry_type = 'general';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
