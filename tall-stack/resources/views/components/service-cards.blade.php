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
                    src="/images/services/liveband.jpg"
                    alt="Livebands für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    Livebands
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-6">
                    Sänger:innen wählen wir passend zu eurem Event aus. Von klassischen Interpretationen über Rock-Hymnen bis zu aktuellen Hits – unsere Bands sind für eure Firmenevents von Solo bis 20-köpfig verfügbar.
                </p>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Wir arbeiten mit einer eingespielten Stammband, die je nach Eventgröße flexibel in unterschiedlichen Besetzungen auftritt. Alle Musiker:innen haben wir persönlich ausgewählt.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/livebands" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-semibold rounded-full hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="window.dispatchEvent(new CustomEvent('openMFFCalculator'))"
                        class="inline-block px-8 py-3 bg-[#2DD4A8] text-white font-semibold rounded-full hover:bg-[#22a883] transition-all duration-300 uppercase tracking-wide text-sm"
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
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    DJs
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-6">
                    Unsere DJs spielen einen maßgeschneiderten Mix aus Klassikern und aktuellen Hits, abgestimmt auf euer Event und eure Gäste. Ob elegant, energetisch oder ein Mix aus beidem — unsere DJs finden für jede Party den richtigen Sound.
                </p>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Dank modernster Technik und Vernetzung mit Labels ist unser DJ-Angebot flexibel für Setups und Sonderwünsche aller Art.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/djs" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-semibold rounded-full hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="window.dispatchEvent(new CustomEvent('openMFFCalculator'))"
                        class="inline-block px-8 py-3 bg-[#2DD4A8] text-white font-semibold rounded-full hover:bg-[#22a883] transition-all duration-300 uppercase tracking-wide text-sm"
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
                    class="absolute inset-0 w-full h-full object-cover"
                />
            </div>

            {{-- Content --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center py-4">
                <h3 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-6">
                    Technik
                </h3>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-6">
                    Wir stellen professionelle Musik- und Lichttechnik im Wert von über 100.000 € bereit, damit unsere Künstler:innen ihre beste Seite optimal auf die Bühne bringen.
                </p>
                <p class="text-base md:text-lg leading-relaxed text-[#4a5568] mb-8">
                    Jede Anforderung professionell umgesetzt. Für jede Event-Location haben wir die passende Technik für optimal ausgestattete Events.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/technik" class="inline-block px-8 py-3 border-2 border-[#1a1a1a] text-[#1a1a1a] font-semibold rounded-full hover:bg-[#1a1a1a] hover:text-white transition-all duration-300 uppercase tracking-wide text-sm">
                        Mehr erfahren
                    </a>
                    <button
                        onclick="window.dispatchEvent(new CustomEvent('openMFFCalculator'))"
                        class="inline-block px-8 py-3 bg-[#2DD4A8] text-white font-semibold rounded-full hover:bg-[#22a883] transition-all duration-300 uppercase tracking-wide text-sm"
                    >
                        Jetzt buchen
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
