{{-- Team Section - Production Accurate Implementation --}}
<section class="team-section">
    <div class="team-row">
        {{-- Bio Card Jonas - Links --}}
        <div class="bio-card">
            <span class="bio-tag">{{ strtoupper($teamMembers[0]['bioTitle']) }}</span>
            <p class="bio-text">{!! nl2br(e($teamMembers[0]['bioText'])) !!}</p>
            <a href="/ueber-uns" class="mehr-link">
                Mehr erfahren
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Person Jonas --}}
        <div class="person-card">
            <div class="container1">
                <div class="container-inner">
                    <div class="circle"></div>
                    <img
                        class="img {{ $teamMembers[0]['imageClass'] }}"
                        src="{{ $teamMembers[0]['image'] }}"
                        alt="{{ $teamMembers[0]['name'] }}"
                        loading="lazy">
                </div>
            </div>
            <div class="divider"></div>
            <div class="name">{{ $teamMembers[0]['name'] }}</div>
            <div class="title">
                {{ $teamMembers[0]['role'] }}<br>
                {{ $teamMembers[0]['roleSecondLine'] }}
            </div>
        </div>

        {{-- Person Nick --}}
        <div class="person-card">
            <div class="container1">
                <div class="container-inner">
                    <div class="circle"></div>
                    <img
                        class="img {{ $teamMembers[1]['imageClass'] }}"
                        src="{{ $teamMembers[1]['image'] }}"
                        alt="{{ $teamMembers[1]['name'] }}"
                        loading="lazy">
                </div>
            </div>
            <div class="divider"></div>
            <div class="name">{{ $teamMembers[1]['name'] }}</div>
            <div class="title">
                {{ $teamMembers[1]['role'] }}<br>
                {{ $teamMembers[1]['roleSecondLine'] }}
            </div>
        </div>

        {{-- Bio Card Nick - Rechts --}}
        <div class="bio-card">
            <span class="bio-tag">{{ strtoupper($teamMembers[1]['bioTitle']) }}</span>
            <p class="bio-text">{!! nl2br(e($teamMembers[1]['bioText'])) !!}</p>
            <a href="/ueber-uns" class="mehr-link">
                Mehr erfahren
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<style>
    .team-section {
        background: #fff;
        padding: 60px 20px;
        font-family: "Poppins", sans-serif;
    }

    .team-row {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
    }

    /* Person Card Styles */
    .person-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    .container1 {
        border-radius: 50%;
        height: 400px;
        -webkit-tap-highlight-color: transparent;
        transform: scale(0.55);
        transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1);
        width: 400px;
        margin-bottom: -39px;
        cursor: pointer;
    }

    .container1:hover {
        transform: scale(0.60);
    }

    .container-inner {
        clip-path: path(
            "M 390,400 C 390,504.9341 304.9341,590 200,590 95.065898,590 10,504.9341 10,400 V 10 H 200 390 Z"
        );
        position: relative;
        transform-origin: 50%;
        top: -200px;
    }

    .circle {
        background-color: #D4F4E8;
        border-radius: 50%;
        height: 380px;
        left: 10px;
        pointer-events: none;
        position: absolute;
        top: 210px;
        width: 380px;
    }

    .img {
        pointer-events: none;
        position: relative;
        transform: translateY(20px) scale(1.15);
        transform-origin: 50% bottom;
        transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .container1:hover .img {
        transform: translateY(0) scale(1.2);
    }

    .img1 {
        left: 22px;
        top: 220px;
        width: 340px;
    }

    .img2 {
        left: -6px;
        top: 205px;
        width: 444px;
    }

    .divider {
        background-color: #BAD6EB;
        height: 1px;
        width: 160px;
        margin-bottom: 16px;
    }

    .name {
        color: #404245;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 10px;
        text-align: center;
    }

    .title {
        color: #333;
        font-size: 14px;
        text-align: center;
        font-weight: 300;
        line-height: 1.5;
    }

    /* Bio Card */
    .bio-card {
        width: 280px;
        background: #f8f9fa;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
    }

    .bio-tag {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #2DD4A8;
        margin-bottom: 14px;
    }

    .bio-text {
        font-size: 14px;
        line-height: 1.7;
        color: #555;
        margin: 0 0 18px;
    }

    .mehr-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #2DD4A8;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .mehr-link svg {
        flex-shrink: 0;
        vertical-align: middle;
    }

    .mehr-link:hover {
        gap: 12px;
        color: #22a883;
    }

    /* Large Desktop (1600px+) */
    @media (min-width: 1600px) {
        .team-row {
            gap: 50px;
        }
        .bio-card {
            width: 320px;
            padding: 32px;
        }
        .container1 {
            transform: scale(0.60);
        }
        .container1:hover {
            transform: scale(0.65);
        }
        .name {
            font-size: 32px;
        }
    }

    /* MacBook 16" (1400-1599px) */
    @media (max-width: 1599px) and (min-width: 1400px) {
        .team-row {
            gap: 35px;
        }
        .bio-card {
            width: 260px;
            padding: 24px;
        }
        .container1 {
            transform: scale(0.52);
        }
        .container1:hover {
            transform: scale(0.57);
        }
    }

    /* MacBook 14" (1200-1399px) */
    @media (max-width: 1399px) and (min-width: 1200px) {
        .team-row {
            gap: 25px;
        }
        .bio-card {
            width: 220px;
            padding: 20px;
        }
        .bio-text {
            font-size: 13px;
        }
        .container1 {
            transform: scale(0.48);
        }
        .container1:hover {
            transform: scale(0.52);
        }
        .name {
            font-size: 24px;
        }
        .title {
            font-size: 12px;
        }
    }

    /* Small Laptop (1024-1199px) */
    @media (max-width: 1199px) and (min-width: 1024px) {
        .team-row {
            gap: 20px;
        }
        .bio-card {
            width: 200px;
            padding: 18px;
        }
        .bio-tag {
            font-size: 10px;
        }
        .bio-text {
            font-size: 12px;
            line-height: 1.6;
        }
        .mehr-link {
            font-size: 12px;
        }
        .container1 {
            transform: scale(0.42);
        }
        .container1:hover {
            transform: scale(0.46);
        }
        .name {
            font-size: 22px;
        }
        .title {
            font-size: 11px;
        }
    }

    /* iPad Landscape (900-1023px) */
    @media (max-width: 1023px) and (min-width: 900px) {
        .team-row {
            flex-wrap: wrap;
            gap: 30px;
            max-width: 800px;
        }
        .bio-card {
            width: calc(50% - 15px);
            order: 3;
        }
        .bio-card:first-child {
            order: 3;
        }
        .bio-card:last-child {
            order: 4;
        }
        .person-card:nth-child(2) {
            order: 1;
        }
        .person-card:nth-child(3) {
            order: 2;
        }
        .container1 {
            transform: scale(0.50);
        }
        .container1:hover {
            transform: scale(0.54);
        }
    }

    /* iPad Portrait (768-899px) */
    @media (max-width: 899px) and (min-width: 768px) {
        .team-row {
            flex-wrap: wrap;
            gap: 30px;
            max-width: 600px;
        }
        .bio-card {
            width: calc(50% - 15px);
            order: 3;
        }
        .bio-card:first-child {
            order: 3;
        }
        .bio-card:last-child {
            order: 4;
        }
        .person-card:nth-child(2) {
            order: 1;
        }
        .person-card:nth-child(3) {
            order: 2;
        }
        .container1 {
            transform: scale(0.45);
        }
        .container1:hover {
            transform: scale(0.48);
        }
        .name {
            font-size: 24px;
        }
    }

    /* Large Phone (480-767px) */
    @media (max-width: 767px) and (min-width: 480px) {
        .team-section {
            padding: 40px 20px;
        }
        .team-row {
            flex-direction: column;
            gap: 10px;
        }
        .bio-card {
            width: 100%;
            max-width: 360px;
            order: unset !important;
        }
        .person-card {
            order: unset !important;
        }
        .bio-card:first-child {
            order: 2 !important;
        }
        .person-card:nth-child(2) {
            order: 1 !important;
        }
        .person-card:nth-child(3) {
            order: 3 !important;
        }
        .bio-card:last-child {
            order: 4 !important;
        }
        .container1 {
            transform: scale(0.55);
        }
        .container1:hover {
            transform: scale(0.58);
        }
        .name {
            font-size: 28px;
        }
    }

    /* Small Phone (<480px) */
    @media (max-width: 479px) {
        .team-section {
            padding: 30px 16px;
            background: #f9f9f9;
        }
        .team-row {
            flex-direction: column;
            gap: 8px;
        }
        .bio-card {
            width: 100%;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
        }
        .bio-card:first-child {
            order: 2 !important;
        }
        .person-card:nth-child(2) {
            order: 1 !important;
        }
        .person-card:nth-child(3) {
            order: 3 !important;
        }
        .bio-card:last-child {
            order: 4 !important;
        }
        .container1 {
            transform: scale(0.50);
        }
        .container1:hover {
            transform: scale(0.52);
        }
        .name {
            font-size: 26px;
        }
        .title {
            font-size: 13px;
        }
        .bio-text {
            font-size: 13px;
        }
    }
</style