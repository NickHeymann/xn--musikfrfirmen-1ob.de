{{-- CTA Section - Production Accurate Implementation --}}
<section class="py-24 bg-gradient-to-br from-[#0D7A5F] to-[#0a5c47]">
    <div class="max-w-4xl mx-auto px-4 text-center">
        {{-- Heading --}}
        <h2
            x-data="{ visible: false }"
            x-intersect.once="visible = true"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-white mb-6 text-4xl md:text-5xl font-bold"
            style="font-family: 'Poppins', sans-serif">
            Bereit für unvergessliche Musik?
        </h2>

        {{-- Subheading --}}
        <p
            x-data="{ visible: false }"
            x-intersect.once="setTimeout(() => visible = true, 100)"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-white/90 font-light mb-10 text-lg max-w-2xl mx-auto"
            style="font-family: 'Poppins', sans-serif">
            Fordere jetzt dein unverbindliches Angebot an und mach dein nächstes
            Firmenevent zu einem Highlight.
        </p>

        {{-- CTA Button --}}
        <button
            x-data="{ visible: false }"
            x-intersect.once="setTimeout(() => visible = true, 200)"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0"
            @click="window.dispatchEvent(new CustomEvent('openMFFCalculator'))"
            class="bg-white text-[#0D7A5F] px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 active:scale-98 transition-all duration-200 shadow-lg"
            style="font-family: 'Poppins', sans-serif">
            Jetzt Angebot anfragen
        </button>
    </div>
</section>
