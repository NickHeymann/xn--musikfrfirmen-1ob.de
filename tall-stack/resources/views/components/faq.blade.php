{{-- FAQ Component - Compact Accordion --}}
@php
$faqItems = \App\Models\Faq::active()->get();
$halfCount = ceil($faqItems->count() / 2);
@endphp

<section
    class="faq-section max-w-[1100px] mx-auto px-5"
    style="font-family: 'Poppins', sans-serif"
    x-data="{ activeIndex: null }"
>
    {{-- Desktop: 2-column grid, Mobile: single column --}}
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-12">
        {{-- Left Column --}}
        <div>
            @foreach($faqItems->take($halfCount) as $index => $item)
                <div class="border-b border-[#C8E6DC] transition-all duration-500 ease-in-out rounded-lg"
                     :class="activeIndex === {{ $index }} ? 'bg-[#C8E6DC]' : 'bg-transparent'">
                    <button
                        @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                        class="w-full bg-transparent border-none outline-none text-left text-sm sm:text-base font-semibold text-black cursor-pointer flex justify-between items-center py-3 md:py-4 transition-opacity duration-300 hover:opacity-70"
                        style="font-family: 'Poppins', sans-serif"
                    >
                        <span class="pr-4 leading-snug">{{ $item->question }}</span>
                        <span
                            class="text-lg font-light min-w-[24px] text-center ml-3 transition-all duration-300"
                            :class="activeIndex === {{ $index }} ? 'rotate-45 text-[#5a9a84]' : 'text-[#5a9a84]'"
                        >+</span>
                    </button>
                    <div
                        x-show="activeIndex === {{ $index }}"
                        x-collapse
                        class="overflow-hidden"
                    >
                        <p class="pb-3 md:pb-4 text-sm leading-relaxed font-light text-[#555] whitespace-pre-line">
                            @if($item->has_link)
                                {!! str_replace(
                                    '"Unverbindliches Angebot anfragen"',
                                    '<span onclick="Livewire.dispatch(\'openMFFCalculator\')" class="text-[#A0C4B5] cursor-pointer underline hover:text-[#8AB4A5] transition-colors">Unverbindliches Angebot anfragen</span>',
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

        {{-- Right Column --}}
        <div>
            @foreach($faqItems->skip($halfCount) as $index => $item)
                @php $realIndex = $index; @endphp
                <div class="border-b border-[#C8E6DC] transition-all duration-500 ease-in-out rounded-lg"
                     :class="activeIndex === {{ $realIndex }} ? 'bg-[#C8E6DC]' : 'bg-transparent'">
                    <button
                        @click="activeIndex = activeIndex === {{ $realIndex }} ? null : {{ $realIndex }}"
                        class="w-full bg-transparent border-none outline-none text-left text-sm sm:text-base font-semibold text-black cursor-pointer flex justify-between items-center py-3 md:py-4 transition-opacity duration-300 hover:opacity-70"
                        style="font-family: 'Poppins', sans-serif"
                    >
                        <span class="pr-4 leading-snug">{{ $item->question }}</span>
                        <span
                            class="text-lg font-light min-w-[24px] text-center ml-3 transition-all duration-300"
                            :class="activeIndex === {{ $realIndex }} ? 'rotate-45 text-[#5a9a84]' : 'text-[#5a9a84]'"
                        >+</span>
                    </button>
                    <div
                        x-show="activeIndex === {{ $realIndex }}"
                        x-collapse
                        class="overflow-hidden"
                    >
                        <p class="pb-3 md:pb-4 text-sm leading-relaxed font-light text-[#555] whitespace-pre-line">
                            @if($item->has_link)
                                {!! str_replace(
                                    '"Unverbindliches Angebot anfragen"',
                                    '<span onclick="Livewire.dispatch(\'openMFFCalculator\')" class="text-[#A0C4B5] cursor-pointer underline hover:text-[#8AB4A5] transition-colors">Unverbindliches Angebot anfragen</span>',
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
    </div>
</section>
