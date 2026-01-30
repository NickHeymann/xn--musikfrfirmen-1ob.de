{{-- Hero Section - Canva Redesign (Clean White Background) --}}
<section class="hero-section relative w-full min-h-screen flex items-center justify-center bg-white"
         x-data="{
            scrollToContent() {
                const target = document.getElementById('waswirbieten');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                } else {
                    window.scrollBy({ top: window.innerHeight, behavior: 'smooth' });
                }
            }
         }"
         style="font-family: 'Poppins', sans-serif">

    {{-- Content --}}
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-[#1a1a1a]">

        {{-- Main Heading - Slightly larger, moved up --}}
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-32 leading-tight -mt-24">
            Livemusik für Firmenevents.
        </h1>

        {{-- Subtitle --}}
        <p class="text-lg md:text-xl lg:text-2xl text-[#4a4a4a] max-w-2xl mx-auto mb-20 leading-relaxed">
            Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss.
        </p>

        {{-- CTA Button --}}
        <button
            class="inline-flex items-center gap-2 border-2 border-black text-black px-10 py-4 font-thin text-lg hover:bg-black hover:text-white transition-all duration-300 uppercase tracking-wide"
            onclick="Livewire.dispatch('openMFFCalculator')"
        >
            Unverbindliches Angebot anfragen
        </button>
    </div>

    {{-- Down Arrow - Positioned at bottom center, visible above fold --}}
    <div
        @click="scrollToContent()"
        class="absolute bottom-24 md:bottom-32 left-1/2 -translate-x-1/2 cursor-pointer hover:opacity-70 transition-opacity animate-bounce">
        <svg class="w-10 h-10 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>
