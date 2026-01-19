{{-- Über uns Page --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Hero Section --}}
    <section class="relative py-32 px-6 bg-gradient-to-br from-teal-500 via-emerald-500 to-cyan-600 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">Über uns</h1>
            <p class="text-xl md:text-2xl text-[#D4F4E8]">
                Wir sind euer zuverlässiger Partner für unvergessliche Firmenevents in ganz Deutschland
            </p>
        </div>
    </section>

    {{-- Team Section --}}
    <section id="team" class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-5xl font-bold text-center mb-20 text-[#1a1a1a]">Unser Team</h2>

            <div class="space-y-24">
                @foreach($teamMembers as $index => $member)
                    <div class="flex flex-col {{ $index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} items-center gap-12 lg:gap-16">
                        {{-- Image --}}
                        <div class="lg:w-1/2">
                            <div class="person-card-large relative w-full max-w-[500px] h-[700px] group mx-auto">
                                <div class="circle-container-large">
                                    <img
                                        src="{{ $member->image }}"
                                        alt="{{ $member->name }}"
                                        class="person-image-large {{ $member->image_class }}"
                                    />
                                </div>
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div class="lg:w-1/2">
                            <h3 class="text-4xl md:text-5xl font-bold text-[#1a1a1a] mb-3">{{ $member->name }}</h3>
                            <p class="text-[#2DD4A8] font-semibold text-xl mb-2">{{ $member->role }}</p>
                            @if($member->role_second_line)
                                <p class="text-[#4a4a4a] text-lg mb-8">{{ $member->role_second_line }}</p>
                            @endif

                            @if($member->bio_title)
                                <h4 class="text-3xl font-semibold text-[#1a1a1a] mb-6 mt-8">{{ $member->bio_title }}</h4>
                            @endif

                            @if($member->bio_text)
                                <div class="prose prose-lg max-w-none">
                                    <p class="text-[#4a4a4a] leading-relaxed whitespace-pre-line">{{ $member->bio_text }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Our Story Section --}}
    <section class="py-24 px-6 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12 text-[#1a1a1a]">Unsere Geschichte</h2>
            <div class="prose prose-lg max-w-none">
                <p class="text-[#4a4a4a] leading-relaxed mb-6">
                    Seit Jahren organisieren wir erfolgreiche Firmenevents in ganz Deutschland. Was als kleine Agentur begann,
                    ist heute ein professionelles Team, das sich auf die Bedürfnisse von Unternehmen spezialisiert hat.
                </p>
                <p class="text-[#4a4a4a] leading-relaxed mb-6">
                    Unser Fokus liegt auf erstklassiger Musik und perfekter Technik für eure Veranstaltungen.
                    Wir verstehen, dass jedes Event einzigartig ist und entwickeln maßgeschneiderte Konzepte,
                    die genau zu euren Vorstellungen passen.
                </p>
                <p class="text-[#4a4a4a] leading-relaxed">
                    Von der ersten Anfrage bis zur erfolgreichen Durchführung stehen wir euch mit Erfahrung,
                    Leidenschaft und einem professionellen Netzwerk zur Seite.
                </p>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="py-24 px-6 bg-white">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-16 text-[#1a1a1a]">Unsere Werte</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Professionalität --}}
                <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 bg-[#D4F4E8] rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#2DD4A8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#1a1a1a] mb-4">Professionalität</h3>
                    <p class="text-[#4a4a4a] leading-relaxed">
                        Wir arbeiten mit geprüften Partnern und garantieren höchste Qualität bei jedem Event.
                    </p>
                </div>

                {{-- Zuverlässigkeit --}}
                <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 bg-[#D4F4E8] rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#2DD4A8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#1a1a1a] mb-4">Zuverlässigkeit</h3>
                    <p class="text-[#4a4a4a] leading-relaxed">
                        Auf uns könnt ihr euch verlassen - von der Planung bis zur Durchführung.
                    </p>
                </div>

                {{-- Leidenschaft --}}
                <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                    <div class="w-16 h-16 bg-[#D4F4E8] rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#2DD4A8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#1a1a1a] mb-4">Leidenschaft</h3>
                    <p class="text-[#4a4a4a] leading-relaxed">
                        Wir lieben was wir tun und setzen uns mit vollem Einsatz für euer Event ein.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 px-6 bg-gradient-to-br from-teal-500 via-emerald-500 to-cyan-600 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Bereit für euer Event?</h2>
            <p class="text-xl mb-10 text-[#D4F4E8]">
                Schickt uns eure Anfrage und erhaltet innerhalb von 24 Stunden ein individuelles Angebot.
            </p>
            <a href="/#kontakt" class="inline-block bg-white text-teal-600 px-10 py-4 rounded-full font-semibold text-lg hover:bg-[#D4F4E8] hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                Jetzt Angebot anfragen
            </a>
        </div>
    </section>
</div>

@push('styles')
<style>
    /* About Page - Larger Circular Image Containers */
    .person-card-large {
        position: relative;
        overflow: visible;
    }

    .circle-container-large {
        position: relative;
        width: 500px;
        height: 700px;
        background-color: #D4F4E8;
        clip-path: path("M 487.5,500 C 487.5,631.168 381.168,737.5 250,737.5 118.83225,737.5 12.5,631.168 12.5,500 V 12.5 H 250 487.5 Z");
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform: scale(0.60);
        transform-origin: center bottom;
    }

    .person-card-large:hover .circle-container-large {
        transform: scale(0.65);
    }

    .person-image-large {
        position: absolute;
        width: 100%;
        height: auto;
        object-fit: cover;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Jonas Image Positioning (Large) */
    .person-image-large.img1 {
        left: 28px;
        top: 275px;
        width: 425px;
        transform: translateY(25px) scale(1.15);
    }

    .person-card-large:hover .person-image-large.img1 {
        transform: translateY(0) scale(1.2);
    }

    /* Nick Image Positioning (Large) */
    .person-image-large.img2 {
        left: -8px;
        top: 256px;
        width: 555px;
        transform: translateY(25px) scale(1.15);
    }

    .person-card-large:hover .person-image-large.img2 {
        transform: translateY(0) scale(1.2);
    }
</style>
@endpush
