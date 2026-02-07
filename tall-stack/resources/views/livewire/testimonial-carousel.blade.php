{{-- Testimonial Carousel - Client-side Alpine.js with swipe support --}}
<section id="testimonial" class="testimonial-section sticky top-[80px] lg:top-[108px] w-full py-12 md:py-20 bg-[#C8E6DC] scroll-mt-[80px] lg:scroll-mt-[108px] relative z-[21]"
         data-section-theme="light"
         data-section-bg="#C8E6DC"
         data-card-index="1"
         style="font-family: 'Poppins', sans-serif">
    <div class="card-stack-overlay absolute inset-0 pointer-events-none z-50"></div>
    <div class="card-stack-content">
    <div class="max-w-3xl mx-auto px-6 sm:px-8">
        @if($testimonials->count() > 0)
            <div class="flex items-center gap-2 sm:gap-4"
                 x-data="{
                    current: 0,
                    count: {{ $testimonials->count() }},
                    autoplay: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    next() {
                        this.current = (this.current + 1) % this.count;
                    },
                    prev() {
                        this.current = (this.current - 1 + this.count) % this.count;
                    },
                    startAutoplay() {
                        this.autoplay = setInterval(() => this.next(), 8000);
                    },
                    resetAutoplay() {
                        clearInterval(this.autoplay);
                        this.startAutoplay();
                    },
                    init() {
                        this.startAutoplay();
                    }
                 }">

                {{-- Left Arrow --}}
                @if($testimonials->count() > 1)
                    <button
                        @click="prev(); resetAutoplay()"
                        class="shrink-0 p-2 rounded-full hover:bg-white/80 transition-colors"
                        aria-label="Previous testimonial">
                        <svg class="w-5 h-5 text-[#1a1a1a]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                @endif

                {{-- Testimonials Container --}}
                <div class="relative w-full overflow-visible min-h-[280px] sm:min-h-[240px]"
                     @touchstart.passive="touchStartX = $event.changedTouches[0].screenX"
                     @touchend.passive="
                        touchEndX = $event.changedTouches[0].screenX;
                        if (touchStartX - touchEndX > 50) { next(); resetAutoplay(); }
                        if (touchEndX - touchStartX > 50) { prev(); resetAutoplay(); }
                     ">
                    @foreach($testimonials as $index => $testimonial)
                        <div x-show="current === {{ $index }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="flex flex-col items-center"
                             @if($index > 0) style="position: absolute; inset: 0;" @endif>

                            {{-- Quote --}}
                            <blockquote class="mb-4">
                                <p class="text-sm sm:text-base text-[#1a1a1a]/90 leading-relaxed text-center font-normal max-w-xl mx-auto">
                                    {{ $testimonial->quote }}
                                </p>
                            </blockquote>

                            {{-- Author --}}
                            <footer class="text-center">
                                <cite class="not-italic">
                                    <p class="text-xs sm:text-sm text-[#1a1a1a] font-medium">
                                        {{ $testimonial->author_name }}
                                    </p>
                                    @if($testimonial->author_position || $testimonial->author_company)
                                        <p class="text-xs text-[#1a1a1a]/60 mt-0.5">
                                            @if($testimonial->author_position){{ $testimonial->author_position }}@endif@if($testimonial->author_position && $testimonial->author_company), @endif@if($testimonial->author_company){{ $testimonial->author_company }}@endif
                                        </p>
                                    @endif
                                </cite>
                            </footer>
                        </div>
                    @endforeach
                </div>

                {{-- Right Arrow --}}
                @if($testimonials->count() > 1)
                    <button
                        @click="next(); resetAutoplay()"
                        class="shrink-0 p-2 rounded-full hover:bg-white/80 transition-colors"
                        aria-label="Next testimonial">
                        <svg class="w-5 h-5 text-[#1a1a1a]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @endif
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-lg text-gray-500">Keine Testimonials verfügbar.</p>
            </div>
        @endif
    </div>
    </div>
</section>
