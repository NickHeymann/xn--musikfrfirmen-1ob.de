{{-- "Warum Wir?" Section - Inspired by Seed Hub Layout --}}
<section
    class="why-us-section w-full py-20 md:py-28 bg-[#f5f5f0] overflow-hidden relative z-20"
    data-section-bg="#f5f5f0"
    data-section-theme="light"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ visible: false }"
    x-intersect.once="visible = true"
>
    <div class="max-w-7xl mx-auto px-6">

        {{-- Top Section: Story Left + Stats Right --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 mb-16 md:mb-24">

            {{-- Left: Story --}}
            <div
                x-show="visible"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                {{-- Label Pill --}}
                <span class="inline-block text-xs font-medium tracking-wider uppercase text-[#1a1a1a] border border-[#1a1a1a]/30 rounded-full px-4 py-1.5 mb-8">
                    Warum wir
                </span>

                {{-- Big Headline --}}
                <h2 class="text-4xl md:text-5xl lg:text-[3.5rem] font-bold text-[#1a1a1a] leading-[1.1] mb-8">
                    Musik ist nicht nur ein Baustein. Musik ist der Abend.
                </h2>

                {{-- Body Text - vollständiger Originaltext --}}
                <p class="text-sm md:text-base leading-relaxed text-[#1a1a1a]/70">
                    Die gängige Sicht: Livemusik ist bloß ein Baustein unter vielen. Davon sind auch Eventagenturen nicht ausgenommen. Bei ihnen sind Bands und DJs häufig bloß Teil eines großen Portfolios, weshalb es zu Qualitätsschwankungen kommen kann. Diesen Faktor der Ungewissheit möchten wir euch abnehmen.
                </p>
            </div>

            {{-- Right: Stats --}}
            <div
                x-show="visible"
                x-transition:enter="transition ease-out duration-700 delay-200"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="flex flex-col justify-center"
            >
                {{-- Stat 1 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#1a1a1a]">10+</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Jahre in der Musikbranche</span>
                    </div>
                </div>

                {{-- Stat 2 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#1a1a1a]">100%</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">hauseigene Stammbands</span>
                    </div>
                </div>

                {{-- Stat 3 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#1a1a1a]">100k€+</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">professionelles Equipment</span>
                    </div>
                </div>

                {{-- Stat 4 --}}
                <div class="py-5">
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#1a1a1a]">1</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Ansprechpartner für alles</span>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
