{{-- Booking Calendar - Cal.com Style --}}
<div class="min-h-screen bg-gray-50" style="font-family: 'Poppins', sans-serif">
    {{-- Header --}}
    <x-header />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Success Message --}}
        @if (session('booking-success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-xl font-semibold text-green-900 mb-2">{{ session('booking-success') }}</p>
                <a href="/" class="inline-block mt-4 text-green-700 hover:text-green-900 underline">
                    Zurück zur Startseite
                </a>
            </div>
        @else
            {{-- Booking Interface - Progressive Disclosure --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                {{-- Header Section --}}
                <div class="p-8 border-b border-gray-200">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-2">
                        Kostenloses Erstgespräch
                    </h1>
                    <p class="text-lg text-gray-600">
                        Vereinbare einen Termin für ein 30-minütiges Erstgespräch
                    </p>
                </div>

                {{-- Progress Indicator --}}
                <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-center gap-4">
                        {{-- Step 1: Date --}}
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="$wire.step >= 1 ? 'bg-[#C8E6DC] text-white' : 'bg-gray-300 text-gray-600'">
                                1
                            </div>
                            <span class="text-sm font-medium" :class="$wire.step >= 1 ? 'text-[#1a1a1a]' : 'text-gray-500'">
                                Datum
                            </span>
                        </div>

                        <div class="w-12 h-0.5 bg-gray-300"></div>

                        {{-- Step 2: Time --}}
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="$wire.step >= 2 ? 'bg-[#C8E6DC] text-white' : 'bg-gray-300 text-gray-600'">
                                2
                            </div>
                            <span class="text-sm font-medium" :class="$wire.step >= 2 ? 'text-[#1a1a1a]' : 'text-gray-500'">
                                Uhrzeit
                            </span>
                        </div>

                        <div class="w-12 h-0.5 bg-gray-300"></div>

                        {{-- Step 3: Contact --}}
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="$wire.step >= 3 ? 'bg-[#C8E6DC] text-white' : 'bg-gray-300 text-gray-600'">
                                3
                            </div>
                            <span class="text-sm font-medium" :class="$wire.step >= 3 ? 'text-[#1a1a1a]' : 'text-gray-500'">
                                Kontakt
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Main Content - Left/Right Split --}}
                <div class="flex flex-col lg:flex-row min-h-[600px]">
                    {{-- Left Column: Current Selection Summary --}}
                    <div class="lg:w-1/3 bg-gray-50 p-8 border-r border-gray-200">
                        <h3 class="text-lg font-semibold text-[#1a1a1a] mb-6">Deine Auswahl</h3>

                        <div class="space-y-4">
                            {{-- Selected Date --}}
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[#5a9a84] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Datum</p>
                                    @if($selectedDate)
                                        <p class="font-medium text-[#1a1a1a]">
                                            {{ \Carbon\Carbon::parse($selectedDate)->locale('de')->isoFormat('dddd, D. MMMM YYYY') }}
                                        </p>
                                    @else
                                        <p class="text-gray-400 italic">Noch nicht ausgewählt</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Selected Time --}}
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[#5a9a84] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Uhrzeit</p>
                                    @if($selectedTime)
                                        <p class="font-medium text-[#1a1a1a]">{{ $selectedTime }} Uhr</p>
                                    @else
                                        <p class="text-gray-400 italic">Noch nicht ausgewählt</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Duration --}}
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[#5a9a84] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Dauer</p>
                                    <p class="font-medium text-[#1a1a1a]">30 Minuten</p>
                                </div>
                            </div>
                        </div>

                        {{-- Back Button --}}
                        @if($step > 1)
                            <button
                                wire:click="goBack"
                                class="mt-8 w-full px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-full hover:border-gray-400 hover:bg-gray-100 transition-all duration-300 font-medium"
                            >
                                ← Zurück
                            </button>
                        @endif
                    </div>

                    {{-- Right Column: Progressive Disclosure Content --}}
                    <div class="lg:w-2/3 p-8">
                        {{-- Step 1: Date Selection --}}
                        @if($step === 1)
                            <div>
                                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-6">Wähle ein Datum</h2>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($this->availableDates as $dateOption)
                                        <button
                                            wire:click="selectDate('{{ $dateOption['date'] }}')"
                                            class="p-4 border-2 rounded-xl text-center transition-all duration-300 hover:scale-105"
                                            :class="$wire.selectedDate === '{{ $dateOption['date'] }}' ? 'border-[#C8E6DC] bg-[#C8E6DC]/10' : 'border-gray-200 hover:border-[#C8E6DC]/50'"
                                        >
                                            <div class="text-xs text-gray-500 mb-1">{{ $dateOption['dayOfWeek'] }}</div>
                                            <div class="text-2xl font-bold text-[#1a1a1a]">{{ $dateOption['day'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $dateOption['month'] }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Step 2: Time Selection --}}
                        @if($step === 2)
                            <div>
                                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-6">Wähle eine Uhrzeit</h2>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($availableSlots as $slot)
                                        <button
                                            wire:click="selectTime('{{ $slot }}')"
                                            class="p-4 border-2 rounded-xl text-center font-medium transition-all duration-300 hover:scale-105"
                                            :class="$wire.selectedTime === '{{ $slot }}' ? 'border-[#C8E6DC] bg-[#C8E6DC]/10 text-[#1a1a1a]' : 'border-gray-200 hover:border-[#C8E6DC]/50 text-gray-700'"
                                        >
                                            {{ $slot }} Uhr
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Step 3: Contact Form --}}
                        @if($step === 3)
                            <div>
                                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-6">Deine Kontaktdaten</h2>

                                <form wire:submit.prevent="submitBooking" class="space-y-6">
                                    {{-- Name --}}
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Name *
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            wire:model="name"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#C8E6DC] focus:outline-none transition-colors"
                                            placeholder="Max Mustermann"
                                        >
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            E-Mail *
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            wire:model="email"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#C8E6DC] focus:outline-none transition-colors"
                                            placeholder="max@beispiel.de"
                                        >
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Phone --}}
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Telefon *
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            wire:model="phone"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#C8E6DC] focus:outline-none transition-colors"
                                            placeholder="+49 123 456789"
                                        >
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Message --}}
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nachricht (optional)
                                        </label>
                                        <textarea
                                            id="message"
                                            wire:model="message"
                                            rows="4"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#C8E6DC] focus:outline-none transition-colors resize-none"
                                            placeholder="Erzähl uns kurz über dein geplantes Event..."
                                        ></textarea>
                                        @error('message')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <button
                                        type="submit"
                                        class="w-full btn-primary text-lg py-4"
                                    >
                                        Termin anfragen
                                    </button>

                                    <p class="text-sm text-gray-500 text-center">
                                        * Pflichtfelder
                                    </p>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <x-footer />
</div>
