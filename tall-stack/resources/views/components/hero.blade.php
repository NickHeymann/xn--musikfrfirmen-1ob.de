{{-- Hero Section - Video Background --}}
<section class="hero-section relative w-full h-screen flex items-center justify-center overflow-hidden"
         data-section-theme="dark"
         data-section-bg="#000000"
         x-data="{
            scrollToContent() {
                const target = document.getElementById('testimonial');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                } else {
                    window.scrollBy({ top: window.innerHeight, behavior: 'smooth' });
                }
            }
         }">

    {{-- Video Background - lighter blur for better visibility --}}
    <video
        autoplay
        muted
        loop
        playsinline
        preload="auto"
        poster="{{ asset('images/hero-poster.webp') }}"
        disablepictureinpicture
        style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -20; filter: blur(0.5px);"
    >
        <source src="{{ asset('videos/hero-landing-page.mp4') }}" type="video/mp4">
    </video>

    {{-- Lighter Dark Overlay (50% opacity for brighter video) - also fixed, static --}}
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: -19;"></div>

    {{-- Content with staggered fade-in animation and scroll-based blur - FIXED POSITION --}}
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-full max-w-[1600px] px-8 lg:px-16 xl:px-20 text-white"
         x-data="{ scrollBlur: 0 }"
         x-init="
            const updateBlur = () => {
                const scrollY = window.scrollY;
                const maxScroll = window.innerHeight * 0.5;
                const blurAmount = Math.min((scrollY / maxScroll) * 15, 15);
                scrollBlur = blurAmount;
            };
            window.addEventListener('scroll', updateBlur, { passive: true });
         "
         :style="`filter: blur(${scrollBlur}px);`">

        {{-- Full-width decorative line with glassmorphism --}}
        <div class="w-full mb-4 opacity-0 animate-[fadeInUp_0.5s_ease-out_0.05s_forwards]">
            <div class="w-full backdrop-blur-sm" style="height: 0.5px; background: rgba(255, 255, 255, 0.15);"></div>
        </div>

        {{-- 2-Column Layout: Headline Left at Edge, Description + Buttons Right at Edge --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-12 lg:gap-32 items-center">

            {{-- Left Column: Large Headline with line break --}}
            <div class="opacity-0 translate-y-8 animate-[fadeInUp_0.5s_ease-out_0.1s_forwards]">
                <h1 class="font-poppins font-semibold text-2xl md:text-3xl lg:text-4xl xl:text-5xl leading-tight">
                    Livemusik für<br><span class="text-[#C8E6DC]">Firmenevents.</span>
                </h1>
            </div>

            {{-- Right Column: Subtitle + Buttons --}}
            <div class="space-y-4 opacity-0 translate-y-8 animate-[fadeInUp_0.5s_ease-out_0.2s_forwards]">
                {{-- Subtitle --}}
                <p class="font-poppins text-sm md:text-base leading-relaxed max-w-md">
                    Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss.
                </p>

                {{-- CTA Buttons - Swapped with founder images and enhanced hover effects --}}
                <div class="flex flex-col sm:flex-row gap-2">
                    {{-- Left: Green "Jetzt Angebot einholen" with animated hover (like reference) --}}
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="group px-4 py-2 rounded-full bg-[#C8E6DC] text-black font-medium text-xs tracking-wide transition-all duration-500 hover:bg-black/80 shadow-lg hover:shadow-2xl whitespace-nowrap hover:scale-105">
                        <span class="transition-colors duration-500 group-hover:text-[#C8E6DC]">
                            Jetzt Angebot einholen
                        </span>
                    </button>

                    {{-- Right: Glassmorphism "Kostenloses Erstgespräch" with founders + green dot --}}
                    <button
                        onclick="Livewire.dispatch('openBookingModal')"
                        class="group px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/30 text-white font-medium text-xs tracking-wide transition-all duration-500 hover:bg-white/20 hover:border-white/40 shadow-lg hover:shadow-2xl whitespace-nowrap flex items-center gap-2 hover:scale-105">
                        {{-- Founder Images Stack with green dot --}}
                        <div class="flex items-center gap-1">
                            {{-- Overlapping founder images --}}
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full overflow-hidden border-2 border-white/20 bg-gray-700">
                                    <img src="{{ asset('images/team/nick.png') }}" alt="Nick" class="w-full h-full object-cover">
                                </div>
                                <div class="w-6 h-6 rounded-full overflow-hidden border-2 border-white/20 bg-gray-700">
                                    <img src="{{ asset('images/team/jonas.png') }}" alt="Jonas" class="w-full h-full object-cover">
                                </div>
                            </div>
                            {{-- Green availability dot (right of images) --}}
                            <div class="w-2 h-2 bg-[#C8E6DC] rounded-full"></div>
                        </div>
                        <span class="transition-colors duration-500 group-hover:text-[#C8E6DC]">
                            Kostenloses Erstgespräch
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Down Arrow - Fixed below content --}}
        <div
            @click="scrollToContent()"
            class="fixed bottom-8 left-1/2 -translate-x-1/2 cursor-pointer hover:opacity-50 transition-all duration-300 opacity-0 animate-[fadeInUp_0.5s_ease-out_0.4s_forwards]">
            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7"/>
            </svg>
        </div>
    </div>
</section>
