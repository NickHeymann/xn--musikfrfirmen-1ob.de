<?php

namespace App\View\Components;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FAQSection extends Component
{
    public $faqs;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch active FAQs ordered by display_order
        $this->faqs = Faq::active()->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.f-a-q-section');
    }
}
