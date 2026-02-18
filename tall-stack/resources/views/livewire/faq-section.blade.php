<div>
    <section
        class="max-w-[900px] mx-auto px-5"
        x-data="{ activeIndex: null }"
    >
        <div class="divide-y divide-[#e5e5e5]">
            @foreach($faqItems as $index => $item)
                <div class="faq-item rounded-lg transition-all duration-300 hover:bg-white" wire:key="faq-{{ $item->id ?? $index }}">
                    <button
                        @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}; $nextTick(() => { if(activeIndex === {{ $index }}) { $el.closest('.faq-item').scrollIntoView({ behavior: 'smooth', block: 'nearest' }) } })"
                        class="w-full flex items-center justify-between gap-4 py-5 px-4 text-left text-base md:text-lg font-medium text-[#1a1a1a] transition-colors duration-200 hover:text-[#5a9a84] cursor-pointer"
                    >
                        <span>{{ $item->question }}</span>
                        <span
                            class="shrink-0 text-xl text-[#5a9a84] transition-transform duration-300 select-none"
                            :class="activeIndex === {{ $index }} ? 'rotate-45' : ''"
                        >+</span>
                    </button>
                    <div
                        x-show="activeIndex === {{ $index }}"
                        x-collapse
                    >
                        <p class="pb-5 px-4 text-sm md:text-base leading-relaxed text-[#1a1a1a]/70 whitespace-pre-line">
                            @php
                                $answer = $item->answer;
                                $lastChar = mb_substr(rtrim($answer), -1);
                                $needsDot = !in_array($lastChar, ['.', '!', '?']);
                            @endphp
                            @if($item->has_link)
                                {!! str_replace(
                                    '"Unverbindliches Angebot anfragen"',
                                    '<span onclick="Livewire.dispatch(\'openMFFCalculator\')" class="text-[#5a9a84] cursor-pointer underline hover:text-[#4a8a74] transition-colors">Unverbindliches Angebot anfragen</span>',
                                    $answer
                                ) !!}{{ $needsDot ? '.' : '' }}
                            @else
                                {{ $answer }}{{ $needsDot ? '.' : '' }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
