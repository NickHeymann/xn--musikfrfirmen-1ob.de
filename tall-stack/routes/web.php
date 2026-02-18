<?php

use App\Livewire\AboutPage;
use App\Livewire\BookingCalendar;
use App\Livewire\ContactForm;
use App\Livewire\EventShow;
use App\Livewire\EventsIndex;
use App\Livewire\HoerprobePage;
use App\Livewire\Homepage;
use App\Livewire\PageShow;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/uber-uns', AboutPage::class)->name('about');
Route::get('/events', EventsIndex::class)->name('events.index');
Route::get('/events/{event}', EventShow::class)->name('events.show');
Route::get('/contact', ContactForm::class)->name('contact');
Route::get('/erstgespraech', BookingCalendar::class)->name('booking.calendar');
Route::get('/hoerprobe', HoerprobePage::class)->name('hoerprobe');

// Legal pages (content managed via Filament admin)
Route::get('/impressum', PageShow::class)->name('impressum');
Route::get('/datenschutz', PageShow::class)->name('datenschutz');

// Dynamic pages (catch-all for other pages created in Filament)
Route::get('/seite/{slug}', PageShow::class)->name('page.show');
