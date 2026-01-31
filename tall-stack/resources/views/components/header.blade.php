{{-- Navigation Header - Canva Redesign --}}
<header class="fixed top-0 left-0 right-0"
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
        style="transition: background-color 0.2s ease-out;"
        :class="!isDark ? 'shadow-sm' : ''">

    <nav class="w-full px-6 md:px-[80px] h-[108px] flex items-center">
        {{-- Left Navigation (Desktop) --}}
        <div class="hidden md:flex items-center gap-[40px]">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="text-lg hover:text-[#2DD4A8] transition-colors font-thin cursor-pointer"
                style="font-family: 'Poppins', sans-serif"
                :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Kostenloses Erstgespräch
            </button>
            <a href="/#angebote"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Angebote
            </a>
        </div>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Logo (filigran, lighter weight) --}}
        <a href="/"
           class="hidden md:block text-[20px] font-light tracking-wide hover:text-[#2DD4A8] transition-colors leading-none whitespace-nowrap"
           style="font-family: 'Poppins', sans-serif"
           :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
            musikfürfirmen.de
        </a>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Right Navigation (Desktop) --}}
        <div class="hidden md:flex items-center gap-[40px]">
            <a href="/#ueberuns"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Über uns
            </a>
            <a href="/#kontakt"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Kontakt
            </a>
        </div>

        {{-- Mobile Hamburger --}}
        <button @click="isOpen = !isOpen"
                class="md:hidden relative w-8 h-6 flex flex-col justify-center items-center gap-1.5 focus:outline-none">
            <span class="w-6 h-0.5 transition-all duration-300"
                  :class="isDark ? 'bg-white' : 'bg-[#1a1a1a]'"
                  :style="{ transform: isOpen ? 'rotate(45deg) translateY(4px)' : 'rotate(0)' }"></span>
            <span class="w-6 h-0.5 transition-all duration-300"
                  :class="[isDark ? 'bg-white' : 'bg-[#1a1a1a]', isOpen ? 'opacity-0' : 'opacity-100']"></span>
            <span class="w-6 h-0.5 transition-all duration-300"
                  :class="isDark ? 'bg-white' : 'bg-[#1a1a1a]'"
                  :style="{ transform: isOpen ? 'rotate(-45deg) translateY(-4px)' : 'rotate(0)' }"></span>
        </button>
    </nav>

    {{-- Mobile Menu Overlay --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-100%]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-100%]"
         @click.away="isOpen = false"
         class="md:hidden fixed top-[108px] left-0 right-0 bg-white shadow-lg"
         style="z-index: 999998;">
        <nav class="flex flex-col px-6 py-8 gap-6" style="font-family: 'Poppins', sans-serif">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                @click="isOpen = false"
                class="text-lg text-left hover:text-[#2DD4A8] transition-colors font-thin text-[#1a1a1a]">
                Kostenloses Erstgespräch
            </button>
            <a href="/#angebote"
               @click="isOpen = false"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin text-[#1a1a1a]">
                Angebote
            </a>
            <a href="/#ueberuns"
               @click="isOpen = false"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin text-[#1a1a1a]">
                Über uns
            </a>
            <a href="/#kontakt"
               @click="isOpen = false"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin text-[#1a1a1a]">
                Kontakt
            </a>
        </nav>
    </div>
</header>
