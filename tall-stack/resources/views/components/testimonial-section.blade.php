{{-- "Unser letztes Event" Testimonial Section - Canva Redesign --}}
<section class="testimonial-section w-full py-24 bg-white" style="font-family: 'Poppins', sans-serif">
    <div class="max-w-4xl mx-auto px-6 text-center">
        {{-- Section Heading --}}
        <h2 class="text-3xl md:text-4xl font-bold text-[#1a1a1a] mb-12">
            Unser letztes Event.
        </h2>

        {{-- Large Quote Icon --}}
        <div class="mb-8">
            <svg class="w-16 h-16 mx-auto text-[#2DD4A8] opacity-50" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
        </div>

        {{-- Testimonial Quote --}}
        <blockquote class="mb-10">
            <p class="text-xl md:text-2xl text-[#4a5568] leading-relaxed italic mb-8">
                "Wir haben die Band für die Feier zum 25. Jubiläum unseres Unternehmens gebucht und sie haben unsere Erwartungen mehr als übertroffen. Vielen Dank noch mal an das ganze MFF-Team — wir werden euch unseren Kunden weiterempfehlen!"
            </p>
            <footer class="text-[#1a1a1a]">
                <cite class="not-italic">
                    <span class="font-semibold text-lg">Peter Müller</span>
                    <span class="text-[#6b7280]">, CEO</span>
                    <br>
                    <span class="text-[#2DD4A8] font-medium">Red Life King GmbH</span>
                </cite>
            </footer>
        </blockquote>

        {{-- Optional: Rating Stars --}}
        <div class="flex justify-center gap-1">
            @for ($i = 0; $i < 5; $i++)
                <svg class="w-6 h-6 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>
    </div>
</section>
