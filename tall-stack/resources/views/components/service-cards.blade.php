{{-- Service Cards - Expandable Hover with Video --}}
{{-- RULE: Text block has FIXED width (never reflows). Hovered card zooms text via transform:scale (title shifts up visually). Non-hovered titles stay put. Paragraph blur-in on hover only. --}}
<section
    class="mff-services w-full pt-12 pb-0 md:py-20 bg-white"
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
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-medium text-center text-[#1a1a1a] mb-8 md:mb-16">
            Wir bieten euch
        </h2>

        {{-- Mobile/Tablet: Sticky stacking deck (cards slide up over each other on scroll) --}}
        <div class="lg:hidden mff-sticky-deck">
            {{-- Mobile Service 1: Livebands --}}
            <div class="mff-sticky-card">
                <img src="/images/services/liveband.jpg" alt="Livebands für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="mff-sticky-card-text">
                    <h3 class="text-2xl font-bold text-white mb-1">Livebands</h3>
                    <p class="text-sm text-white/80 leading-relaxed max-w-[280px]">
                        Sänger:innen wählen wir passend zu eurem Event aus. Für höchste Qualität mit hauseigenen Stammbands.
                    </p>
                </div>
            </div>

            {{-- Mobile Service 2: DJs --}}
            <div class="mff-sticky-card">
                <img src="/images/services/dj.jpg" alt="DJs für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="mff-sticky-card-text">
                    <h3 class="text-2xl font-bold text-white mb-1">DJs</h3>
                    <p class="text-sm text-white/80 leading-relaxed max-w-[280px]">
                        Ein maßgeschneiderter Mix aus Klassikern und aktuellen Hits, abgestimmt auf euer Event.
                    </p>
                </div>
            </div>

            {{-- Mobile Service 3: Technik --}}
            <div class="mff-sticky-card">
                <img src="/images/services/technik.jpg" alt="Veranstaltungstechnik"
                    class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="mff-sticky-card-text">
                    <h3 class="text-2xl font-bold text-white mb-1">Technik</h3>
                    <p class="text-sm text-white/80 leading-relaxed max-w-[280px]">
                        Professionelle Musik- und Lichttechnik im Wert von über 100.000 €. Für jede Eventgröße.
                    </p>
                </div>
            </div>
        </div>

        {{-- Desktop: Expandable Flex Container --}}
        <div
            class="hidden lg:flex flex-row"
            x-show="visible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-data="{ videoReady: { livebands: false, djs: false, technik: false } }"
        >
            {{-- Service 1: Livebands --}}
            <div
                @mouseenter="hoveredCard = 'livebands'; if(videoReady.livebands) $refs.videoLivebands.play().catch(() => {})"
                @mouseleave="hoveredCard = null; $refs.videoLivebands.pause(); $refs.videoLivebands.currentTime = 0"
                class="service-card group relative overflow-hidden min-h-[500px]"
                :style="hoveredCard === 'livebands' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 1')"
            >
                <img src="/images/services/liveband.jpg" alt="Livebands für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'livebands' && videoReady.livebands ? 'opacity-0' : 'opacity-100'" />
                <video x-ref="videoLivebands"
                    @canplay="videoReady.livebands = true"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'livebands' && videoReady.livebands ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata">
                    <source src="/videos/services/liveband.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="service-card-text" :class="hoveredCard === 'livebands' ? 'is-hovered' : ''">
                    <h3 class="service-card-title">Livebands</h3>
                    <p class="service-card-desc" :class="hoveredCard === 'livebands' ? 'is-visible' : ''">
                        Sänger:innen wählen wir passend zu eurem Event aus. Für höchste Qualität mit hauseigenen Stammbands.
                    </p>
                </div>
            </div>

            {{-- Service 2: DJs --}}
            <div
                @mouseenter="hoveredCard = 'djs'; if(videoReady.djs) $refs.videoDjs.play().catch(() => {})"
                @mouseleave="hoveredCard = null; $refs.videoDjs.pause(); $refs.videoDjs.currentTime = 0"
                class="service-card group relative overflow-hidden min-h-[500px]"
                :style="hoveredCard === 'djs' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 1')"
            >
                <img src="/images/services/dj.jpg" alt="DJs für Firmenevents"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'djs' && videoReady.djs ? 'opacity-0' : 'opacity-100'" />
                <video x-ref="videoDjs"
                    @canplay="videoReady.djs = true"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'djs' && videoReady.djs ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata">
                    <source src="/videos/services/dj.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="service-card-text" :class="hoveredCard === 'djs' ? 'is-hovered' : ''">
                    <h3 class="service-card-title">DJs</h3>
                    <p class="service-card-desc" :class="hoveredCard === 'djs' ? 'is-visible' : ''">
                        Ein maßgeschneiderter Mix aus Klassikern und aktuellen Hits, abgestimmt auf euer Event.
                    </p>
                </div>
            </div>

            {{-- Service 3: Technik --}}
            <div
                @mouseenter="hoveredCard = 'technik'; if(videoReady.technik) $refs.videoTechnik.play().catch(() => {})"
                @mouseleave="hoveredCard = null; $refs.videoTechnik.pause(); $refs.videoTechnik.currentTime = 0"
                class="service-card group relative overflow-hidden min-h-[500px]"
                :style="hoveredCard === 'technik' ? 'flex: 2' : (hoveredCard === null ? 'flex: 1' : 'flex: 1')"
            >
                <img src="/images/services/technik.jpg" alt="Veranstaltungstechnik"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'technik' && videoReady.technik ? 'opacity-0' : 'opacity-100'" />
                <video x-ref="videoTechnik"
                    @canplay="videoReady.technik = true"
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                    :class="hoveredCard === 'technik' && videoReady.technik ? 'opacity-100' : 'opacity-0'"
                    muted loop playsinline preload="metadata">
                    <source src="/videos/services/technik.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="service-card-text" :class="hoveredCard === 'technik' ? 'is-hovered' : ''">
                    <h3 class="service-card-title">Technik</h3>
                    <p class="service-card-desc" :class="hoveredCard === 'technik' ? 'is-visible' : ''">
                        Professionelle Musik- und Lichttechnik im Wert von über 100.000 €. Für jede Eventgröße.
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

    /* Mobile/Tablet: Sticky stacking deck — cards slide up over each other on scroll */
    @media (max-width: 1023px) {
        .mff-sticky-deck {
            /* 3 cards × 70svh = total scrollable height */
            height: 210svh;
            margin-left: -24px;
            margin-right: -24px;
        }

        .mff-sticky-card {
            position: sticky;
            top: 0;
            height: 70svh;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        /* Text flows in the document — scrolls up with the card naturally */
        .mff-sticky-card-text {
            position: sticky;
            bottom: 0;
            z-index: 10;
            padding: 24px 24px 28px;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 70%, transparent 100%);
        }
    }

    /*
     * Text container: fixed width, centered in card, anchored at bottom.
     * Scales up on hover → title visually lifts, text visually widens.
     * No reflow ever — only transform.
     */
    .service-card-text {
        position: absolute;
        z-index: 10;
        bottom: 6%;
        left: 50%;
        width: 300px;
        text-align: center;
        transform: translateX(-50%) scale(1);
        transform-origin: bottom center;
        transition: transform 600ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .service-card-text.is-hovered {
        transform: translateX(-50%) scale(1.15);
    }

    /* Title */
    .service-card-title {
        font-size: clamp(1.25rem, 2.2vw, 1.875rem);
        font-weight: 700;
        color: white;
        white-space: nowrap;
        margin-bottom: 0.5rem;
    }

    /*
     * Paragraph: visually hidden + no flow impact by default.
     * Uses max-height:0 + overflow:hidden to collapse, and scale(0.95) for zoom feel.
     * On hover: max-height opens, blur+opacity animate in.
     * The max-height transition is intentionally fast so it doesn't look like a slide.
     */
    .service-card-desc {
        font-size: clamp(0.8125rem, 1.1vw, 0.9375rem);
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        text-align: center;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        filter: blur(8px);
        transform: scale(0.95);
        transition: max-height 200ms cubic-bezier(0.16, 1, 0.3, 1),
                    opacity 500ms cubic-bezier(0.16, 1, 0.3, 1) 100ms,
                    filter 500ms cubic-bezier(0.16, 1, 0.3, 1) 100ms,
                    transform 500ms cubic-bezier(0.16, 1, 0.3, 1) 100ms;
    }
    .service-card-desc.is-visible {
        max-height: 12em;
        opacity: 1;
        filter: blur(0px);
        transform: scale(1);
    }
</style>
