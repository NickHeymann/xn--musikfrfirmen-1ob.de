<?php

namespace App\Livewire;

use App\Models\Testimonial;
use Livewire\Component;

class TestimonialCarousel extends Component
{
    public $currentIndex = 0;
    public $testimonials;

    public function mount()
    {
        $this->testimonials = Testimonial::active()
            ->featured()
            ->ordered()
            ->get();
    }

    public function next()
    {
        $this->currentIndex = ($this->currentIndex + 1) % $this->testimonials->count();
    }

    public function previous()
    {
        $this->currentIndex = ($this->currentIndex - 1 + $this->testimonials->count()) % $this->testimonials->count();
    }

    public function goTo($index)
    {
        $this->currentIndex = $index;
    }

    public function render()
    {
        return view('livewire.testimonial-carousel');
    }
}
