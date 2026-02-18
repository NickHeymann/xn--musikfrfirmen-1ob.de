<div class="w-full flex-1 grid bg-[#C8E6DC] text-[#1a1a1a]"
     style="font-family: 'Poppins', sans-serif; grid-template-rows: auto 2fr 1fr auto auto;">

    {{-- ROW 1: Header "Hör mal rein." – fix oben --}}
    <div class="px-4 sm:px-6 lg:px-[80px] pt-6 pb-4">
        <h1 class="text-2xl sm:text-3xl font-light tracking-[-1px] text-[#1a1a1a] text-center animate-fade-in-up-1">
            Hör mal rein.
        </h1>
    </div>

    {{-- ROW 2: Audio Player – unten in seiner 2fr Row (= visuell mittig auf Screen) --}}
    <div class="flex items-end justify-center px-4 sm:px-6 lg:px-[80px] pb-6">
        <div class="max-w-2xl w-full">
            <div class="border border-black/10 rounded-2xl p-4 sm:p-6 animate-fade-in-up-2 bg-white/60 backdrop-blur-sm"
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

                {{-- Hidden Audio Element (AAC primary = better quality, MP3 fallback = universal) --}}
                <audio x-ref="audioEl" preload="metadata">
                    <source src="{{ asset('audio/hoerprobe.m4a') }}" type="audio/mp4">
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
                <div class="flex items-center justify-center gap-4">
                    <span class="text-xs text-[#1a1a1a]/40 font-mono tabular-nums w-20">
                        <span x-text="formatTime(currentTime)">0:00</span>
                        <span class="text-[#1a1a1a]/20"> / </span>
                        <span x-text="duration ? formatTime(duration) : '--:--'">--:--</span>
                    </span>

                    <button
                        @click="toggle()"
                        class="w-14 h-14 rounded-full bg-white text-[#1a1a1a] flex items-center justify-center hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 hover:scale-105 shadow-sm"
                        :aria-label="playing ? 'Pause' : 'Play'">
                        <svg x-show="!playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg x-show="playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                        </svg>
                    </button>

                    <span class="w-20"></span>
                </div>
            </div>

            <style>
                @keyframes waveBar {
                    from { transform: scaleY(0.4); }
                    to { transform: scaleY(1); }
                }
            </style>
        </div>
    </div>

    {{-- ROW 3: Firmenbeschreibung – 1fr, vertikal zentriert zwischen Player und Buttons --}}
    <div class="flex items-center px-4 sm:px-6 lg:px-[80px] animate-fade-in-up-3">
        <div class="max-w-2xl w-full mx-auto">
            <p class="text-[#1a1a1a]/70 text-sm leading-relaxed font-light mb-4">
                Musik ist das Erste, was auffällt, wenn es nicht passt –
                wir sorgen dafür, dass es passt. Liveband, DJ oder beides, aus einer Hand.
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
    </div>

    {{-- ROW 4: CTA Buttons --}}
    <div class="py-4 px-4 sm:px-6 lg:px-[80px] animate-fade-in-up-4">
        <div class="max-w-2xl mx-auto">
            <div class="grid grid-cols-2 gap-2 w-full">
                <button
                    onclick="Livewire.dispatch('openMFFCalculator')"
                    class="btn-cta-sm">
                    Angebot einholen
                </button>
                <button
                    onclick="Livewire.dispatch('openBookingModal')"
                    class="btn-cta-sm gap-1.5">
                    <div class="flex -space-x-1.5 shrink-0">
                        <div class="w-5 h-5 rounded-full overflow-hidden border border-black/10 bg-gray-200 shrink-0">
                            <img src="{{ asset('images/team/nick.png') }}" alt="Nick" class="w-full h-full object-cover object-top">
                        </div>
                        <div class="w-5 h-5 rounded-full overflow-hidden border border-black/10 bg-gray-200 shrink-0">
                            <img src="{{ asset('images/team/jonas.png') }}" alt="Jonas" class="w-full h-full object-cover object-top">
                        </div>
                    </div>
                    Erstgespräch
                </button>
            </div>

            <style>
                .btn-cta-sm {
                    position: relative;
                    overflow: hidden;
                    border-radius: 9999px;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 500;
                    font-size: 0.75rem;
                    padding: 0.5rem 0.75rem;
                    cursor: pointer;
                    transition: color 0.4s ease, box-shadow 0.3s ease, transform 0.2s ease;
                    z-index: 0;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border: none;
                    color: #1a1a1a;
                    background: #ffffff;
                    box-shadow: 0 4px 0px rgba(0,0,0,0.18), 0 6px 16px rgba(0,0,0,0.10);
                    white-space: nowrap;
                }
                .btn-cta-sm::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: #1a1a1a;
                    transform: translateY(-101%);
                    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    z-index: -1;
                }
                .btn-cta-sm:hover::before { transform: translateY(0); }
                .btn-cta-sm:hover {
                    color: #ffffff;
                    box-shadow: 0 2px 0px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.08);
                    transform: translateY(2px);
                }
            </style>
        </div>
    </div>

    {{-- ROW 5: Mini Footer --}}
    <div class="px-4 py-3 flex items-center justify-between gap-2">
        <span class="text-xs font-light text-[#1a1a1a]/40">musikfürfirmen</span>
        <div class="flex gap-3 text-[10px] text-[#1a1a1a]/25 font-light">
            <a href="https://musikfürfirmen.de/impressum" target="_blank" class="hover:text-[#1a1a1a] transition-colors">Impressum</a>
            <a href="https://musikfürfirmen.de/datenschutz" target="_blank" class="hover:text-[#1a1a1a] transition-colors">Datenschutz</a>
            <span>&copy; {{ date('Y') }}</span>
        </div>
    </div>

</div>
