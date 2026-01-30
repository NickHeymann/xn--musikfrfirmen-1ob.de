{{-- Footer Component - Canva Redesign --}}
<footer id="kontakt" class="bg-[#1a1a1a] scroll-mt-[108px]" style="font-family: 'Poppins', sans-serif">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row justify-center gap-16 md:gap-32">
            {{-- Kontakt --}}
            <div>
                <h4 class="text-base font-semibold text-white mb-6">
                    Kontakt
                </h4>
                <div class="space-y-3 text-[15px] text-gray-300 font-light">
                    <p>
                        <a
                            href="mailto:kontakt@xn--musikfrfirmen-1ob.de"
                            class="hover:text-[#2DD4A8] transition-colors"
                        >
                            kontakt@musikfürfirmen.de
                        </a>
                    </p>
                    <p>
                        <a
                            href="tel:+491746935533"
                            class="hover:text-[#2DD4A8] transition-colors"
                        >
                            +49 174 6935533
                        </a>
                    </p>
                </div>
            </div>

            {{-- Info --}}
            <div>
                <h4 class="text-base font-semibold text-white mb-6">Info</h4>
                <div class="space-y-3 text-[15px]">
                    <p>
                        <a
                            href="/uber-uns"
                            class="text-gray-300 hover:text-[#2DD4A8] transition-colors font-light"
                        >
                            Über uns
                        </a>
                    </p>
                    <p>
                        <a
                            href="/impressum"
                            class="text-gray-300 hover:text-[#2DD4A8] transition-colors font-light"
                        >
                            Impressum
                        </a>
                    </p>
                    <p>
                        <a
                            href="/datenschutz"
                            class="text-gray-300 hover:text-[#2DD4A8] transition-colors font-light"
                        >
                            Datenschutz
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800 py-6">
        <div class="flex flex-col items-center gap-6">
            {{-- Animated Logo --}}
            <div
                class="footer-logo-container relative inline-block"
                x-data="{ hovering: false }"
                @mouseenter="hovering = true"
                @mouseleave="hovering = false"
            >
                <span class="text-2xl font-poppins font-semibold text-white">musikfürfirmen</span>

                {{-- Animated Musical Notes --}}
                <div class="notes-container absolute inset-0 pointer-events-none overflow-visible">
                    <template x-for="i in 5" :key="i">
                        <div
                            class="musical-note absolute bottom-0 text-2xl"
                            :class="{ 'note-animate': hovering }"
                            :style="`
                                left: ${20 + i * 15}%;
                                animation-delay: ${i * 0.1}s;
                                color: #b8ddd2;
                                opacity: 0;
                                transform: translateY(0);
                            `"
                            x-show="hovering"
                            x-transition:enter="transition ease-out duration-1500"
                            x-transition:enter-start="opacity-0 translate-y-0"
                            x-transition:enter-end="opacity-0 -translate-y-20"
                        >
                            ♪
                        </div>
                    </template>
                </div>
            </div>

            <p class="text-sm text-gray-400 text-center font-light font-poppins">
                © {{ date('Y') }} musikfürfirmen.de
            </p>
        </div>
    </div>
</footer>

<style>
.musical-note.note-animate {
    animation: float-up 1.5s ease-out forwards;
}
</style>
