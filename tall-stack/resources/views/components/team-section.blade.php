{{-- Team Section - Canva Redesign --}}
<section class="team-section py-20" style="font-family: 'Poppins', sans-serif">
    {{-- Intro Text --}}
    <div class="text-center max-w-3xl mx-auto mb-16 px-6">
        <p class="text-lg md:text-xl text-[#4a5568] leading-relaxed">
            musikfürfirmen ist ein Hamburger Unternehmen spezialisiert auf den musikalischen Aspekt von Firmenevents. Wir verbinden professionelle Musik mit persönlicher Beratung.
        </p>
    </div>

    {{-- Team Members Grid --}}
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 md:gap-16">
            {{-- Jonas Glamann --}}
            <div class="team-member">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    {{-- Photo --}}
                    <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 rounded-full overflow-hidden bg-[#D4F4E8]">
                        <img
                            src="/images/team/jonas.png"
                            alt="Jonas Glamann"
                            class="w-full h-full object-cover object-top"
                            loading="lazy"
                        >
                    </div>

                    {{-- Bio Content --}}
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-[#1a1a1a] mb-1">Jonas Glamann</h3>
                        <p class="text-[#2DD4A8] font-medium mb-4">Co-Founder</p>
                        <p class="text-[#4a5568] leading-relaxed mb-4">
                            Seit 7 Jahren helfe ich dabei, unvergessliche Momente auf die Bühne zu bringen. Als Profi-Musiker bei Band- und Solokünstlern wurde mir klar, wie wichtig professionelle Musikbegleitung für Events ist.
                        </p>
                        <a href="/uber-uns" class="inline-flex items-center gap-2 text-[#2DD4A8] font-medium hover:underline">
                            Mehr erfahren
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Nick Heymann --}}
            <div class="team-member">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    {{-- Photo --}}
                    <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 rounded-full overflow-hidden bg-[#D4F4E8]">
                        <img
                            src="/images/team/nick.png"
                            alt="Nick Heymann"
                            class="w-full h-full object-cover object-top"
                            loading="lazy"
                        >
                    </div>

                    {{-- Bio Content --}}
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-[#1a1a1a] mb-1">Nick Heymann</h3>
                        <p class="text-[#2DD4A8] font-medium mb-4">Co-Founder</p>
                        <p class="text-[#4a5568] leading-relaxed mb-4">
                            Mit technischem Know-how und Leidenschaft für Veranstaltungen kümmere ich mich darum, dass bei jedem Event die Technik perfekt läuft und unsere Künstler optimal präsentiert werden.
                        </p>
                        <a href="/uber-uns" class="inline-flex items-center gap-2 text-[#2DD4A8] font-medium hover:underline">
                            Mehr erfahren
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- More About Us Link --}}
        <div class="text-center mt-12">
            <a href="/uber-uns" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-semibold rounded-full hover:bg-[#1a1a1a] hover:text-white transition-all duration-300">
                Mehr Über Uns erfahren
            </a>
        </div>
    </div>
</section>
