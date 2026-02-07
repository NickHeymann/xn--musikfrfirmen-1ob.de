<div>
    <section
        class="max-w-[900px] mx-auto px-5"
        x-data="{ openItems: {} }"
    >
        <div class="divide-y divide-[#e5e5e5]">
            @foreach($faqItems as $index => $item)
                <div class="faq-item" wire:key="faq-{{ $item->id ?? $index }}">
                    <button
                        @click="openItems[{{ $index }}] = !openItems[{{ $index }}]; $nextTick(() => { if(openItems[{{ $index }}]) { $el.closest('.faq-item').scrollIntoView({ behavior: 'smooth', block: 'nearest' }) } })"
                        class="w-full flex items-center justify-between gap-4 py-5 text-left text-base md:text-lg font-medium text-[#1a1a1a] transition-colors duration-200 hover:text-[#5a9a84] cursor-pointer"
                    >
                        <span>{{ $item->question }}</span>
                        <span
                            class="shrink-0 text-xl text-[#5a9a84] transition-transform duration-300 select-none"
                            :class="openItems[{{ $index }}] ? 'rotate-45' : ''"
                        >+</span>
                    </button>
                    <div
                        x-show="openItems[{{ $index }}]"
                        x-collapse
                    >
                        <p class="pb-5 text-sm md:text-base leading-relaxed text-[#1a1a1a]/70 whitespace-pre-line">
                            @if($item->has_link)
                                {!! str_replace(
                                    '"Unverbindliches Angebot anfragen"',
                                    '<span onclick="Livewire.dispatch(\'openMFFCalculator\')" class="text-[#5a9a84] cursor-pointer underline hover:text-[#4a8a74] transition-colors">Unverbindliches Angebot anfragen</span>',
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
