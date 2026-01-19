<?php

use App\Livewire\Homepage;
use App\Livewire\AboutPage;
use App\Livewire\EventsIndex;
use App\Livewire\EventShow;
use App\Livewire\ContactForm;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/uber-uns', AboutPage::class)->name('about');
Route::get('/events', EventsIndex::class)->name('events.index');
Route::get('/events/{event}', EventShow::class)->name('events.show');
Route::get('/contact', ContactForm::class)->name('contact');

// Test route without Livewire
Route::get('/test', function () {
    return view('welcome');
});
