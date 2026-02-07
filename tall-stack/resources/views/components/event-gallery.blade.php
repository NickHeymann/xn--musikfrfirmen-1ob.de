{{-- Event Gallery Section - Swipeable Carousel --}}
<section class="sticky top-[80px] lg:top-[108px] py-12 md:py-20 bg-[#C8E6DC] relative z-[25]" data-section-theme="light" data-section-bg="#C8E6DC" data-card-index="5">
    <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
    <div class="container mx-auto px-4">
        {{-- Swiper Gallery --}}
        <div
            x-data="{
                currentSlide: 0,
                totalSlides: 5,
                touchStartX: 0,
                touchEndX: 0,
                dragging: false,
                dragStartX: 0,
                autoplayTimer: null,
                wheelCooldown: false,
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                startAutoplay() {
                    this.autoplayTimer = setInterval(() => this.next(), 5000);
                },
                resetAutoplay() {
                    clearInterval(this.autoplayTimer);
                    this.startAutoplay();
                },
                init() {
                    this.startAutoplay();
                }
            }"
            class="relative max-w-6xl mx-auto"
        >
            {{-- Gallery with Side Arrows --}}
            <div class="flex items-center gap-2 lg:gap-4">
                {{-- Left Arrow - Desktop only --}}
                <button
                    @click="prev(); resetAutoplay()"
                    class="hidden lg:flex shrink-0 w-10 h-10 items-center justify-center rounded-full border border-white/40 text-white/60 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all duration-200"
                    aria-label="Vorheriges Bild"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Gallery Container --}}
                <div class="overflow-hidden rounded-3xl flex-1 cursor-grab active:cursor-grabbing select-none"
                     @touchstart.passive="touchStartX = $event.changedTouches[0].screenX"
                     @touchend.passive="
                         touchEndX = $event.changedTouches[0].screenX;
                         if (touchStartX - touchEndX > 50) { next(); resetAutoplay(); }
                         if (touchEndX - touchStartX > 50) { prev(); resetAutoplay(); }
                     "
                     @mousedown.prevent="dragging = true; dragStartX = $event.clientX"
                     @mousemove.prevent="if (!dragging) return"
                     @mouseup.prevent="
                         if (!dragging) return;
                         dragging = false;
                         let diff = dragStartX - $event.clientX;
                         if (diff > 50) { next(); resetAutoplay(); }
                         if (diff < -50) { prev(); resetAutoplay(); }
                     "
                     @mouseleave="dragging = false"
                     @wheel="
                         if (wheelCooldown) return;
                         if (Math.abs($event.deltaX) > Math.abs($event.deltaY) && Math.abs($event.deltaX) > 30) {
                             $event.preventDefault();
                             wheelCooldown = true;
                             if ($event.deltaX > 30) { next(); resetAutoplay(); }
                             if ($event.deltaX < -30) { prev(); resetAutoplay(); }
                             setTimeout(() => wheelCooldown = false, 500);
                         }
                     ">
                    <div
                        class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${currentSlide * 100}%)`"
                    >
                        @foreach(['event-1.jpg', 'event-2.jpg', 'event-3.jpg', 'event-4.jpg', 'event-5.jpg'] as $photo)
                            <div class="w-full shrink-0 px-2">
                                <img
                                    src="/images/events/{{ $photo }}"
                                    alt="Event Foto"
                                    class="w-full h-[500px] object-cover rounded-3xl"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right Arrow - Desktop only --}}
                <button
                    @click="next(); resetAutoplay()"
                    class="hidden lg:flex shrink-0 w-10 h-10 items-center justify-center rounded-full border border-white/40 text-white/60 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all duration-200"
                    aria-label="Nächstes Bild"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Dots Indicator - Smaller --}}
            <div class="flex justify-center gap-2 mt-6">
                <template x-for="index in totalSlides" :key="index">
                    <button
                        @click="currentSlide = index - 1"
                        class="h-2 rounded-full transition-all"
                        :class="currentSlide === (index - 1) ? 'w-5' : 'w-2'"
                        :style="currentSlide === (index - 1) ? 'background-color: #1a1a1a' : 'background-color: rgba(26,26,26,0.2)'"
                        :aria-label="`Gehe zu Bild ${index}`"
                    ></button>
                </template>
            </div>
        </div>

    </div>
</section>
