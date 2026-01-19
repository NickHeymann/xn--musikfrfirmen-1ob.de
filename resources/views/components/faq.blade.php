{{-- FAQ Section - Production Accurate Implementation --}}
<section class="faq-section max-w-[900px] mx-auto px-5 py-16" style="font-family: 'Poppins', sans-serif">
    <div class="faq-container border-t border-[#e0e0e0]">
        @foreach ($faqItems as $index => $item)
            <div class="faq-item border-b border-[#e0e0e0]"
                 x-data="{
                     isActive: false,
                     maxHeight: '0px',
                     updateHeight() {
                         if (this.isActive) {
                             this.maxHeight = this.$refs.answer.scrollHeight + 'px';
                         } else {
                             this.maxHeight = '0px';
                         }
                     },
                     init() {
                         // Resize listener for height recalculation
                         window.addEventListener('resize', () => {
                             if (this.isActive) {
                                 this.maxHeight = this.$refs.answer.scrollHeight + 'px';
                             }
                         });

                         // Listen for accordion toggle from parent
                         this.$watch('isActive', () => this.updateHeight());
                     },
                     openCalculator(event) {
                         event.stopPropagation();
                         window.dispatchEvent(new CustomEvent('openMFFCalculator'));
                     }
                 }"
                 @toggle-accordion.window="if ($event.detail.index === {{ $index }}) { isActive = !isActive; } else { isActive = false; }">

                {{-- Question Button --}}
                <button
                    @click="$dispatch('toggle-accordion', { index: {{ $index }} })"
                    class="faq-question w-full bg-transparent border-none outline-none text-left text-[1.25rem] font-semibold text-black cursor-pointer flex justify-between items-center py-[30px] transition-opacity duration-300 hover:opacity-70"
                    style="font-family: 'Poppins', sans-serif">
                    <span class="pr-4">{{ $item['question'] }}</span>
                    <span
                        class="icon text-2xl font-light min-w-[30px] text-center ml-5 transition-transform duration-300"
                        :style="{ transform: isActive ? 'rotate(45deg)' : 'rotate(0deg)' }">
                        +
                    </span>
                </button>

                {{-- Answer --}}
                <div
                    x-ref="answer"
                    class="faq-answer overflow-hidden transition-[max-height] duration-[400ms] ease-out"
                    :style="{ maxHeight: maxHeight }">
                    <p class="pb-[30px] text-base leading-[1.6] font-light text-[#333] whitespace-pre-line">
                        @if (isset($item['hasLink']) && $item['hasLink'])
                            @php
                                $parts = explode('"Unverbindliches Angebot anfragen"', $item['answer']);
                            @endphp
                            {{ $parts[0] }}
                            <span
                                @click="openCalculator($event)"
                                class="text-[#7dc9b1] cursor-pointer underline hover:text-[#5eb89d] transition-colors">
                                Unverbindliches Angebot anfragen
                            </span>
                            {{ $parts[1] ?? '' }}
                        @else
                            {{ $item['answer'] }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
