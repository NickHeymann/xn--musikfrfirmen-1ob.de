{{-- Benefits Cards Section - "Das heißt für euch" (Refined & Airy) --}}
<section class="benefits-cards-section w-full py-24 md:py-32 bg-white" data-section-theme="light" data-section-bg="#ffffff" style="font-family: 'Poppins', sans-serif"
         x-data="{ visible: false }"
         x-intersect="visible = true">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-6xl font-bold text-[#1a1a1a] mb-20 md:mb-24 transition-all duration-700"
            :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
            Das heißt für euch
        </h2>

        {{-- Benefits Cards Grid - More spacing --}}
        <div class="grid md:grid-cols-3 gap-8 md:gap-10 lg:gap-12 mb-32">
            {{-- Card 1: Einen Ansprechpartner --}}
            <div class="bg-[#C8E6DC] rounded-3xl p-10 md:p-12 transition-all duration-700 hover:-translate-y-1"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
                 style="transition-delay: 100ms">
                <h3 class="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-6 leading-tight">
                    Einen Ansprechpartner
                </h3>
                <p class="text-base md:text-lg text-[#1a1a1a] leading-relaxed">
                    Rundumbetreuung bis zum Ende eures Events! Wir als Gründer sind bei dem Event vor Ort und stehen euch auch währenddessen zur Verfügung!
                </p>
            </div>

            {{-- Card 2: Kein Ausfallrisiko --}}
            <div class="bg-[#C8E6DC] rounded-3xl p-10 md:p-12 transition-all duration-700 hover:-translate-y-1"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
                 style="transition-delay: 200ms">
                <h3 class="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-6 leading-tight">
                    Kein Ausfallrisiko.
                </h3>
                <p class="text-base md:text-lg text-[#1a1a1a] leading-relaxed">
                    Mehrere feste Konstellationen für Bands. So geplant, dass im Falle eines Ausfalls unsere "Subs" einspringen. Gilt auch für Djs und Technik.
                </p>
            </div>

            {{-- Card 3: 100% Qualität --}}
            <div class="bg-[#C8E6DC] rounded-3xl p-10 md:p-12 transition-all duration-700 hover:-translate-y-1"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
                 style="transition-delay: 300ms">
                <h3 class="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-6 leading-tight">
                    100% Qualität
                </h3>
                <p class="text-base md:text-lg text-[#1a1a1a] leading-relaxed">
                    Wir arbeiten ausschließlich mit Profi-Musiker:innen und Ausstattung von unserem professionellen Technikpartner
                </p>
            </div>
        </div>

        {{-- CTA Section - More breathing room --}}
        <div class="text-center max-w-4xl mx-auto space-y-10 transition-all duration-700"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition-delay: 400ms">
            <p class="text-2xl md:text-3xl font-medium text-[#1a1a1a] leading-relaxed">
                Wir sind 24/7 für euch telefonisch oder auch über WhatsApp erreichbar.
            </p>

            {{-- CTA Button --}}
            <div>
                <button
                    onclick="Livewire.dispatch('openBookingModal')"
                    class="btn-secondary">
                    Kostenloses Erstgespräch
                </button>
            </div>

            {{-- Description Text --}}
            <p class="text-lg text-[#6b7280] leading-relaxed max-w-3xl mx-auto">
                Gemeinsam definieren wir die musikalischen Anforderungen, besprechen eure Vorstellungen und schaffen die Grundlage für ein individuelles Angebot.
            </p>
        </div>
    </div>
</section>
