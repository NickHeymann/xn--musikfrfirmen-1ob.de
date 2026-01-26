<div class="blog-index-container">
    {{-- Header Section --}}
    <div class="blog-header">
        <h1>Blog</h1>
        <p class="blog-subtitle">Inspiration, Gedanken und praktische Tipps für mehr Klarheit im Leben</p>
    </div>

    {{-- Filters Section --}}
    <div class="blog-filters">
        <div class="search-box">
            <input 
                type="search" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Artikel durchsuchen..."
                class="search-input"
            >
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </div>

        <div class="category-filter">
            <label for="category">Kategorie:</label>
            <select wire:model.live="category" id="category" class="category-select">
                <option value="all">Alle Kategorien</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Blog Posts Grid --}}
    <div class="blog-grid">
        @forelse($posts as $post)
            <article class="blog-card">
                @if($post->featured_image)
                    <div class="blog-card-image">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" loading="lazy">
                    </div>
                @endif

                <div class="blog-card-content">
                    <div class="blog-card-header">
                        <h2>
                            <a href="/blog/{{ $post->slug }}" wire:navigate>
                                {{ $post->title }}
                            </a>
                        </h2>
                        <div class="blog-card-bookmark">
                            <livewire:bookmark-toggle :postId="$post->id" :key="'bookmark-' . $post->id" />
                        </div>
                    </div>

                    @if($post->category)
                        <span class="blog-card-category">{{ $post->category }}</span>
                    @endif

                    <p class="blog-card-excerpt">
                        {{ Str::limit($post->excerpt, 150) }}
                    </p>

                    <div class="blog-card-meta">
                        <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                            {{ $post->published_at->format('d.m.Y') }}
                        </time>
                        <span>·</span>
                        <span>{{ $post->reading_time }} Min. Lesezeit</span>
                    </div>

                    <a href="/blog/{{ $post->slug }}" wire:navigate class="blog-card-link">
                        Weiterlesen →
                    </a>
                </div>
            </article>
        @empty
            <div class="blog-empty">
                <p>Keine Artikel gefunden.</p>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn-reset">
                        Suche zurücksetzen
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="blog-pagination">
        {{ $posts->links() }}
    </div>
</div>
