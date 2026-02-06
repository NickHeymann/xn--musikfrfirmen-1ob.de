{{-- Testimonial Carousel - Direkt nach Hero --}}
<section id="testimonial" class="testimonial-section w-full py-12 sm:py-16 md:py-20 bg-[#f5f5f0] scroll-mt-[80px] lg:scroll-mt-[108px] relative z-20"
         data-section-theme="light"
         data-section-bg="#f5f5f0"
         style="font-family: 'Poppins', sans-serif">
    <div class="max-w-3xl mx-auto px-6 sm:px-8">
        {{-- Section Heading --}}
        <h2 class="text-lg sm:text-xl md:text-2xl font-medium text-[#1a1a1a] mb-6 sm:mb-8 text-center">
            Testimonials
        </h2>

        @if($testimonials->count() > 0)
            {{-- Testimonial Content --}}
            <div class="flex flex-col items-center"
                 x-data="{
                    currentIndex: @entangle('currentIndex'),
                    displayIndex: @entangle('currentIndex'),
                    animating: false,
                    autoplay: null,
                    init() {
                        this.startAutoplay();
                        this.$watch('currentIndex', (val) => {
                            this.animating = true;
                            setTimeout(() => {
                                this.displayIndex = val;
                                setTimeout(() => { this.animating = false; }, 50);
                            }, 150);
                        });
                    },
                    startAutoplay() {
                        this.autoplay = setInterval(() => {
                            $wire.next();
                        }, 8000);
                    },
                    stopAutoplay() {
                        clearInterval(this.autoplay);
                    },
                    resetAutoplay() {
                        this.stopAutoplay();
                        this.startAutoplay();
                    }
                 }">

                {{-- Testimonials Container with fixed height to prevent layout shift --}}
                <div class="relative w-full min-h-[240px] sm:min-h-[220px] md:min-h-[200px] overflow-hidden">
                    @foreach($testimonials as $index => $testimonial)
                        <div x-show="displayIndex === {{ $index }}"
                             :class="animating ? 'opacity-0' : 'opacity-100'"
                             class="absolute inset-0 flex flex-col items-center justify-start transition-opacity duration-300 ease-out">

                            {{-- Quote with modern quotation marks --}}
                            <blockquote class="mb-5 relative">
                                <svg class="absolute -top-2 -left-4 w-6 h-6 text-[#2DD4A8]/20" fill="currentColor" viewBox="0 0 32 32">
                                    <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"/>
                                </svg>
                                <p class="text-sm sm:text-base text-[#1a1a1a]/90 leading-relaxed text-center font-normal max-w-xl mx-auto px-6">
                                    {{ $testimonial->quote }}
                                </p>
                            </blockquote>

                            {{-- Author - compact & modern --}}
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

                {{-- Navigation Dots (only if multiple testimonials) --}}
                @if($testimonials->count() > 1)
                    <div class="flex gap-1.5 mt-6">
                        @foreach($testimonials as $index => $testimonial)
                            <button
                                wire:click="goTo({{ $index }})"
                                @click="resetAutoplay()"
                                class="h-1.5 rounded-full transition-all duration-300"
                                :class="currentIndex === {{ $index }} ? 'bg-[#2DD4A8] w-6' : 'bg-gray-300 w-1.5 hover:bg-gray-400'"
                                aria-label="Go to testimonial {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>

                    {{-- Navigation Arrows - Smaller & Modern --}}
                    <div class="flex gap-3 mt-4">
                        <button
                            wire:click="previous"
                            @click="resetAutoplay()"
                            class="p-1.5 rounded-full hover:bg-white/80 transition-colors"
                            aria-label="Previous testimonial">
                            <svg class="w-4 h-4 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button
                            wire:click="next"
                            @click="resetAutoplay()"
                            class="p-1.5 rounded-full hover:bg-white/80 transition-colors"
                            aria-label="Next testimonial">
                            <svg class="w-4 h-4 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <p class="text-lg text-gray-500">Keine Testimonials verfügbar.</p>
            </div>
        @endif
    </div>
</section>
