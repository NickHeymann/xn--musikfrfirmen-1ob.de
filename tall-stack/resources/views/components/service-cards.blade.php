{{-- Service Cards - Canva Redesign (Alternating Layout) --}}
<section
    class="mff-services w-full py-20 bg-white"
    data-section-theme="light"
    data-section-bg="#ffffff"
    x-data="{ visible: false }"
    x-intersect="visible = true"
>
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#1a1a1a] mb-16">
            Wir bieten euch...
        </h2>

        {{-- Service 1: Livebands - Image Left, Text Right --}}
        <div
            class="flex flex-col md:flex-row items-stretch gap-8 md:gap-12 mb-20"
            x-show="visible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: 150ms"
        >
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/services/liveband.jpg"
                    alt="Livebands für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    ...Livebands
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Sänger:innen wählen wir passend zu eurem Event aus. Für höchste Qualität arbeiten wir mit hauseigenen Stammbands zusammen. Auf Wunsch können wir unsere Bands für euch erweitern (z.B. Saxofon, Cello).
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/livebands" class="btn-primary text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="btn-primary text-sm"
                    >
                        Jetzt Angebot einholen
                    </button>
                </div>
            </div>
        </div>

        {{-- Service 2: DJs - Text Left, Image Right --}}
        <div
            class="flex flex-col md:flex-row-reverse items-stretch gap-8 md:gap-12 mb-20"
            x-show="visible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: 300ms"
        >
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/services/dj.jpg"
                    alt="DJs für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    ...DJs
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Unsere DJs spielen einen maßgeschneiderten Mix aus Klassikern und aktuellen Hits – abgestimmt auf euer Event und eure Musikwünsche. Über unser Netzwerk vermitteln wir den passenden DJ für jeden Anlass. Ideal kombinierbar mit Liveband, auf Wunsch auch mit Sänger:innen oder Saxofon.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/djs" class="btn-primary text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="btn-primary text-sm"
                    >
                        Jetzt Angebot einholen
                    </button>
                </div>
            </div>
        </div>

        {{-- Service 3: Technik - Image Left, Text Right --}}
        <div
            class="flex flex-col md:flex-row items-stretch gap-8 md:gap-12"
            x-show="visible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="transition-delay: 450ms"
        >
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/services/technik.jpg"
                    alt="Veranstaltungstechnik"
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    ...Technik
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Wir stellen professionelle Musik- und Lichttechnik im Wert von über 100.000 € bereit, damit unsere Künstler:innen bei jeder Eventgröße optimal zur Geltung kommen. Egal welche Anforderungen euer Event hat, durch unseren Technikpartner sind wir optimal ausgestattet.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/technik" class="btn-primary text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="btn-primary text-sm"
                    >
                        Jetzt Angebot einholen
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
