{{-- Service Cards - Canva Redesign (Alternating Layout) --}}
<section
    class="mff-services w-full py-20 bg-white"
    style="font-family: 'Poppins', sans-serif"
>
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#1a1a1a] mb-16">
            Wir bieten euch...
        </h2>

        {{-- Service 1: Livebands - Image Left, Text Right --}}
        <div class="flex flex-col md:flex-row items-stretch gap-8 md:gap-12 mb-20">
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/service-live-band.png"
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
                    <a href="/livebands" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm"
                    >
                        Jetzt buchen
                    </button>
                </div>
            </div>
        </div>

        {{-- Service 2: DJs - Text Left, Image Right --}}
        <div class="flex flex-col md:flex-row-reverse items-stretch gap-8 md:gap-12 mb-20">
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/services/dj.jpg"
                    alt="DJs für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover grayscale"
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
                    <a href="/djs" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm"
                    >
                        Jetzt buchen
                    </button>
                </div>
            </div>
        </div>

        {{-- Service 3: Technik - Image Left, Text Right --}}
        <div class="flex flex-col md:flex-row items-stretch gap-8 md:gap-12">
            {{-- Image --}}
            <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl min-h-[300px] md:min-h-[450px]">
                <img
                    src="/images/services/technik.jpg"
                    alt="Veranstaltungstechnik"
                    class="absolute inset-0 w-full h-full object-cover grayscale"
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
                    <a href="/technik" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="Livewire.dispatch('openMFFCalculator')"
                        class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-medium hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm"
                    >
                        Jetzt buchen
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
