<?php

namespace App\Livewire;

use App\Mail\BookingRequestSubmitted;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class BookingCalendar extends Component
{
    // Progressive disclosure state
    public $step = 1; // 1: Date selection, 2: Time selection, 3: Contact form

    // Booking data
    public $selectedDate = null;
    public $selectedTime = null;

    #[Validate('required|string|min:2')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|string')]
    public $phone = '';

    #[Validate('nullable|string|max:500')]
    public $message = '';

    // Available time slots (9:00 - 17:00, 30-minute intervals)
    public $availableSlots = [
        '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
        '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
        '15:00', '15:30', '16:00', '16:30', '17:00'
    ];

    public function mount()
    {
        // Default to today
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->step = 2; // Progress to time selection
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
        $this->step = 3; // Progress to contact form
    }

    public function goBack()
    {
        if ($this->step > 1) {
            $this->step--;

            // Clear data from abandoned step
            if ($this->step === 1) {
                $this->selectedTime = null;
            } elseif ($this->step === 2) {
                $this->reset(['name', 'email', 'phone', 'message']);
            }
        }
    }

    public function submitBooking()
    {
        $this->validate();

        // Prepare booking data for email
        $bookingData = [
            'selectedDate' => $this->selectedDate,
            'selectedTime' => $this->selectedTime,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ];

        // Send email notification to admin
        $recipients = explode(',', env('EVENT_REQUEST_RECIPIENTS', 'moin@jonasglamann.de'));
        Mail::to($recipients)->send(new BookingRequestSubmitted($bookingData));

        session()->flash('booking-success', 'Vielen Dank! Wir haben Ihre Anfrage erhalten und melden uns in Kürze bei Ihnen.');

        // Reset component
        $this->reset();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function getAvailableDatesProperty()
    {
        // Generate next 30 days (excluding weekends for this example)
        $dates = [];
        $current = Carbon::today();

        for ($i = 0; $i < 30; $i++) {
            $date = $current->copy()->addDays($i);

            // Skip weekends
            if (!$date->isWeekend()) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->format('d'),
                    'month' => $date->format('M'),
                    'dayOfWeek' => $date->format('D'),
                ];
            }
        }

        return $dates;
    }

    public function render()
    {
        return view('livewire.booking-calendar')->layout('components.layouts.app');
    }
}
