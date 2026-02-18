<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interior Touch | Advanced Project Orchestration Engine</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/theme/black.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --brand: #4F70FA;
            --emerald: #10B981;
            --rose: #F43F5E;
            --dark: #0F172A;
            --amber: #F59E0B;
            --violet: #8B5CF6;
        }

        .reveal {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at center, #1E293B 0%, #0F172A 100%);
        }

        /* Premium Animations */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes pulse-soft {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 112, 250, 0.4);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(79, 112, 250, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(79, 112, 250, 0);
            }
        }

        @keyframes slide-up {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .reveal .slides section {
            padding: 15px;
        }

        /* 2D Background Ornaments */
        .bg-ornaments {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .ornament {
            position: absolute;
            background: linear-gradient(135deg, rgba(79, 112, 250, 0.1), rgba(167, 139, 250, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            filter: blur(40px);
            animation: float-slow infinite ease-in-out;
        }

        .ornament-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
            animation-duration: 20s;
        }

        .ornament-2 {
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
            animation-duration: 25s;
            animation-delay: -5s;
        }

        .ornament-3 {
            width: 200px;
            height: 200px;
            top: 40%;
            left: 10%;
            animation-duration: 18s;
            animation-delay: -2s;
            border-radius: 30%;
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, 50px) rotate(10deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(-5deg);
            }
        }

        /* Mouse Follower Glow */
        #mouse-glow {
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(79, 112, 250, 0.15) 0%, rgba(79, 112, 250, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 100;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse-soft 3s infinite;
        }

        .animate-up {
            animation: slide-up 0.8s ease-out forwards;
        }

        .reveal .slides section {
            padding: 15px;
        }

        .reveal h1,
        .reveal h2,
        .reveal h3 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            color: #fff;
        }

        .reveal h1 {
            font-size: 2.5em !important;
        }

        .reveal h2 {
            font-size: 1.6em !important;
            margin-bottom: 25px !important;
        }

        .reveal h3 {
            font-size: 1.1em !important;
        }

        .reveal .slides section {
            padding: 15px;
        }

        .highlight {
            color: var(--brand);
        }

        .success {
            color: var(--emerald);
        }

        .warning {
            color: var(--amber);
        }

        .danger {
            color: var(--rose);
        }

        .gradient-text {
            background: linear-gradient(90deg, #4F70FA, #A78BFA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glassmorphism 2.0 */
        .card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 24px;
            margin: 12px 0;
            text-align: left;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-top: 30px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .card-accent {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 4px;
        }

        .card-accent span {
            width: 4px;
            height: 4px;
            background: var(--brand);
            border-radius: 50%;
            opacity: 0.5;
        }

        .icon-2d {
            width: 40px;
            height: 40px;
            stroke: var(--brand);
            stroke-width: 1.5;
            fill: none;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 25px;
            text-align: left;
        }

        .avatar-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), #A78BFA);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            font-weight: 800;
            color: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .tag {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(79, 112, 250, 0.1);
            border: 1px solid var(--brand);
            border-radius: 50px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .bullet-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .bullet-list li {
            font-size: 0.55em;
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
            color: #CBD5E1;
        }

        .bullet-list li::before {
            content: "→";
            position: absolute;
            left: 0;
            color: var(--brand);
            font-weight: 800;
        }

        .icon-box {
            font-size: 1.3em;
            margin-bottom: 8px;
        }

        .step-pill {
            background: var(--brand);
            color: white;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.35em;
            font-weight: 800;
            display: inline-block;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div id="mouse-glow"></div>
    <div class="bg-ornaments">
        <div class="ornament ornament-1"></div>
        <div class="ornament ornament-2"></div>
        <div class="ornament ornament-3"></div>
    </div>
    <div class="reveal">
        <div class="slides">

            @if($slides->count() > 0)
            @foreach($slides as $slide)
            <section data-background-color="{{ $slide->bg_color ?: '#0F172A' }}">
                @if($slide->layout_type == 'center')
                <div class="center">
                    @if($slide->subtitle)<p class="tag" style="color:var(--brand)">{{ $slide->subtitle }}</p>@endif
                    <h1>{!! $slide->title !!}</h1>
                    {!! $slide->content !!}
                </div>
                @elseif($slide->layout_type == 'profile')
                <div class="profile-container">
                    <div class="avatar-circle">IS</div>
                    <div>
                        <h2 style="font-size: 0.8em !important; margin-bottom: 5px !important; color: var(--brand);">{{
                            $slide->subtitle }}</h2>
                        <h1 style="font-size: 1.6em !important; margin-bottom: 12px !important;">{!! $slide->title !!}
                        </h1>
                        {!! $slide->content !!}
                    </div>
                </div>
                @elseif($slide->layout_type == 'chart')
                <div class="chart-container" style="height: 450px; position: relative;">
                    <div style="margin-bottom: 20px;">
                        <h2 style="margin-bottom: 5px !important;">{!! $slide->title !!}</h2>
                        @if($slide->subtitle)<p style="font-size: 0.6em; color: #94A3B8;">{{ $slide->subtitle }}</p>
                        @endif
                    </div>
                    <canvas id="chart-{{ $slide->id }}"
                        data-chart-data="{{ json_encode($slide->chart_data) }}"></canvas>
                    <div style="margin-top: 20px; font-size: 0.5em; opacity: 0.6;">
                        {!! $slide->content !!}
                    </div>
                </div>
                @else
                <h2>{!! $slide->title !!}</h2>
                @if($slide->subtitle)<p style="font-size: 0.6em; color: #94A3B8; margin-bottom: 20px;">{{
                    $slide->subtitle }}</p>@endif
                {!! $slide->content !!}
                @endif
            </section>
            @endforeach
            @else
            {{-- Fallback: Hardcoded Slides if DB is empty --}}
            <section data-background-transition="zoom" class="center">
                <p class="tag" style="color:var(--brand)">Bespoke Enterprise Systems</p>
                <h1><span class="gradient-text">Interior Touch</span></h1>
                <h3>Advanced Project Orchestration</h3>
                <div style="margin-top: 30px;">
                    <p
                        style="font-size: 0.45em; font-weight: 800; opacity: 0.6; letter-spacing: 0.3em; text-transform: uppercase;">
                        Engineered By <span class="highlight">Krizia Technologies</span> @ 2026
                    </p>
                </div>
            </section>

            <section>
                <div class="profile-container">
                    <div class="avatar-circle">IS</div>
                    <div>
                        <h2 style="font-size: 0.8em !important; margin-bottom: 5px !important; color: var(--brand);">
                            Architect & SME</h2>
                        <h1 style="font-size: 1.6em !important; margin-bottom: 12px !important;">Indresh Singh</h1>
                        <p style="font-size: 0.55em; color: #94A3B8; margin-bottom: 15px;">
                            Cloud Solution Architect | 7+ Years IT Experience<br>
                            Proven Expertise in 100+ Enterprise Transformations
                        </p>
                        <ul class="bullet-list">
                            <li>Design Lead for Interior Touch Core Execution Engine.</li>
                            <li>Architecture optimized for Data Consistency & Integrity.</li>
                            <li>Integrated Cloud Security & Rapid Recovery frameworks.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 style="color: var(--violet);">Journey: <span class="highlight">Lead ➔ Handover</span></h2>
                <div class="card" style="border-top: 4px solid var(--violet);">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div class="fragment" style="display: flex; align-items: center;">
                            <span class="step-pill">01</span>
                            <p style="font-size: 0.5em; color: #fff;"><strong>Sales Victory:</strong> Lead converted
                                from Pipeline ➔ Won status.</p>
                        </div>
                        <div class="fragment" style="display: flex; align-items: center;">
                            <span class="step-pill">02</span>
                            <p style="font-size: 0.5em; color: #fff;"><strong>Auto-Provisioning:</strong> System
                                initializes Client Dossier & Workspace.</p>
                        </div>
                        <div class="fragment" style="display: flex; align-items: center;">
                            <span class="step-pill">03</span>
                            <p style="font-size: 0.5em; color: #fff;"><strong>Resource Mapping:</strong> Site-admins &
                                materials are allocated instantly.</p>
                        </div>
                        <div class="fragment" style="display: flex; align-items: center;">
                            <span class="step-pill">04</span>
                            <p style="font-size: 0.5em; color: #fff;"><strong>Site Kick-off:</strong> Visual Timeline
                                activated for professional delivery.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="highlight">Get In Touch</h2>
                <div class="card" style="text-align: center; padding: 40px;">
                    <div style="margin-bottom: 25px;">
                        <p
                            style="font-size: 0.5em; color: #94A3B8; text-transform: uppercase; font-weight: 800; letter-spacing: 2px;">
                            Contact Us</p>
                        <h3 style="font-size: 1.4em !important; color: #fff; margin: 5px 0;">+91-8376097938</h3>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <p
                            style="font-size: 0.5em; color: #94A3B8; text-transform: uppercase; font-weight: 800; letter-spacing: 2px;">
                            Mail for Enquiries</p>
                        <h3 style="font-size: 1.1em !important; color: var(--brand); margin: 5px 0;">Contact@Krizia.in
                        </h3>
                    </div>
                    <div style="margin-top: 35px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 25px;">
                        <p style="font-size: 0.4em; color: #64748B; margin-bottom: 12px;">Visit our website for more
                            services</p>
                        <a href="https://krizia.in" target="_blank"
                            style="font-family: 'Outfit'; font-weight: 800; font-size: 1.4em; color: #fff; text-decoration: none; letter-spacing: 3px;">KRIZIA.IN</a>
                    </div>
                </div>
            </section>
            @endif

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.js"></script>
    <script>
        Reveal.initialize({
            width: 1100,
            height: 700,
            margin: 0.1,
            hash: true,
            center: true,
            controls: true,
            progress: true,
            mouseWheel: true,
            transition: 'convex',
            backgroundTransition: 'zoom',
            autoAnimate: true,
            autoAnimateEasing: 'ease-out',
            autoAnimateDuration: 0.8,
        });

        const charts = {};

        function initChart(slideElement) {
            const canvas = slideElement.querySelector('canvas');
            if (!canvas || charts[canvas.id]) return;

            const ctx = canvas.getContext('2d');
            const data = JSON.parse(canvas.dataset.chartData || '{}');

            if (data.type && data.data) {
                charts[canvas.id] = new Chart(ctx, {
                    type: data.type,
                    data: data.data,
                    options: data.options || {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: '#fff', font: { family: 'Outfit', size: 14 } } }
                        },
                                                  y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#94A3B8' } },
                            x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#94A3B8' } }
                        }
                    }
                } else if (data.labels && data.datasets) {
                // Fallback for simple format
                charts[canvas.id] = new Chart(ctx, {
                    type: data.type || 'bar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: '#fff', font: { family: 'Outfit', size: 14 } } }
                        }
                    }
                });
            }
        }

        Reveal.on('slidechanged', event => {
            initChart(event.currentSlide);
        });

        // Init initial slide if it's a chart
        initChart(Reveal.getCurrentSlide());

        // Mouse Follower Logic
        const glow = document.getElementById('mouse-glow');
        window.addEventListener('mousemove', (e) => {
            glow.style.opacity = '1';
            glow.style.left = e.clientX + 'px';
            glow.style.top = e.clientY + 'px';
        });

        window.addEventListener('mouseleave', () => {
            glow.style.opacity = '0';
        });
    </script>
</body>

</html>