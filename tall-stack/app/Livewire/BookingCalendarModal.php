<?php

namespace App\Livewire;

use App\Mail\BookingRequestSubmitted;
use App\Models\CalendarBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BookingCalendarModal extends Component
{
    public $isOpen = false;

    // Progressive disclosure state
    public $step = 1; // 1: Date selection, 2: Time selection, 3: Contact form

    // Calendar navigation
    public $currentYear;

    public $currentMonth;

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
        '15:00', '15:30', '16:00', '16:30', '17:00',
    ];

    public $showSuccess = false;

    #[On('openBookingModal')]
    public function open()
    {
        $this->isOpen = true;

        // Find first month with available dates
        $today = Carbon::today();
        $checkDate = $today->copy();
        $foundAvailableMonth = false;

        // Check up to 3 months ahead
        for ($i = 0; $i < 3; $i++) {
            $firstDay = $checkDate->copy()->startOfMonth();
            $lastDay = $checkDate->copy()->endOfMonth();

            // Check if this month has any available weekdays in the future
            $current = max($today, $firstDay);
            while ($current <= $lastDay) {
                if (! $current->isPast() && ! $current->isWeekend()) {
                    $foundAvailableMonth = true;
                    $this->currentYear = $checkDate->year;
                    $this->currentMonth = $checkDate->month;

                    // Auto-select first available date
                    $this->selectedDate = $current->format('Y-m-d');
                    break 2;
                }
                $current->addDay();
            }

            $checkDate->addMonth();
        }

        // Fallback to current month if no available dates found
        if (! $foundAvailableMonth) {
            $this->currentYear = $today->year;
            $this->currentMonth = $today->month;
            $this->selectedDate = null;
        }

        $this->step = 1;
        $this->showSuccess = false;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['step', 'selectedDate', 'selectedTime', 'name', 'email', 'phone', 'message', 'showSuccess']);
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        // Stay on step 1 to show time slots in right column
        $this->step = 1;
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

        // Save booking to database
        $booking = CalendarBooking::create([
            'selected_date' => $this->selectedDate,
            'selected_time' => $this->selectedTime,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        // Prepare booking data for email
        $bookingData = [
            'selectedDate' => $this->selectedDate,
            'selectedTime' => $this->selectedTime,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ];

        // Send email notification to all recipients (Punycode for IDN domains)
        $recipients = explode(',', env('EVENT_REQUEST_RECIPIENTS', 'kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de'));
        Mail::to(array_map('trim', $recipients))->send(new BookingRequestSubmitted($bookingData));

        // Show success state
        $this->showSuccess = true;
    }

    public function getCalendarDaysProperty()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();

        // Start from the first day of the week (Monday)
        $startDate = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
        $endDate = $lastDay->copy()->endOfWeek(Carbon::MONDAY);

        $days = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $isCurrentMonth = $current->month === $this->currentMonth;
            $isPast = $current->lt(Carbon::today());
            $isWeekend = $current->isWeekend();
            $isAvailable = ! $isPast && ! $isWeekend && $isCurrentMonth;

            $days[] = [
                'date' => $current->format('Y-m-d'),
                'day' => $current->day,
                'isCurrentMonth' => $isCurrentMonth,
                'isPast' => $isPast,
                'isWeekend' => $isWeekend,
                'isAvailable' => $isAvailable,
                'isToday' => $current->isToday(),
            ];

            $current->addDay();
        }

        return $days;
    }

    public function getCurrentMonthNameProperty()
    {
        return Carbon::create($this->currentYear, $this->currentMonth, 1)->locale('de')->isoFormat('MMMM YYYY');
    }

    public function getFormattedSlotsProperty()
    {
        return collect($this->availableSlots)->map(function ($slot) {
            if ($this->timeFormat === '12h') {
                return Carbon::createFromFormat('H:i', $slot)->format('g:i a');
            }

            return $slot;
        })->toArray();
    }

    public function render()
    {
        return view('livewire.booking-calendar-modal');
    }
}
