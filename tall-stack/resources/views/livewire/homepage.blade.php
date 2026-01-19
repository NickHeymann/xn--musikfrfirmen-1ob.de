{{-- musikfürfirmen.de Homepage - TALL Stack Version --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Hero Section --}}
    <x-hero />

    {{-- Was wir bieten - Service Cards --}}
    <section id="waswirbieten" class="bg-white overflow-visible pt-[187px] scroll-mt-0">
        <x-service-cards />
    </section>

    {{-- Service Steps - Process --}}
    <section id="wiewirarbeiten" class="pt-[108px] bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-[60px]">
                <h2
                    onclick="document.getElementById('wiewirarbeiten').scrollIntoView({ behavior: 'smooth' })"
                    class="text-[3rem] font-semibold text-[#1a1a1a] mb-[10px] cursor-pointer hover:opacity-70 transition-opacity"
                    style="font-family: 'Poppins', sans-serif"
                >
                    Musik und Technik? Läuft.
                </h2>
                <p
                    class="text-[1.5rem] font-normal text-[#1a1a1a] max-w-[600px] mx-auto mb-2"
                    style="font-family: 'Poppins', sans-serif"
                >
                    Von uns geplant. Von euch gefeiert.
                </p>
            </div>
            <x-process-steps />
        </div>
    </section>

    {{-- Team Section --}}
    <section id="ueberuns" class="pt-[178px] bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="text-center px-5 overflow-visible"
                style="font-family: 'Poppins', sans-serif"
            >
                <div
                    onclick="document.getElementById('ueberuns').scrollIntoView({ behavior: 'smooth' })"
                    class="cursor-pointer hover:opacity-70 transition-opacity inline-block"
                >
                    <x-hamburg-animation />
                </div>
            </div>
            <x-team-section />
        </div>
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="pt-[134px] bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                onclick="document.getElementById('faq').scrollIntoView({ behavior: 'smooth' })"
                class="text-center text-[3rem] font-semibold mb-[120px] tracking-[-1px] text-black cursor-pointer hover:opacity-70 transition-opacity"
                style="font-family: 'Poppins', sans-serif"
            >
                FAQ
            </h2>
            <x-faq />
        </div>
    </section>

    {{-- Logo Animation --}}
    <section class="pt-[190px] pb-[163px] bg-white relative z-[1]">
        <x-logo-animation />
    </section>

    {{-- CTA Section --}}
    <x-cta-section />

    {{-- Footer --}}
    <x-footer />
</div>
