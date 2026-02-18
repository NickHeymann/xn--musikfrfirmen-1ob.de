{{-- Navigation Header --}}
<script>
document.addEventListener('alpine:init', function () {
    Alpine.data('mffHeader', function () {
        return {
            isOpen: false,
            isDark: true,
            isGreen: false,
            bgColor: '#000000',
            heroCTAsVisible: true,
            init: function () {
                var self = this;
                var getH = function () { return window.innerWidth >= 1024 ? 108 : 80; };
                var updateTheme = function () {
                    var hH = getH();
                    var els = document.querySelectorAll('[data-section-bg]');
                    var bg = '#000000'; var dark = true; var best = null; var bz = -1;
                    for (var i = 0; i < els.length; i++) {
                        var r = els[i].getBoundingClientRect();
                        if (r.top <= hH + 50 && r.bottom > hH) {
                            var z = parseInt(getComputedStyle(els[i]).zIndex) || 0;
                            if (z > bz) { bz = z; best = els[i]; }
                        }
                    }
                    if (best) {
                        bg = best.getAttribute('data-section-bg') || '#ffffff';
                        dark = best.getAttribute('data-section-theme') === 'dark';
                    }
                    var green = bg === '#C8E6DC';
                    if (self.bgColor !== bg || self.isDark !== dark || self.isGreen !== green) {
                        self.bgColor = bg; self.isDark = dark; self.isGreen = green;
                    }
                };
                var updateCTAs = function () {
                    if (window.innerWidth < 1024) { self.heroCTAsVisible = true; return; }
                    self.heroCTAsVisible = window.scrollY < window.innerHeight * 0.35;
                };
                window.addEventListener('scroll', updateTheme, { passive: true });
                window.addEventListener('scroll', updateCTAs, { passive: true });
                window.addEventListener('resize', updateTheme);
                window.addEventListener('resize', updateCTAs);
                setTimeout(function () { updateTheme(); updateCTAs(); }, 150);
                setTimeout(function () {
                    var el = document.querySelector('header[x-data]');
                    if (el) { el.classList.add('header-smooth-transition'); }
                }, 600);
            }
        };
    });
});
</script>

<header class="fixed top-0 left-0 right-0 header-smooth-transition"
        style="z-index: 999999 !important;"
        x-data="mffHeader"
        :style="{ backgroundColor: bgColor }">

    <nav class="w-full px-4 sm:px-6 lg:px-[80px] h-[80px] lg:h-[108px] flex items-center justify-between relative">
        {{-- Logo --}}
        <a href="#"
           @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })"
           class="text-[20px] sm:text-[22px] lg:text-[24px] font-light hover:text-[#C8E6DC] transition-colors leading-none tracking-wide z-10 cursor-pointer shrink-0"
           style="font-family: 'Poppins', sans-serif"
           :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
            <span>musikfürfirmen</span>
        </a>

        {{-- Center Navigation (Large Desktop only) --}}
        <div class="hidden lg:flex items-center gap-[40px] xl:gap-[56px] text-sm xl:text-base absolute left-1/2 -translate-x-1/2">
            <a href="/#waswirbieten"
               class="hover:text-[#C8E6DC] transition-colors font-light"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Dienstleistungen
            </a>
            <a href="/#ueberuns"
               class="hover:text-[#C8E6DC] transition-colors font-light"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Über uns
            </a>
            <a href="/#kontakt"
               class="hover:text-[#C8E6DC] transition-colors font-light"
               style="font-family: 'Poppins', sans-serif"
               :class="isDark ? 'text-white' : 'text-[#1a1a1a]'">
                Kontakt
            </a>
        </div>

        {{-- Right CTAs — appear once hero is scrolled away. x-cloak hides until Alpine runs. --}}
        <div class="lg:flex items-center gap-3 shrink-0"
             x-cloak
             x-show="!heroCTAsVisible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1">
            <button
                onclick="Livewire.dispatch('openBookingModal')"
                class="bg-white text-black px-4 py-2 rounded-full text-sm font-light cursor-pointer overflow-hidden transition-all duration-200 hover:-translate-y-[1px]"
                style="font-family: 'Poppins', sans-serif">
                Kostenloses Erstgespräch
            </button>
            <button
                onclick="Livewire.dispatch('openMFFCalculator')"
                class="bg-white text-black px-4 py-2 rounded-full text-sm font-light cursor-pointer whitespace-nowrap overflow-hidden transition-all duration-200 hover:-translate-y-[1px]"
                style="font-family: 'Poppins', sans-serif">
                Unverbindliches Angebot
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
                Dienstleistungen
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
