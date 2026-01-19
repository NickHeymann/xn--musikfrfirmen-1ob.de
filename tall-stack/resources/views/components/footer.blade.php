{{-- Footer Component --}}
<footer class="bg-white" style="font-family: 'Poppins', sans-serif">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row justify-center gap-16 md:gap-32">
            {{-- Kontakt --}}
            <div>
                <h4 class="text-base font-semibold text-black mb-6">
                    Kontakt
                </h4>
                <div class="space-y-3 text-[15px] text-black font-light">
                    <p>
                        <a
                            href="mailto:info@musikfuerfirmen.de"
                            class="hover:underline transition-colors"
                        >
                            info@musikfuerfirmen.de
                        </a>
                    </p>
                    <p>
                        <a
                            href="tel:+491234567890"
                            class="hover:underline transition-colors"
                        >
                            +49 123 456 7890
                        </a>
                    </p>
                </div>
            </div>

            {{-- Info --}}
            <div>
                <h4 class="text-base font-semibold text-black mb-6">Info</h4>
                <div class="space-y-3 text-[15px]">
                    <p>
                        <a
                            href="/impressum"
                            class="text-black hover:underline transition-colors font-light"
                        >
                            Impressum
                        </a>
                    </p>
                    <p>
                        <a
                            href="/datenschutz"
                            class="text-black hover:underline transition-colors font-light"
                        >
                            Datenschutz
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-black py-4">
        <p class="text-sm text-white text-center font-light" style="font-family: 'Poppins', sans-serif">
            © {{ date('Y') }} musikfürfirmen.de
        </p>
    </div>
</footer>
