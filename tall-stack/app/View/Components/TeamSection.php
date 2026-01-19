<?php

namespace App\View\Components;

use App\Models\TeamMember;
use Illuminate\View\Component;
use Illuminate\View\View;

class TeamSection extends Component
{
    public array $teamMembers;

    public function __construct()
    {
        // Fetch active team members from database
        $members = TeamMember::active()->get();

        // Transform to camelCase keys for Blade template compatibility
        $this->teamMembers = $members->map(function ($member) {
            return [
                'name' => $member->name,
                'role' => $member->role,
                'roleSecondLine' => $member->role_second_line,
                'image' => $member->image,
                'bioTitle' => $member->bio_title,
                'bioText' => $member->bio_text,
                'imageClass' => $member->image_class,
                'position' => $member->position,
            ];
        })->toArray();
    }

    public function render(): View
    {
        return view('components.team-section', [
            'teamMembers' => $this->teamMembers,
        ]);
    }
}
