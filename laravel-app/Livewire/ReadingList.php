<?php

namespace App\Livewire;

use App\Models\ReadingListItem;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ReadingList extends Component
{
    public $showModal = false;
    public $items = [];
    public $count = 0;

    protected $listeners = [
        'bookmark-added' => 'refreshList',
        'bookmark-removed' => 'refreshList',
    ];

    public function mount()
    {
        $this->refreshList();
    }

    public function refreshList()
    {
        $sessionId = Session::getId();

        $this->items = ReadingListItem::where('session_id', $sessionId)
            ->with('blogPost')
            ->orderBy('added_at', 'desc')
            ->get();

        $this->count = $this->items->count();
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    public function removeItem($itemId)
    {
        $sessionId = Session::getId();

        ReadingListItem::where('id', $itemId)
            ->where('session_id', $sessionId)
            ->delete();

        $this->refreshList();

        // Dispatch event to update bookmark buttons
        $this->dispatch('bookmark-removed');
    }

    public function render()
    {
        return view('livewire.reading-list');
    }
}
