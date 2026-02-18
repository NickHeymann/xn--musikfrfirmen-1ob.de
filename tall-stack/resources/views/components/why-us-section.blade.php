{{-- "Warum Wir?" Section - Inspired by Seed Hub Layout --}}
<section
    class="why-us-section sticky top-[80px] lg:top-[108px] w-full py-12 md:py-20 bg-[#C8E6DC] overflow-hidden relative z-[23]"
    data-section-bg="#C8E6DC"
    data-section-theme="light"
    data-card-index="3"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ visible: false }"
    x-intersect.once="visible = true"
>
    <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
    <div class="card-stack-content">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Top Section: Story Left + Stats Right --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 mb-0">

            {{-- Left: Story --}}
            <div
                x-show="visible"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                {{-- Section Label --}}
                <p class="text-2xl md:text-3xl lg:text-4xl font-medium text-[#1a1a1a] mb-8">
                    Warum wir
                </p>

                {{-- Big Headline --}}
                <h2 class="text-xl md:text-2xl lg:text-3xl font-normal text-[#1a1a1a] leading-[1.1] mb-8">
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
                x-transition:enter-start="opacity-0 translate-x-12"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="flex flex-col justify-center"
            >
                {{-- Stat 1 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="grid grid-cols-[auto_1fr] md:grid-cols-[140px_1fr] items-baseline gap-x-3 md:gap-x-4">
                        <span class="text-3xl md:text-4xl font-bold text-white text-right" style="-webkit-text-stroke: 1px rgba(0,0,0,0.35); paint-order: stroke fill">10+</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Jahre in der Musikbranche</span>
                    </div>
                </div>

                {{-- Stat 2 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="grid grid-cols-[auto_1fr] md:grid-cols-[140px_1fr] items-baseline gap-x-3 md:gap-x-4">
                        <span class="text-3xl md:text-4xl font-bold text-white text-right" style="-webkit-text-stroke: 1px rgba(0,0,0,0.35); paint-order: stroke fill">100%</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Hauseigene Stammbands</span>
                    </div>
                </div>

                {{-- Stat 3 --}}
                <div class="py-5 border-b border-[#1a1a1a]/15">
                    <div class="grid grid-cols-[auto_1fr] md:grid-cols-[140px_1fr] items-baseline gap-x-3 md:gap-x-4">
                        <span class="text-3xl md:text-4xl font-bold text-white text-right" style="-webkit-text-stroke: 1px rgba(0,0,0,0.35); paint-order: stroke fill">100k€+</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Professionelles Equipment</span>
                    </div>
                </div>

                {{-- Stat 4 --}}
                <div class="py-5">
                    <div class="grid grid-cols-[auto_1fr] md:grid-cols-[140px_1fr] items-baseline gap-x-3 md:gap-x-4">
                        <span class="text-3xl md:text-4xl font-bold text-white text-right" style="-webkit-text-stroke: 1px rgba(0,0,0,0.35); paint-order: stroke fill">1</span>
                        <span class="text-sm md:text-base text-[#1a1a1a]/70">Ansprechpartner für alles</span>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
</section>
