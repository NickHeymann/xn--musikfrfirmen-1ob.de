{{-- Navigation Header --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 transition-transform duration-300"
        x-data="{
            isOpen: false,
            lastScroll: 0,
            hidden: false,
            init() {
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
            }
        }"
        :class="{ '-translate-y-full': hidden }">

    <nav class="w-full pl-[80px] pr-[80px] h-[108px] flex items-center justify-between">
        {{-- Logo --}}
        <a href="/" class="text-[32px] font-medium text-[#1a1a1a] font-['Poppins',sans-serif] hover:text-[#2DD4A8] transition-colors">
            musikfürfirmen.de
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center gap-[60px]">
            <a href="/#waswirbieten" class="text-lg text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-thin" style="font-family: 'Poppins', sans-serif">
                Was wir bieten
            </a>
            <a href="/uber-uns" class="text-lg text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-thin" style="font-family: 'Poppins', sans-serif">
                Über uns
            </a>
            <a href="/#faq" class="text-lg text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-thin" style="font-family: 'Poppins', sans-serif">
                FAQ
            </a>
        </div>

        {{-- Mobile Menu Button --}}
        <button @click="isOpen = !isOpen" class="md:hidden p-2 text-[#1a1a1a]">
            <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- Mobile Menu --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-gray-200">
        <div class="px-6 py-4 space-y-4">
            <a href="/#waswirbieten" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Was wir bieten
            </a>
            <a href="/uber-uns" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                Über uns
            </a>
            <a href="/#faq" @click="isOpen = false" class="block text-[#4a4a4a] hover:text-[#2DD4A8] transition-colors font-medium">
                FAQ
            </a>
        </div>
    </div>
</header>

{{-- Spacer for fixed header --}}
<div class="h-[108px]"></div>
