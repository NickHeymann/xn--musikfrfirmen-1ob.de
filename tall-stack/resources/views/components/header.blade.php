{{-- Navigation Header - Canva Redesign --}}
<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        x-data="{
            isOpen: false,
            lastScroll: 0,
            hidden: false,
            isDark: false,
            init() {
                // Scroll hide/show logic
                window.addEventListener('scroll', () => {
                    const currentScroll = window.pageYOffset;
                    if (currentScroll <= 0) {
                        this.hidden = false;
                        return;
                    }
                    if (currentScroll > this.lastScroll && !this.isOpen) {
                        this.hidden = true;
                    } else {
                        this.hidden = false;
                    }
                    this.lastScroll = currentScroll;
                });

                // Section theme detection
                const sections = document.querySelectorAll('[data-section-theme]');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                            const theme = entry.target.getAttribute('data-section-theme');
                            this.isDark = theme === 'dark';
                        }
                    });
                }, {
                    threshold: [0.5],
                    rootMargin: '-54px 0px 0px 0px' // Half of header height
                });

                sections.forEach(section => observer.observe(section));
            }
        }"
        :class="{
            '-translate-y-full': hidden,
            'bg-transparent border-transparent': isDark,
            'bg-white border-gray-200': !isDark
        }">

    <nav class="w-full px-6 md:px-[80px] h-[108px] flex items-center justify-between">
        {{-- Left Navigation (Desktop) --}}
        <div class="hidden md:flex items-center gap-[40px]">
            <a href="/#kontakt"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#4a4a4a]'">
                Jetzt Buchen
            </a>
            <a href="/#waswirbieten"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#4a4a4a]'">
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
               :class="isDark ? 'text-white' : 'text-[#4a4a4a]'">
                Über uns
            </a>
            <a href="/#kontakt"
               class="text-lg hover:text-[#2DD4A8] transition-colors font-thin"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#4a4a4a]'">
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
            <a href="/#kontakt" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Jetzt Buchen
            </a>
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

{{-- Spacer for fixed header --}}
<div class="h-[108px]"></div>
