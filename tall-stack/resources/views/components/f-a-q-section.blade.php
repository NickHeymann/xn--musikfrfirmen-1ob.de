{{-- FAQ Section - Häufig gestellte Fragen --}}
<section class="faq-section">
    <div class="container">
        <h2 class="section-title">Häufig gestellte Fragen</h2>

        <div class="faq-list">
            @foreach($faqs as $faq)
                <div class="faq-item" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="faq-question"
                        :aria-expanded="open"
                    >
                        <span class="question-text">{{ $faq->question }}</span>
                        <span class="faq-icon" :class="{ 'rotated': open }">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </span>
                    </button>

                    <div
                        x-show="open"
                        x-collapse
                        class="faq-answer"
                    >
                        <div class="answer-content">
                            {!! nl2br(e($faq->answer)) !!}

                            @if($faq->has_link)
                                <a href="#kontakt" class="faq-cta">
                                    Jetzt Anfrage stellen
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/>
                                        <path d="M12 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .faq-section {
        background: #f9f9f9;
        padding: 80px 20px;
        font-family: "Poppins", sans-serif;
    }

    .faq-section .container {
        max-width: 900px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 36px;
        font-weight: 600;
        color: #404245;
        text-align: center;
        margin-bottom: 60px;
    }

    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .faq-item {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.2s ease;
    }

    .faq-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .faq-question {
        width: 100%;
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        transition: background-color 0.2s ease;
    }

    .faq-question:hover {
        background-color: #f8f9fa;
    }

    .question-text {
        font-size: 18px;
        font-weight: 600;
        color: #404245;
        line-height: 1.4;
    }

    .faq-icon {
        flex-shrink: 0;
        color: #C8E6DC;
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .faq-icon.rotated {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 28px 24px;
    }

    .answer-content {
        font-size: 16px;
        line-height: 1.7;
        color: #555;
    }

    .faq-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding: 12px 24px;
        background: #C8E6DC;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .faq-cta:hover {
        background: #22a883;
        gap: 12px;
    }

    .faq-cta svg {
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .faq-section {
            padding: 50px 20px;
        }

        .section-title {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .faq-question {
            padding: 20px;
        }

        .question-text {
            font-size: 16px;
        }

        .faq-answer {
            padding: 0 20px 20px;
        }

        .answer-content {
            font-size: 14px;
        }

        .faq-cta {
            font-size: 13px;
            padding: 10px 20px;
        }
    }
</style>
