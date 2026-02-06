{{-- FAQ Livewire Component - 2-Column Grid with CI Colour --}}
<div>
    <section
        class="faq-section max-w-[900px] mx-auto px-5"
        style="font-family: 'Poppins', sans-serif"
    >
        <div
            class="faq-container grid md:grid-cols-2 gap-0"
            x-data="{ openItems: {} }"
        >
            @foreach($faqItems as $index => $item)
                <div class="faq-item border-b border-[#e0e0e0] px-4 py-0 transition-colors duration-300">
                    <button
                        @click="openItems[{{ $index }}] = !openItems[{{ $index }}]; $nextTick(() => { if(openItems[{{ $index }}]) { $el.closest('.faq-item').scrollIntoView({ behavior: 'smooth', block: 'nearest' }) } })"
                        class="faq-question w-full bg-transparent border-none outline-none text-left text-[1.1rem] font-semibold text-black cursor-pointer flex justify-between items-center py-2 transition-opacity duration-300 hover:opacity-70"
                        style="font-family: 'Poppins', sans-serif"
                    >
                        <span class="pr-4">{{ $item->question }}</span>
                        <span
                            class="icon text-2xl font-light min-w-[30px] text-center ml-5 text-[#5a9a84] transition-transform duration-300"
                            :class="openItems[{{ $index }}] ? 'rotate-45' : ''"
                        >
                            +
                        </span>
                    </button>
                    <div
                        x-show="openItems[{{ $index }}]"
                        x-collapse
                        class="faq-answer overflow-hidden"
                    >
                        <p class="pt-2 pb-2 text-sm leading-[1.6] font-light text-[#333] whitespace-pre-line">
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
    </section>
</div>
