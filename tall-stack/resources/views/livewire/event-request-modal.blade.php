<div
    x-data="{
        showModal: @entangle('showModal'),
        openModal() {
            this.showModal = true;
        },
        citySuggestions: [],
        showSuggestions: false,
        async fetchCities(query) {
            if (query.length < 2) {
                this.citySuggestions = [];
                this.showSuggestions = false;
                return;
            }
            try {
                const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5&lang=de&layer=city&osm_tag=place:city&osm_tag=place:town&bbox=5.87,47.27,15.04,55.06`;
                const response = await fetch(url);
                const data = await response.json();
                if (data.features?.length > 0) {
                    this.citySuggestions = data.features
                        .filter(f => f.properties.country === 'Germany' || f.properties.countrycode === 'DE')
                        .map(f => ({
                            city: f.properties.name,
                            state: f.properties.state || f.properties.county || ''
                        }));
                    this.showSuggestions = this.citySuggestions.length > 0;
                } else {
                    this.citySuggestions = [];
                    this.showSuggestions = false;
                }
            } catch (e) {
                this.citySuggestions = [];
                this.showSuggestions = false;
            }
        },
        selectCity(city) {
            $wire.set('city', city.city);
            this.citySuggestions = [];
            this.showSuggestions = false;
        },
        hideSuggestions() {
            setTimeout(() => this.showSuggestions = false, 200);
        }
    }"
    x-init="
        $watch('showModal', (value) => {
            document.body.style.overflow = value ? 'hidden' : '';
        });

        // Prevent scroll wheel on date/time inputs (UX improvement)
        document.addEventListener('wheel', function(e) {
            if (e.target.type === 'date' || e.target.type === 'time' || e.target.type === 'number') {
                e.target.blur();
            }
        }, { passive: true });
    "
    x-on:open-calcom.window="window.open($event.detail.url, '_blank')"
    x-on:open-m-f-f-calculator.window="openModal()"
    @keydown.escape.window="if (showModal) $wire.closeModal()"
    style="font-family: 'Poppins', sans-serif"
>
    {{-- Modal Overlay --}}
    <div
        x-show="showModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[2147483647] flex items-center justify-center p-2.5"
        style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(8px); display: none;"
        @click.self="$wire.closeModal()"
    >
        {{-- Modal Content - Dark Mode --}}
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-5"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative bg-[#1a1a1a] rounded-2xl max-w-[800px] w-full max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.3)]"
        >
            {{-- Close Button --}}
            <button
                type="button"
                wire:click="closeModal"
                class="absolute top-5 right-5 bg-white/5/10 border-none rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-gray-400 hover:bg-white/5/20 hover:text-white hover:rotate-90"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Content --}}
            <div class="w-full p-4 box-border text-white leading-relaxed">
                <div class="bg-[#1a1a1a] rounded-xl p-6 md:px-[30px] relative">
                    {{-- Back Arrow --}}
                    @if ($step > 1 && $submitStatus !== 'success')
                        <button
                            type="button"
                            wire:click="prevStep"
                            class="absolute top-4 left-4 bg-white/10 border-none rounded-full w-8 h-8 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-gray-300 hover:bg-white/20 hover:-translate-x-[2px]"
                        >
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    @endif

                    {{-- Header --}}
                    @if ($submitStatus !== 'success')
                        <div class="text-center mb-6">
                            <h2 class="text-[26px] font-normal m-0 mb-2 text-white">
                                Deine Wünsche<br />für ein unvergessliches Event
                            </h2>
                            <p class="text-sm font-light text-gray-400 m-0">
                                Teile uns deine Anforderungen mit und erhalte innerhalb von 24 Stunden ein unverbindliches Angebot.
                            </p>
                        </div>
                    @endif

                    {{-- Step 1: Event Details --}}
                    @if ($step === 1)
                        <div class="mb-4">
                            <div class="flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
                                <span class="inline-flex items-center justify-center w-7 h-7 bg-[#2DD4A8] text-gray-300 rounded-full text-[13px] font-semibold">1</span>
                                <span>Event-Details</span>
                            </div>

                            {{-- Date & Time Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[12px] mb-[8px]">
                                <div class="flex flex-col gap-[4px]"
                                     x-data="{
                                        day: '',
                                        month: '',
                                        year: '',
                                        init() {
                                            // Initialize from wire:model if date exists
                                            if ($wire.date) {
                                                const parts = $wire.date.split('-');
                                                this.year = parts[0];
                                                this.month = parts[1];
                                                this.day = parts[2];
                                            }
                                        },
                                        updateDate() {
                                            // Auto-format and combine
                                            if (this.day.length === 2 && this.month && this.year.length === 4) {
                                                const date = `${this.year}-${this.month.padStart(2, '0')}-${this.day.padStart(2, '0')}`;

                                                // Validierung: Max 5 Jahre in die Zukunft
                                                const selectedDate = new Date(date);
                                                const today = new Date();
                                                const maxDate = new Date();
                                                maxDate.setFullYear(today.getFullYear() + 5);

                                                if (selectedDate <= maxDate) {
                                                    $wire.set('date', date);
                                                } else {
                                                    // Datum zu weit in der Zukunft - auf max 5 Jahre begrenzen
                                                    const maxYear = today.getFullYear() + 5;
                                                    this.year = maxYear.toString();
                                                }
                                            }
                                        },
                                        openCalendar() {
                                            $refs.hiddenDateInput.showPicker();
                                        }
                                     }">
                                    <div class="flex items-center gap-2">
                                        <label class="text-[13px] font-normal text-white">Datum *</label>
                                        <button
                                            type="button"
                                            @click="openCalendar()"
                                            class="p-1 rounded hover:bg-white/10 transition-colors"
                                            aria-label="Kalender öffnen">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        {{-- Tag (DD) --}}
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            placeholder="TT"
                                            maxlength="2"
                                            x-model="day"
                                            @input="
                                                day = day.replace(/[^0-9]/g, '');
                                                if (day.length === 2 && parseInt(day) >= 1 && parseInt(day) <= 31) {
                                                    $refs.month.focus();
                                                }
                                                updateDate();
                                            "
                                            @wheel="$event.target.blur()"
                                            class="w-16 p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('date') border-red-600 @else border-white/10 @enderror"
                                        />
                                        <span class="text-white self-center">/</span>

                                        {{-- Monat (MM) --}}
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            placeholder="MM"
                                            maxlength="2"
                                            x-model="month"
                                            x-ref="month"
                                            @input="
                                                month = month.replace(/[^0-9]/g, '');
                                                if (month.length === 2 && parseInt(month) >= 1 && parseInt(month) <= 12) {
                                                    $refs.year.focus();
                                                }
                                                updateDate();
                                            "
                                            @wheel="$event.target.blur()"
                                            class="w-16 p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('date') border-red-600 @else border-white/10 @enderror"
                                        />
                                        <span class="text-white self-center">/</span>

                                        {{-- Jahr (YYYY) --}}
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            placeholder="JJJJ"
                                            maxlength="4"
                                            x-model="year"
                                            x-ref="year"
                                            @input="
                                                year = year.replace(/[^0-9]/g, '');
                                                updateDate();
                                            "
                                            @wheel="$event.target.blur()"
                                            class="w-20 p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('date') border-red-600 @else border-white/10 @enderror"
                                        />

                                        {{-- Hidden native date input for calendar picker --}}
                                        <input
                                            type="date"
                                            x-ref="hiddenDateInput"
                                            wire:model.live="date"
                                            @change="
                                                if ($wire.date) {
                                                    const parts = $wire.date.split('-');
                                                    year = parts[0];
                                                    month = parts[1];
                                                    day = parts[2];
                                                }
                                            "
                                            min="{{ date('Y-m-d') }}"
                                            max="{{ date('Y-m-d', strtotime('+5 years')) }}"
                                            class="absolute opacity-0 pointer-events-none"
                                            style="width: 0; height: 0;"
                                        />
                                    </div>
                                    @error('date')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]"
                                     x-data="{
                                        hours: '',
                                        minutes: '',
                                        init() {
                                            // Initialize from wire:model if time exists
                                            if ($wire.time) {
                                                const parts = $wire.time.split(':');
                                                this.hours = parts[0];
                                                this.minutes = parts[1];
                                            }
                                        },
                                        updateTime() {
                                            // Auto-format and combine
                                            if (this.hours.length === 2 && this.minutes.length === 2) {
                                                const time = `${this.hours.padStart(2, '0')}:${this.minutes.padStart(2, '0')}`;
                                                $wire.set('time', time);
                                            } else if (this.hours === '' && this.minutes === '') {
                                                $wire.set('time', '');
                                            }
                                        },
                                        openTimePicker() {
                                            $refs.hiddenTimeInput.showPicker();
                                        }
                                     }">
                                    <div class="flex items-center gap-2">
                                        <label class="text-[13px] font-normal text-white">Startzeit Event (optional)</label>
                                        <button
                                            type="button"
                                            @click="openTimePicker()"
                                            class="p-1 rounded hover:bg-white/10 transition-colors"
                                            aria-label="Uhrzeit wählen">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        {{-- Stunden (HH) --}}
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            placeholder="HH"
                                            maxlength="2"
                                            x-model="hours"
                                            @input="
                                                hours = hours.replace(/[^0-9]/g, '');
                                                if (hours.length === 2 && parseInt(hours) >= 0 && parseInt(hours) <= 23) {
                                                    $refs.minutes.focus();
                                                }
                                                updateTime();
                                            "
                                            @wheel="$event.target.blur()"
                                            class="w-16 p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] border-white/10"
                                        />
                                        <span class="text-white self-center">:</span>

                                        {{-- Minuten (MM) --}}
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            placeholder="MM"
                                            maxlength="2"
                                            x-model="minutes"
                                            x-ref="minutes"
                                            @input="
                                                minutes = minutes.replace(/[^0-9]/g, '');
                                                if (minutes.length === 2 && parseInt(minutes) > 59) {
                                                    minutes = '59';
                                                }
                                                updateTime();
                                            "
                                            @wheel="$event.target.blur()"
                                            class="w-16 p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] border-white/10"
                                        />

                                        {{-- Hidden native time input for time picker --}}
                                        <input
                                            type="time"
                                            x-ref="hiddenTimeInput"
                                            wire:model.live="time"
                                            @change="
                                                if ($wire.time) {
                                                    const parts = $wire.time.split(':');
                                                    hours = parts[0];
                                                    minutes = parts[1];
                                                }
                                            "
                                            class="absolute opacity-0 pointer-events-none"
                                            style="width: 0; height: 0;"
                                        />
                                    </div>
                                </div>
                            </div>

                            {{-- City & Budget Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px] relative">
                                    <label for="mff-city" class="text-[13px] font-normal text-white">Stadt *</label>
                                    <input
                                        type="text"
                                        id="mff-city"
                                        wire:model="city"
                                        x-on:input="fetchCities($event.target.value)"
                                        x-on:blur="hideSuggestions()"
                                        placeholder="z.B. Hamburg"
                                        autocomplete="off"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('city') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('city')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror

                                    {{-- City Autocomplete Dropdown --}}
                                    <div
                                        x-show="showSuggestions && citySuggestions.length > 0"
                                        x-transition
                                        class="absolute top-full left-0 right-0 bg-white/5/5 border-2 border-white/10 border-t-0 rounded-b-xl max-h-[200px] overflow-y-auto z-[1000] shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
                                    >
                                        <template x-for="(city, index) in citySuggestions" :key="index">
                                            <div
                                                @click="selectCity(city)"
                                                class="p-[10px_12px] cursor-pointer text-[13px] font-light border-b border-[#f0f0f0] last:border-b-0 transition-colors duration-200 hover:bg-[#2DD4A8]"
                                            >
                                                <span class="font-normal text-white" x-text="city.city"></span>
                                                <span x-show="city.state" class="text-xs text-gray-400 ml-2" x-text="city.state"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-budget" class="text-[13px] font-normal text-white">Budget (optional)</label>
                                    <input
                                        type="text"
                                        id="mff-budget"
                                        wire:model="budget"
                                        placeholder="z.B. 5.000 Euro"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 border-white/10 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)]"
                                    />
                                </div>
                            </div>

                            {{-- Guests --}}
                            <div class="flex flex-col gap-[4px] mb-[8px]">
                                <label for="mff-guests" class="text-[13px] font-normal text-white">Anzahl Gäste *</label>
                                <select
                                    id="mff-guests"
                                    wire:model="guests"
                                    class="w-full p-2 px-[10px] pr-14 text-sm font-light border-2 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('guests') border-red-600 @else border-white/10 @enderror appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%23fff\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'m6 8 4 4 4-4\'/%3E%3C/svg%3E')] bg-[length:1.25rem] bg-[right_2rem_center] bg-no-repeat"
                                >
                                    <option value="">Bitte wählen...</option>
                                    @foreach ($guestOptions as $opt)
                                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('guests')
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Next Button --}}
                            <div class="grid grid-cols-1 gap-3 mt-4">
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#2DD4A8] text-black hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
                                >
                                    Weiter
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Step 2: Package Selection --}}
                    @if ($step === 2)
                        <div class="mb-4">
                            <div class="flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
                                <span class="inline-flex items-center justify-center w-7 h-7 bg-[#2DD4A8] text-gray-300 rounded-full text-[13px] font-semibold">2</span>
                                <span>Paket-Auswahl</span>
                            </div>

                            <div class="grid gap-[10px]">
                                @foreach ($packageOptions as $pkg)
                                    <label
                                        for="package-{{ $pkg['value'] }}"
                                        class="relative flex items-center p-[14px_18px] border-2 rounded-[10px] cursor-pointer transition-all duration-200 hover:border-[#B2EAD8] hover:bg-[#f8f8f8] hover:-translate-y-[2px] hover:shadow-[0_2px_12px_rgba(0,0,0,0.08)] {{ $package === $pkg['value'] ? 'border-[#B2EAD8] bg-[#f8f8f8]' : 'border-white/10' }}"
                                    >
                                        <input
                                            type="radio"
                                            id="package-{{ $pkg['value'] }}"
                                            name="package"
                                            value="{{ $pkg['value'] }}"
                                            wire:model.live="package"
                                            class="absolute opacity-0 pointer-events-none"
                                        />
                                        <span class="relative w-5 h-5 border-2 rounded-full mr-3 transition-all duration-200 {{ $package === $pkg['value'] ? 'border-[#B2EAD8] bg-[#2DD4A8]' : 'border-white/10' }}">
                                            @if ($package === $pkg['value'])
                                                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-[#292929]"></span>
                                            @endif
                                        </span>
                                        <span class="text-sm font-light">{{ $pkg['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Next Button --}}
                            <div class="grid grid-cols-1 gap-3 mt-4">
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    @if (!$package) disabled @endif
                                    class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#2DD4A8] text-gray-300 hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
                                >
                                    Weiter
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Step 3: Contact Details --}}
                    @if ($step === 3 && $submitStatus !== 'success')
                        <form wire:submit="submit" class="mb-4">
                            <div class="flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
                                <span class="inline-flex items-center justify-center w-7 h-7 bg-[#2DD4A8] text-gray-300 rounded-full text-[13px] font-semibold">3</span>
                                <span>Deine Kontaktdaten</span>
                            </div>

                            {{-- Name & Company Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-name" class="text-[13px] font-normal text-white">Name *</label>
                                    <input
                                        type="text"
                                        id="mff-name"
                                        wire:model="name"
                                        placeholder="Dein Name"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('name') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('name')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-company" class="text-[13px] font-normal text-white">Firma (optional)</label>
                                    <input
                                        type="text"
                                        id="mff-company"
                                        wire:model="company"
                                        placeholder="Deine Firma"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 border-white/10 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)]"
                                    />
                                </div>
                            </div>

                            {{-- Email & Phone Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-email" class="text-[13px] font-normal text-white">E-Mail *</label>
                                    <input
                                        type="email"
                                        id="mff-email"
                                        wire:model="email"
                                        placeholder="deine@email.de"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('email') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('email')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-phone" class="text-[13px] font-normal text-white">Telefon *</label>
                                    <input
                                        type="tel"
                                        id="mff-phone"
                                        wire:model="phone"
                                        placeholder="+49 123 456789"
                                        class="w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] @error('phone') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('phone')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="flex flex-col gap-[4px] mb-[8px]">
                                <label for="mff-message" class="text-[13px] font-normal text-white">Nachricht (optional)</label>
                                <textarea
                                    id="mff-message"
                                    wire:model="message"
                                    placeholder="Weitere Details zu deinem Event..."
                                    class="w-full p-2 px-[10px] text-sm font-light border-2 border-white/10 rounded-[10px] bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#2DD4A8] focus:shadow-[0_0_0_4px_rgba(45,212,168,0.1)] min-h-[50px] resize-y"
                                ></textarea>
                            </div>

                            {{-- Privacy Checkbox --}}
                            <div class="flex items-start gap-2 mt-2 p-2 bg-[#f8f8f8] rounded-lg">
                                <input
                                    type="checkbox"
                                    id="mff-privacy"
                                    wire:model="privacy"
                                    class="w-4 h-4 mt-[2px] cursor-pointer accent-[#B2EAD8]"
                                />
                                <label for="mff-privacy" class="text-[11px] font-light cursor-pointer leading-[1.4]">
                                    Ich habe die
                                    <a href="/datenschutz" target="_blank" class="text-[#B2EAD8] underline font-normal hover:text-[#7dc9b1]">Datenschutzerklärung</a>
                                    gelesen und akzeptiert. *
                                </label>
                            </div>
                            @error('privacy')
                                <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror

                            {{-- Buttons --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                <button
                                    type="button"
                                    wire:click="openCalCom"
                                    class="p-[10px_20px] text-sm font-normal rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-white/5 text-white border-2 border-[#1a1a1a] hover:bg-[#1a1a1a] hover:text-white hover:-translate-y-[2px] hover:shadow-[0_2px_12px_rgba(0,0,0,0.08)]"
                                >
                                    Gespräch buchen
                                </button>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#2DD4A8] text-gray-300 hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
                                >
                                    <span wire:loading.remove wire:target="submit">Anfrage absenden</span>
                                    <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Wird gesendet...
                                    </span>
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Success State --}}
                    @if ($submitStatus === 'success')
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                                Anfrage gesendet!
                            </h3>
                            <p class="text-gray-600 mb-2">
                                Vielen Dank! Wir melden uns in Kürze bei dir.
                            </p>
                            <p class="text-gray-600 mb-8">
                                In 48 Stunden erhältst du ein detailliertes Angebot.
                            </p>
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer bg-[#2DD4A8] text-gray-300 hover:bg-[#7dc9b1]"
                            >
                                Alles klar!
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
