<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Validate;

class EventShow extends Component
{
    public Event $event;

    #[Validate('required|string|max:255')]
    public $company_name = '';

    #[Validate('required|string|max:255')]
    public $contact_person = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|string')]
    public $phone = '';

    #[Validate('required|integer|min:1')]
    public $num_musicians = 1;

    #[Validate('nullable|string')]
    public $special_requests = '';

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->num_musicians = 1;
    }

    public function submitBooking()
    {
        $validated = $this->validate();

        // Check if num_musicians doesn't exceed musicians_needed
        if ($this->num_musicians > $this->event->musicians_needed) {
            $this->addError('num_musicians', "Maximum {$this->event->musicians_needed} musicians needed for this event.");
            return;
        }

        $booking = Booking::create([
            ...$validated,
            'event_id' => $this->event->id,
            'total_price' => $this->event->price_per_musician * $this->num_musicians,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Booking request submitted! We\'ll contact you shortly.');

        // Reset form
        $this->reset(['company_name', 'contact_person', 'email', 'phone', 'num_musicians', 'special_requests']);
        $this->num_musicians = 1;
    }

    public function render()
    {
        return view('livewire.event-show');
    }
}
