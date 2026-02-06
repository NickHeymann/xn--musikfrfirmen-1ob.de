{{-- Service Cards - Expandable Hover with Video --}}
<section
    id="waswirbieten"
    class="mff-services w-full py-20 bg-white"
    data-section-theme="light"
    data-section-bg="#ffffff"
    x-data="{
        visible: false,
        hoveredCard: null
    }"
    x-intersect="visible = true"
>
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#1a1a1a] mb-16">
            Wir bieten euch...
        </h2>

        {{-- Expandable Flex Container --}}
        <div
            class="flex flex-col md:flex-row gap-4"
            x-show="visible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            {{-- Service 1: Livebands --}}
            <div
                @mouseenter="hoveredCard = 'livebands'; $refs.videoLivebands.play()"
                @mouseleave="hoveredCard = null; $refs.videoLivebands.pause(); $refs.videoLivebands.currentTime = 0"
                class="service-card group relative overflow-hidden rounded-2xl min-h-[400px] md:min-h-[500px] cursor-pointer"
                :style="hoveredCard === 'livebands' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 0.5')"
            >
                <img
                    src="/images/services/liveband.jpg"
                    alt="Livebands für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <video
                    x-ref="videoLivebands"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'livebands' ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata"
                >
                    <source src="/videos/services/liveband.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 origin-bottom-left service-card-text"
                     :style="'transform: scale(' + (hoveredCard !== null && hoveredCard !== 'livebands' ? '0.75' : '1') + ')'">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-2 whitespace-nowrap">Livebands</h3>
                    <p class="text-sm leading-relaxed text-white/85 w-[280px]">
                        Sänger:innen wählen wir passend zu eurem Event aus. Für höchste Qualität arbeiten wir mit hauseigenen Stammbands zusammen.
                    </p>
                </div>
            </div>

            {{-- Service 2: DJs --}}
            <div
                @mouseenter="hoveredCard = 'djs'; $refs.videoDjs.play()"
                @mouseleave="hoveredCard = null; $refs.videoDjs.pause(); $refs.videoDjs.currentTime = 0"
                class="service-card group relative overflow-hidden rounded-2xl min-h-[400px] md:min-h-[500px] cursor-pointer"
                :style="hoveredCard === 'djs' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 0.5')"
            >
                <img
                    src="/images/services/dj.jpg"
                    alt="DJs für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <video
                    x-ref="videoDjs"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'djs' ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata"
                >
                    <source src="/videos/services/dj.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 origin-bottom-left service-card-text"
                     :style="'transform: scale(' + (hoveredCard !== null && hoveredCard !== 'djs' ? '0.75' : '1') + ')'">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-2 whitespace-nowrap">DJs</h3>
                    <p class="text-sm leading-relaxed text-white/85 w-[280px]">
                        Unsere DJs spielen einen maßgeschneiderten Mix aus Klassikern und aktuellen Hits - abgestimmt auf euer Event und eure Musikwünsche.
                    </p>
                </div>
            </div>

            {{-- Service 3: Technik --}}
            <div
                @mouseenter="hoveredCard = 'technik'; $refs.videoTechnik.play()"
                @mouseleave="hoveredCard = null; $refs.videoTechnik.pause(); $refs.videoTechnik.currentTime = 0"
                class="service-card group relative overflow-hidden rounded-2xl min-h-[400px] md:min-h-[500px] cursor-pointer"
                :style="hoveredCard === 'technik' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 0.5')"
            >
                <img
                    src="/images/services/technik.jpg"
                    alt="Veranstaltungstechnik"
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <video
                    x-ref="videoTechnik"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'technik' ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata"
                >
                    <source src="/videos/services/technik.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 origin-bottom-left service-card-text"
                     :style="'transform: scale(' + (hoveredCard !== null && hoveredCard !== 'technik' ? '0.75' : '1') + ')'">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-2 whitespace-nowrap">Technik</h3>
                    <p class="text-sm leading-relaxed text-white/85 w-[280px]">
                        Wir stellen professionelle Musik- und Lichttechnik im Wert von über 100.000 € bereit, damit unsere Künstler:innen bei jeder Eventgröße optimal zur Geltung kommen.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .service-card {
        transition: flex 700ms cubic-bezier(0.4, 0, 0.6, 1);
    }
    .service-card-text {
        transition: transform 700ms cubic-bezier(0.4, 0, 0.6, 1);
    }
</style>
