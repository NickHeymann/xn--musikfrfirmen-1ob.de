<div
    x-data="{
        showModal: @entangle('showModal'),
        showConfirmDialog: false,
        openModal() {
            this.showModal = true;
        },
        hasData() {
            // Check if user has entered any data
            return $wire.date || $wire.city || $wire.budget || $wire.guests || $wire.package ||
                   $wire.firstname || $wire.lastname || $wire.company || $wire.email || $wire.phone;
        },
        closeWithConfirmation() {
            if (this.hasData()) {
                this.showConfirmDialog = true;
            } else {
                $wire.closeModal();
            }
        },
        citySuggestions: [],
        showSuggestions: false,
        citySelected: false,
        async fetchCities(query) {
            this.citySelected = false;
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
            this.citySelected = true;

            // Auto-navigate to budget field
            setTimeout(() => {
                const budgetInput = document.getElementById('mff-budget');
                if (budgetInput) budgetInput.focus();
            }, 100);
        },
        hideSuggestions() {
            setTimeout(() => this.showSuggestions = false, 200);
        },
        // LocalStorage persistence for form data
        saveToStorage() {
            // Only save if user has given consent
            if (!$wire.storageConsent) {
                return;
            }

            const formData = {
                date: $wire.date,
                time: $wire.time,
                city: $wire.city,
                budget: $wire.budget,
                guests: $wire.guests,
                package: $wire.package,
                firstname: $wire.firstname,
                lastname: $wire.lastname,
                company: $wire.company,
                email: $wire.email,
                phone: $wire.phone,
                message: $wire.message,
                // Store consent state
                storageConsent: $wire.storageConsent,
            };
            localStorage.setItem('mff-calculator-data', JSON.stringify(formData));
        },
        loadFromStorage() {
            const stored = localStorage.getItem('mff-calculator-data');
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    // Restore consent state first
                    if (data.storageConsent !== undefined) {
                        $wire.set('storageConsent', data.storageConsent);
                    }

                    // Only restore form data if consent was given
                    if (data.storageConsent) {
                        if (data.date) $wire.set('date', data.date);
                        if (data.time) $wire.set('time', data.time);
                        if (data.city) $wire.set('city', data.city);
                        if (data.budget) $wire.set('budget', data.budget);
                        if (data.guests) $wire.set('guests', data.guests);
                        if (data.package) $wire.set('package', data.package);
                        if (data.firstname) $wire.set('firstname', data.firstname);
                        if (data.lastname) $wire.set('lastname', data.lastname);
                        if (data.company) $wire.set('company', data.company);
                        if (data.email) $wire.set('email', data.email);
                        if (data.phone) $wire.set('phone', data.phone);
                        if (data.message) $wire.set('message', data.message);
                    }

                    // ALWAYS reset to Step 1 when reopening (user reassurance pattern)
                    $wire.set('step', 1);
                } catch (e) {
                    console.error('Failed to restore calculator data:', e);
                }
            }
        },
        clearStorage() {
            localStorage.removeItem('mff-calculator-data');
            $wire.set('storageConsent', false);
        }
    }"
    x-init="
        // Load saved data when component initializes
        loadFromStorage();

        $watch('showModal', (value) => {
            document.body.style.overflow = value ? 'hidden' : '';

            // Load data when modal opens
            if (value) {
                loadFromStorage();
            } else {
                // Save data when modal closes
                saveToStorage();
            }
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
    @clear-calculator-storage.window="clearStorage()"
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
        @click.self="closeWithConfirmation()"
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
            class="relative bg-[#1a1a1a] rounded-xl sm:rounded-2xl shadow-2xl w-full overflow-hidden max-w-5xl h-[95vh] sm:h-auto sm:max-h-[90vh] flex flex-col"
        >
            {{-- Close Button --}}
            <button
                type="button"
                @click="closeWithConfirmation()"
                class="absolute top-5 right-5 bg-white/5/10 border-none rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-gray-400 hover:bg-white/5/20 hover:text-white hover:rotate-90"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Confirm Dialog Overlay --}}
            <div
                x-show="showConfirmDialog"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 z-20 flex items-center justify-center bg-black/60 rounded-xl sm:rounded-2xl"
                style="display: none;"
            >
                <div class="bg-[#2a2a2a] border border-white/10 rounded-xl p-6 max-w-sm mx-4 text-center shadow-2xl">
                    <p class="text-white text-base font-normal mb-2">Abbrechen?</p>
                    <p class="text-gray-400 text-sm font-light mb-6">Du hast bereits Daten eingegeben. Möchtest du wirklich abbrechen?</p>
                    <div class="flex gap-3 justify-center">
                        <button
                            type="button"
                            @click="showConfirmDialog = false"
                            class="px-5 py-2 text-sm font-normal border border-white/10 rounded-lg bg-white/5 text-white hover:bg-white/10 transition-colors cursor-pointer"
                        >
                            Zurück
                        </button>
                        <button
                            type="button"
                            @click="showConfirmDialog = false; $wire.closeModal()"
                            class="px-5 py-2 text-sm font-normal border-none rounded-lg bg-red-500/80 text-white hover:bg-red-500 transition-colors cursor-pointer"
                        >
                            Ja, abbrechen
                        </button>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="w-full p-4 sm:p-6 md:p-10 box-border text-white leading-relaxed overflow-y-auto flex-1 min-h-0 flex flex-col">
                <div class="bg-[#1a1a1a] rounded-xl relative max-w-4xl mx-auto flex-1 flex flex-col">
                    {{-- Back Arrow --}}
                    @if ($step > 1 && $submitStatus !== 'success')
                        <button
                            type="button"
                            wire:click="prevStep"
                            class="absolute top-0 left-0 bg-white/10 border-none rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-gray-300 hover:bg-white/20 hover:-translate-x-[2px]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    @endif

                    {{-- Header --}}
                    @if ($submitStatus !== 'success')
                        <div class="text-center mb-4">
                            <div class="text-2xl md:text-3xl font-normal m-0 mb-4 text-white">
                                <span class="block whitespace-nowrap">Deine Wünsche</span>
                                <span class="block whitespace-nowrap text-xl md:text-2xl">für ein unvergessliches Event</span>
                            </div>
                            <p class="text-base md:text-lg font-light text-gray-400 m-0">
                                Teile uns deine Anforderungen mit und erhalte innerhalb von 24 Stunden ein unverbindliches Angebot.
                            </p>
                        </div>
                    @endif

                    {{-- Step 1: Event Details --}}
                    @if ($step === 1)
                        <div>
                            <div class="flex items-center gap-4 text-lg font-normal mb-4">
                                <span class="inline-flex items-center justify-center w-9 h-9 bg-[#C8E6DC] text-black rounded-full text-base font-semibold">1</span>
                                <span class="text-[#C8E6DC]">Event-Details</span>
                            </div>

                            {{-- Date & Time Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <div class="flex flex-col gap-[4px]"
                                     x-data="{
                                        day: '',
                                        month: '',
                                        year: '',
                                        yearSuffix: '',
                                        init() {
                                            // Initialize from wire:model if date exists
                                            if ($wire.date) {
                                                const parts = $wire.date.split('-');
                                                this.year = parts[0];
                                                this.yearSuffix = parts[0] ? parts[0].substring(2) : '';
                                                this.month = parts[1];
                                                this.day = parts[2];
                                            }
                                        },
                                        getDaysInMonth(month, year) {
                                            if (!month || month < 1 || month > 12) return 31;
                                            const actualYear = year || new Date().getFullYear();
                                            return new Date(actualYear, month, 0).getDate();
                                        },
                                        adjustDayToMonth() {
                                            // Only adjust if we have both month and day
                                            if (this.month && this.day) {
                                                const monthNum = parseInt(this.month);
                                                const dayNum = parseInt(this.day);
                                                const yearNum = this.year ? parseInt(this.year) : new Date().getFullYear();
                                                const maxDays = this.getDaysInMonth(monthNum, yearNum);

                                                // If day exceeds max days for this month, adjust it
                                                if (dayNum > maxDays) {
                                                    this.day = maxDays.toString().padStart(2, '0');
                                                    this.updateDate();
                                                }
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
                                            class="p-1.5 rounded hover:bg-white/10 transition-colors"
                                            aria-label="Kalender öffnen">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            @focus="$event.target.select()"
                                            @input="
                                                day = day.replace(/[^0-9]/g, '');
                                                // Limit to max 31
                                                if (day.length === 2) {
                                                    let dayNum = parseInt(day);
                                                    if (dayNum > 31) {
                                                        day = '31';
                                                        dayNum = 31;
                                                    } else if (dayNum < 1) {
                                                        day = '01';
                                                        dayNum = 1;
                                                    }
                                                    // Auto-advance to month after capping
                                                    $refs.month.focus();
                                                }
                                                updateDate();
                                            "
                                            @keydown.enter.prevent="$wire.nextStep()"
                                            @wheel="$event.target.blur()"
                                            class="w-20 p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('date') border-red-400/60 @else border-white/10 @enderror"
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
                                            @focus="$event.target.select()"
                                            @input="
                                                month = month.replace(/[^0-9]/g, '');
                                                // Limit to max 12
                                                if (month.length === 2) {
                                                    let monthNum = parseInt(month);
                                                    if (monthNum > 12) {
                                                        month = '12';
                                                        monthNum = 12;
                                                    } else if (monthNum < 1) {
                                                        month = '01';
                                                        monthNum = 1;
                                                    }
                                                    // Auto-advance to year after capping
                                                    $refs.year.focus();
                                                }
                                                updateDate();
                                            "
                                            @blur="adjustDayToMonth()"
                                            @keydown.enter.prevent="$wire.nextStep()"
                                            @wheel="$event.target.blur()"
                                            class="w-20 p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white text-center transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('date') border-red-400/60 @else border-white/10 @enderror"
                                        />
                                        <span class="text-white self-center">/</span>

                                        {{-- Jahr (20YY) --}}
                                        <div class="relative w-20">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-light text-white pointer-events-none select-none" style="font-family: inherit; letter-spacing: inherit;">20</span>
                                            <input
                                                type="text"
                                                inputmode="numeric"
                                                placeholder="YY"
                                                maxlength="2"
                                                x-model="yearSuffix"
                                                x-ref="year"
                                                x-init="yearSuffix = year ? year.substring(2) : ''"
                                                @focus="$event.target.select()"
                                                @input="
                                                    yearSuffix = yearSuffix.replace(/[^0-9]/g, '');
                                                    year = '20' + yearSuffix.padEnd(2, '');
                                                    updateDate();
                                                    // Auto-navigate to hours field when year is complete
                                                    if (yearSuffix.length === 2) {
                                                        const hoursInput = document.querySelector('input[placeholder=HH]');
                                                        if (hoursInput) hoursInput.focus();
                                                    }
                                                "
                                                @keydown.tab.prevent="
                                                    const hoursInput = document.querySelector('input[placeholder=HH]');
                                                    if (hoursInput) hoursInput.focus();
                                                "
                                                @keydown.enter.prevent="$wire.nextStep()"
                                                @wheel="$event.target.blur()"
                                                class="w-full p-2.5 pl-8 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white text-left transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('date') border-red-400/60 @else border-white/10 @enderror"
                                            />
                                        </div>

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
                                        <span class="text-xs text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label class="text-[13px] font-normal text-white">Startzeit (optional)</label>
                                    <div class="flex gap-2">
                                        @foreach([
                                            ['value' => '08:00-12:00', 'label' => 'Morgens', 'sub' => '08–12 Uhr'],
                                            ['value' => '12:00-16:00', 'label' => 'Mittags', 'sub' => '12–16 Uhr'],
                                            ['value' => '16:00-20:00', 'label' => 'Abends', 'sub' => '16–20 Uhr'],
                                        ] as $slot)
                                            <button
                                                type="button"
                                                @click="$wire.time === '{{ $slot['value'] }}' ? $wire.set('time', '') : $wire.set('time', '{{ $slot['value'] }}')"
                                                class="flex-1 py-2 px-1 text-center border-2 rounded-xl transition-all duration-200 cursor-pointer"
                                                :class="$wire.time === '{{ $slot['value'] }}' ? 'border-[#C8E6DC] bg-[#C8E6DC]/15 text-white' : 'border-white/10 bg-white/5 text-white hover:border-white/20'"
                                            >
                                                <span class="block text-sm font-normal">{{ $slot['label'] }}</span>
                                                <span class="block text-[11px] font-light text-white/60">{{ $slot['sub'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- City & Budget Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px] relative" wire:ignore.self>
                                    <label for="mff-city" class="text-[13px] font-normal text-white">Stadt *</label>
                                    <input
                                        type="text"
                                        id="mff-city"
                                        wire:model.blur="city"
                                        x-on:input="fetchCities($event.target.value)"
                                        x-on:blur="hideSuggestions()"
                                        @focus="$event.target.select(); if (citySuggestions.length > 0 && !citySelected) showSuggestions = true;"
                                        @keydown.tab.prevent="
                                            const budgetInput = document.getElementById('mff-budget');
                                            if (budgetInput) budgetInput.focus();
                                        "
                                        @keydown.enter.prevent="
                                            if (citySuggestions.length > 0) {
                                                selectCity(citySuggestions[0]);
                                            } else if ($event.target.value.length >= 2) {
                                                // User typed city name without selecting from dropdown - close suggestions
                                                showSuggestions = false;
                                                citySuggestions = [];
                                                const budgetInput = document.getElementById('mff-budget');
                                                if (budgetInput) budgetInput.focus();
                                            }
                                        "
                                        placeholder="z.B. Hamburg"
                                        autocomplete="off"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('city') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('city')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror

                                    {{-- City Autocomplete Dropdown --}}
                                    <div
                                        x-show="showSuggestions && citySuggestions.length > 0"
                                        x-transition
                                        class="absolute top-full left-0 right-0 bg-[#1a1a1a] border-2 border-white/10 border-t-0 rounded-b-xl max-h-[200px] overflow-y-auto z-[1000] shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
                                    >
                                        <template x-for="(city, index) in citySuggestions" :key="index">
                                            <div
                                                @click="selectCity(city)"
                                                class="p-[10px_12px] cursor-pointer text-[13px] font-light border-b border-[#f0f0f0] last:border-b-0 transition-colors duration-200 hover:bg-[#C8E6DC]"
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
                                        @focus="$event.target.select()"
                                        @keydown.tab.prevent="
                                            const guestsSelect = document.getElementById('mff-guests');
                                            if (guestsSelect) {
                                                guestsSelect.focus();
                                            }
                                        "
                                        @keydown.enter.prevent="
                                            const guestsSelect = document.getElementById('mff-guests');
                                            if (guestsSelect) {
                                                guestsSelect.focus();
                                            }
                                        "
                                        placeholder="z.B. 5.000 Euro"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 border-white/10 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)]"
                                    />
                                </div>
                            </div>

                            {{-- Guests (Alpine.js Dropdown) --}}
                            <div class="flex flex-col gap-[4px] mb-[8px]">
                                <label for="mff-guests" class="text-[13px] font-normal text-white">Anzahl Gäste *</label>

                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button
                                        type="button"
                                        id="mff-guests"
                                        @click="open = !open"
                                        class="w-full p-2.5 px-3 pr-14 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('guests') border-red-600 @else border-white/10 @enderror text-left"
                                    >
                                        @if($guests)
                                            <span>{{ collect($guestOptions)->firstWhere('value', $guests)['label'] ?? 'Bitte wählen...' }}</span>
                                        @else
                                            <span class="text-gray-400">Bitte wählen...</span>
                                        @endif
                                    </button>

                                    {{-- Dropdown Arrow --}}
                                    <div class="absolute right-[2rem] top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg
                                            class="w-5 h-5 text-white transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m6 8 4 4 4-4" />
                                        </svg>
                                    </div>

                                    {{-- Dropdown Options --}}
                                    <div
                                        x-show="open"
                                        x-transition
                                        class="absolute top-full left-0 right-0 mt-1 bg-[#1a1a1a] border-2 border-white/10 rounded-[10px] max-h-[200px] overflow-y-auto z-[1000] shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
                                    >
                                        @foreach($guestOptions as $option)
                                            <div
                                                @click="$wire.set('guests', '{{ $option['value'] }}'); open = false"
                                                class="p-[10px_12px] cursor-pointer text-[13px] font-light border-b border-white/5 last:border-b-0 transition-colors duration-200 text-white hover:bg-white/10 {{ $guests === $option['value'] ? 'bg-white/5' : '' }}"
                                            >
                                                <span>{{ $option['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @error('guests')
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Next Button --}}
                            <div style="margin-top: 32px">
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    class="w-full p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#C8E6DC] text-black hover:bg-[#A0C4B5] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
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
                                <span class="inline-flex items-center justify-center w-7 h-7 bg-[#C8E6DC] text-gray-300 rounded-full text-[13px] font-semibold">2</span>
                                <span class="text-[#C8E6DC]">Paket-Auswahl</span>
                            </div>

                            <div class="grid gap-[10px]">
                                @foreach ($packageOptions as $pkg)
                                    <label
                                        for="package-{{ $pkg['value'] }}"
                                        class="relative flex items-center p-[14px_18px] border-2 rounded-[10px] cursor-pointer transition-all duration-200 hover:border-[#C8E6DC] hover:bg-[#C8E6DC]/10 hover:-translate-y-[2px] hover:shadow-[0_2px_12px_rgba(200,230,220,0.2)] {{ $package === $pkg['value'] ? 'border-[#C8E6DC] bg-[#C8E6DC]/10 -translate-y-[2px] shadow-[0_2px_12px_rgba(200,230,220,0.2)]' : 'border-white/10' }}"
                                    >
                                        <input
                                            type="radio"
                                            id="package-{{ $pkg['value'] }}"
                                            name="package"
                                            value="{{ $pkg['value'] }}"
                                            wire:model.live="package"
                                            class="absolute opacity-0 pointer-events-none"
                                        />
                                        <span class="relative w-5 h-5 border-2 rounded-full mr-3 transition-all duration-200 {{ $package === $pkg['value'] ? 'border-[#C8E6DC] bg-[#C8E6DC]' : 'border-white/10' }}">
                                            @if ($package === $pkg['value'])
                                                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-[#292929]"></span>
                                            @endif
                                        </span>
                                        <span class="text-sm font-light text-white">{{ $pkg['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Next Button --}}
                            <div class="grid grid-cols-1 gap-3 mt-4">
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    @if (!$package) disabled @endif
                                    class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#C8E6DC] text-black hover:bg-[#A0C4B5] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
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
                                <span class="inline-flex items-center justify-center w-7 h-7 bg-[#C8E6DC] text-gray-300 rounded-full text-[13px] font-semibold">3</span>
                                <span class="text-[#C8E6DC]">Deine Kontaktdaten</span>
                            </div>

                            {{-- Firstname & Lastname Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-firstname" class="text-[13px] font-normal text-white">Vorname *</label>
                                    <input
                                        type="text"
                                        id="mff-firstname"
                                        wire:model="firstname"
                                        @focus="$event.target.select()"
                                        @keydown.tab.prevent="
                                            const lastnameInput = document.getElementById('mff-lastname');
                                            if (lastnameInput) lastnameInput.focus();
                                        "
                                        placeholder="Dein Vorname"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('firstname') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('firstname')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-lastname" class="text-[13px] font-normal text-white">Nachname (optional)</label>
                                    <input
                                        type="text"
                                        id="mff-lastname"
                                        wire:model="lastname"
                                        @focus="$event.target.select()"
                                        @keydown.tab.prevent="
                                            const companyInput = document.getElementById('mff-company');
                                            if (companyInput) companyInput.focus();
                                        "
                                        placeholder="Dein Nachname"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 border-white/10 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)]"
                                    />
                                </div>
                            </div>

                            {{-- Company Row --}}
                            <div class="flex flex-col gap-[4px] mb-[8px]">
                                <label for="mff-company" class="text-[13px] font-normal text-white">Firma *</label>
                                <input
                                    type="text"
                                    id="mff-company"
                                    wire:model="company"
                                    @focus="$event.target.select()"
                                    @keydown.tab.prevent="
                                        const emailInput = document.getElementById('mff-email');
                                        if (emailInput) emailInput.focus();
                                    "
                                    placeholder="Deine Firma"
                                    class="w-full p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('company') border-red-600 @else border-white/10 @enderror"
                                />
                                @error('company')
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email & Phone Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[8px] mb-[8px]">
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-email" class="text-[13px] font-normal text-white">E-Mail *</label>
                                    <input
                                        type="email"
                                        id="mff-email"
                                        wire:model="email"
                                        @focus="$event.target.select()"
                                        @keydown.tab.prevent="
                                            const phoneInput = document.getElementById('mff-phone');
                                            if (phoneInput) phoneInput.focus();
                                        "
                                        placeholder="deine@email.de"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('email') border-red-600 @else border-white/10 @enderror"
                                    />
                                    @error('email')
                                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-[4px]">
                                    <label for="mff-phone" class="text-[13px] font-normal text-white">Telefon (optional)</label>
                                    <input
                                        type="tel"
                                        id="mff-phone"
                                        wire:model="phone"
                                        @focus="$event.target.select()"
                                        @keydown.tab.prevent="
                                            const messageTextarea = document.getElementById('mff-message');
                                            if (messageTextarea) messageTextarea.focus();
                                        "
                                        placeholder="+49 123 456789"
                                        class="w-full p-2.5 px-3 text-sm font-light border-2 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] @error('phone') border-red-600 @else border-white/10 @enderror"
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
                                    class="w-full p-2.5 px-3 text-sm font-light border-2 border-white/10 rounded-xl bg-white/5 text-white transition-all duration-200 focus:outline-none focus:border-[#C8E6DC] focus:shadow-[0_0_0_4px_rgba(200,230,220,0.1)] min-h-[50px] resize-y"
                                ></textarea>
                            </div>

                            {{-- Privacy & Data Storage Checkboxes --}}
                            <div class="space-y-3 mt-4">
                                {{-- Privacy Policy Checkbox - Required --}}
                                <div class="flex items-start gap-3 p-3 bg-white/5 border border-white/10 rounded-lg hover:border-[#C8E6DC]/50 transition-colors">
                                    <input
                                        type="checkbox"
                                        id="mff-privacy"
                                        wire:model="privacy"
                                        class="w-5 h-5 mt-[2px] cursor-pointer accent-[#C8E6DC] flex-shrink-0"
                                    />
                                    <label for="mff-privacy" class="text-[13px] font-normal text-white cursor-pointer leading-[1.5]">
                                        <a href="/datenschutz" target="_blank" class="text-[#C8E6DC] underline font-semibold hover:text-[#A0C4B5]">Datenschutzerklärung</a> gelesen und akzeptiert *
                                    </label>
                                </div>
                                @error('privacy')
                                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                                @enderror

                                {{-- LocalStorage Consent Checkbox - Optional for UX --}}
                                <div class="flex items-start gap-3 p-3 bg-white/5 border border-white/10 rounded-lg hover:border-[#C8E6DC]/50 transition-colors">
                                    <input
                                        type="checkbox"
                                        id="mff-storage-consent"
                                        x-model="$wire.storageConsent"
                                        @change="if ($wire.storageConsent) { saveToStorage(); } else { clearStorage(); }"
                                        class="w-5 h-5 mt-[2px] cursor-pointer accent-[#C8E6DC] flex-shrink-0"
                                    />
                                    <label for="mff-storage-consent" class="text-[13px] font-normal text-white/80 cursor-pointer leading-[1.5]">
                                        <span class="text-white">Formular-Daten lokal speichern</span>
                                        <span class="text-[11px] text-white/60 ml-1">– nur in deinem Browser, nicht auf dem Server.</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="mt-4">
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full p-[12px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[8px] bg-[#C8E6DC] text-black hover:bg-[#A0C4B5] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
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
                                class="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer bg-[#C8E6DC] text-black hover:bg-[#A0C4B5]"
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
