{{-- "Warum Wir?" Section - Scroll-Linked Parallax Animation --}}
<section class="why-us-section w-full py-20 bg-white overflow-hidden relative z-20" data-section-bg="#ffffff" data-section-theme="light" style="font-family: 'Poppins', sans-serif"
         x-data="{
            visible: false,
            init() {
                const section = this.$el;

                const updatePositions = () => {
                    const sectionRect = section.getBoundingClientRect();
                    const windowHeight = window.innerHeight;

                    // All elements animate based on section position
                    // When section heading is visible (top at 80% viewport), all animations complete
                    const scrollProgress = Math.max(0, Math.min(1,
                        (windowHeight * 1.2 - sectionRect.top) / (windowHeight * 0.8)
                    ));

                    // Update each element
                    section.querySelectorAll('[data-scroll-dir]').forEach(el => {
                        const dir = el.dataset.scrollDir;
                        const distance = 150; // pixels to travel

                        let x = 0, y = 0;

                        if (dir === 'top') {
                            y = -distance * (1 - scrollProgress);
                        } else if (dir === 'left') {
                            x = -distance * (1 - scrollProgress);
                        } else if (dir === 'right') {
                            x = distance * (1 - scrollProgress);
                        }

                        const opacity = scrollProgress;
                        el.style.transform = `translate(${x}px, ${y}px)`;
                        el.style.opacity = opacity;
                    });
                };

                // Update on scroll
                window.addEventListener('scroll', updatePositions, { passive: true });

                // IntersectionObserver to trigger animation when section becomes visible
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.visible = true;
                            updatePositions();
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -10% 0px'
                });

                observer.observe(section);

                // Initial update
                updatePositions();
            }
         }">
    <div class="max-w-6xl mx-auto px-6">
        {{-- Section Heading --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#1a1a1a] mb-20">
            Warum Wir?
        </h2>

        {{-- Main Intro Text - Slide from Top --}}
        <div data-scroll-dir="top" class="mb-16 max-w-4xl mx-auto text-center">
            <p class="text-base md:text-lg leading-relaxed text-[#1a1a1a]">
                Die gängige Sicht: Livemusik ist bloß ein Baustein unter vielen. Davon sind auch Eventagenturen nicht ausgenommen. Bei ihnen sind Bands und DJs häufig bloß Teil eines großen Portfolios, weshalb es zu Qualitätsschwankungen kommen kann. Diesen Faktor der Ungewissheit möchten wir euch abnehmen.
            </p>
        </div>

        {{-- Testimonial Blocks - Slide from Different Directions --}}
        <div class="space-y-12">
            {{-- Testimonial 1 - From Left --}}
            <div data-scroll-dir="left" class="max-w-xl">
                <p class="text-sm md:text-base italic font-light leading-relaxed text-[#1a1a1a]">
                    "Die Band hat bei unserem Firmenevent gespielt und für eine fantastische Atmosphäre gesorgt. Die Songauswahl war perfekt abgestimmt und die Musiker total sympathisch. Klare Empfehlung für alle, die echte Livemusik schätzen!"
                </p>
            </div>

            {{-- Testimonial 2 - From Right --}}
            <div data-scroll-dir="right" class="max-w-2xl ml-auto">
                <p class="text-sm md:text-base italic font-light leading-relaxed text-[#1a1a1a]">
                    "Diese Band ist ein absolutes Highlight. Mit ihrer fantastischen Stimme hat die Lead Sängerin sofort alle in ihren Bann gezogen."
                </p>
                <p class="text-sm md:text-base italic font-light leading-relaxed text-[#1a1a1a] mt-4">
                    "Mit einem sehr umfangreichen Repertoire bieten sie den gesamten Abend ein abwechslungsreiches Programm das jeden Wunsch erfüllt . Ich würde auch 6 Sterne vergeben .....ein absoluter Tipp für größere Veranstaltungen ! Danke für den unvergesslichen Abend"
                </p>
            </div>

            {{-- Testimonial 3 - From Left --}}
            <div data-scroll-dir="left" class="max-w-xl">
                <p class="text-sm md:text-base italic font-light leading-relaxed text-[#1a1a1a]">
                    "Wir haben die Band bei einem Firmenevent mit Open-air Bühne erlebt und sind absolut begeistert. Die Band hat das gesamte Publikum in ihren Bann gezogen und mit der Auswahl der Lieder die Stimmung immer mehr gesteigert, so dass alle getanzt haben und Zugaben gefordert wurden."
                </p>
            </div>
        </div>
    </div>
</section>
