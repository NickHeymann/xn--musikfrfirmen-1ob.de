{{-- Footer Component - Canva Redesign --}}
<footer class="bg-[#1a1a1a]" style="font-family: 'Poppins', sans-serif">
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
                            href="mailto:kontakt@musikfuerfirmen.de"
                            class="hover:text-[#2DD4A8] transition-colors"
                        >
                            kontakt@musikfuerfirmen.de
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
        <p class="text-sm text-gray-400 text-center font-light" style="font-family: 'Poppins', sans-serif">
            © {{ date('Y') }} musikfürfirmen.de
        </p>
    </div>
</footer>
