<?php

namespace App\Livewire;

use App\Models\Faq;
use Livewire\Component;

class FaqSection extends Component
{
    public function render()
    {
        $faqItems = Faq::active()->get();

        return view('livewire.faq-section', [
            'faqItems' => $faqItems,
        ]);
    }
}
