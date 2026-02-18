<?php

namespace App\Livewire;

use Livewire\Component;

class HoerprobePage extends Component
{
    public function render()
    {
        return view('livewire.hoerprobe-page')
            ->layout('components.layouts.hoerprobe');
    }
}
