<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Livewire\Component;

class BlogShow extends Component
{
    public BlogPost $post;

    public function mount(string $slug)
    {
        // Find the blog post by slug
        $this->post = BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $this->post->increment('views');
    }

    public function render()
    {
        // Get related posts from the same category
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('category', $this->post->category)
            ->where('id', '!=', $this->post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.blog-show', [
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
