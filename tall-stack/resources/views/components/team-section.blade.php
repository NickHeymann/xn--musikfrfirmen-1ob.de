{{-- Team Section - Moin aus Hamburg --}}
<section class="team-section py-24 bg-white" style="font-family: 'Poppins', sans-serif" id="ueberuns">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-5xl font-bold text-[#1a1a1a] text-center mb-6">
            Moin aus Hamburg.
        </h2>

        {{-- Intro Text --}}
        <p class="text-center text-base md:text-lg text-[#1a1a1a] max-w-3xl mx-auto mb-20">
            musikfürfirmen.de ist ein Hamburger Unternehmen spezialisiert<br class="hidden md:block">
            auf den musikalischen Aspekt von Firmenevents.
        </p>

        {{-- Two Column Layout: Jonas Quote + Photo | Nick Photo + Quote --}}
        <div class="grid md:grid-cols-2 gap-16 md:gap-20 mb-20">
            {{-- Jonas Section --}}
            <div class="flex flex-col gap-8">
                {{-- Jonas Quote --}}
                <div class="order-1">
                    <p class="text-sm md:text-base text-[#1a1a1a] italic leading-relaxed mb-4">
                        "Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt."
                    </p>
                    <p class="text-sm md:text-base font-semibold text-[#1a1a1a]">
                        Jonas Glamann, Co-Founder
                    </p>
                </div>

                {{-- Jonas Photo --}}
                <div class="order-2">
                    <div class="relative w-full max-w-xs mx-auto md:mx-0 aspect-[3/4] rounded-3xl overflow-hidden" style="background-color: #b8b8b8;">
                        <img
                            src="/images/team/jonas.png"
                            alt="Jonas Glamann"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>

            {{-- Nick Section --}}
            <div class="flex flex-col gap-8">
                {{-- Nick Photo --}}
                <div class="order-1">
                    <div class="relative w-full max-w-xs mx-auto md:ml-auto md:mr-0 aspect-[3/4] rounded-3xl overflow-hidden" style="background-color: #b8b8b8;">
                        <img
                            src="/images/team/nick.png"
                            alt="Nick Heymann"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                </div>

                {{-- Nick Quote --}}
                <div class="order-2">
                    <p class="text-sm md:text-base text-[#1a1a1a] italic leading-relaxed mb-4">
                        Nick Text
                    </p>
                    <p class="text-sm md:text-base font-semibold text-[#1a1a1a]">
                        Nick Heymann, Co-Founder
                    </p>
                </div>
            </div>
        </div>

        {{-- More About Us Link --}}
        <div class="text-center">
            <a href="/#ueberuns" class="inline-block text-sm md:text-base text-[#1a1a1a] font-normal border-b border-[#1a1a1a] hover:border-[#2DD4A8] hover:text-[#2DD4A8] transition-colors duration-300">
                Mehr über uns erfahren
            </a>
        </div>
    </div>
</section>
