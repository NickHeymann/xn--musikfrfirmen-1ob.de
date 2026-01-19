<div class="homepage">
    {{-- Hero Section with Video Background --}}
    <section class="hero relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Video Background --}}
        <video
            autoplay
            loop
            muted
            playsinline
            class="absolute inset-0 w-full h-full object-cover"
        >
            <source src="/videos/hero-background.mp4" type="video/mp4">
        </video>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-6xl mx-auto px-6 text-center text-white"
             x-data="{
                currentIndex: 0,
                letters: [],
                isHolding: false,
                words: ['Musik', 'Livebands', 'Djs', 'Technik'],
                init() {
                    this.changeWord();
                    setInterval(() => this.changeWord(), 3000);
                },
                changeWord() {
                    const word = this.words[this.currentIndex];
                    this.letters = word.split('');
                    this.isHolding = true;
                    setTimeout(() => {
                        this.isHolding = false;
                        setTimeout(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.words.length;
                        }, 350);
                    }, 2650);
                }
             }">

            {{-- Main Heading with Word Rotation --}}
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-8 leading-tight">
                Deine
                <span class="inline-block min-w-[300px] md:min-w-[500px]" :class="{ 'holding': isHolding }">
                    <template x-for="(letter, index) in letters" :key="`${currentIndex}-${index}`">
                        <span
                            class="inline-block letter-fade"
                            :style="`animation-delay: ${index * 0.04 + 0.05}s`"
                            x-text="letter === ' ' ? '\u00A0' : letter"
                        ></span>
                    </template>
                </span>
                <br />
                <span class="text-[#D4F4E8]">für Firmenevents!</span>
            </h1>

            {{-- Features List --}}
            <ul class="mb-12 space-y-3 text-lg md:text-xl max-w-2xl mx-auto">
                <li class="flex items-center justify-center gap-3 check-pop" style="animation-delay: 0.2s">
                    <svg class="w-6 h-6 text-[#2DD4A8]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Musik für jedes Firmenevent</span>
                </li>
                <li class="flex items-center justify-center gap-3 check-pop" style="animation-delay: 0.4s">
                    <svg class="w-6 h-6 text-[#2DD4A8]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Rundum-sorglos-Paket</span>
                </li>
                <li class="flex items-center justify-center gap-3 check-pop" style="animation-delay: 0.6s">
                    <svg class="w-6 h-6 text-[#2DD4A8]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Angebot innerhalb von 24 Stunden</span>
                </li>
            </ul>

            {{-- CTA Button --}}
            <button class="inline-block bg-white text-teal-600 px-10 py-4 rounded-full font-semibold text-lg hover:bg-[#D4F4E8] hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                Unverbindliches Angebot anfragen
            </button>

            {{-- Scroll Prompt --}}
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 scroll-prompt">
                <span class="text-sm opacity-80">Scroll runter</span>
                <div class="flex flex-col">
                    <svg class="w-6 h-6 chevron-bounce" style="animation-delay: 0s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <svg class="w-6 h-6 chevron-bounce -mt-3" style="animation-delay: 0.2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest Videos Section --}}
    @if($recentVideos->count() > 0)
    <section class="recent-videos mb-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Aktuelle Videos</h2>
                <a href="{{ route('videos.index') }}" class="text-purple-600 hover:underline">
                    Alle Videos ansehen →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recentVideos as $video)
                    <div class="video-card bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                        {{-- YouTube Thumbnail --}}
                        <div class="relative pb-[56.25%] bg-gray-200">
                            @if($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" 
                                     alt="{{ $video->title }}" 
                                     class="absolute inset-0 w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg" 
                                     alt="{{ $video->title }}" 
                                     class="absolute inset-0 w-full h-full object-cover"
                                     loading="lazy">
                            @endif
                            
                            {{-- Play Button Overlay --}}
                            <div class="absolute inset-0 flex items-center justify-center">
                                <a href="{{ $video->youtube_url }}" 
                                   target="_blank"
                                   class="bg-red-600 text-white rounded-full p-4 hover:bg-red-700 transition">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 line-clamp-2">{{ $video->title }}</h3>
                            
                            @if($video->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($video->description, 100) }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                    {{ ucfirst($video->category) }}
                                </span>
                                <span>{{ $video->published_on_youtube->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Latest Blog Posts Section --}}
    @if($recentPosts->count() > 0)
    <section class="recent-posts mb-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Aktuelle Blogbeiträge</h2>
                <a href="{{ route('blog.index') }}" class="text-purple-600 hover:underline">
                    Alle Beiträge lesen →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recentPosts as $post)
                    <article class="blog-card bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-48 object-cover"
                                 loading="lazy">
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $post->title }}</h3>
                            
                            @if($post->excerpt)
                                <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 120) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>{{ $post->published_at->format('d.m.Y') }}</span>
                                @if($post->reading_time)
                                    <span>{{ $post->reading_time }} Min. Lesezeit</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('blog.show', $post->slug) }}" 
                               class="text-purple-600 hover:underline font-semibold">
                                Weiterlesen →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Services Section --}}
    @if($featuredServices->count() > 0)
    <section class="featured-services mb-16 bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Meine Angebote</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Entdecke meine Coaching-Angebote für mehr Selbstbewusstsein, Klarheit und innere Stärke
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredServices as $service)
                    <div class="service-card bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                        @if($service->icon)
                            <div class="text-4xl mb-4">{{ $service->icon }}</div>
                        @endif
                        
                        <h3 class="text-xl font-semibold mb-3">{{ $service->title }}</h3>
                        
                        <p class="text-gray-600 mb-4">{{ Str::limit($service->short_description, 150) }}</p>
                        
                        @if($service->price)
                            <p class="text-purple-600 font-bold mb-4">ab {{ $service->price }}€</p>
                        @endif
                        
                        <a href="{{ route('services.show', $service->slug) }}" 
                           class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                            Mehr erfahren
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('services.index') }}" 
                   class="text-purple-600 hover:underline font-semibold">
                    Alle Angebote ansehen →
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials Section --}}
    @if($testimonials->count() > 0)
    <section class="testimonials mb-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Was Klienten sagen</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-card bg-white shadow-lg rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                        
                        <p class="text-gray-700 mb-4 italic">"{{ $testimonial->content }}"</p>
                        
                        <div class="border-t pt-4">
                            <p class="font-semibold">{{ $testimonial->client_name }}</p>
                            @if($testimonial->client_role)
                                <p class="text-sm text-gray-500">{{ $testimonial->client_role }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Call to Action Section --}}
    <section class="cta bg-purple-600 text-white py-16 rounded-lg">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Bereit für deinen nächsten Schritt?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Lass uns gemeinsam an deiner persönlichen Entwicklung arbeiten. 
                Buche jetzt ein kostenloses Erstgespräch!
            </p>
            <a href="{{ route('contact') }}" 
               class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">
                Kostenloses Erstgespräch buchen
            </a>
        </div>
    </section>
</div>
