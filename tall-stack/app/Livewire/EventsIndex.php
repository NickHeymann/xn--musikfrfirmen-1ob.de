<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $musicStyle = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMusicStyle()
    {
        $this->resetPage();
    }

    public function render()
    {
        $events = Event::query()
            ->where('status', 'published')
            ->when($this->search, fn($query) =>
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('location', 'like', "%{$this->search}%")
            )
            ->when($this->musicStyle !== 'all', fn($query) =>
                $query->where('music_style', $this->musicStyle)
            )
            ->orderBy('start_time')
            ->paginate(9);

        $musicStyles = Event::where('status', 'published')
            ->whereNotNull('music_style')
            ->distinct()
            ->pluck('music_style')
            ->sort()
            ->values();

        return view('livewire.events-index', [
            'events' => $events,
            'musicStyles' => $musicStyles,
        ]);
    }
}
