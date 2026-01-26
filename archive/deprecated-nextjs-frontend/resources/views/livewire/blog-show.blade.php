<div class="blog-post-container max-w-4xl mx-auto px-4 py-12">
    {{-- Back Link --}}
    <div class="mb-8">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Zurück zur Übersicht
        </a>
    </div>

    {{-- Article Header --}}
    <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Featured Image --}}
        @if($post->featured_image)
            <div class="aspect-video w-full overflow-hidden">
                <img
                    src="{{ $post->featured_image }}"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover"
                    loading="eager"
                >
            </div>
        @endif

        {{-- Article Content --}}
        <div class="p-8 md:p-12">
            {{-- Meta Information --}}
            <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-600">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $post->published_at->format('d.m.Y') }}
                </span>

                @if($post->category)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                        {{ ucfirst($post->category) }}
                    </span>
                @endif

                @if($post->reading_time)
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $post->reading_time }} Min. Lesezeit
                    </span>
                @endif

                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ number_format($post->views) }} Aufrufe
                </span>
            </div>

            {{-- Title with Bookmark --}}
            <div class="flex items-start justify-between gap-4 mb-6">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight flex-1">
                    {{ $post->title }}
                </h1>
                <div class="flex-shrink-0 pt-2">
                    <livewire:bookmark-toggle :postId="$post->id" :key="'bookmark-' . $post->id" />
                </div>
            </div>

            {{-- Excerpt --}}
            @if($post->excerpt)
                <div class="text-xl text-gray-700 mb-8 leading-relaxed border-l-4 border-green-500 pl-6 italic">
                    {{ $post->excerpt }}
                </div>
            @endif

            {{-- Content --}}
            <div class="prose prose-lg max-w-none">
                {!! $post->content !!}
            </div>

            {{-- Tags --}}
            @if($post->tags && count($post->tags) > 0)
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Themen:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Ähnliche Artikel</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $relatedPost)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition group">
                        @if($relatedPost->featured_image)
                            <div class="aspect-video overflow-hidden">
                                <img
                                    src="{{ $relatedPost->featured_image }}"
                                    alt="{{ $relatedPost->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    loading="lazy"
                                >
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                <span>{{ $relatedPost->published_at->format('d.m.Y') }}</span>
                                @if($relatedPost->reading_time)
                                    <span>•</span>
                                    <span>{{ $relatedPost->reading_time }} Min.</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition">
                                {{ $relatedPost->title }}
                            </h3>

                            <p class="text-gray-600 mb-4 line-clamp-2">
                                {{ $relatedPost->excerpt }}
                            </p>

                            <a
                                href="{{ route('blog.show', $relatedPost->slug) }}"
                                class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold transition"
                            >
                                Weiterlesen
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    {{-- CTA Section --}}
    <div class="mt-16 bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-xl p-8 md:p-12 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">Bereit für Deine Transformation?</h2>
        <p class="text-xl mb-8 text-green-50">
            Lass uns gemeinsam Deinen Weg zu mehr Achtsamkeit und innerer Ruhe gestalten.
        </p>
        <a
            href="/kontakt.html"
            class="inline-block bg-white text-green-700 px-8 py-4 rounded-full font-bold text-lg hover:bg-green-50 transition shadow-lg"
        >
            Kostenloses Erstgespräch vereinbaren
        </a>
    </div>
</div>
