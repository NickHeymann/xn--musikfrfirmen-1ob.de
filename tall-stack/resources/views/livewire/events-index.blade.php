<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">All Events</h1>

    {{-- Search and Filter --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                    type="text"
                    id="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search events by title, description, or location..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div>
                <label for="musicStyle" class="block text-sm font-medium text-gray-700 mb-2">Music Style</label>
                <select
                    id="musicStyle"
                    wire:model.live="musicStyle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="all">All Styles</option>
                    @foreach($musicStyles as $style)
                        <option value="{{ $style }}">{{ $style }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Events Grid --}}
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($events as $event)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold">{{ $event->title }}</h3>
                            @if($event->music_style)
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                    {{ $event->music_style }}
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-600 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $event->start_time->format('M d, Y - H:i') }}
                        </p>

                        <p class="text-gray-600 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $event->location }}
                        </p>

                        <p class="text-gray-700 mb-4">{{ Str::limit($event->description, 120) }}</p>

                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">
                                €{{ number_format($event->price_per_musician, 2) }} / musician
                            </span>
                            <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:underline font-semibold">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-12 text-center">
            <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-600 text-lg">No events found matching your criteria.</p>
            <button wire:click="$set('search', '')" wire:click="$set('musicStyle', 'all')" class="mt-4 text-blue-600 hover:underline">
                Clear filters
            </button>
        </div>
    @endif
</div>
