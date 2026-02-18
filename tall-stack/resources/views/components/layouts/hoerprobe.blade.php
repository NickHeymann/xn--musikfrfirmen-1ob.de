<!DOCTYPE html>
<html lang="de" class="scroll-smooth bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hörprobe – musikfürfirmen.de</title>
    <meta name="description" content="Hör rein: Echte Livemusik für euren Firmenevent. Jetzt Hörprobe anhören und kostenloses Erstgespräch vereinbaren.">

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + Alpine.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="antialiased bg-white font-sans h-screen flex flex-col overflow-hidden">
    {{ $slot }}

    {{-- Event Request Modal (MFF Calculator) --}}
    <livewire:event-request-modal />

    {{-- Booking Calendar Modal --}}
    <livewire:booking-calendar-modal />

    {{-- Livewire Scripts --}}
    @livewireScripts
</body>
</html>
