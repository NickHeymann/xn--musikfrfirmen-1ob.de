<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Faq;
use Livewire\Component;

class Homepage extends Component
{
    public function render()
    {
        $upcomingEvents = Event::where('status', 'published')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(3)
            ->get();

        // Load musikfürfirmen.de content
        $services = Service::active()->get();
        $teamMembers = TeamMember::active()->get();
        $faqs = Faq::active()->get();

        return view('livewire.homepage', [
            'upcomingEvents' => $upcomingEvents,
            'services' => $services,
            'teamMembers' => $teamMembers,
            'faqs' => $faqs,
        ])->layout('components.layouts.app');
    }
}
