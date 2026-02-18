{{-- Footer Component - Compact Responsive --}}
<footer id="kontakt" class="bg-[#1a1a1a] relative z-[27] scroll-mt-[80px] lg:scroll-mt-[108px] flex flex-col" data-section-bg="#1a1a1a" data-section-theme="dark" style="font-family: 'Poppins', sans-serif">
    <div class="max-w-7xl mx-auto px-6 py-10 md:py-16 flex-grow">
        <div class="flex flex-col md:flex-row justify-center gap-10 md:gap-32 items-start">
            {{-- Kontakt --}}
            <div>
                <h4 class="text-sm md:text-base font-semibold text-white mb-4 md:mb-6">
                    Kontakt
                </h4>
                <div class="space-y-2 md:space-y-3 text-[13px] md:text-[15px] text-gray-300 font-light">
                    <p>
                        <a href="mailto:kontakt@musikfuerfirmen.com" class="hover:text-[#C8E6DC] transition-colors">
                            kontakt@musikfürfirmen.com
                        </a>
                    </p>
                    <p>
                        <a href="/erstgespraech" class="hover:text-[#C8E6DC] transition-colors">
                            Kostenloses Erstgespräch
                        </a>
                    </p>
                    <p>
                        <a href="#" onclick="event.preventDefault(); Livewire.dispatch('openMFFCalculator')" class="hover:text-[#C8E6DC] transition-colors">
                            Jetzt Angebot einholen
                        </a>
                    </p>
                </div>
            </div>

            {{-- Info --}}
            <div>
                <h4 class="text-sm md:text-base font-semibold text-white mb-4 md:mb-6">Info</h4>
                <div class="space-y-2 md:space-y-3 text-[13px] md:text-[15px]">
                    <p>
                        <a href="#ueberuns" class="text-gray-300 hover:text-[#C8E6DC] transition-colors font-light">
                            Über uns
                        </a>
                    </p>
                    <p>
                        <a href="/impressum" class="text-gray-300 hover:text-[#C8E6DC] transition-colors font-light">
                            Impressum
                        </a>
                    </p>
                    <p>
                        <a href="/datenschutz" class="text-gray-300 hover:text-[#C8E6DC] transition-colors font-light">
                            Datenschutz
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright - Pushed to bottom --}}
    <div class="mt-auto py-4 text-center">
        <p class="text-sm text-gray-500 font-light">
            &copy; {{ date('Y') }} musikfürfirmen.de
        </p>
    </div>

    {{-- Overscroll safety extension --}}
    <div class="bg-[#1a1a1a] h-[20px]" aria-hidden="true"></div>
</footer>
