<?php

namespace App\View\Components;

use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServicesSection extends Component
{
    public $services;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch active services ordered by display_order
        $this->services = Service::active()->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.services-section');
    }
}
