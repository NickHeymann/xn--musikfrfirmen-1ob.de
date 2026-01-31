{{-- Navigation Header - Canva Redesign --}}
<header class="fixed top-0 left-0 right-0 z-[99999]"
        x-data="{
            isOpen: false,
            isDark: true,
            bgColor: '#000000',
            ticking: false,
            init() {
                const updateTheme = () => {
                    const sections = document.querySelectorAll('[data-section-bg]');
                    let newBgColor = '#000000';
                    let newIsDark = true;

                    for (const section of sections) {
                        const rect = section.getBoundingClientRect();
                        if (rect.top <= 108 && rect.bottom > 108) {
                            newBgColor = section.getAttribute('data-section-bg') || '#ffffff';
                            newIsDark = section.getAttribute('data-section-theme') === 'dark';
                            break;
                        }
                    }

                    if (this.bgColor !== newBgColor || this.isDark !== newIsDark) {
                        this.bgColor = newBgColor;
                        this.isDark = newIsDark;
                    }
                    this.ticking = false;
                };

                // Throttled scroll handler with requestAnimationFrame
                window.addEventListener('scroll', () => {
                    if (!this.ticking) {
                        window.requestAnimationFrame(() => updateTheme());
                        this.ticking = true;
                    }
                }, { passive: true });

                // Initial detection
                setTimeout(() => updateTheme(), 0);
            }
        }"
        style="transition: background-color 0.15s ease-out;"
        :style="{ backgroundColor: bgColor }"
        :class="!isDark ? 'shadow-sm' : ''">

    <nav class="w-full px-6 md:px-[80px] h-[108px] flex items-center justify-between">
        {{-- Left Navigation (Desktop) --}}
        <div class="hidden md:flex items-center gap-[40px]">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="text-lg hover:text-[#2DD4A8] transition-colors font-thin cursor-pointer"
                style="font-family: 'Poppins', sans-serif"
                :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Kostenloses Erstgespräch
            </button>
            <a href="/#waswirbieten"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Angebote
            </a>
        </div>

        {{-- Centered Logo --}}
        <a href="/"
           class="text-[24px] md:text-[30px] font-normal font-['Poppins',sans-serif] hover:text-[#2DD4A8] transition-colors absolute left-1/2 -translate-x-1/2 leading-none whitespace-nowrap"
           :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
            musikfürfirmen.de
        </a>

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

        {{-- Mobile Menu Button --}}
        <button @click="isOpen = !isOpen"
                class="md:hidden p-2 ml-auto transition-colors"
                :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
            <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- Mobile Menu - Always solid background --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-gray-200">
        <div class="px-6 py-4 space-y-4">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                @click="isOpen = false"
                class="block w-full text-left text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Kostenloses Erstgespräch
            </button>
            <a href="/#waswirbieten" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Angebote
            </a>
            <a href="/#ueberuns" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Über uns
            </a>
            <a href="/#kontakt" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Kontakt
            </a>
        </div>
    </div>
</header>
