{{-- Service Cards - Sticky Scroll Implementation --}}
<section
    class="mff-sticky-cards relative w-full pb-[40px]"
    id="mff-sticky-section"
    x-data="{
        init() {
            // Ensure parent elements don't hide overflow
            let el = this.$el.parentElement;
            let depth = 0;
            const maxDepth = 20;

            while (el && el !== document.documentElement && depth < maxDepth) {
                el.style.overflow = 'visible';
                el = el.parentElement;
                depth++;
            }
        }
    }"
    style="--mff-accent: #2DD4A8; --mff-accent-dark: #22a883; --mff-accent-light: #e6faf5; --mff-primary: #1a1a2e; --mff-text: #2d3748; --mff-text-light: #4a5568; font-family: 'Poppins', sans-serif"
>
    <div class="mff-cards-wrapper max-w-[1200px] mx-auto px-6 relative">
        {{-- Livebands Card --}}
        <div
            class="mff-card mff-card-1 sticky w-full mb-[30px] rounded-[20px] shadow-[0_4px_6px_-1px_rgba(26,26,46,0.12),0_10px_40px_-10px_rgba(26,26,46,0.12)] overflow-hidden transition-shadow duration-300 border border-[rgba(45,212,168,0.1)] bg-white hover:shadow-[0_8px_16px_-2px_rgba(26,26,46,0.12),0_20px_60px_-15px_rgba(45,212,168,0.15)] hover:border-[rgba(45,212,168,0.25)]"
            style="top: 20px; z-index: 10"
        >
            <div class="mff-card-inner flex flex-col md:flex-row items-stretch min-h-[450px] md:min-h-[450px]">
                {{-- Content --}}
                <div class="mff-card-content flex-1 p-7 md:p-12 flex flex-col justify-center order-2 md:order-1">
                    {{-- Icon --}}
                    <div class="mff-card-icon w-12 h-12 mb-5 p-[10px] rounded-xl bg-[#e6faf5] shadow-[0_2px_8px_rgba(45,212,168,0.08)]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-[#2DD4A8]">
                            <path d="M9 18V5l12-2v13" />
                            <circle cx="6" cy="18" r="3" />
                            <circle cx="18" cy="16" r="3" />
                        </svg>
                    </div>

                    <span class="mff-card-number text-xs font-bold tracking-[2px] mb-2 text-[#2DD4A8] opacity-70">01</span>
                    <h3 class="mff-card-title text-[22px] md:text-[32px] font-bold text-[#1a1a2e] m-0 mb-6 leading-[1.2]">Livebands</h3>

                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Unser absolutes Alleinstellungsmerkmal. Wir arbeiten mit einer festen Stammband, die wir persönlich kennen und die wir je nach Bedarf individuell für euch zusammenstellen und auf euer Event abstimmen.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Viele Agenturen vermitteln nur Kontakte, die Bands kennen sie kaum persönlich. Bei musikfürfirmen.de läuft das anders. Wir arbeiten mit einer eingespielten Stammband, die je nach Eventgröße flexibel in unterschiedlichen Besetzungen auftritt.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Alle Musiker:innen haben wir persönlich ausgewählt und zu einem harmonischen Team geformt. So garantieren wir absolute Spitzenqualität und eine sorgenfreie Zusammenarbeit.
                    </p>
                </div>

                {{-- Image with Gradient Overlay --}}
                <div class="mff-card-visual flex-none w-full h-[180px] md:h-auto md:w-[42%] relative order-1 md:order-2 overflow-hidden group">
                    <img
                        src="/images/services/liveband.jpg"
                        alt="Livebands"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    {{-- Bold Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/65 to-teal-500/75 mix-blend-multiply transition-opacity duration-500 group-hover:opacity-90"></div>
                    {{-- Subtle grain texture --}}
                    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 400 400\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.9\' numOctaves=\'4\' /%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\' /%3E%3C/svg%3E')"></div>
                </div>
            </div>
        </div>

        {{-- DJs Card --}}
        <div
            class="mff-card mff-card-2 sticky w-full mb-[30px] rounded-[20px] shadow-[0_4px_6px_-1px_rgba(26,26,46,0.12),0_10px_40px_-10px_rgba(26,26,46,0.12)] overflow-hidden transition-shadow duration-300 border border-[rgba(45,212,168,0.1)] bg-white hover:shadow-[0_8px_16px_-2px_rgba(26,26,46,0.12),0_20px_60px_-15px_rgba(45,212,168,0.15)] hover:border-[rgba(45,212,168,0.25)]"
            style="top: 20px; z-index: 20"
        >
            <div class="mff-card-inner flex flex-col md:flex-row items-stretch min-h-[450px] md:min-h-[450px]">
                <div class="mff-card-content flex-1 p-7 md:p-12 flex flex-col justify-center order-2 md:order-1">
                    <div class="mff-card-icon w-12 h-12 mb-5 p-[10px] rounded-xl bg-[#e6faf5] shadow-[0_2px_8px_rgba(45,212,168,0.08)]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-[#2DD4A8]">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </div>

                    <span class="mff-card-number text-xs font-bold tracking-[2px] mb-2 text-[#2DD4A8] opacity-70">02</span>
                    <h3 class="mff-card-title text-[22px] md:text-[32px] font-bold text-[#1a1a2e] m-0 mb-6 leading-[1.2]">DJ's</h3>

                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Unsere DJs liefern euch den perfekten Mix aus Klassikern und aktuellen Hits, maßgeschneidert für euer Event und perfekt abgestimmt auf eure individuellen Musikwünsche.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Ob entspannte Afterwork-Party oder festliches Firmenjubiläum: Wir finden über unser Netzwerk genau den richtigen DJ für euren Anlass. DJs lassen sich hervorragend mit einer Liveband kombinieren.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Auf Wunsch erweitern wir das Setup um live auftretende Sänger:innen oder Saxofonist:innen.
                    </p>
                </div>

                <div class="mff-card-visual flex-none w-full h-[180px] md:h-auto md:w-[42%] relative order-1 md:order-2 overflow-hidden group">
                    <img
                        src="/images/services/dj.jpg"
                        alt="DJs"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/65 to-emerald-500/75 mix-blend-multiply transition-opacity duration-500 group-hover:opacity-90"></div>
                    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 400 400\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.9\' numOctaves=\'4\' /%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\' /%3E%3C/svg%3E')"></div>
                </div>
            </div>
        </div>

        {{-- Technik Card --}}
        <div
            class="mff-card mff-card-3 sticky w-full mb-[30px] rounded-[20px] shadow-[0_4px_6px_-1px_rgba(26,26,46,0.12),0_10px_40px_-10px_rgba(26,26,46,0.12)] overflow-hidden transition-shadow duration-300 border border-[rgba(45,212,168,0.1)] bg-white hover:shadow-[0_8px_16px_-2px_rgba(26,26,46,0.12),0_20px_60px_-15px_rgba(45,212,168,0.15)] hover:border-[rgba(45,212,168,0.25)]"
            style="top: 20px; z-index: 30"
        >
            <div class="mff-card-inner flex flex-col md:flex-row items-stretch min-h-[450px] md:min-h-[450px]">
                <div class="mff-card-content flex-1 p-7 md:p-12 flex flex-col justify-center order-2 md:order-1">
                    <div class="mff-card-icon w-12 h-12 mb-5 p-[10px] rounded-xl bg-[#e6faf5] shadow-[0_2px_8px_rgba(45,212,168,0.08)]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-[#2DD4A8]">
                            <polygon points="12 2 2 7 12 12 22 7 12 2" />
                            <polyline points="2 17 12 22 22 17" />
                            <polyline points="2 12 12 17 22 12" />
                        </svg>
                    </div>

                    <span class="mff-card-number text-xs font-bold tracking-[2px] mb-2 text-[#2DD4A8] opacity-70">03</span>
                    <h3 class="mff-card-title text-[22px] md:text-[32px] font-bold text-[#1a1a2e] m-0 mb-6 leading-[1.2]">Technik</h3>

                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Mit Musik- und Lichttechnik im Wert von über 100.000 € stellen wir für jede Eventgröße die perfekte Ausstattung damit unsere Künstler:innen ihre Performance optimal präsentieren können.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Unser umfangreiches Equipment ermöglicht es uns, für Events jeder Größenordnung die ideale Sound- und Lichtausstattung bereitzustellen.
                    </p>
                    <p class="mff-card-text text-sm md:text-base leading-[1.7] text-[#4a5568] m-0 mb-4">
                        Das Ergebnis: Ihr seid begeistert von der professionellen Darbietung, und unsere Musiker:innen können sich im besten Licht präsentieren.
                    </p>
                </div>

                <div class="mff-card-visual flex-none w-full h-[180px] md:h-auto md:w-[42%] relative order-1 md:order-2 overflow-hidden group">
                    <img
                        src="/images/services/technik.jpg"
                        alt="Technik"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-500/70 to-emerald-600/80 mix-blend-multiply transition-opacity duration-500 group-hover:opacity-90"></div>
                    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 400 400\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.9\' numOctaves=\'4\' /%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\' /%3E%3C/svg%3E')"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media (max-width: 768px) {
        .mff-card {
            position: relative !important;
            top: auto !important;
        }
    }
</style>
