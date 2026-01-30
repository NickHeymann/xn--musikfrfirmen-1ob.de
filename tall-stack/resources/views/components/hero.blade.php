{{-- Hero Section - Video Background --}}
<section class="hero-section relative w-full h-screen flex items-center justify-center overflow-hidden"
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

    {{-- Static Fallback Background (instant load) --}}
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-image: url('/images/events/event-1.jpg'); background-size: cover; background-position: center; filter: blur(2px); z-index: -21;"></div>

    {{-- Video Background - fixed to viewport, completely static --}}
    <video
        autoplay
        muted
        loop
        playsinline
        preload="auto"
        style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -20; filter: blur(2px); opacity: 0;"
        onloadeddata="this.style.opacity='1'"
    >
        <source src="/videos/hero-landing-page.mp4" type="video/mp4">
    </video>

    {{-- Dark Overlay (80% opacity - much darker) - also fixed, static --}}
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.8); z-index: -19;"></div>

    {{-- Content with staggered fade-in animation --}}
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white">

        {{-- Text Content - Moved up 80px --}}
        <div style="margin-bottom: 80px;">
            {{-- Main Heading - Appears first --}}
            <h1 class="font-poppins font-semibold text-5xl md:text-6xl lg:text-7xl mb-8 leading-tight
                       opacity-0 translate-y-8 animate-[fadeInUp_0.5s_ease-out_0.1s_forwards]">
                Livemusik für Firmenevents.
            </h1>

            {{-- Subtitle - Appears second (100ms delay) --}}
            <p class="font-poppins text-lg md:text-xl lg:text-2xl max-w-2xl mx-auto leading-relaxed
                      opacity-0 translate-y-8 animate-[fadeInUp_0.5s_ease-out_0.2s_forwards]">
                Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss.
            </p>
        </div>

        {{-- CTA Button - Stays in place --}}
        <div class="flex justify-center items-center
                    opacity-0 translate-y-8 animate-[fadeInUp_0.5s_ease-out_0.3s_forwards]">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="px-8 py-4 rounded-full border-2 border-white bg-white text-black font-medium text-base uppercase tracking-wide transition-all duration-300 hover:bg-[#2DD4A8] hover:border-[#2DD4A8] hover:text-white shadow-lg">
                Kostenloses Erstgespräch
            </button>
        </div>
    </div>

    {{-- Down Arrow - Fixed to bottom of viewport with fade-in animation --}}
    <div
        @click="scrollToContent()"
        style="position: fixed !important; bottom: 2rem !important; left: 50% !important; z-index: 9999 !important; opacity: 0; animation: fadeInUp 0.5s ease-out 0.4s forwards;"
        class="cursor-pointer hover:opacity-50 transition-all duration-300">
        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7"/>
        </svg>
    </div>
</section>
