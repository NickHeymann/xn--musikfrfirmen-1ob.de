{{-- Booking Calendar Modal - Responsive Popup --}}
{{-- Modal Overlay with Blur --}}
<div
    x-data="{
        show: @entangle('isOpen'),
        showCloseConfirm: false,
        saveToStorage() {
            const formData = {
                name: $wire.name,
                company: $wire.company,
                email: $wire.email,
                phone: $wire.phone,
                message: $wire.message,
            };
            localStorage.setItem('mff-booking-data', JSON.stringify(formData));
        },
        loadFromStorage() {
            const stored = localStorage.getItem('mff-booking-data');
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    if (data.name) $wire.set('name', data.name);
                    if (data.company) $wire.set('company', data.company);
                    if (data.email) $wire.set('email', data.email);
                    if (data.phone) $wire.set('phone', data.phone);
                    if (data.message) $wire.set('message', data.message);
                } catch (e) {
                    console.error('Failed to restore booking data:', e);
                }
            }
        },
        clearStorage() {
            localStorage.removeItem('mff-booking-data');
        },
        handleClose() {
            const hasData = $wire.name || $wire.company || $wire.email || $wire.phone || $wire.message;
            const isContactForm = $wire.step === 3;
            if (hasData && isContactForm) {
                this.showCloseConfirm = true;
            } else {
                $wire.close();
            }
        },
        confirmClose() {
            this.showCloseConfirm = false;
            $wire.close();
        },
        cancelClose() {
            this.showCloseConfirm = false;
        }
    }"
    x-show="show"
    x-cloak
    @click.self="handleClose()"
    @keydown.escape.window="if (show && !showCloseConfirm) { handleClose() }"
    @clear-booking-storage.window="clearStorage()"
    @reset-success-after-close.window="
        setTimeout(() => {
            $wire.dispatch('reset-success-state');
        }, 250);
    "
    x-init="
        loadFromStorage();

        $watch('show', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
                loadFromStorage();
            } else {
                document.body.style.overflow = '';
                saveToStorage();
            }
        })
    "
    class="fixed inset-0 z-[9999] flex items-center justify-center p-2 sm:p-4 backdrop-blur-md bg-black/50"
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
            class="bg-[#1a1a1a] rounded-xl sm:rounded-2xl shadow-2xl w-full overflow-hidden max-w-5xl h-[95vh] sm:h-auto sm:max-h-[90vh] md:h-[650px] flex flex-col"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Close Button - More Visible --}}
            <button
                @click.stop="handleClose()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all duration-200 z-10 hover:rotate-90"
                aria-label="Schließen"
            >
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            @if($showSuccess)
                {{-- Success Message - Dark Theme --}}
                <div class="flex items-center justify-center md:h-[650px] h-auto py-12">
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
                <div class="flex flex-col md:grid md:grid-cols-2 md:divide-x divide-white/10 h-full overflow-hidden">
                    {{-- Left Column: Calendar --}}
                    <div class="p-4 sm:p-6 md:p-10 flex flex-col overflow-y-auto">
                        <div class="mb-4 sm:mb-6 md:mb-8">
                            <h2 class="text-base sm:text-lg md:text-xl font-semibold text-white mb-1">
                                Kostenloses Erstgespräch
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500">
                                30 Minuten
                            </p>
                        </div>

                        {{-- Month Navigation --}}
                        <div class="flex items-center justify-between mb-4 sm:mb-6 md:mb-8">
                            <button
                                wire:click="previousMonth"
                                class="p-1.5 sm:p-2 hover:bg-white/10 rounded-lg transition-colors touch-manipulation"
                                aria-label="Vorheriger Monat"
                            >
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <h3 class="text-sm sm:text-base font-medium text-white capitalize">
                                {{ $this->currentMonthName }}
                            </h3>

                            <button
                                wire:click="nextMonth"
                                class="p-1.5 sm:p-2 hover:bg-white/10 rounded-lg transition-colors touch-manipulation"
                                aria-label="Nächster Monat"
                            >
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Weekday Headers --}}
                        <div class="grid grid-cols-7 gap-1 sm:gap-1.5 md:gap-2 mb-2 md:mb-3">
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Mo</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Di</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Mi</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Do</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Fr</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">Sa</div>
                            <div class="text-center text-[10px] sm:text-xs font-medium text-gray-600 uppercase">So</div>
                        </div>

                        {{-- Calendar Grid - Cal.com Style --}}
                        <div class="grid grid-cols-7 gap-1 sm:gap-1.5 md:gap-2">
                            @foreach($this->calendarDays as $day)
                                <button
                                    wire:click="selectDate('{{ $day['date'] }}')"
                                    @if(!$day['isAvailable']) disabled @endif
                                    class="aspect-square flex items-center justify-center rounded-md text-center transition-all duration-200 text-xs sm:text-sm md:text-base font-normal touch-manipulation min-h-[40px] sm:min-h-[44px]"
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
                    <div class="p-4 sm:p-6 md:p-10 overflow-y-auto flex flex-col border-t md:border-t-0 border-white/10">
                        @if($step === 3)
                            {{-- Contact Form --}}
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-white mb-4 sm:mb-6">Ihre Kontaktdaten</h3>

                                <form wire:submit.prevent="submitBooking" class="space-y-3 sm:space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                            Name *
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            wire:model="name"
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 bg-white/5 border border-white/10 rounded-lg text-white text-sm sm:text-base placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
                                            placeholder="Max Mustermann"
                                        >
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="company" class="block text-sm font-medium text-gray-300 mb-2">
                                            Firma *
                                        </label>
                                        <input
                                            type="text"
                                            id="company"
                                            wire:model="company"
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 bg-white/5 border border-white/10 rounded-lg text-white text-sm sm:text-base placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
                                            placeholder="Firmenname GmbH"
                                        >
                                        @error('company')
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
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 bg-white/5 border border-white/10 rounded-lg text-white text-sm sm:text-base placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
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
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 bg-white/5 border border-white/10 rounded-lg text-white text-sm sm:text-base placeholder-gray-500 focus:border-[#2DD4A8] focus:outline-none transition-colors"
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
                                            wire:loading.attr="disabled"
                                            class="px-6 py-3 bg-white text-black rounded-lg font-semibold transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed active:scale-95 hover:bg-[#2DD4A8] hover:shadow-[0_0_20px_rgba(45,212,168,0.4)] hover:-translate-y-0.5"
                                        >
                                            <span wire:loading.remove wire:target="submitBooking">Termin anfragen</span>
                                            <span wire:loading wire:target="submitBooking" class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Wird gesendet...
                                            </span>
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
                                {{-- Header: Selected Date --}}
                                <div class="mb-4 sm:mb-6">
                                    <p class="text-xs sm:text-sm text-gray-400 mb-1">
                                        {{ \Carbon\Carbon::parse($selectedDate)->locale('de')->isoFormat('MMMM YYYY') }}
                                    </p>
                                    <h3 class="text-lg sm:text-xl font-light text-gray-300">
                                        {{ \Carbon\Carbon::parse($selectedDate)->locale('de')->isoFormat('ddd') }}
                                        <span class="text-xl sm:text-2xl font-normal text-white">{{ \Carbon\Carbon::parse($selectedDate)->format('d') }}</span>
                                    </h3>
                                </div>

                                {{-- Time Slots Grid - Full height scrollable --}}
                                <style>
                                    /* Custom scrollbar styling */
                                    .time-slots-container::-webkit-scrollbar {
                                        width: 6px;
                                    }
                                    .time-slots-container::-webkit-scrollbar-track {
                                        background: transparent;
                                    }
                                    .time-slots-container::-webkit-scrollbar-thumb {
                                        background: rgba(255, 255, 255, 0.2);
                                        border-radius: 3px;
                                    }
                                    .time-slots-container::-webkit-scrollbar-thumb:hover {
                                        background: rgba(255, 255, 255, 0.3);
                                    }
                                </style>
                                <div class="space-y-2 sm:space-y-3 time-slots-container overflow-y-auto pr-1 sm:pr-2" style="flex: 1; min-height: 0;">
                                    @foreach($availableSlots as $index => $slot)
                                        <button
                                            wire:click="selectTime('{{ $slot }}')"
                                            class="w-full px-3 py-3 sm:px-4 sm:py-3.5 rounded-md text-left transition-all duration-150 flex items-center justify-between group touch-manipulation min-h-[48px]"
                                            :class="$wire.selectedTime === '{{ $slot }}' ? 'bg-white text-black' : 'border border-white/10 text-gray-300 hover:border-white/20 hover:bg-white/5 active:bg-white/10'"
                                        >
                                            <span class="text-sm sm:text-base font-medium">{{ $slot }}</span>
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

        {{-- Custom Close Confirmation - Subtle & Helpful --}}
        <div x-show="showCloseConfirm"
             x-cloak
             @click.self="cancelClose()"
             class="absolute inset-0 z-[100] flex items-center justify-center bg-black/20 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.stop
                 class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm mx-4"
                 x-transition:enter="transition ease-out duration-200 delay-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <p class="text-gray-800 text-base mb-6 text-center">
                    Deine eingegebenen Daten gehen verloren. Möchtest du wirklich abbrechen?
                </p>
                <div class="flex gap-3">
                    <button @click="cancelClose()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Zurück
                    </button>
                    <button @click="confirmClose()"
                            class="flex-1 px-4 py-2.5 bg-[#2DD4A8] text-black rounded-lg font-medium hover:bg-[#7dc9b1] transition-colors">
                        Abbrechen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
