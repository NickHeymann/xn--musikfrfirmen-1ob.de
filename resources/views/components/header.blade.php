{{-- Header Component - Production Accurate Implementation --}}
<div x-data="{
    isVisible: true,
    lastScrollY: 0,
    isMobileMenuOpen: false,
    basePath: '{{ config('app.url') }}',

    init() {
        // Scroll handler with 5px hysteresis
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY <= 10) {
                this.isVisible = true;
            } else if (currentScrollY > this.lastScrollY + 5) {
                this.isVisible = false;
                this.isMobileMenuOpen = false;
            } else if (currentScrollY < this.lastScrollY - 5) {
                this.isVisible = true;
            }

            this.lastScrollY = currentScrollY;
        }, { passive: true });
    },

    handleNavClick(event, href, isAnchor) {
        if (!isAnchor) {
            // Regular page navigation
            return;
        }

        event.preventDefault();
        const targetId = href.replace('/#', '');

        // Check if we're on the main page
        const isOnMainPage = window.location.pathname === '/' ||
                             window.location.pathname === this.basePath ||
                             window.location.pathname === this.basePath + '/';

        if (isOnMainPage) {
            // On main page - just scroll
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                window.history.pushState(null, '', this.basePath + '/' + href.replace('/', ''));
            }
        } else {
            // On other page - navigate to main page with anchor
            window.location.href = this.basePath + '/#' + targetId;
        }
        this.isMobileMenuOpen = false;
    }
}">
    {{-- Header --}}
    <header
        id="header"
        :class="isVisible ? 'translate-y-0' : '-translate-y-full'"
        class="fixed top-0 left-0 right-0 z-[9999] transition-transform duration-300 ease-in-out bg-white"
        style="background-color: #ffffff">

        <div class="header-inner w-full px-[80px]">
            <div class="flex items-center justify-between h-[108px]">
                {{-- Logo/Title --}}
                <div class="header-title">
                    <a
                        href="/"
                        class="text-[32px] font-medium text-black hover:opacity-70 transition-opacity duration-200"
                        style="font-family: 'Poppins', sans-serif">
                        musikfürfirmen.de
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <nav class="header-nav hidden md:flex items-center gap-14">
                    @foreach (config('site.nav_links') as $item)
                        <a
                            href="{{ $item['href'] }}"
                            @click="handleNavClick($event, '{{ $item['href'] }}', {{ $item['isAnchor'] ? 'true' : 'false' }})"
                            class="text-[17px] font-light text-black hover:opacity-70 transition-opacity duration-200"
                            style="font-family: 'Poppins', sans-serif">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- Mobile Menu Button --}}
                <button
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="header-burger md:hidden p-2"
                    :aria-label="isMobileMenuOpen ? 'Menü schließen' : 'Menü öffnen'">
                    <div class="burger-box w-6 flex flex-col gap-[6px]">
                        <span
                            :class="isMobileMenuOpen ? 'rotate-45 translate-y-[3.5px]' : ''"
                            class="w-full h-[1px] bg-black transform transition-all duration-300 origin-center">
                        </span>
                        <span
                            :class="isMobileMenuOpen ? '-rotate-45 -translate-y-[3.5px]' : ''"
                            class="w-full h-[1px] bg-black transform transition-all duration-300 origin-center">
                        </span>
                    </div>
                </button>
            </div>
        </div>

        {{-- Header Border --}}
        <div class="h-[1px]" style="background-color: #e5e7eb"></div>
    </header>

    {{-- Mobile Menu Overlay --}}
    <div
        :class="isMobileMenuOpen ? 'opacity-100 visible' : 'opacity-0 invisible pointer-events-none'"
        class="fixed inset-0 z-[9998] md:hidden transition-all duration-300">

        {{-- Background Overlay --}}
        <div
            @click="isMobileMenuOpen = false"
            class="absolute inset-0 bg-black/20">
        </div>

        {{-- Menu Panel --}}
        <nav
            :class="isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
            class="absolute top-[108px] left-0 right-0 bg-white shadow-lg transform transition-transform duration-300"
            style="background-color: #ffffff">
            <div class="py-4 px-6">
                @foreach (config('site.nav_links') as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @click="handleNavClick($event, '{{ $item['href'] }}', {{ $item['isAnchor'] ? 'true' : 'false' }})"
                        class="block py-4 text-base font-normal text-black hover:opacity-70 transition-opacity"
                        style="font-family: 'Poppins', sans-serif">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </div>

    {{-- Header Spacer --}}
    <div class="h-[108px]"></div>
</div>
