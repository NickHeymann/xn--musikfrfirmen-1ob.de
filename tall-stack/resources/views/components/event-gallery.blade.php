{{-- Event Gallery Section - Swipeable Carousel --}}
<section class="py-12 md:py-20 bg-[#C8E6DC] relative z-20" data-section-theme="light" data-section-bg="#C8E6DC">
    <div class="container mx-auto px-4">
        {{-- Swiper Gallery --}}
        <div
            x-data="{
                currentSlide: 0,
                totalSlides: 5,
                touchStartX: 0,
                touchEndX: 0,
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                }
            }"
            class="relative max-w-6xl mx-auto"
        >
            {{-- Gallery with Side Arrows --}}
            <div class="flex items-center gap-2 lg:gap-4">
                {{-- Left Arrow - Desktop only --}}
                <button
                    @click="prev()"
                    class="hidden lg:flex shrink-0 w-8 h-8 items-center justify-center rounded-full text-[#1a1a1a]/30 hover:text-[#1a1a1a]/60 transition-colors"
                    aria-label="Vorheriges Bild"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Gallery Container --}}
                <div class="overflow-hidden rounded-3xl flex-1"
                     @touchstart.passive="touchStartX = $event.changedTouches[0].screenX"
                     @touchend.passive="
                         touchEndX = $event.changedTouches[0].screenX;
                         if (touchStartX - touchEndX > 50) { next(); }
                         if (touchEndX - touchStartX > 50) { prev(); }
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
                    @click="next()"
                    class="hidden lg:flex shrink-0 w-8 h-8 items-center justify-center rounded-full text-[#1a1a1a]/30 hover:text-[#1a1a1a]/60 transition-colors"
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
