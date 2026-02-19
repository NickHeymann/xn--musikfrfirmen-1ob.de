<?php

namespace App\Livewire;

use App\Models\CalendarBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
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

    public $selectedTimeRange = null;

    #[Validate('required|string|min:2')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|string')]
    public $phone = '';

    #[Validate('required|string|min:2')]
    public $company = '';

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

        // Reset success state after close animation completes (300ms)
        // This prevents the calendar from briefly appearing during close transition
        $this->dispatch('reset-success-after-close');
    }

    #[On('reset-success-state')]
    public function resetSuccessState()
    {
        $this->reset(['showSuccess']);
    }

    public function selectDate($date): void
    {
        // Validate: must be a real future weekday
        $parsed = Carbon::createFromFormat('Y-m-d', $date);
        if (! $parsed || $parsed->isPast() || $parsed->isWeekend()) {
            return;
        }
        $this->selectedDate = $date;
        $this->selectedTimeRange = null;
        // Stay on step 1 to show time slots in right column
        $this->step = 1;
    }

    public function selectTime($time): void
    {
        // Validate: must be one of the allowed slots
        if (! in_array($time, $this->availableSlots, true)) {
            return;
        }
        $this->selectedTime = $time;
        $this->step = 3; // Progress to contact form
    }

    public function selectTimeRange($range)
    {
        $this->selectedTimeRange = $range;
    }

    public function goBack()
    {
        if ($this->step > 1) {
            $this->step--;

            // Clear data from abandoned step
            if ($this->step === 1) {
                $this->selectedTime = null;
                $this->selectedTimeRange = null;
            } elseif ($this->step === 2) {
                $this->reset(['name', 'company', 'email', 'phone', 'message']);
            }
        }
    }

    public function submitBooking(): void
    {
        $key = 'booking:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('email', 'Zu viele Anfragen. Bitte versuchen Sie es später erneut.');
            return;
        }
        RateLimiter::hit($key, 3600);

        $this->validate();

        // Prepare booking data
        $bookingData = [
            'selectedDate' => $this->selectedDate,
            'selectedTime' => $this->selectedTime,
            'name' => $this->name,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ];

        // 1. Save booking to database (ONLY blocking operation - fast!)
        CalendarBooking::create([
            'selected_date' => $this->selectedDate,
            'selected_time' => $this->selectedTime,
            'name' => $this->name,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        // 2. Dispatch job for email + Google Sheets (runs in background queue)
        \App\Jobs\SendBookingNotification::dispatch($bookingData);

        // Show success state IMMEDIATELY (no waiting for email/sheets)
        $this->showSuccess = true;

        // Clear stored form data after successful submission
        $this->dispatch('clear-booking-storage');
    }

    public function getTimeRangesProperty()
    {
        return [
            'morning' => ['label' => 'Vormittag', 'subtitle' => '9 – 12 Uhr', 'start' => '09:00', 'end' => '12:00'],
            'afternoon' => ['label' => 'Nachmittag', 'subtitle' => '12 – 16 Uhr', 'start' => '12:00', 'end' => '16:00'],
            'evening' => ['label' => 'Abend', 'subtitle' => '16 – 17 Uhr', 'start' => '16:00', 'end' => '17:30'],
        ];
    }

    public function getFilteredSlotsProperty()
    {
        if (! $this->selectedTimeRange) {
            return [];
        }

        $ranges = $this->timeRanges;
        $range = $ranges[$this->selectedTimeRange] ?? null;

        if (! $range) {
            return [];
        }

        return collect($this->availableSlots)->filter(function ($slot) use ($range) {
            return $slot >= $range['start'] && $slot < $range['end'];
        })->values()->toArray();
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
        // Always return 24h format for DACH region
        return $this->availableSlots;
    }

    public function render()
    {
        return view('livewire.booking-calendar-modal');
    }
}
