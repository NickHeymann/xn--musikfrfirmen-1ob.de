{{-- Hero Section - Video Background --}}
<section class="hero-section relative w-full min-h-screen flex items-center justify-center overflow-hidden"
         data-section-theme="dark"
         x-data="{
            scrollToContent() {
                const target = document.getElementById('waswirbieten');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                } else {
                    window.scrollBy({ top: window.innerHeight, behavior: 'smooth' });
                }
            }
         }">

    {{-- Video Background --}}
    <video
        autoplay
        muted
        loop
        playsinline
        class="absolute inset-0 w-full h-full object-cover"
        poster="/images/events/event-1.jpg"
    >
        <source src="/videos/hero-landing-page.mp4" type="video/mp4">
    </video>

    {{-- Dark Overlay (45% opacity) --}}
    <div class="absolute inset-0 bg-black/45"></div>

    {{-- Content --}}
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white">

        {{-- Main Heading --}}
        <h1 class="font-poppins font-semibold text-5xl md:text-6xl lg:text-7xl mb-8 leading-tight">
            Livemusik für Firmenevents.
        </h1>

        {{-- Subtitle --}}
        <p class="font-poppins text-lg md:text-xl lg:text-2xl max-w-2xl mx-auto mb-12 leading-relaxed">
            Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button
                class="btn-primary"
                onclick="Livewire.dispatch('openMFFCalculator')"
            >
                Jetzt Buchen
            </button>

            <a href="/erstgespraech" class="btn-secondary text-white border-white hover:border-black">
                Kostenloses Erstgespräch
            </a>
        </div>
    </div>

    {{-- Down Arrow --}}
    <div
        @click="scrollToContent()"
        class="absolute bottom-24 md:bottom-32 left-1/2 -translate-x-1/2 cursor-pointer hover:opacity-70 transition-opacity animate-bounce z-20">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>
