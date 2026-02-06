{{-- Benefits Cards Section - "Das heißt für euch" (Collapsible) --}}
<section class="benefits-cards-section w-full py-12 md:py-20 bg-white relative z-20" data-section-theme="light" data-section-bg="#ffffff" style="font-family: 'Poppins', sans-serif"
         x-data="{ visible: false, openIndex: null }"
         x-intersect="visible = true">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#1a1a1a] mb-10 md:mb-16 transition-all duration-700"
            :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
            Das heißt für euch
        </h2>

        {{-- Collapsible Benefits --}}
        <div class="max-w-4xl mb-10 md:mb-16 space-y-2">
            {{-- Item 1: Einen Ansprechpartner --}}
            <div class="rounded-2xl transition-all duration-300"
                 :class="[
                     visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12',
                     openIndex === 0 ? 'bg-[#C8E6DC]' : 'bg-transparent border-b border-[#1a1a1a]/10'
                 ]"
                 style="transition-delay: 100ms">
                <button @click="openIndex = openIndex === 0 ? null : 0"
                        class="w-full flex justify-between items-center py-4 px-4 md:px-6 text-left">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#1a1a1a]">
                        Einen Ansprechpartner
                    </h3>
                    <svg class="w-5 h-5 text-[#1a1a1a]/50 transition-transform duration-300 shrink-0 ml-4"
                         :class="openIndex === 0 ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openIndex === 0" x-collapse>
                    <div class="px-4 md:px-6 pb-4">
                        <p class="text-sm sm:text-base text-[#1a1a1a]/80 leading-relaxed">
                            Rundumbetreuung bis zum Ende eures Events! Wir als Gründer sind bei dem Event vor Ort und stehen euch auch währenddessen zur Verfügung!
                        </p>
                    </div>
                </div>
            </div>

            {{-- Item 2: Kein Ausfallrisiko --}}
            <div class="rounded-2xl transition-all duration-300"
                 :class="[
                     visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12',
                     openIndex === 1 ? 'bg-[#C8E6DC]' : 'bg-transparent border-b border-[#1a1a1a]/10'
                 ]"
                 style="transition-delay: 200ms">
                <button @click="openIndex = openIndex === 1 ? null : 1"
                        class="w-full flex justify-between items-center py-4 px-4 md:px-6 text-left">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#1a1a1a]">
                        Kein Ausfallrisiko.
                    </h3>
                    <svg class="w-5 h-5 text-[#1a1a1a]/50 transition-transform duration-300 shrink-0 ml-4"
                         :class="openIndex === 1 ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openIndex === 1" x-collapse>
                    <div class="px-4 md:px-6 pb-4">
                        <p class="text-sm sm:text-base text-[#1a1a1a]/80 leading-relaxed">
                            Mehrere feste Konstellationen für Bands. So geplant, dass im Falle eines Ausfalls unsere "Subs" einspringen. Gilt auch für Djs und Technik.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Item 3: 100% Qualität --}}
            <div class="rounded-2xl transition-all duration-300"
                 :class="[
                     visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12',
                     openIndex === 2 ? 'bg-[#C8E6DC]' : 'bg-transparent border-b border-[#1a1a1a]/10'
                 ]"
                 style="transition-delay: 300ms">
                <button @click="openIndex = openIndex === 2 ? null : 2"
                        class="w-full flex justify-between items-center py-4 px-4 md:px-6 text-left">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#1a1a1a]">
                        100% Qualität
                    </h3>
                    <svg class="w-5 h-5 text-[#1a1a1a]/50 transition-transform duration-300 shrink-0 ml-4"
                         :class="openIndex === 2 ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openIndex === 2" x-collapse>
                    <div class="px-4 md:px-6 pb-4">
                        <p class="text-sm sm:text-base text-[#1a1a1a]/80 leading-relaxed">
                            Wir arbeiten ausschließlich mit Profi-Musiker:innen und Ausstattung von unserem professionellen Technikpartner.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section - Compact & Subtle --}}
        <div class="text-center max-w-2xl mx-auto space-y-5 transition-all duration-700"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition-delay: 400ms">

            <h3 class="text-lg md:text-xl font-medium text-[#1a1a1a] leading-relaxed">
                Kostenloses Erstgespräch
            </h3>

            <p class="text-sm md:text-base text-[#6b7280] leading-relaxed max-w-xl mx-auto">
                Gemeinsam definieren wir die musikalischen Anforderungen, besprechen eure Vorstellungen und schaffen die Grundlage für ein individuelles Angebot.
            </p>

            <div>
                <button
                    onclick="Livewire.dispatch('openBookingModal')"
                    class="inline-block px-5 py-2.5 text-sm rounded-full border border-black/30 bg-transparent text-black hover:bg-[#b8ddd2] transition-all duration-300">
                    Jetzt Termin vereinbaren
                </button>
            </div>
        </div>
    </div>
</section>
