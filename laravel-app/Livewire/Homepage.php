<?php

namespace App\Livewire;

use App\Models\Video;
use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Homepage extends Component
{
    /**
     * Render the homepage component.
     * 
     * Displays:
     * - Latest 3 videos (from Video model)
     * - Latest 3 blog posts (from BlogPost model)
     * - Featured services (top 3 by display_order)
     * - Random 2 testimonials (for social proof)
     */
    public function render()
    {
        // Latest 3 videos (using Video model's published scope)
        $recentVideos = Video::published()
            ->limit(3)
            ->get();

        // Latest 3 blog posts (published, sorted by date)
        $recentPosts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Featured services (display_order ASC)
        // NOTE: Service model will be created in Day 4
        // For now, this will return empty collection until Service seeder runs
        $featuredServices = Service::where('status', 'active')
            ->orderBy('display_order')
            ->limit(3)
            ->get();

        // Random testimonials for social proof
        // NOTE: Testimonial model will be created in Day 4
        // For now, this will return empty collection until Testimonial seeder runs
        $testimonials = Testimonial::where('status', 'published')
            ->inRandomOrder()
            ->limit(2)
            ->get();

        return view('livewire.homepage', [
            'recentVideos' => $recentVideos,
            'recentPosts' => $recentPosts,
            'featuredServices' => $featuredServices,
            'testimonials' => $testimonials,
        ]);
    }
}
