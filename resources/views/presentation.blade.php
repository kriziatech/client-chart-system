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

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin: 8px 0;
            text-align: left;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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
            marg         hash: true,
            center: true,
            controls: true,
            progress: true,
            mouseWheel: true,
            transition: 'slide',
            backgroundTransition: 'fade',
        });
    </script>
</body>

</html>