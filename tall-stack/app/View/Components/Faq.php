<?php

namespace App\View\Components;

use App\Models\Faq as FaqModel;
use Illuminate\View\Component;
use Illuminate\View\View;

class Faq extends Component
{
    public $faqItems;

    public function __construct()
    {
        // Fetch active FAQs from database, ordered by display_order
        $faqs = FaqModel::active()->get();

        // Transform to match the expected structure for the template
        $this->faqItems = $faqs->map(function ($faq) {
            return [
                'question' => $faq->question,
                'answer' => $faq->answer,
                'hasLink' => $faq->has_link,
            ];
        })->toArray();
    }

    public function render(): View
    {
        return view('components.faq');
    }
}
