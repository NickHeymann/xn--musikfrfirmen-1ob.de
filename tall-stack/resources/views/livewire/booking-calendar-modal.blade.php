{{-- Booking Calendar Modal - Responsive Popup --}}
{{-- Modal Overlay with Blur --}}
<div
    x-data="{ show: @entangle('isOpen') }"
    x-show="show"
    x-cloak
    @click="$wire.close()"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 backdrop-blur-md bg-black/50"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="font-family: 'Poppins', sans-serif"
    >
        {{-- Modal Content - Cal.com Style Dark Theme --}}
        <div
            @click.stop
            class="bg-[#1a1a1a] rounded-2xl shadow-2xl max-w-5xl w-full overflow-hidden"
            style="height: 650px;"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Close Button --}}
            <button
                wire:click="close"
                class="absolute top-4 right-4 w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center transition-colors z-10"
                aria-label="Schließen"
            >
                <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            @if($showSuccess)
                {{-- Success Message - Dark Theme --}}
                <div class="flex items-center justify-center" style="height: 650px;">
                    <div class="text-center max-w-md px-8">
                        <svg class="w-16 h-16 text-[#2DD4A8] mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-white mb-3">Vielen Dank!</h3>
                        <p class="text-base text-gray-400 mb-8">
                            Wir haben Ihre Anfrage erhalten und melden uns in Kürze bei Ihnen.
                        </p>
                        <button
                            wire:click="close"
                            class="px-6 py-3 bg-white text-black rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                        >
                            Schließen
                        </button>
                    </div>
                </div>
            @else
                {{-- Cal.com Style: 2-Column Layout --}}
                <div class="grid md:grid-cols-2 divide-x divide-white/10" style="height: 650px;">
                    {{-- Left Column: Calendar --}}
                    <div class="p-10 flex flex-col">
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-white mb-1">
                                Kostenloses Erstgespräch
                            </h2>
                            <p class="text-sm text-gray-500">
                                30 Minuten
                            </p>
                        </div>

                        {{-- Month Navigation --}}
                        <div class="flex items-center justify-between mb-8">
                            <button
                                wire:click="previousMonth"
                                class="p-2 hover:bg-white/10 rounded-lg transition-colors"
                                aria-label="Vorheriger Monat"
                            >
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <h3 class="text-sm font-medium text-white capitalize">
                                {{ $this->currentMonthName }}
                            </h3>

                            <button
                                wire:click="nextMonth"
                                class="p-2 hover:bg-white/10 rounded-lg transition-colors"
                                aria-label="Nächster Monat"
                            >
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Weekday Headers --}}
                        <div class="grid grid-cols-7 gap-2 mb-3">
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Mo</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Di</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Mi</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Do</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Fr</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">Sa</div>
                            <div class="text-center text-xs font-medium text-gray-600 uppercase">So</div>
                        </div>

                        {{-- Calendar Grid - Cal.com Style --}}
                        <div class="grid grid-cols-7 gap-2">
                            @foreach($this->calendarDays as $day)
                                <button
                                    wire:click="selectDate('{{ $day['date'] }}')"
                                    @if(!$day['isAvailable']) disabled @endif
                                    class="aspect-square flex items-center justify-center rounded-md text-center transition-all duration-200 text-base font-normal"
                                    :class="{
                                        'bg-white text-black font-medium': $wire.selectedDate === '{{ $day['date'] }}',
                                        'hover:bg-white/5 cursor-pointer text-gray-300': {{ $day['isAvailable'] ? 'true' : 'false' }} && $wire.selectedDate !== '{{ $day['date'] }}',
                                        'text-gray-700 cursor-not-allowed': !{{ $day['isAvailable'] ? 'true' : 'false' }},
                                        'opacity-30': !{{ $day['isCurrentMonth'] ? 'true' : 'false' }}
                                    }"
                                >
                                    {{ $day['day'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right Column: Time Slots & Contact Form --}}
                    <div class="p-10 overflow-y-auto flex flex-col">
                        @if($step === 3)
                            {{-- Contact Form --}}
                            {{-- Contact Form --}}
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-6">Ihre Kontaktdaten</h3>

                                <form wire:submit.prevent="submitBooking" class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                            Name *
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            wire:model="name"
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
                                            placeholder="Max Mustermann"
                                        >
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                            E-Mail *
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            wire:model="email"
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
                                            placeholder="max@beispiel.de"
                                        >
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                            Telefon *
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            wire:model="phone"
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
                                            placeholder="+49 123 456789"
                                        >
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                                            Nachricht (optional)
                                        </label>
                                        <textarea
                                            id="message"
                                            wire:model="message"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors resize-none"
                                            placeholder="Erzählen Sie uns kurz über Ihr geplantes Event..."
                                        ></textarea>
                                        @error('message')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-center justify-between pt-4">
                                        <button
                                            type="button"
                                            wire:click="goBack"
                                            class="text-sm text-gray-400 hover:text-white flex items-center gap-2 transition-colors"
                                        >
                                            ← Zurück
                                        </button>

                                        <button
                                            type="submit"
                                            class="px-6 py-3 bg-white text-black rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                                        >
                                            Termin anfragen
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            {{-- Time Slots View --}}
                            @if(!$selectedDate)
                                {{-- Empty State --}}
                                <div class="flex items-center justify-center h-full text-gray-600 text-center text-sm">
                                    <p>Wählen Sie ein Datum</p>
                                </div>
                            @else
                                {{-- Date Display --}}
                                <div class="mb-6 pb-6 border-b border-white/10">
                                    <h3 class="text-base font-medium text-white mb-1">
                                        {{ \Carbon\Carbon::parse($selectedDate)->locale('de')->isoFormat('ddd, D. MMM') }}
                                    </h3>
                                    <p class="text-xs text-gray-500">Wählen Sie eine Uhrzeit</p>
                                </div>

                                {{-- Time Slots Grid - Scrollable --}}
                                <div class="space-y-3 overflow-y-auto flex-1 pr-2" style="max-height: 480px;">
                                    @foreach($availableSlots as $slot)
                                        <button
                                            wire:click="selectTime('{{ $slot }}')"
                                            class="w-full px-4 py-3 rounded-md text-left transition-all duration-150 flex items-center justify-between group"
                                            :class="$wire.selectedTime === '{{ $slot }}' ? 'bg-white text-black' : 'border border-white/10 text-gray-300 hover:border-white/20 hover:bg-white/5'"
                                        >
                                            <span class="text-sm font-medium">{{ $slot }}</span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2DD4A8]" x-show="$wire.selectedTime !== '{{ $slot }}'"></span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
