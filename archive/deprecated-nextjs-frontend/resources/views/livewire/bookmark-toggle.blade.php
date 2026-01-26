<button 
    wire:click="toggleBookmark" 
    class="bookmark-toggle {{ $isBookmarked ? 'bookmarked' : '' }}"
    aria-label="{{ $isBookmarked ? 'Aus Leseliste entfernen' : 'Zur Leseliste hinzufügen' }}"
    title="{{ $isBookmarked ? 'Aus Leseliste entfernen' : 'Zur Leseliste hinzufügen' }}"
>
    @if($isBookmarked)
        {{-- Filled bookmark icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
        </svg>
    @else
        {{-- Outline bookmark icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
        </svg>
    @endif
</button>
