{{-- Team Grid - Member portraits and bios (sticky card) --}}
<section
    class="team-section py-12 md:py-20 bg-white" data-section-theme="light" data-section-bg="#ffffff"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ modalOpen: false, currentMember: null, visible: false, hoveredMember: null }"
>
    <div class="max-w-7xl mx-auto px-6">
        {{-- Cutout Animation Layout - Always side-by-side --}}
        <div class="flex flex-row flex-wrap justify-center items-start gap-8 md:gap-16 lg:gap-24"
             x-intersect.once="visible = true"
             @mouseleave="hoveredMember = null">
            {{-- Desktop hover dim overlay --}}
            <div class="hidden lg:block fixed inset-0 bg-black/20 transition-opacity duration-300 pointer-events-none z-[-1]"
                 x-show="hoveredMember"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>
            {{-- Jonas --}}
            <div class="cutout-person relative flex flex-col items-center transition-all duration-700 cursor-pointer"
                 :class="[visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8', hoveredMember === 'jonas' ? 'z-10' : 'z-0']"
                 style="transition-delay: 100ms"
                 @mouseenter="hoveredMember = 'jonas'" @mouseleave="hoveredMember = null"
                 @click="hoveredMember = null; modalOpen = true; currentMember = 'jonas'">
                <div class="cutout-container">
                    <div class="cutout-inner">
                        <div class="cutout-circle"></div>
                        <img src="/images/team/jonas.png" alt="Jonas Glamann" class="cutout-img cutout-img-jonas" loading="lazy" />
                    </div>
                </div>
                <div class="text-center mt-2 transition-all duration-500"
                     :class="visible ? 'opacity-100' : 'opacity-0'"
                     style="transition-delay: 300ms">
                    <div class="cutout-divider mx-auto"></div>
                    <p class="cutout-name">Jonas Glamann</p>
                    <p class="cutout-role">Co-Founder</p>
                </div>
                <div class="text-center mt-3 max-w-[220px] mx-auto transition-all duration-700"
                     :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                     style="transition-delay: 500ms">
                    <p class="text-sm italic text-[#1a1a1a]/70 leading-relaxed">"Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne."</p>
                </div>
                {{-- Desktop Hover Bio - LEFT side of Jonas --}}
                <div class="hidden lg:block absolute right-full top-[200px] -translate-y-1/2 mr-8 w-[280px] pointer-events-none z-10"
                     x-show="hoveredMember === 'jonas'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-75"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition duration-250"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-50"
                     style="transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1)">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-[#1a1a1a] mb-1">Jonas Glamann</h3>
                        <p class="text-sm text-[#5a9a84] mb-3">Co-Founder &<br>Musikalischer Leiter</p>
                        <p class="text-sm text-[#1a1a1a]/80 leading-relaxed">Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik.</p>
                    </div>
                </div>
            </div>
            {{-- Nick --}}
            <div class="cutout-person relative flex flex-col items-center transition-all duration-700 cursor-pointer"
                 :class="[visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8', hoveredMember === 'nick' ? 'z-10' : 'z-0']"
                 style="transition-delay: 200ms"
                 @mouseenter="hoveredMember = 'nick'" @mouseleave="hoveredMember = null"
                 @click="hoveredMember = null; modalOpen = true; currentMember = 'nick'">
                <div class="cutout-container">
                    <div class="cutout-inner">
                        <div class="cutout-circle"></div>
                        <img src="/images/team/nick.png" alt="Nick Heymann" class="cutout-img cutout-img-nick" loading="lazy" />
                    </div>
                </div>
                <div class="text-center mt-2 transition-all duration-500"
                     :class="visible ? 'opacity-100' : 'opacity-0'"
                     style="transition-delay: 400ms">
                    <div class="cutout-divider mx-auto"></div>
                    <p class="cutout-name">Nick Heymann</p>
                    <p class="cutout-role">Co-Founder</p>
                </div>
                <div class="text-center mt-3 max-w-[220px] mx-auto transition-all duration-700"
                     :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                     style="transition-delay: 600ms">
                    <p class="text-sm italic text-[#1a1a1a]/70 leading-relaxed">"Mit technischem Know-how und Leidenschaft sorge ich dafür, dass die Technik bei jedem Event perfekt läuft."</p>
                </div>
                {{-- Desktop Hover Bio - RIGHT side of Nick --}}
                <div class="hidden lg:block absolute left-full top-[200px] -translate-y-1/2 ml-8 w-[280px] pointer-events-none z-10"
                     x-show="hoveredMember === 'nick'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-75"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition duration-250"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-50"
                     style="transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1)">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-[#1a1a1a] mb-1">Nick Heymann</h3>
                        <p class="text-sm text-[#5a9a84] mb-3">Co-Founder & Technischer Leiter</p>
                        <p class="text-sm text-[#1a1a1a]/80 leading-relaxed">Durch unsere Partnerschaft mit einem führenden Technikpartner können wir Equipment im Wert von über 100.000 € einsetzen. Ich bin bei jedem Event persönlich vor Ort.</p>
                    </div>
                </div>
            </div>
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
        {{-- Modal Content - Compact with X close --}}
        <div
            @click.stop
            class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl relative"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            {{-- Close X Button --}}
            <button @click="modalOpen = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full hover:bg-black/5 transition-colors z-10">
                <svg class="w-5 h-5 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Jonas Modal --}}
            <div x-show="currentMember === 'jonas'" class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <img src="/images/team/jonas.png" alt="Jonas Glamann" class="w-20 h-20 rounded-full object-cover object-top shrink-0">
                    <div>
                        <h3 class="text-2xl font-bold text-[#1a1a1a]">Jonas Glamann</h3>
                        <p class="text-sm text-[#5a9a84]">Co-Founder & Musikalischer Leiter</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm text-[#1a1a1a] leading-relaxed">
                    <p>Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik.</p>
                    <p>Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt und dabei unzählige Events begleitet. Diese Erfahrung fließt direkt in unser Angebot ein - ich weiß genau, worauf es ankommt, damit die Musik perfekt zur Stimmung eures Events passt.</p>
                    <p>Als Teil der Band auf der Bühne zu stehen und gleichzeitig die technischen und organisatorischen Aspekte zu koordinieren, gibt mir einen einzigartigen Einblick in alle Facetten eines erfolgreichen Firmenevents.</p>
                </div>
            </div>

            {{-- Nick Modal --}}
            <div x-show="currentMember === 'nick'" class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <img src="/images/team/nick.png" alt="Nick Heymann" class="w-20 h-20 rounded-full object-cover object-top shrink-0">
                    <div>
                        <h3 class="text-2xl font-bold text-[#1a1a1a]">Nick Heymann</h3>
                        <p class="text-sm text-[#5a9a84]">Co-Founder & Technischer Leiter</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm text-[#1a1a1a] leading-relaxed">
                    <p>Meine Expertise liegt in der professionellen Event-Technik und der nahtlosen Integration von Sound- und Lichtsystemen. Durch unsere Partnerschaft mit einem führenden Technikpartner können wir Equipment im Wert von über 100.000 € einsetzen.</p>
                    <p>Ich bin bei jedem Event persönlich vor Ort, um sicherzustellen, dass alles reibungslos funktioniert - von der ersten Planung bis zum letzten Ton. So könnt ihr euch entspannt auf eure Gäste konzentrieren, während ich mich um die perfekte technische Umsetzung kümmere.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
[x-cloak] { display: none !important; }
/* Cutout Animation Styles */
.cutout-container {
    border-radius: 50%;
    height: 400px;
    width: 400px;
    -webkit-tap-highlight-color: transparent;
    transform: scale(0.55);
    transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: -39px;
    cursor: pointer;
}
.cutout-container:hover { transform: scale(0.60); }
.cutout-inner {
    clip-path: path("M 390,400 C 390,504.9341 304.9341,590 200,590 95.065898,590 10,504.9341 10,400 V 10 H 200 390 Z");
    position: relative;
    transform-origin: 50%;
    top: -200px;
}
.cutout-circle {
    background-color: #D4F4E8;
    border-radius: 50%;
    height: 380px; width: 380px;
    left: 10px;
    pointer-events: none;
    position: absolute;
    top: 210px;
}
.cutout-img {
    pointer-events: none;
    position: relative;
    transform: translateY(20px) scale(1.15);
    transform-origin: 50% bottom;
    transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
}
.cutout-container:hover .cutout-img { transform: translateY(0) scale(1.2); }
/* Normalized: both portraits same size and position */
.cutout-img-jonas { left: 10px; top: 200px; width: 380px; }
.cutout-img-nick { left: 10px; top: 200px; width: 380px; }
.cutout-divider { background-color: #BAD6EB; height: 1px; width: 160px; margin-bottom: 16px; }
.cutout-name { color: #404245; font-size: 28px; font-weight: 600; margin-bottom: 10px; text-align: center; }
.cutout-role { color: #333; font-size: 14px; text-align: center; font-weight: 300; line-height: 1.5; }
@media (max-width: 767px) {
    .cutout-container { width: 300px; height: 300px; transform: scale(0.6); margin-bottom: -40px; }
    .cutout-container:hover { transform: scale(0.65); }
    .cutout-inner { top: -150px; transform: scale(0.75); transform-origin: top center; }
    .cutout-name { font-size: 20px; }
    .cutout-role { font-size: 13px; }
    .cutout-divider { width: 120px; }
    .cutout-img-jonas, .cutout-img-nick { left: 50%; transform: translateX(-50%) translateY(20px) scale(1.15); }
    .cutout-container:hover .cutout-img-jonas, .cutout-container:hover .cutout-img-nick { transform: translateX(-50%) translateY(0) scale(1.2); }
}
</style>
