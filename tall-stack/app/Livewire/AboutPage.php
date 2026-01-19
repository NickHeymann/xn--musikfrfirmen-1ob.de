<?php

namespace App\Livewire;

use App\Models\TeamMember;
use Livewire\Component;

class AboutPage extends Component
{
    public function render()
    {
        $teamMembers = TeamMember::active()->get();

        return view('livewire.about-page', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
