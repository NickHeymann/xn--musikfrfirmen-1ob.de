{{-- Hero Section - Production Accurate Implementation --}}
<section class="hero-section relative w-full h-screen flex items-center justify-center overflow-hidden">
    {{-- Video Background --}}
    <video
        autoplay
        loop
        muted
        playsinline
        class="absolute inset-0 w-full h-full object-cover"
        poster="/images/hero-fallback.jpg"
    >
        <source src="/videos/hero-background.mp4" type="video/mp4">
    </video>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40"></div>

    {{-- Content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 text-center text-white"
         x-data="{
            currentIndex: 0,
            letters: [],
            isHolding: false,
            scrollPromptVisible: true,
            sliderContent: ['Musik', 'Livebands', 'Djs', 'Technik'],
            init() {
                this.changeWord();
                setInterval(() => this.changeWord(), 3000);

                // Scroll prompt visibility toggle
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 100) {
                        this.scrollPromptVisible = false;
                    } else {
                        this.scrollPromptVisible = true;
                    }
                }, { passive: true });

                // Video force play
                const video = this.$el.closest('section').querySelector('video');
                if (video) {
                    video.play().catch(() => {
                        // Autoplay blocked - video will show poster
                    });
                }
            },
            changeWord() {
                const word = this.sliderContent[this.currentIndex];
                this.letters = word.split('');
                this.isHolding = true;
                setTimeout(() => {
                    this.isHolding = false;
                    setTimeout(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.sliderContent.length;
                    }, 350);
                }, 2650);
            },
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

        {{-- Main Heading with Word Rotation - CI Color, Bigger, Higher --}}
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-12 leading-tight mt-[-150px]">
            Deine <span class="inline-block min-w-[250px] md:min-w-[450px] text-[#D4F4E8]" :class="{ 'holding': isHolding }">
                <template x-for="(letter, index) in letters" :key="`${currentIndex}-${index}`">
                    <span
                        class="inline-block animate-letter-fade"
                        :style="`animation-delay: ${index * 0.04 + 0.05}s`"
                        x-text="letter === ' ' ? '\u00A0' : letter"
                    ></span>
                </template>
            </span> für Firmenevents!
        </h1>

        {{-- CTA Button --}}
        <button
            class="mff-btn inline-flex items-center gap-2 bg-white text-teal-600 px-10 py-4 rounded-full font-semibold text-lg hover:bg-[#D4F4E8] hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 mb-8"
            onclick="window.dispatchEvent(new CustomEvent('openMFFCalculator'))"
        >
            Unverbindliches Angebot anfragen
        </button>

        {{-- Features List - Centered Below Button --}}
        <ul class="mff-btn-features list-none space-y-3 text-lg md:text-xl max-w-2xl mx-auto text-center">
            <li class="flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-[#D4F4E8] animate-pop-in-check flex-shrink-0" style="animation-delay: 0s" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="animate-fade-in-text" style="animation-delay: 0.3s">Musik für jedes Firmenevent</span>
            </li>
            <li class="flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-[#D4F4E8] animate-pop-in-check flex-shrink-0" style="animation-delay: 0.6s" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="animate-fade-in-text" style="animation-delay: 0.9s">Rundum-sorglos-Paket</span>
            </li>
            <li class="flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-[#D4F4E8] animate-pop-in-check flex-shrink-0" style="animation-delay: 1.2s" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="animate-fade-in-text" style="animation-delay: 1.5s">Angebot innerhalb von 24 Stunden</span>
            </li>
        </ul>

        {{-- Scroll Chevron Animation - Bigger and Lower --}}
        <div
            x-show="scrollPromptVisible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="scrollToContent()"
            class="absolute bottom-4 left-1/2 -translate-x-1/2 flex flex-col items-center cursor-pointer hover:opacity-80 transition-opacity animate-scroll-prompt">
            <div class="flex flex-col">
                <svg class="w-10 h-10 animate-chevron-bounce" style="animation-delay: 0s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <svg class="w-10 h-10 animate-chevron-bounce -mt-5" style="animation-delay: 0.2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<style>
    /* Letter Fade Animation */
    @keyframes letterFade {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-letter-fade {
        animation: letterFade 0.35s ease-out forwards;
        opacity: 0;
    }

    .holding .animate-letter-fade {
        opacity: 1;
        transform: translateY(0);
    }

    /* Checkmark Pop-In Animation with Rotation */
    @keyframes popInCheck {
        0% {
            opacity: 0;
            transform: scale(0) rotate(-180deg);
        }
        70% {
            transform: scale(1.2) rotate(10deg); /* Overshoot */
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    .animate-pop-in-check {
        animation: popInCheck 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        opacity: 0;
    }

    /* Text Fade-In Animation with Slide */
    @keyframes fadeInText {
        0% {
            opacity: 0;
            transform: translateX(-10px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-text {
        animation: fadeInText 0.5s ease-out forwards;
        opacity: 0;
    }

    /* Chevron Bounce Animation */
    @keyframes chevronBounce {
        0%, 100% {
            transform: translateY(0);
            opacity: 1;
        }
        50% {
            transform: translateY(5px);
            opacity: 0.7;
        }
    }

    .animate-chevron-bounce {
        animation: chevronBounce 1.5s ease-in-out infinite;
    }

    /* Scroll Prompt Fade In */
    @keyframes scrollPromptFade {
        0% {
            opacity: 0;
            transform: translateX(-50%) translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    .animate-scroll-prompt {
        animation: scrollPromptFade 1s ease-out 1s forwards;
        opacity: 0;
    }
</style>
