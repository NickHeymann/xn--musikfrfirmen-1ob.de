<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = BlogPost::query()
            ->where('status', 'published')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category !== 'all', function ($query) {
                $query->where('category', $this->category);
            })
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogPost::where('status', 'published')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('livewire.blog-index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
