{{-- musikfürfirmen.de Homepage - Canva Redesign --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Hero Section - Simplified --}}
    <x-hero />

    {{-- NEW: Testimonial Section (Dynamic with Filament) --}}
    <livewire:testimonial-carousel />

    {{-- Was wir bieten - Alternating Service Layout --}}
    <section id="waswirbieten" class="bg-white scroll-mt-[80px] lg:scroll-mt-[108px] relative z-20">
        <x-service-cards />
    </section>

    {{-- NEW: Warum Wir? Section --}}
    <x-why-us-section />

    {{-- NEW: Das heißt für euch - Benefits --}}
    <x-benefits-section />

    {{-- Event Gallery - Swipeable Carousel --}}
    <x-event-gallery />

    {{-- Team Section --}}
    <section id="ueberuns" class="bg-white scroll-mt-[80px] lg:scroll-mt-[108px] relative z-20">
        <x-team-section />
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="pt-24 pb-16 bg-white scroll-mt-[80px] lg:scroll-mt-[108px] relative z-20" data-section-bg="#ffffff" data-section-theme="light">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                class="text-center text-4xl md:text-5xl font-bold mb-16 tracking-[-1px] text-black"
                style="font-family: 'Poppins', sans-serif"
            >
                FAQ
            </h2>
            <livewire:faq-section />

            {{-- Animated Logo with Musical Notes --}}
            <div class="mt-20 relative flex justify-center">
                <div class="relative">
                    <a href="/"
                       class="text-2xl sm:text-3xl font-light hover:text-[#2DD4A8] transition-colors text-[#1a1a1a]"
                       style="font-family: 'Poppins', sans-serif">
                        musikfürfirmen.de
                    </a>

                    {{-- Floating Musical Notes Animation --}}
                    <div class="absolute inset-0 pointer-events-none">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="absolute text-[#2DD4A8] opacity-0"
                                  style="
                                      left: {{ ($i - 1) * 20 }}%;
                                      animation: floatNote{{ $i }} {{ 3 + $i * 0.5 }}s infinite ease-in-out;
                                      animation-delay: {{ $i * 0.3 }}s;
                                      font-size: {{ 16 + $i * 2 }}px;
                                  ">
                                {{ $i % 3 === 0 ? '♫' : '♪' }}
                            </span>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Musical Notes Animation Keyframes --}}
            <style>
                @keyframes floatNote1 {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.6; }
                    50% { transform: translateY(-30px) rotate(10deg); opacity: 0.8; }
                    90% { opacity: 0.3; }
                }
                @keyframes floatNote2 {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.5; }
                    50% { transform: translateY(-40px) rotate(-15deg); opacity: 0.7; }
                    90% { opacity: 0.2; }
                }
                @keyframes floatNote3 {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.7; }
                    50% { transform: translateY(-35px) rotate(20deg); opacity: 0.9; }
                    90% { opacity: 0.4; }
                }
                @keyframes floatNote4 {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.4; }
                    50% { transform: translateY(-45px) rotate(-10deg); opacity: 0.6; }
                    90% { opacity: 0.2; }
                }
                @keyframes floatNote5 {
                    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.6; }
                    50% { transform: translateY(-38px) rotate(15deg); opacity: 0.8; }
                    90% { opacity: 0.3; }
                }
            </style>
        </div>
    </section>

    {{-- Footer --}}
    <x-footer />

    {{-- Booking Calendar Modal --}}
    <livewire:booking-calendar-modal />
</div>
