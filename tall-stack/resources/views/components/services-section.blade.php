{{-- Services Section - How We Work --}}
<section class="services-section">
    <div class="container">
        <h2 class="section-title">Wie wir arbeiten</h2>

        <div class="service-blocks">
            @foreach($services as $service)
                <div class="service-block">
                    <h3 class="service-title">{{ $service->title }}</h3>
                    <p class="service-text">
                        {{ $service->text }}
                        <span class="service-highlight">{{ $service->highlight }}</span>
                        {{ $service->description }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .services-section {
        background: #fff;
        padding: 80px 20px;
        font-family: "Poppins", sans-serif;
    }

    .services-section .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 36px;
        font-weight: 600;
        color: #404245;
        text-align: center;
        margin-bottom: 60px;
    }

    .service-blocks {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .service-block {
        background: #f8f9fa;
        padding: 40px 32px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-block:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .service-title {
        font-size: 24px;
        font-weight: 600;
        color: #404245;
        margin-bottom: 20px;
    }

    .service-text {
        font-size: 16px;
        line-height: 1.7;
        color: #555;
    }

    .service-highlight {
        color: #2DD4A8;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .services-section {
            padding: 50px 20px;
        }

        .section-title {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .service-blocks {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .service-block {
            padding: 28px 24px;
        }

        .service-title {
            font-size: 20px;
        }

        .service-text {
            font-size: 14px;
        }
    }
</style>
