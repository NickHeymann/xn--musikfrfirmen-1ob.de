{{-- musikfürfirmen.de Homepage - Canva Redesign --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Hero Section - Simplified --}}
    <x-hero />

    {{-- Was wir bieten - Alternating Service Layout --}}
    <section id="waswirbieten" class="bg-white scroll-mt-[108px]">
        <x-service-cards />
    </section>

    {{-- NEW: Warum Wir? Section --}}
    <x-why-us-section />

    {{-- NEW: Das heißt für euch - Benefits --}}
    <x-benefits-section />

    {{-- NEW: WhatsApp CTA Section --}}
    <x-whatsapp-cta />

    {{-- NEW: Testimonial Section --}}
    <x-testimonial-section />

    {{-- Team Section --}}
    <section id="ueberuns" class="bg-white scroll-mt-[108px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Moin aus Hamburg Animation --}}
            <div
                class="text-center pt-24 pb-8"
                style="font-family: 'Poppins', sans-serif"
            >
                <h2 class="text-4xl md:text-5xl font-bold text-[#1a1a1a]">
                    Moin aus Hamburg!
                </h2>
            </div>
            <x-team-section />
        </div>
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="pt-24 pb-16 bg-white scroll-mt-[108px]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                class="text-center text-4xl md:text-5xl font-bold mb-16 tracking-[-1px] text-black"
                style="font-family: 'Poppins', sans-serif"
            >
                FAQ
            </h2>
            <x-faq />
        </div>
    </section>

    {{-- CTA Section --}}
    <x-cta-section />

    {{-- Footer --}}
    <x-footer />
</div>
