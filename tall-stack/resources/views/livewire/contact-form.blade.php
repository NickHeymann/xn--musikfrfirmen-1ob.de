<div class="container mx-auto px-4 py-8"
    x-data="{
        saveToStorage() {
            const formData = {
                name: $wire.name,
                email: $wire.email,
                phone: $wire.phone,
                company: $wire.company,
                subject: $wire.subject,
                message: $wire.message,
            };
            localStorage.setItem('mff-contact-data', JSON.stringify(formData));
        },
        loadFromStorage() {
            const stored = localStorage.getItem('mff-contact-data');
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    if (data.name) $wire.set('name', data.name);
                    if (data.email) $wire.set('email', data.email);
                    if (data.phone) $wire.set('phone', data.phone);
                    if (data.company) $wire.set('company', data.company);
                    if (data.subject) $wire.set('subject', data.subject);
                    if (data.message) $wire.set('message', data.message);
                } catch (e) {
                    console.error('Failed to restore contact data:', e);
                }
            }
        },
        clearStorage() {
            localStorage.removeItem('mff-contact-data');
        }
    }"
    x-init="
        loadFromStorage();

        // Auto-save on input changes
        setInterval(() => saveToStorage(), 2000);
    "
    @clear-contact-storage.window="clearStorage()"
>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold mb-4">Contact Us</h1>
        <p class="text-gray-600 mb-8">Have a question or want to book our services? Send us a message and we'll respond within 24 hours.</p>

        <div class="bg-white shadow-lg rounded-lg p-8">
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="submit">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            />
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            />
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model="phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <input
                                type="text"
                                id="company"
                                wire:model="company"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="inquiry_type" class="block text-sm font-medium text-gray-700 mb-2">Inquiry Type *</label>
                        <select
                            id="inquiry_type"
                            wire:model="inquiry_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('inquiry_type') border-red-500 @enderror"
                        >
                            <option value="general">General Inquiry</option>
                            <option value="booking">Event Booking</option>
                            <option value="partnership">Partnership Opportunity</option>
                            <option value="other">Other</option>
                        </select>
                        @error('inquiry_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea
                            id="message"
                            wire:model="message"
                            rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                            placeholder="Tell us about your event or inquiry..."
                        ></textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
                    >
                        Send Message
                    </button>
                </div>
            </form>
        </div>

        {{-- Contact Information --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <svg class="mx-auto w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h3 class="font-semibold mb-1">Email</h3>
                <p class="text-gray-600">info@musikfuerfirmen.de</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6 text-center">
                <svg class="mx-auto w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <h3 class="font-semibold mb-1">Phone</h3>
                <p class="text-gray-600">+49 123 456 7890</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6 text-center">
                <svg class="mx-auto w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="font-semibold mb-1">Business Hours</h3>
                <p class="text-gray-600">Mon-Fri: 9AM-6PM</p>
            </div>
        </div>
    </div>
</div>
