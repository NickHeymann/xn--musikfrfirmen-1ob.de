<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\ReadingListItem;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class BookmarkToggle extends Component
{
    public $postId;
    public $isBookmarked = false;

    protected $listeners = [
        'bookmark-removed' => 'checkBookmarkStatus',
    ];

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->checkBookmarkStatus();
    }

    public function checkBookmarkStatus()
    {
        $sessionId = Session::getId();

        $this->isBookmarked = ReadingListItem::where('session_id', $sessionId)
            ->where('blog_post_id', $this->postId)
            ->exists();
    }

    public function toggleBookmark()
    {
        $sessionId = Session::getId();

        if ($this->isBookmarked) {
            // Remove from reading list
            ReadingListItem::where('session_id', $sessionId)
                ->where('blog_post_id', $this->postId)
                ->delete();

            $this->isBookmarked = false;

            // Dispatch event to update ReadingList FAB
            $this->dispatch('bookmark-removed');
        } else {
            // Add to reading list
            ReadingListItem::create([
                'session_id' => $sessionId,
                'blog_post_id' => $this->postId,
                'added_at' => now(),
            ]);

            $this->isBookmarked = true;

            // Dispatch event to update ReadingList FAB
            $this->dispatch('bookmark-added');
        }
    }

    public function render()
    {
        return view('livewire.bookmark-toggle');
    }
}
