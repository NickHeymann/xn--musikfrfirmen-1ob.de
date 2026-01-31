{{-- Team Section - Moin aus Hamburg --}}
<section
    class="team-section py-24 bg-white" data-section-theme="light" data-section-bg="#ffffff"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ modalOpen: false, currentMember: null }"
>
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-[#1a1a1a] text-center mb-4 md:mb-6 px-4">
            Moin aus Hamburg.
        </h2>

        {{-- Intro Text --}}
        <p class="text-center text-sm md:text-base lg:text-lg text-[#1a1a1a] max-w-3xl mx-auto mb-12 md:mb-16 lg:mb-20 px-4">
            musikfürfirmen.de ist ein Hamburger Unternehmen spezialisiert<br class="hidden md:block">
            auf den musikalischen Aspekt von Firmenevents.
        </p>

        {{-- Two Column Layout: Jonas Quote + Photo | Nick Photo + Quote --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 lg:gap-20 mb-16 md:mb-20">
            {{-- Jonas Section --}}
            <div class="flex flex-col gap-6 md:gap-8">
                {{-- Jonas Quote --}}
                <div class="order-1">
                    <p class="text-sm md:text-base text-[#1a1a1a] italic leading-relaxed mb-4">
                        "Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt."
                    </p>
                    <p class="text-sm md:text-base font-semibold text-[#1a1a1a]">
                        Jonas Glamann, Co-Founder
                    </p>
                </div>

                {{-- Jonas Photo - Clickable --}}
                <div class="order-2">
                    <button
                        @click="modalOpen = true; currentMember = 'jonas'"
                        class="relative w-full max-w-[280px] sm:max-w-xs mx-auto md:mx-0 aspect-[3/4] rounded-2xl md:rounded-3xl overflow-hidden cursor-pointer transition-transform duration-300 hover:scale-105 group"
                        style="background-color: #b8b8b8;"
                    >
                        <img
                            src="/images/team/jonas.png"
                            alt="Jonas Glamann"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                            <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                Mehr erfahren
                            </span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Nick Section --}}
            <div class="flex flex-col gap-6 md:gap-8">
                {{-- Nick Photo - Clickable --}}
                <div class="order-1">
                    <button
                        @click="modalOpen = true; currentMember = 'nick'"
                        class="relative w-full max-w-[280px] sm:max-w-xs mx-auto md:ml-auto md:mr-0 aspect-[3/4] rounded-2xl md:rounded-3xl overflow-hidden cursor-pointer transition-transform duration-300 hover:scale-105 group"
                        style="background-color: #b8b8b8;"
                    >
                        <img
                            src="/images/team/nick.png"
                            alt="Nick Heymann"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                            <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                Mehr erfahren
                            </span>
                        </div>
                    </button>
                </div>

                {{-- Nick Quote --}}
                <div class="order-2">
                    <p class="text-sm md:text-base text-[#1a1a1a] italic leading-relaxed mb-4">
                        "Mit technischem Know-how und Leidenschaft für Veranstaltungen kümmere ich mich darum, dass bei jedem Event die Technik perfekt läuft und unsere Künstler optimal präsentiert werden."
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

    {{-- Modal Overlay --}}
    <div
        x-show="modalOpen"
        x-cloak
        @click="modalOpen = false"
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Modal Content --}}
        <div
            @click.stop
            class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            {{-- Jonas Modal --}}
            <div x-show="currentMember === 'jonas'" class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/3">
                        <img
                            src="/images/team/jonas.png"
                            alt="Jonas Glamann"
                            class="w-full aspect-[3/4] object-cover rounded-2xl"
                        >
                    </div>
                    <div class="md:w-2/3">
                        <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-2">Jonas Glamann</h3>
                        <p class="text-lg text-[#2DD4A8] mb-6">Co-Founder & Musikalischer Leiter</p>

                        <div class="space-y-4 text-[#1a1a1a]">
                            <p class="leading-relaxed">
                                Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik.
                            </p>
                            <p class="leading-relaxed">
                                Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt und dabei unzählige Events begleitet. Diese Erfahrung fließt direkt in unser Angebot ein – ich weiß genau, worauf es ankommt, damit die Musik perfekt zur Stimmung eures Events passt.
                            </p>
                            <p class="leading-relaxed">
                                Als Teil der Band auf der Bühne zu stehen und gleichzeitig die technischen und organisatorischen Aspekte zu koordinieren, gibt mir einen einzigartigen Einblick in alle Facetten eines erfolgreichen Firmenevents.
                            </p>
                        </div>

                        <button
                            @click="modalOpen = false"
                            class="mt-8 btn-primary"
                        >
                            Schließen
                        </button>
                    </div>
                </div>
            </div>

            {{-- Nick Modal --}}
            <div x-show="currentMember === 'nick'" class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/3">
                        <img
                            src="/images/team/nick.png"
                            alt="Nick Heymann"
                            class="w-full aspect-[3/4] object-cover rounded-2xl"
                        >
                    </div>
                    <div class="md:w-2/3">
                        <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-2">Nick Heymann</h3>
                        <p class="text-lg text-[#2DD4A8] mb-6">Co-Founder & Technischer Leiter</p>

                        <div class="space-y-4 text-[#1a1a1a]">
                            <p class="leading-relaxed">
                                Mit technischem Know-how und Leidenschaft für Veranstaltungen kümmere ich mich darum, dass bei jedem Event die Technik perfekt läuft und unsere Künstler optimal präsentiert werden.
                            </p>
                            <p class="leading-relaxed">
                                Meine Expertise liegt in der professionellen Event-Technik und der nahtlosen Integration von Sound- und Lichtsystemen. Durch unsere Partnerschaft mit einem führenden Technikpartner können wir Equipment im Wert von über 100.000 € einsetzen.
                            </p>
                            <p class="leading-relaxed">
                                Ich bin bei jedem Event persönlich vor Ort, um sicherzustellen, dass alles reibungslos funktioniert – von der ersten Planung bis zum letzten Ton. So könnt ihr euch entspannt auf eure Gäste konzentrieren, während ich mich um die perfekte technische Umsetzung kümmere.
                            </p>
                        </div>

                        <button
                            @click="modalOpen = false"
                            class="mt-8 btn-primary"
                        >
                            Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
[x-cloak] { display: none !important; }
</style>
