{{-- Testimonial Carousel - Direkt nach Hero --}}
<section id="testimonial" class="testimonial-section w-full py-16 sm:py-20 md:py-24 bg-[#f5f5f0] scroll-mt-[80px] lg:scroll-mt-[108px] relative z-20"
         data-section-theme="light"
         data-section-bg="#f5f5f0"
         style="font-family: 'Poppins', sans-serif">
    <div class="max-w-4xl mx-auto px-6 sm:px-8 md:px-12">
        {{-- Section Heading --}}
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-light text-[#1a1a1a] mb-8 sm:mb-12 md:mb-16 text-center">
            Testimonial
        </h2>

        @if($testimonials->count() > 0)
            {{-- Testimonial Content --}}
            <div class="flex flex-col items-center"
                 x-data="{
                    currentIndex: @entangle('currentIndex'),
                    autoplay: null,
                    init() {
                        this.startAutoplay();
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

                {{-- Testimonials Loop --}}
                @foreach($testimonials as $index => $testimonial)
                    <div x-show="currentIndex === {{ $index }}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-8"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-8"
                         class="w-full">

                        {{-- Quote --}}
                        <blockquote class="mb-8 sm:mb-10 md:mb-12">
                            <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-[#1a1a1a] leading-relaxed text-center font-light max-w-3xl mx-auto">
                                {{ $testimonial->quote }}
                            </p>
                        </blockquote>

                        {{-- Author --}}
                        <footer class="text-center">
                            <cite class="not-italic">
                                <p class="text-base sm:text-lg md:text-xl text-[#1a1a1a] font-normal">
                                    {{ $testimonial->author_name }}@if($testimonial->author_position), {{ $testimonial->author_position }}@endif
                                </p>
                                @if($testimonial->author_company)
                                    <p class="text-base sm:text-lg md:text-xl text-[#1a1a1a] font-normal">
                                        {{ $testimonial->author_company }}
                                    </p>
                                @endif
                            </cite>
                        </footer>
                    </div>
                @endforeach

                {{-- Navigation Dots (only if multiple testimonials) --}}
                @if($testimonials->count() > 1)
                    <div class="flex gap-2 mt-8 sm:mt-10">
                        @foreach($testimonials as $index => $testimonial)
                            <button
                                wire:click="goTo({{ $index }})"
                                @click="resetAutoplay()"
                                class="w-2 h-2 rounded-full transition-all duration-300"
                                :class="currentIndex === {{ $index }} ? 'bg-[#2DD4A8] w-8' : 'bg-gray-400 hover:bg-gray-600'"
                                aria-label="Go to testimonial {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>

                    {{-- Navigation Arrows --}}
                    <div class="flex gap-4 mt-6">
                        <button
                            wire:click="previous"
                            @click="resetAutoplay()"
                            class="p-2 rounded-full hover:bg-white/50 transition-colors"
                            aria-label="Previous testimonial">
                            <svg class="w-6 h-6 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button
                            wire:click="next"
                            @click="resetAutoplay()"
                            class="p-2 rounded-full hover:bg-white/50 transition-colors"
                            aria-label="Next testimonial">
                            <svg class="w-6 h-6 text-[#1a1a1a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
