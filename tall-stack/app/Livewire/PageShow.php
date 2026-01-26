<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Facades\Route;

class PageShow extends Component
{
    public Page $page;

    public function mount(?string $slug = null)
    {
        // If no slug provided, use the route name as slug
        // This allows /impressum and /datenschutz to work
        if ($slug === null) {
            $routeName = Route::currentRouteName();
            $slug = $routeName; // 'impressum' or 'datenschutz'
        }

        $this->page = Page::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.page-show', [
            'page' => $this->page,
        ]);
    }
}
