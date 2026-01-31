{{-- Hero Section - Video Background --}}
<section class="hero-section relative w-full h-screen flex items-center justify-center overflow-hidden"
         data-section-theme="dark"
         data-section-bg="#000000"
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
        preload="auto"
        class="absolute top-0 left-0 w-full h-full object-cover"
        style="z-index: 1; filter: blur(2px);"
    >
        <source src="/videos/hero-landing-page.mp4" type="video/mp4">
    </video>

    {{-- Dark Overlay (80% opacity) --}}
    <div class="absolute top-0 left-0 w-full h-full bg-black/80" style="z-index: 2;"></div>

    {{-- Content with staggered fade-in animation --}}
    <div class="relative max-w-4xl mx-auto px-6 text-center text-white" style="z-index: 10;">

        {{-- Text Content - Moved up 80px --}}
        <div style="margin-bottom: 80px;">
            {{-- Main Heading - Appears first --}}
            <h1 class="font-poppins font-semibold text-5xl md:text-6xl lg:text-7xl mb-8 leading-tight
                       opacity-0 translate-y-8 animate-[fadeInUp_0.3s_ease-out_0s_forwards]"
                style="will-change: opacity, transform;">
                Livemusik für Firmenevents.
            </h1>

            {{-- Subtitle - Appears second (50ms delay) --}}
            <p class="font-poppins text-lg md:text-xl lg:text-2xl max-w-2xl mx-auto leading-relaxed
                      opacity-0 translate-y-8 animate-[fadeInUp_0.3s_ease-out_0.05s_forwards]"
               style="will-change: opacity, transform;">
                Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss.
            </p>
        </div>

        {{-- CTA Button - Stays in place --}}
        <div class="flex justify-center items-center
                    opacity-0 translate-y-8 animate-[fadeInUp_0.3s_ease-out_0.1s_forwards]"
             style="will-change: opacity, transform;">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="px-8 py-4 rounded-full border-2 border-white bg-white text-black font-medium text-base uppercase tracking-wide transition-all duration-300 hover:bg-[#2DD4A8] hover:border-[#2DD4A8] hover:text-white shadow-lg">
                Kostenloses Erstgespräch
            </button>
        </div>
    </div>

    {{-- Down Arrow - At bottom of hero section with fade-in animation --}}
    <div
        @click="scrollToContent()"
        class="absolute bottom-8 left-1/2 -translate-x-1/2 cursor-pointer hover:opacity-50 transition-all duration-200"
        style="z-index: 10; opacity: 0; animation: fadeInUp 0.3s ease-out 0.15s forwards;">
        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7"/>
        </svg>
    </div>
</section>
