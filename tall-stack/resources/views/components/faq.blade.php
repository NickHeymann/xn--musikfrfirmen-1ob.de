{{-- FAQ Component - Accordion Implementation --}}
@php
$faqItems = \App\Models\Faq::active()->get();
@endphp

<section
    class="faq-section max-w-[900px] mx-auto px-5"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ activeIndex: null }"
>
    <div class="faq-container border-t border-[#e0e0e0]">
        @foreach($faqItems as $index => $item)
            <div class="faq-item border-b border-[#e0e0e0]">
                <button
                    @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                    class="faq-question w-full bg-transparent border-none outline-none text-left text-[1.25rem] font-semibold text-black cursor-pointer flex justify-between items-center py-[30px] transition-opacity duration-300 hover:opacity-70"
                    style="font-family: 'Poppins', sans-serif"
                >
                    <span class="pr-4">{{ $item->question }}</span>
                    <span
                        class="icon text-2xl font-light min-w-[30px] text-center ml-5 transition-transform duration-300"
                        :style="activeIndex === {{ $index }} ? 'transform: rotate(45deg)' : 'transform: rotate(0deg)'"
                    >
                        +
                    </span>
                </button>
                <div
                    x-show="activeIndex === {{ $index }}"
                    x-collapse
                    class="faq-answer overflow-hidden"
                >
                    <p class="pb-[30px] text-base leading-[1.6] font-light text-[#333] whitespace-pre-line">
                        @if($item->has_link)
                            {!! str_replace(
                                '"Unverbindliches Angebot anfragen"',
                                '<span onclick="window.dispatchEvent(new CustomEvent(\'openMFFCalculator\'))" class="text-[#7dc9b1] cursor-pointer underline hover:text-[#5eb89d] transition-colors">Unverbindliches Angebot anfragen</span>',
                                $item->answer
                            ) !!}
                        @else
                            {{ $item->answer }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
