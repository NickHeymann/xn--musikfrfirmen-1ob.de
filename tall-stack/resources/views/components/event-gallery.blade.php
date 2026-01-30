{{-- Event Gallery Section - Swipeable Carousel --}}
<section class="py-20 bg-gray-900" data-section-theme="dark">
    <div class="container mx-auto px-4">
        <h2 class="font-poppins font-semibold text-4xl md:text-5xl text-center mb-12 text-white">
            Unser letztes Event.
        </h2>

        {{-- Swiper Gallery --}}
        <div
            x-data="{
                currentSlide: 0,
                totalSlides: 5,
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                }
            }"
            class="relative max-w-6xl mx-auto"
        >
            {{-- Gallery Container --}}
            <div class="overflow-hidden rounded-3xl">
                <div
                    class="flex transition-transform duration-500 ease-out"
                    :style="`transform: translateX(-${currentSlide * 100}%)`"
                >
                    @foreach(['event-1.jpg', 'event-2.jpg', 'event-3.jpg', 'event-4.jpg', 'event-5.jpg'] as $photo)
                        <div class="w-full flex-shrink-0 px-2">
                            <img
                                src="/images/events/{{ $photo }}"
                                alt="Event Foto"
                                class="w-full h-[500px] object-cover rounded-3xl"
                            >
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Navigation Arrows --}}
            <button
                @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full flex items-center justify-center transition-all hover:scale-110"
                style="background-color: #b8ddd2;"
                aria-label="Vorheriges Bild"
            >
                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button
                @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full flex items-center justify-center transition-all hover:scale-110"
                style="background-color: #b8ddd2;"
                aria-label="Nächstes Bild"
            >
                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Dots Indicator --}}
            <div class="flex justify-center gap-2 mt-6">
                <template x-for="index in totalSlides" :key="index">
                    <button
                        @click="currentSlide = index - 1"
                        class="h-3 rounded-full transition-all"
                        :class="currentSlide === (index - 1) ? 'w-8' : 'w-3'"
                        :style="currentSlide === (index - 1) ? 'background-color: #b8ddd2' : 'background-color: #4b5563'"
                        :aria-label="`Gehe zu Bild ${index}`"
                    ></button>
                </template>
            </div>
        </div>

        {{-- Link zur Band-Seite --}}
        <div class="text-center mt-12">
            <a href="/band" class="text-xl underline font-poppins hover:opacity-80 transition-opacity" style="color: #b8ddd2;">
                hier gehts auf neue seite mit bandvideos und den Bandurlauden + mehr info
            </a>
            <p class="font-poppins text-base mt-2 text-gray-400">Mehr Über Uns erfahren</p>
        </div>
    </div>
</section>
