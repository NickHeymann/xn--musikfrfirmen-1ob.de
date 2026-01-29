{{-- Process Steps - Animated Implementation --}}
<div
    class="service-animation-container flex flex-col items-center"
    style="font-family: 'Poppins', sans-serif"
    x-data="{
        visibleBlocks: [false, false, false],
        fillProgress: [0, 0, 0],
        lineProgress: [0, 0],
        ctaVisible: false,
        animationStarted: false,
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && !this.animationStarted) {
                        this.animationStarted = true;
                        this.startAnimation();
                    }
                });
            }, { threshold: 0.3 });

            observer.observe(this.\$el);
        },
        async startAnimation() {
            // Show first block
            this.visibleBlocks[0] = true;
            await new Promise(r => setTimeout(r, 300));
            await this.animateFill(0, 800);
            await this.animateLine(0, 200);

            // Show second block
            this.visibleBlocks[1] = true;
            await new Promise(r => setTimeout(r, 300));
            await this.animateFill(1, 800);
            await this.animateLine(1, 200);

            // Show third block
            this.visibleBlocks[2] = true;
            await new Promise(r => setTimeout(r, 300));
            await this.animateFill(2, 800);

            // Show CTA
            this.ctaVisible = true;
        },
        animateFill(blockIndex, duration) {
            return new Promise((resolve) => {
                const startTime = Date.now();

                const animate = () => {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Ease-in-out
                    const easeProgress = progress < 0.5
                        ? 2 * progress * progress
                        : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                    this.fillProgress[blockIndex] = easeProgress;

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        setTimeout(resolve, 400);
                    }
                };

                requestAnimationFrame(animate);
            });
        },
        animateLine(lineIndex, duration) {
            return new Promise((resolve) => {
                const startTime = Date.now();

                const animate = () => {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    this.lineProgress[lineIndex] = progress;

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        resolve();
                    }
                };

                requestAnimationFrame(animate);
            });
        },
        getConicGradient(index) {
            const progress = this.fillProgress[index];
            const degrees = progress * 360;
            return `conic-gradient(
                from 0deg at 50% 50%,
                #D4F4E8 0deg,
                #B2EAD8 ${degrees * 0.95}deg,
                #D4F4E8 ${degrees}deg,
                transparent ${degrees}deg,
                transparent 360deg
            )`;
        }
    }"
>
    <div class="service-blocks-wrapper flex flex-col md:flex-row items-center gap-0">
        {{-- Block 1: 60 Sekunden --}}
        <div class="flex flex-col md:flex-row items-center">
            <div
                class="service-block relative bg-white rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.06)] w-full md:w-[380px] min-h-[350px] max-h-[350px] overflow-hidden flex flex-col items-center justify-center p-10 md:p-[50px_40px] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)]"
                :class="{ 'opacity-100': visibleBlocks[0], 'opacity-0': !visibleBlocks[0] }"
                style="transition: opacity 1.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.3s ease, box-shadow 0.3s ease"
            >
                <div
                    class="absolute inset-0 rounded-xl"
                    :style="{
                        background: getConicGradient(0),
                        opacity: fillProgress[0] > 0 && fillProgress[0] < 1 ? 0.7 : 0,
                        transition: 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                    }"
                ></div>

                <div class="relative z-10 text-center w-full max-w-[300px]">
                    <h3 class="text-[2rem] font-semibold text-[#1a1a1a] mb-5 leading-[1.2] min-h-[40px] flex items-center justify-center">
                        60 Sekunden
                    </h3>
                    <p class="text-[#4a4a4a] font-light leading-[1.7]">
                        Schickt uns eure Anfrage innerhalb von
                        <span class="text-[#2DD4A8] font-semibold">60 Sekunden</span>
                         über unser Formular. Schnell, einfach und unkompliziert.
                    </p>
                </div>
            </div>

            <div class="service-connecting-line w-16 h-1 md:w-16 md:h-1 relative bg-[#e5e7eb] rounded-sm my-4 md:my-0 md:mx-0 rotate-90 md:rotate-0">
                <div
                    class="absolute top-0 left-0 h-full bg-[#2DD4A8] rounded-sm"
                    :style="`width: ${lineProgress[0] * 100}%; transition: width 0.05s linear`"
                ></div>
            </div>
        </div>

        {{-- Block 2: 24 Stunden --}}
        <div class="flex flex-col md:flex-row items-center">
            <div
                class="service-block relative bg-white rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.06)] w-full md:w-[380px] min-h-[350px] max-h-[350px] overflow-hidden flex flex-col items-center justify-center p-10 md:p-[50px_40px] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)]"
                :class="{ 'opacity-100': visibleBlocks[1], 'opacity-0': !visibleBlocks[1] }"
                style="transition: opacity 1.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.3s ease, box-shadow 0.3s ease"
            >
                <div
                    class="absolute inset-0 rounded-xl"
                    :style="{
                        background: getConicGradient(1),
                        opacity: fillProgress[1] > 0 && fillProgress[1] < 1 ? 0.7 : 0,
                        transition: 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                    }"
                ></div>

                <div class="relative z-10 text-center w-full max-w-[300px]">
                    <h3 class="text-[2rem] font-semibold text-[#1a1a1a] mb-5 leading-[1.2] min-h-[40px] flex items-center justify-center">
                        24 Stunden
                    </h3>
                    <p class="text-[#4a4a4a] font-light leading-[1.7]">
                        Erhaltet ein kostenloses Angebot innerhalb von
                        <span class="text-[#2DD4A8] font-semibold">24 Stunden</span>
                        . Durch das von euch ausgefüllte Formular liefern wir euch ein individuelles Angebot.
                    </p>
                </div>
            </div>

            <div class="service-connecting-line w-16 h-1 md:w-16 md:h-1 relative bg-[#e5e7eb] rounded-sm my-4 md:my-0 md:mx-0 rotate-90 md:rotate-0">
                <div
                    class="absolute top-0 left-0 h-full bg-[#2DD4A8] rounded-sm"
                    :style="`width: ${lineProgress[1] * 100}%; transition: width 0.05s linear`"
                ></div>
            </div>
        </div>

        {{-- Block 3: Rundum-Service --}}
        <div class="flex flex-col md:flex-row items-center">
            <div
                class="service-block relative bg-white rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.06)] w-full md:w-[380px] min-h-[350px] max-h-[350px] overflow-hidden flex flex-col items-center justify-center p-10 md:p-[50px_40px] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)]"
                :class="{ 'opacity-100': visibleBlocks[2], 'opacity-0': !visibleBlocks[2] }"
                style="transition: opacity 1.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.3s ease, box-shadow 0.3s ease"
            >
                <div
                    class="absolute inset-0 rounded-xl"
                    :style="{
                        background: getConicGradient(2),
                        opacity: fillProgress[2] > 0 && fillProgress[2] < 1 ? 0.7 : 0,
                        transition: 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                    }"
                ></div>

                <div class="relative z-10 text-center w-full max-w-[300px]">
                    <h3 class="text-[2rem] font-semibold text-[#1a1a1a] mb-5 leading-[1.2] min-h-[40px] flex items-center justify-center">
                        Rundum-Service
                    </h3>
                    <p class="text-[#4a4a4a] font-light leading-[1.7]">
                        Genießt
                        <span class="text-[#2DD4A8] font-semibold">professionelle Betreuung</span>
                         bis zum großen Tag! Wir sind 24/7 erreichbar. Über WhatsApp, Telefon oder E-Mail.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full flex justify-center mt-[60px]">
        <button
            onclick="Livewire.dispatch('openMFFCalculator')"
            class="service-cta-button py-[18px] px-12 bg-[#D4F4E8] text-[#292929] border-none rounded-[50px] text-lg font-medium cursor-pointer transition-all duration-300 hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
            :class="{ 'opacity-100': ctaVisible, 'opacity-0': !ctaVisible }"
            style="font-family: 'Poppins', sans-serif; transition: all 0.3s ease, opacity 1s ease-out"
        >
            Zum Angebot
        </button>
    </div>
</div>

<style>
    @media (max-width: 900px) {
        .service-connecting-line {
            width: 4px !important;
            height: 40px !important;
            transform: rotate(0deg) !important;
        }
    }
</style>
