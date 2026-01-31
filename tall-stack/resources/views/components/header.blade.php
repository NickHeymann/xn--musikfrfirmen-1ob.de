{{-- Navigation Header - Scroll-Based Color System --}}
<header class="fixed top-0 left-0 right-0"
        style="z-index: 999999 !important; position: fixed !important;"
        x-data="{
            isOpen: false,
            isDark: true,
            bgColor: '#000000',
            init() {
                const updateColors = () => {
                    const scrollY = window.scrollY;

                    // Hero (0-800px): schwarz
                    if (scrollY < 800) {
                        this.bgColor = '#000000';
                        this.isDark = true;
                    }
                    // WhatsApp CTA (~800-1400px): grün
                    else if (scrollY < 1400) {
                        this.bgColor = '#2DD4A8';
                        this.isDark = false;
                    }
                    // Service Cards (~1400-2400px): weiß
                    else if (scrollY < 2400) {
                        this.bgColor = '#ffffff';
                        this.isDark = false;
                    }
                    // Event Gallery (~2400-3400px): dunkelgrau
                    else if (scrollY < 3400) {
                        this.bgColor = '#111827';
                        this.isDark = true;
                    }
                    // Benefits Section (~3400-4400px): weiß
                    else if (scrollY < 4400) {
                        this.bgColor = '#ffffff';
                        this.isDark = false;
                    }
                    // Team Section (~4400-5400px): weiß
                    else if (scrollY < 5400) {
                        this.bgColor = '#ffffff';
                        this.isDark = false;
                    }
                    // FAQ (~5400-6400px): weiß
                    else if (scrollY < 6400) {
                        this.bgColor = '#ffffff';
                        this.isDark = false;
                    }
                    // Footer: weiß
                    else {
                        this.bgColor = '#ffffff';
                        this.isDark = false;
                    }
                };

                window.addEventListener('scroll', updateColors, { passive: true });
                updateColors();
            }
        }"
        :style="{ backgroundColor: bgColor }"
        style="transition: background-color 0.2s ease-out;"
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
            <a href="/#angebote"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Angebote
            </a>
        </div>

        {{-- Logo --}}
        <a href="/" class="text-2xl md:text-3xl font-bold"
           style="font-family: 'Poppins', sans-serif"
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

        {{-- Mobile Hamburger --}}
        <button @click="isOpen = !isOpen"
                class="md:hidden relative w-8 h-6 flex flex-col justify-center items-center gap-1.5 focus:outline-none z-[99999]">
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
