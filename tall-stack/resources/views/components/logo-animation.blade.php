{{-- Logo Animation - Metallic Swoosh + Music Notes --}}
<div
    class="mff-logo-wrapper flex justify-center items-center w-full relative z-[1]"
    x-data="{
        isAnimated: false,
        musicNotes: [],
        noteId: 0,
        notes: ['♪', '♫', '♬', '♩'],
        init() {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                            this.isAnimated = false;
                            // Force reflow
                            this.\$el.offsetWidth;
                            this.isAnimated = true;
                        } else if (!entry.isIntersecting) {
                            this.isAnimated = false;
                        }
                    });
                },
                { threshold: [0, 0.5], rootMargin: '0px' }
            );
            observer.observe(this.\$el);
        },
        handleMouseEnter() {
            this.isAnimated = false;
            this.\$el.offsetWidth;
            this.isAnimated = true;
            this.createMusicNotes();
        },
        createMusicNotes() {
            const numberOfNotes = 6;
            const newNotes = [];

            for (let i = 0; i < numberOfNotes; i++) {
                const randomNote = this.notes[Math.floor(Math.random() * this.notes.length)];
                const randomX = (Math.random() - 0.5) * 200;

                newNotes.push({
                    id: this.noteId++,
                    note: randomNote,
                    x: randomX
                });
            }

            newNotes.forEach((note, index) => {
                setTimeout(() => {
                    this.musicNotes.push(note);

                    setTimeout(() => {
                        this.musicNotes = this.musicNotes.filter(n => n.id !== note.id);
                    }, 2000);
                }, index * 150);
            });
        }
    }"
    @mouseenter="handleMouseEnter()"
>
    <div class="mff-logo-elegant relative inline-flex flex-col items-center cursor-default overflow-visible">
        <div
            class="mff-logo-elegant-text text-[42px] font-semibold relative inline-block leading-none"
            :class="{ 'animate-metallic-swoosh': isAnimated }"
            style="font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                   background: linear-gradient(90deg, #000 0%, #000 45%, #B0D4C5 49%, #C8E6DC 50%, #B0D4C5 51%, #000 55%, #000 100%);
                   background-size: 300% 100%;
                   background-position: 0% 0;
                   -webkit-background-clip: text;
                   background-clip: text;
                   -webkit-text-fill-color: transparent;
                   transition: none"
            :style="isAnimated ? 'transition: background-position 1.9s ease-out; background-position: -200% 0' : 'background-position: 0% 0'"
        >
            musikfürfirmen.de
        </div>

        <div
            class="mff-tagline-elegant text-lg font-light text-[#666] tracking-[0.5px] relative inline-block whitespace-nowrap leading-[1.4] cursor-default"
            :class="{ 'animate-tagline-appear': isAnimated, 'mff-tagline-hidden': !isAnimated }"
            style="font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
        >
            Dein Partner für Firmenevents
            <span
                class="absolute bottom-[-4px] left-0 w-full h-[2px] bg-[#C8E6DC]"
                :class="{ 'animate-underline-appear': isAnimated }"
                :style="!isAnimated ? 'opacity: 0' : ''"
            ></span>
        </div>

        {{-- Music Notes --}}
        <template x-for="note in musicNotes" :key="note.id">
            <div
                class="mff-music-note absolute text-xl text-[#B0D4C5] pointer-events-none z-10 animate-note-float"
                :style="`left: calc(50% + ${note.x}px); top: 50%`"
                x-text="note.note"
            ></div>
        </template>
    </div>
</div>

<style>
    @keyframes metallic-swoosh {
        0% { background-position: 0% 0; }
        100% { background-position: -200% 0; }
    }

    .animate-metallic-swoosh {
        animation: metallic-swoosh 1.9s ease-out forwards;
    }

    .mff-tagline-hidden {
        opacity: 0;
        transform: translateY(-10px);
    }

    @keyframes tagline-appear {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-tagline-appear {
        opacity: 0;
        transform: translateY(-10px);
        animation: tagline-appear 0.8s ease 1.0s forwards;
    }

    @keyframes underline-appear {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .animate-underline-appear {
        animation: underline-appear 1.4s cubic-bezier(0.4, 0, 0.2, 1) 1.2s forwards;
    }

    @keyframes note-float {
        0% {
            opacity: 1;
            transform: translateY(0) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: translateY(-80px) rotate(20deg);
        }
    }

    .animate-note-float {
        animation: note-float 2s ease-out forwards;
    }

    @media (max-width: 768px) {
        .mff-logo-elegant-text {
            font-size: 32px !important;
        }
        .mff-tagline-elegant {
            font-size: 15px !important;
        }
        .mff-music-note {
            font-size: 16px !important;
        }
    }

    @media (max-width: 480px) {
        .mff-logo-elegant-text {
            font-size: 24px !important;
        }
        .mff-tagline-elegant {
            font-size: 13px !important;
        }
        .mff-music-note {
            font-size: 14px !important;
        }
    }
</style>
