<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'musikfürfirmen.de' }}</title>

    {{-- Preload Hero Poster Image for Instant Display --}}
    <link rel="preload" href="{{ asset('images/hero-poster.webp') }}" as="image" fetchpriority="high">

    {{-- Google Fonts: Poppins (erweiterte Weights) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + Alpine.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="antialiased bg-[#1a1a1a] font-sans">
    {{ $slot }}

    {{-- Event Request Modal --}}
    <livewire:event-request-modal />

    {{-- Livewire Scripts --}}
    @livewireScripts
</body>
</html>
