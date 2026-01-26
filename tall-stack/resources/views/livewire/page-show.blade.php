{{-- Dynamic Page Display --}}
<div class="w-full">
    {{-- Header Navigation --}}
    <x-header />

    {{-- Page Content --}}
    <section class="py-24 px-6 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="mb-8">
                <a href="{{ route('home') }}" class="text-[#0D7A5F] hover:underline text-sm">
                    ← Zurück zur Startseite
                </a>
            </nav>

            <h1 class="text-4xl md:text-5xl font-semibold text-[#1a1a1a] mb-12" style="font-family: 'Poppins', sans-serif">
                {{ $page->title }}
            </h1>

            <div class="prose prose-lg max-w-none text-[#444]" style="font-family: 'Poppins', sans-serif">
                @php
                    $content = $page->content;
                @endphp

                @if(is_array($content) && isset($content['blocks']))
                    {{-- Visual editor block format --}}
                    @foreach($content['blocks'] as $block)
                        @if(isset($block['props']['content']))
                            {!! $block['props']['content'] !!}
                        @endif
                    @endforeach
                @elseif(is_array($content))
                    {{-- Simple array format --}}
                    @foreach($content as $item)
                        @if(is_string($item))
                            {!! $item !!}
                        @elseif(isset($item['content']))
                            {!! $item['content'] !!}
                        @endif
                    @endforeach
                @elseif(is_string($content))
                    {{-- Plain HTML string --}}
                    {!! $content !!}
                @else
                    <p class="text-gray-500">Inhalt wird geladen...</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-footer />
</div>
