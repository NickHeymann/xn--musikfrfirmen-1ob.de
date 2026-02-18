{{-- musikfürfirmen.de Homepage - Canva Redesign --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Hero Section - Simplified --}}
    <x-hero />

    {{-- NEW: Testimonial Section (Dynamic with Filament) --}}
    <livewire:testimonial-carousel />

    {{-- Was wir bieten - Alternating Service Layout --}}
    <section id="waswirbieten" class="sticky top-[80px] lg:top-[108px] bg-white scroll-mt-[80px] lg:scroll-mt-[108px] relative z-[22]" data-section-bg="#ffffff" data-section-theme="light" data-card-index="2">
        <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
        <div class="card-stack-content">
            <x-service-cards />
        </div>
    </section>

    {{-- NEW: Warum Wir? Section --}}
    <x-why-us-section />

    {{-- NEW: Das heißt für euch - Benefits --}}
    <x-benefits-section />

    {{-- Event Gallery - Swipeable Carousel --}}
    <x-event-gallery />

    {{-- Team Section - Intro (scrolls freely, not sticky) --}}
    <section id="ueberuns" class="bg-white relative z-[26] scroll-mt-[80px] lg:scroll-mt-[108px]" data-section-bg="#ffffff" data-section-theme="light">
        <x-team-intro />
    </section>

    {{-- Team Section - Member Grid (sticky card) --}}
    <section class="sticky top-[80px] lg:top-[108px] bg-white relative z-[26]" data-section-bg="#ffffff" data-section-theme="light" data-card-index="6">
        <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
        <div class="card-stack-content">
            <x-team-grid />
        </div>
        {{-- Team hover dim overlay — outside card-stack-content so it won't be blurred --}}
        <div class="hidden lg:block absolute inset-0 bg-black/20 pointer-events-none opacity-0 transition-opacity duration-300"
             id="team-hover-overlay"
             style="z-index: 49;"></div>
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="bg-[#C8E6DC] scroll-mt-[80px] lg:scroll-mt-[108px] relative z-[27]" data-section-bg="#C8E6DC" data-section-theme="light" data-card-index="7">
        <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
        <div class="card-stack-content pt-12 md:pt-20 pb-10 md:pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                class="text-center text-2xl md:text-3xl lg:text-4xl font-medium mb-8 md:mb-12 tracking-[-1px] text-black"
                style="font-family: 'Poppins', sans-serif"
            >
                FAQ
            </h2>
            <livewire:faq-section />

            {{-- Animated Logo with Musical Notes (click to spawn) --}}
            <div class="mt-20 relative flex justify-center">
                <div class="relative group"
                     x-data="{
                         hovered: false,
                         musicNotes: [],
                         noteId: 0,
                         symbols: ['♪', '♫', '♬', '♩'],
                         spawnNotes() {
                             const count = 8;
                             for (let i = 0; i < count; i++) {
                                 const note = this.symbols[Math.floor(Math.random() * this.symbols.length)];
                                 const x = (Math.random() - 0.5) * 250;
                                 const id = this.noteId++;
                                 setTimeout(() => {
                                     this.musicNotes.push({ id, note, x });
                                     setTimeout(() => {
                                         this.musicNotes = this.musicNotes.filter(n => n.id !== id);
                                     }, 2000);
                                 }, i * 100);
                             }
                         }
                     }"
                     @mouseenter="hovered = true"
                     @mouseleave="hovered = false">
                    <span @click="spawnNotes()"
                          class="text-2xl sm:text-3xl font-light text-[#1a1a1a] cursor-pointer select-none"
                          style="font-family: 'Poppins', sans-serif">
                        musikfürfirmen.de
                    </span>

                    {{-- Floating Musical Notes - Hover (ambient) --}}
                    <div class="absolute inset-0 pointer-events-none overflow-visible">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="absolute opacity-0 text-2xl text-[#1a1a1a]"
                                  style="left: {{ ($i - 1) * 22 }}%; top: -8px; -webkit-text-stroke: 1px #000; text-stroke: 1px #000;"
                                  :class="hovered ? 'animate-floatNote{{ $i }}' : 'opacity-0'">
                                {{ $i % 3 === 0 ? '♫' : '♪' }}
                            </span>
                        @endfor
                    </div>

                    {{-- Click-spawned Notes --}}
                    <template x-for="note in musicNotes" :key="note.id">
                        <span class="absolute pointer-events-none text-2xl text-[#1a1a1a] animate-note-burst"
                              style="-webkit-text-stroke: 1px #000; text-stroke: 1px #000;"
                              :style="`left: calc(50% + ${note.x}px); top: 50%`"
                              x-text="note.note"></span>
                    </template>
                </div>
            </div>

            {{-- Musical Notes Animation Keyframes --}}
            <style>
                .animate-floatNote1 { animation: floatNote 2.5s ease-in-out infinite; animation-delay: 0s; }
                .animate-floatNote2 { animation: floatNote 2.8s ease-in-out infinite; animation-delay: 0.2s; }
                .animate-floatNote3 { animation: floatNote 3.0s ease-in-out infinite; animation-delay: 0.4s; }
                .animate-floatNote4 { animation: floatNote 2.6s ease-in-out infinite; animation-delay: 0.6s; }
                .animate-floatNote5 { animation: floatNote 3.2s ease-in-out infinite; animation-delay: 0.8s; }
                @keyframes floatNote {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    15% { opacity: 0.7; }
                    50% { transform: translateY(-25px) rotate(10deg); opacity: 0.9; }
                    85% { opacity: 0.3; }
                }
                @keyframes noteBurst {
                    0% { opacity: 1; transform: translateY(0) rotate(0deg) scale(1); }
                    50% { opacity: 0.8; }
                    100% { opacity: 0; transform: translateY(-70px) rotate(25deg) scale(1.3); }
                }
                .animate-note-burst {
                    animation: noteBurst 2s ease-out forwards;
                }
            </style>
        </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-footer />

    {{-- Booking Calendar Modal --}}
    <livewire:booking-calendar-modal />

    {{-- Card Stack Blur/Darken Effect --}}
    <script>
        (function() {
            let ticking = false;
            let sections = null;

            function buildSections() {
                const sectionEls = Array.from(document.querySelectorAll('[data-card-index]'));
                if (!sectionEls.length) return;
                sectionEls.sort((a, b) => parseInt(a.dataset.cardIndex) - parseInt(b.dataset.cardIndex));

                sections = sectionEls.map((sec, i) => {
                    // For overlap detection, use the immediate next DOM sibling
                    // if it's a non-card section between this and the next card.
                    // This ensures sections like Event Gallery (idx=5) blur when
                    // #ueberuns scrolls over them, not only when Team Grid (idx=6) does.
                    let overlapEl = null;
                    const nextCard = sectionEls[i + 1];
                    if (nextCard) {
                        const sibling = sec.nextElementSibling;
                        if (sibling && sibling !== nextCard && !sibling.hasAttribute('data-card-index')) {
                            overlapEl = sibling;
                        }
                    }

                    return {
                        el: sec,
                        content: sec.querySelector('.card-stack-content'),
                        overlay: sec.querySelector('.card-stack-overlay'),
                        stickyTop: parseFloat(getComputedStyle(sec).top) || 0,
                        overlapEl: overlapEl,
                    };
                });
            }

            function updateCardEffects() {
                if (!sections) return;

                // Blur the ACTIVE visible card as the NEXT card slides up.
                //
                // The next card approaches from below. When its top edge
                // reaches the bottom edge of the current pinned card,
                // the overlap begins and blur starts. When it reaches the
                // header (pins), it fully covers the current card.
                //
                // Progress: from when next.top == sec.bottom (t=0)
                //           to when next.top == sec.stickyTop (t=1)
                // Range = sec.bottom - sec.stickyTop = sec.height (since sec is pinned at stickyTop)
                //
                // This is fully live — no cached positions, no state tracking.

                sections.forEach((sec, i) => {
                    if (!sec.content) return;

                    let blur = 0;
                    let darken = 0;

                    const next = sections[i + 1];
                    if (next) {
                        const secRect  = sec.el.getBoundingClientRect();
                        // Use the closer element for overlap: either an intermediate
                        // non-card sibling or the next card-index section
                        const overlapTop = sec.overlapEl
                            ? Math.min(sec.overlapEl.getBoundingClientRect().top, next.el.getBoundingClientRect().top)
                            : next.el.getBoundingClientRect().top;
                        const secBottom = secRect.top + secRect.height;

                        // Next element is overlapping the current card's area
                        if (overlapTop < secBottom) {
                            const range = secBottom - sec.stickyTop;
                            if (range > 0) {
                                const t = Math.min(1, (secBottom - overlapTop) / range);
                                blur   = t * 4.8;   // 80% of 6px max
                                darken = t * 0.24;   // 80% of 0.3 max
                            }
                        }
                    }

                    sec.content.style.filter = blur > 0.1 ? `blur(${blur.toFixed(1)}px)` : 'none';
                    sec.content.style.webkitFilter = blur > 0.1 ? `blur(${blur.toFixed(1)}px)` : 'none';

                    if (sec.overlay) {
                        sec.overlay.style.background = darken > 0.01 ? `rgba(0,0,0,${darken.toFixed(3)})` : 'transparent';
                    }
                });

                // Expose state for Playwright tests
                window.__cardStackCache = sections.map(s => {
                    const rect = s.el.getBoundingClientRect();
                    const overlapRect = s.overlapEl ? s.overlapEl.getBoundingClientRect() : null;
                    return {
                        idx: parseInt(s.el.dataset.cardIndex),
                        stickyTop: s.stickyTop,
                        rectTop: Math.round(rect.top),
                        height: Math.round(rect.height),
                        hasContent: !!s.content,
                        overlapTop: overlapRect ? Math.round(overlapRect.top) : null,
                    };
                });
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        updateCardEffects();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            window.addEventListener('resize', function() {
                sections = null;
                requestAnimationFrame(function() {
                    buildSections();
                    updateCardEffects();
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                buildSections();
                updateCardEffects();
            });

            document.addEventListener('livewire:navigated', function() {
                sections = null;
                requestAnimationFrame(function() {
                    buildSections();
                    updateCardEffects();
                });
            });
        })();
    </script>
</div>
