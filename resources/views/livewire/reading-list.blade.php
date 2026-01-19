<div>
    {{-- Floating Action Button (FAB) --}}
    <button 
        wire:click="toggleModal" 
        class="reading-list-fab"
        aria-label="Leseliste anzeigen"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
        </svg>
        @if($count > 0)
            <span class="reading-list-badge">{{ $count }}</span>
        @endif
    </button>

    {{-- Modal --}}
    @if($showModal)
        <div class="reading-list-modal-overlay" wire:click="toggleModal">
            <div class="reading-list-modal" wire:click.stop>
                <div class="reading-list-header">
                    <h2>Meine Leseliste</h2>
                    <button wire:click="toggleModal" class="reading-list-close" aria-label="Schließen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="reading-list-content">
                    @if($items->isEmpty())
                        <div class="reading-list-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <p>Deine Leseliste ist noch leer.</p>
                            <p class="text-muted">Markiere Artikel mit dem Lesezeichen-Symbol, um sie hier zu speichern.</p>
                        </div>
                    @else
                        <ul class="reading-list-items">
                            @foreach($items as $item)
                                <li class="reading-list-item">
                                    <div class="reading-list-item-content">
                                        <h3>
                                            <a href="/blog/{{ $item->blogPost->slug }}" wire:navigate>
                                                {{ $item->blogPost->title }}
                                            </a>
                                        </h3>
                                        @if($item->blogPost->excerpt)
                                            <p class="reading-list-excerpt">
                                                {{ Str::limit($item->blogPost->excerpt, 120) }}
                                            </p>
                                        @endif
                                        <p class="reading-list-meta">
                                            Hinzugefügt: {{ $item->added_at->format('d.m.Y') }}
                                        </p>
                                    </div>
                                    <button 
                                        wire:click="removeItem({{ $item->id }})" 
                                        class="reading-list-remove"
                                        aria-label="Entfernen"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
