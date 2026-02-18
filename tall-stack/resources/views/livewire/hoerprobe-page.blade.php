<div class="w-full bg-white text-[#1a1a1a]" style="font-family: 'Poppins', sans-serif">

    {{-- A. Header – exakt wie Hauptseite (light mode) --}}
    <header class="sticky top-0 z-50 bg-white border-b border-black/5 header-smooth-transition">
        <div class="w-full px-4 sm:px-6 lg:px-[80px] h-[80px] lg:h-[108px] flex items-center justify-between">

            {{-- Logo --}}
            <a href="https://musikfürfirmen.de" target="_blank" rel="noopener noreferrer"
               class="text-[20px] sm:text-[22px] lg:text-[24px] font-light text-[#1a1a1a] hover:text-[#C8E6DC] transition-colors leading-none tracking-wide shrink-0">
                musikfürfirmen
            </a>

            {{-- Desktop CTAs --}}
            <div class="hidden sm:flex items-center gap-3 shrink-0">
                <button
                    onclick="Livewire.dispatch('openBookingModal')"
                    class="btn-fill-sm-secondary whitespace-nowrap">
                    Kostenloses Erstgespräch
                </button>
                <button
                    onclick="Livewire.dispatch('openMFFCalculator')"
                    class="btn-fill-sm-green whitespace-nowrap">
                    Unverbindliches Angebot
                </button>
            </div>

            {{-- Mobile: nur grüner Button --}}
            <button
                onclick="Livewire.dispatch('openMFFCalculator')"
                class="sm:hidden px-3 py-2 rounded-full text-xs font-medium bg-[#C8E6DC] text-black whitespace-nowrap">
                Angebot einholen
            </button>
        </div>
    </header>

    {{-- B. Hero --}}
    <section class="pt-16 pb-10 px-4 sm:px-6 lg:px-[80px] text-center" data-section-bg="#ffffff" data-section-theme="light">
        <div class="max-w-3xl mx-auto">
            <h1 class="font-semibold text-4xl sm:text-5xl md:text-6xl leading-tight tracking-[-1.5px] text-[#1a1a1a] opacity-0 animate-[fadeInUp_0.5s_ease-out_0.1s_forwards]">
                Hör mal rein.
            </h1>
        </div>
    </section>

    {{-- C. Audio Player --}}
    <section class="py-8 px-4 sm:px-6 lg:px-[80px]" data-section-bg="#ffffff" data-section-theme="light">
        <div class="max-w-2xl mx-auto">
            <div class="border border-black/10 rounded-2xl p-6 sm:p-8 opacity-0 animate-[fadeInUp_0.5s_ease-out_0.4s_forwards]"
                 x-data="{
                     audio: null,
                     playing: false,
                     currentTime: 0,
                     duration: 0,
                     get progress() { return this.duration ? (this.currentTime / this.duration) * 100 : 0 },
                     init() {
                         this.audio = this.$refs.audioEl;
                         this.audio.addEventListener('timeupdate', () => { this.currentTime = this.audio.currentTime; });
                         this.audio.addEventListener('loadedmetadata', () => { this.duration = this.audio.duration; });
                         this.audio.addEventListener('ended', () => { this.playing = false; });
                     },
                     toggle() {
                         if (this.playing) {
                             this.audio.pause();
                         } else {
                             this.audio.play();
                         }
                         this.playing = !this.playing;
                     },
                     seek(e) {
                         const rect = e.currentTarget.getBoundingClientRect();
                         this.audio.currentTime = ((e.clientX - rect.left) / rect.width) * this.duration;
                     },
                     formatTime(s) {
                         const m = Math.floor(s / 60);
                         return m + ':' + (Math.floor(s % 60)).toString().padStart(2, '0');
                     }
                 }">

                {{-- Hidden Audio Element --}}
                <audio x-ref="audioEl" preload="metadata">
                    <source src="{{ asset('audio/hoerprobe.mp3') }}" type="audio/mpeg">
                </audio>

                {{-- Label --}}
                <p class="text-xs text-[#1a1a1a]/40 uppercase tracking-[0.15em] mb-6 font-medium">
                    Zusammenschnitt aus echten Events
                </p>

                {{-- Waveform Visualizer --}}
                <div class="flex items-end justify-center gap-[3px] h-10 mb-6" aria-hidden="true">
                    @php
                        $heights = [40, 55, 35, 70, 45, 80, 50, 65, 30, 75, 55, 40, 85, 45, 60, 35, 70, 50, 80, 40, 65, 30, 75, 55, 45, 85, 50, 60, 35, 70, 45, 80];
                    @endphp
                    @foreach($heights as $i => $h)
                        <div class="w-1 rounded-full transition-all duration-150"
                             :class="playing ? 'bg-[#1a1a1a]' : 'bg-black/15'"
                             :style="playing ? 'height: {{ $h }}%; animation: waveBar {{ 0.4 + ($i % 5) * 0.1 }}s ease-in-out infinite alternate; animation-delay: {{ $i * 0.04 }}s;' : 'height: {{ $h }}%;'">
                        </div>
                    @endforeach
                </div>

                {{-- Progress Bar --}}
                <div class="w-full h-[2px] bg-black/10 rounded-full cursor-pointer mb-4 relative group"
                     @click="seek($event)">
                    <div class="h-full bg-[#1a1a1a] rounded-full transition-all duration-100 relative"
                         :style="`width: ${progress}%`">
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-[#1a1a1a] rounded-full shadow translate-x-1/2 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                             :class="duration > 0 ? 'group-hover:opacity-100' : 'opacity-0'"></div>
                    </div>
                </div>

                {{-- Controls Row --}}
                <div class="flex items-center justify-between">
                    {{-- Time Display --}}
                    <span class="text-xs text-[#1a1a1a]/40 font-mono tabular-nums">
                        <span x-text="formatTime(currentTime)">0:00</span>
                        <span class="text-[#1a1a1a]/20"> / </span>
                        <span x-text="duration ? formatTime(duration) : '--:--'">--:--</span>
                    </span>

                    {{-- Play/Pause Button --}}
                    <button
                        @click="toggle()"
                        class="w-14 h-14 rounded-full bg-[#C8E6DC] text-[#1a1a1a] flex items-center justify-center hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 hover:scale-105 shadow-sm"
                        :aria-label="playing ? 'Pause' : 'Play'">
                        <svg x-show="!playing" class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg x-show="playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                        </svg>
                    </button>

                    {{-- Spacer --}}
                    <span class="w-16"></span>
                </div>
            </div>

            {{-- Waveform Animation --}}
            <style>
                @keyframes waveBar {
                    from { transform: scaleY(0.4); }
                    to { transform: scaleY(1); }
                }
            </style>
        </div>
    </section>

    {{-- D. Divider --}}
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-0">
        <div class="h-[1px] bg-black/8"></div>
    </div>

    {{-- E. Firmenbeschreibung --}}
    <section class="py-14 px-4 sm:px-6 lg:px-[80px]" data-section-bg="#ffffff" data-section-theme="light">
        <div class="max-w-2xl mx-auto opacity-0 animate-[fadeInUp_0.5s_ease-out_0.5s_forwards]">
            <p class="text-[#1a1a1a] text-base sm:text-lg leading-relaxed font-light mb-5">
                Musik ist auf den meisten Firmenevents das letzte, worüber nachgedacht wird –
                und das erste, was auffällt, wenn es nicht passt.
            </p>
            <p class="text-[#1a1a1a]/50 text-sm sm:text-base leading-relaxed font-light mb-8">
                Wir kümmern uns darum, dass das bei euch anders läuft. Von der ersten Anfrage bis zum letzten Ton –
                Liveband, DJ oder beides, aus einer Hand.
            </p>
            <a href="https://musikfürfirmen.de"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-2 text-[#1a1a1a] text-sm font-medium border-b border-[#1a1a1a]/20 pb-[1px] hover:border-[#1a1a1a] hover:gap-3 transition-all duration-300">
                Mehr über uns erfahren
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </section>

    {{-- F. CTA Block – wie cta-section der Hauptseite --}}
    <section class="py-16 px-4 sm:px-6 lg:px-[80px] bg-[#C8E6DC]" data-section-bg="#C8E6DC" data-section-theme="light">
        <div class="max-w-3xl mx-auto text-center opacity-0 animate-[fadeInUp_0.5s_ease-out_0.6s_forwards]">
            <h2 class="font-semibold text-2xl sm:text-3xl md:text-4xl mb-4 text-[#1a1a1a] tracking-[-0.5px]">
                Bereit für unvergessliche Musik?
            </h2>
            <p class="text-[#1a1a1a]/60 font-light mb-10 text-base sm:text-lg max-w-xl mx-auto">
                Fordere jetzt dein unverbindliches Angebot an und mach dein nächstes Firmenevent zu einem Highlight.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                {{-- Fill-Button: schwarz füllt von links --}}
                <button
                    onclick="Livewire.dispatch('openMFFCalculator')"
                    class="btn-fill-primary">
                    Jetzt Angebot einholen
                </button>
                {{-- Fill-Button: schwarz füllt von links, mit Founder-Avataren --}}
                <button
                    onclick="Livewire.dispatch('openBookingModal')"
                    class="btn-fill-secondary inline-flex items-center justify-center gap-3">
                    <div class="flex -space-x-3 shrink-0">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-black/10 bg-gray-200 shrink-0">
                            <img src="{{ asset('images/team/nick.png') }}" alt="Nick" class="w-full h-full object-cover object-top">
                        </div>
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-black/10 bg-gray-200 shrink-0">
                            <img src="{{ asset('images/team/jonas.png') }}" alt="Jonas" class="w-full h-full object-cover object-top">
                        </div>
                    </div>
                    Kostenloses Erstgespräch
                </button>
            </div>

            <style>
                /* ── Base fill-button mixin ── */
                .btn-fill-primary,
                .btn-fill-secondary,
                .btn-fill-sm-secondary,
                .btn-fill-sm-green {
                    position: relative;
                    overflow: hidden;
                    border-radius: 9999px;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 500;
                    cursor: pointer;
                    transition: color 0.4s ease;
                    z-index: 0;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }
                /* Large (CTA section) */
                .btn-fill-primary,
                .btn-fill-secondary {
                    padding: 1rem 2rem;
                    font-size: 1rem;
                }
                /* Small (header) */
                .btn-fill-sm-secondary,
                .btn-fill-sm-green {
                    padding: 0.5rem 1rem;
                    font-size: 0.875rem;
                    font-weight: 400;
                }
                /* Colors */
                .btn-fill-primary     { border: 2px solid #1a1a1a; color: #1a1a1a; background: transparent; }
                .btn-fill-secondary   { border: 2px solid rgba(0,0,0,0.2); color: #1a1a1a; background: transparent; }
                .btn-fill-sm-secondary{ border: 2px solid rgba(0,0,0,0.25); color: #1a1a1a; background: transparent; }
                .btn-fill-sm-green    { border: 2px solid #C8E6DC; color: #1a1a1a; background: #C8E6DC; }

                /* The sliding fill – top to bottom */
                .btn-fill-primary::before,
                .btn-fill-secondary::before,
                .btn-fill-sm-secondary::before,
                .btn-fill-sm-green::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: #ffffff;
                    transform: translateY(-101%);
                    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    z-index: -1;
                }
                .btn-fill-primary:hover::before,
                .btn-fill-secondary:hover::before,
                .btn-fill-sm-secondary:hover::before,
                .btn-fill-sm-green:hover::before {
                    transform: translateY(0);
                }
                .btn-fill-primary:hover,
                .btn-fill-secondary:hover,
                .btn-fill-sm-secondary:hover,
                .btn-fill-sm-green:hover {
                    color: #1a1a1a;
                }
            </style>
        </div>
    </section>

    {{-- Footer – schlank, weiß --}}
    <footer class="bg-white border-t border-black/8" style="font-family: 'Poppins', sans-serif">
        <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-[20px] font-light text-[#1a1a1a]">musikfürfirmen</span>
            <div class="flex gap-6 text-sm text-[#1a1a1a]/40 font-light">
                <a href="https://musikfürfirmen.de/impressum" target="_blank" class="hover:text-[#1a1a1a] transition-colors">Impressum</a>
                <a href="https://musikfürfirmen.de/datenschutz" target="_blank" class="hover:text-[#1a1a1a] transition-colors">Datenschutz</a>
            </div>
            <p class="text-sm text-[#1a1a1a]/30 font-light">&copy; {{ date('Y') }} musikfürfirmen.de</p>
        </div>
    </footer>

</div>
