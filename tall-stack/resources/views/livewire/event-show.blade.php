<div class="container mx-auto px-4 py-8">
    {{-- Back Button --}}
    <a href="{{ route('events.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Events
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Event Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-lg p-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-4xl font-bold">{{ $event->title }}</h1>
                    @if($event->music_style)
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $event->music_style }}
                        </span>
                    @endif
                </div>

                <div class="space-y-3 mb-6 text-gray-600">
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <strong>Start:</strong>&nbsp;{{ $event->start_time->format('F d, Y - H:i') }}
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <strong>End:</strong>&nbsp;{{ $event->end_time->format('F d, Y - H:i') }}
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <strong>Location:</strong>&nbsp;{{ $event->location }}
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <strong>Capacity:</strong>&nbsp;{{ $event->capacity }} people
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        <strong>Musicians Needed:</strong>&nbsp;{{ $event->musicians_needed }}
                    </p>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-3">Description</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                </div>

                @if($event->requirements && count($event->requirements) > 0)
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold mb-3">Requirements</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach($event->requirements as $key => $value)
                                <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800 font-semibold text-lg">
                        Price: €{{ number_format($event->price_per_musician, 2) }} per musician
                    </p>
                </div>
            </div>
        </div>

        {{-- Booking Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-lg p-6 sticky top-4">
                <h2 class="text-2xl font-bold mb-4">Book This Event</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit="submitBooking">
                    <div class="space-y-4">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                            <input
                                type="text"
                                id="company_name"
                                wire:model="company_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-500 @enderror"
                            />
                            @error('company_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person *</label>
                            <input
                                type="text"
                                id="contact_person"
                                wire:model="contact_person"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_person') border-red-500 @enderror"
                            />
                            @error('contact_person')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            />
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model="phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                            />
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="num_musicians" class="block text-sm font-medium text-gray-700 mb-1">
                                Number of Musicians (max {{ $event->musicians_needed }}) *
                            </label>
                            <input
                                type="number"
                                id="num_musicians"
                                wire:model="num_musicians"
                                min="1"
                                max="{{ $event->musicians_needed }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('num_musicians') border-red-500 @enderror"
                            />
                            @error('num_musicians')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                            <textarea
                                id="special_requests"
                                wire:model="special_requests"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Estimated Total</p>
                            <p class="text-2xl font-bold text-blue-600">
                                €{{ number_format($event->price_per_musician * $num_musicians, 2) }}
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
                        >
                            Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
