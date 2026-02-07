{{-- Navigation Header - Canva Redesign --}}
<header class="fixed top-0 left-0 right-0 header-smooth-transition"
        style="z-index: 999999 !important;"
        x-data="{
            isOpen: false,
            isDark: true,
            bgColor: '#000000',
            init() {
                const updateTheme = () => {
                    const sections = document.querySelectorAll('[data-section-bg]');
                    let newBgColor = '#000000';
                    let newIsDark = true;

                    for (const section of sections) {
                        const rect = section.getBoundingClientRect();
                        // Check if section starts at or above header top (0px)
                        // and extends below it - header adopts this section's color
                        if (rect.top <= 0 && rect.bottom > 0) {
                            newBgColor = section.getAttribute('data-section-bg') || '#ffffff';
                            newIsDark = section.getAttribute('data-section-theme') === 'dark';
                            break;
                        }
                    }

                    if (this.bgColor !== newBgColor || this.isDark !== newIsDark) {
                        this.bgColor = newBgColor;
                        this.isDark = newIsDark;
                    }
                };

                // Update on scroll
                window.addEventListener('scroll', updateTheme, { passive: true });

                // Initial detection
                setTimeout(() => updateTheme(), 100);
            }
        }"
        :style="{ backgroundColor: bgColor }"
        :class="!isDark ? 'shadow-sm' : ''">

    <nav class="w-full px-4 sm:px-6 lg:px-[80px] h-[80px] lg:h-[108px] flex items-center justify-between relative">
        {{-- Logo - Left on Mobile/Tablet, Centered on Desktop --}}
        <a href="#"
           @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })"
           class="text-[20px] sm:text-[22px] lg:text-[28px] font-light hover:text-[#C8E6DC] transition-colors leading-none tracking-wide lg:absolute lg:left-1/2 lg:-translate-x-1/2 z-10 cursor-pointer"
           style="font-family: 'Poppins', sans-serif"
           :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
            <span class="hidden sm:inline">musikfürfirmen.de</span>
            <span class="inline sm:hidden">musikfürfirmen</span>
        </a>

        {{-- Left Navigation (Large Desktop only) --}}
        <div class="hidden lg:flex items-center gap-[32px] xl:gap-[40px] text-sm xl:text-lg">
            <a href="/#waswirbieten"
               class="hover:text-[#C8E6DC] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Angebote
            </a>
        </div>

        {{-- Right Navigation (Large Desktop only) --}}
        <div class="hidden lg:flex items-center gap-[32px] xl:gap-[40px] text-sm xl:text-lg">
            <a href="/#ueberuns"
               class="hover:text-[#C8E6DC] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Über uns
            </a>
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="hover:text-[#C8E6DC] transition-colors font-thin cursor-pointer"
                style="font-family: 'Poppins', sans-serif"
                :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Kontakt
            </button>
        </div>

        {{-- Mobile/Tablet Hamburger --}}
        <button @click="isOpen = !isOpen"
                class="lg:hidden relative w-7 h-5 flex flex-col justify-center items-center gap-1 focus:outline-none z-20">
            <span class="w-5 h-[1.5px] rounded-full transition-all duration-300"
                  :class="isDark ? 'bg-white' : 'bg-[#1a1a1a]'"
                  :style="{ transform: isOpen ? 'rotate(45deg) translateY(3px)' : 'rotate(0)' }"></span>
            <span class="w-5 h-[1.5px] rounded-full transition-all duration-300"
                  :class="[isDark ? 'bg-white' : 'bg-[#1a1a1a]', isOpen ? 'opacity-0' : 'opacity-100']"></span>
            <span class="w-5 h-[1.5px] rounded-full transition-all duration-300"
                  :class="isDark ? 'bg-white' : 'bg-[#1a1a1a]'"
                  :style="{ transform: isOpen ? 'rotate(-45deg) translateY(-3px)' : 'rotate(0)' }"></span>
        </button>
    </nav>

    {{-- Mobile/Tablet Menu Overlay --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-100%]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-100%]"
         @click.away="isOpen = false"
         class="lg:hidden fixed left-0 right-0 bg-black shadow-lg overflow-hidden"
         :style="{ top: window.innerWidth >= 1024 ? '108px' : '80px' }"
         style="z-index: 999998;">
        <nav class="flex flex-col px-4 sm:px-6 py-6 sm:py-8 gap-4 sm:gap-6" style="font-family: 'Poppins', sans-serif">
            <a href="/#waswirbieten"
               @click="isOpen = false"
               class="text-base sm:text-lg hover:text-[#C8E6DC] transition-colors font-thin text-white py-2">
                Angebote
            </a>
            <a href="/#ueberuns"
               @click="isOpen = false"
               class="text-base sm:text-lg hover:text-[#C8E6DC] transition-colors font-thin text-white py-2">
                Über uns
            </a>
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                @click="isOpen = false"
                class="text-base sm:text-lg text-left hover:text-[#C8E6DC] transition-colors font-thin text-white py-2">
                Kontakt
            </button>
        </nav>
    </div>
</header>
