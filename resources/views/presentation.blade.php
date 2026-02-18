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

            <!-- Slide 1: Welcome -->
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

            <!-- Slide 2: Indresh Singh Profile -->
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

            <!-- Slide 3: Lead to Handover Journey -->
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
                <p style="font-size: 0.35em; color: #94A3B8; margin-top: 10px;">Seamless transition from sales handshake
                    to professional site execution.</p>
            </section>

            <!-- Slide 4: Visual Timeline -->
            <section>
                <h2>Visual <span class="highlight">Timeline</span> (Gantt)</h2>
                <div class="grid-2">
                    <div class="card fragment" style="margin: 0;">
                        <h3 style="font-size: 0.85em !important; color: var(--brand);">Multi-Project Mastery</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">View all active projects on a single synchronized
                            plane. Identify labor bottlenecks and resource overlaps before they occur.</p>
                    </div>
                    <div class="card fragment" style="margin: 0;">
                        <h3 style="font-size: 0.85em !important; color: var(--emerald);">Real-time Scaling</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">Switch perspectives from Monthly milestones to
                            Granular Daily tasks. High-performance drag-and-drop orchestration.</p>
                    </div>
                </div>
                <div class="card fragment" style="text-align: center; border: 1px dashed var(--rose);">
                    <p style="font-size: 0.45em;"><span class="danger font-black">ALERT:</span> Automatic red-flagging
                        of overdue dependencies across site reports.</p>
                </div>
            </section>

            <!-- Slide 5: Proper Financial Analysis -->
            <section>
                <h2>Deep <span class="success">Financial Analysis</span></h2>
                <div class="grid-2">
                    <div class="card fragment">
                        <h3 class="success" style="font-size: 0.8em !important;">P&L Tracking</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">Real-time Profit & Loss analysis per project. Track
                            material vs labor costs with unmatched precision.</p>
                    </div>
                    <div class="card fragment">
                        <h3 class="highlight" style="font-size: 0.8em !important;">Vendor Audit</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">Mode-of-payment verification (UPI/Chq/Cash) with
                            mandatory receipt-image archives.</p>
                    </div>
                </div>
                <div class="card fragment" style="border-left: 5px solid var(--emerald);">
                    <h3 style="font-size: 0.85em !important;">Liquidity Safety Lock</h3>
                    <p style="font-size: 0.45em; color: #fff;">Integrated "Finance Lock" mechanism prevents budget
                        tampering after professional approval.</p>
                </div>
            </section>

            <!-- Slide 6: Backup & Disaster Recovery -->
            <section>
                <h2 style="color: var(--amber);">Security: <span class="warning">Backup & Restore</span></h2>
                <div class="grid-2">
                    <div class="card fragment">
                        <div class="icon-box">☁️</div>
                        <h3>Cloud Backups</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">Daily automated snapshots of all site data secured
                            in encrypted off-site vaults.</p>
                    </div>
                    <div class="card fragment">
                        <div class="icon-box">🔄</div>
                        <h3>1-Click Recovery</h3>
                        <p style="font-size: 0.4em; color: #CBD5E1;">Instant system restoration in case of emergencies.
                            Zero data loss guarantee for critical assets.</p>
                    </div>
                </div>
                <div class="card fragment" style="background: rgba(245, 158, 11, 0.08);">
                    <p style="font-size: 0.45em; color: #fff; text-align: center;"><strong>Resilience:</strong>
                        Enterprise-grade CRC verification for every restore point.</p>
                </div>
            </section>

            <!-- Slide 7: Site Photo Journal -->
            <section>
                <h2>Field <span class="highlight">Photo Journal</span></h2>
                <p style="font-size: 0.55em; color: #94A3B8; margin-bottom: 15px;">Eliminating site disputes through
                    Mandatory Visual Tracking.</p>
                <div class="grid-2">
                    <div class="card" style="text-align: center; border-color: rgba(255,255,255,0.1);">
                        <p style="font-size: 0.4em; color: #64748B;">[MANDATORY]</p>
                        <h4 style="font-size: 0.7em; margin: 8px 0;">BEFORE PHOTO</h4>
                        <div
                            style="height: 80px; background: rgba(255,255,255,0.02); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.45em;">
                            Morning Status</div>
                    </div>
                    <div class="card" style="text-align: center; border-color: var(--emerald);">
                        <p style="font-size: 0.4em; color: var(--emerald);">[MANDATORY]</p>
                        <h4 style="font-size: 0.7em; margin: 8px 0;">AFTER PHOTO</h4>
                        <div
                            style="height: 80px; background: rgba(16, 185, 129, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.45em;">
                            Work Done (EOD)</div>
                    </div>
                </div>
                <p style="font-size: 0.45em; margin-top: 15px; color: #CBD5E1;">Reports are <strong>REJECTED</strong> if
                    photo metadata doesn't match geofence.</p>
            </section>

            <!-- Slide 8: Team & Inventory -->
            <section>
                <h2>System <span class="warning">Efficiency</span></h2>
                <div class="card fragment">
                    <h3 style="color: var(--amber); font-size: 0.85em !important;">Team Geofencing</h3>
                    <p style="font-size: 0.45em; color: #CBD5E1;">GPS-tagged attendance eliminates proxy logins and
                        calculates precise labor costs.</p>
                </div>
                <div class="card fragment">
                    <h3 style="color: var(--violet); font-size: 0.85em !important;">Smart Inventory</h3>
                    <p style="font-size: 0.45em; color: #CBD5E1;">Track material inward with visual proof and
                        auto-generate PDF Work Orders (WO).</p>
                </div>
            </section>

            <!-- Slide 9: Conclusion -->
            <section data-background-color="#0F172A">
                <h1 style="font-size: 2em !important;">FUTURE READY <span class="gradient-text">EXECUTION</span></h1>
                <div style="display: flex; justify-content: space-around; margin-top: 40px;">
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.1em !important; color: var(--emerald);">40%</h2>
                        <p style="font-size: 0.35em; font-weight: 800;">Optimization</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.1em !important; color: var(--brand);">100%</h2>
                        <p style="font-size: 0.35em; font-weight: 800;">Visibility</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.1em !important; color: var(--rose);">ZERO</h2>
                        <p style="font-size: 0.35em; font-weight: 800;">Disputes</p>
                    </div>
                </div>
                <div style="margin-top: 50px;">
                    <p style="font-size: 0.45em; opacity: 0.7;">Engineered by <span class="highlight font-black">Indresh
                            Singh</span></p>
                    <p style="font-size: 0.35em; letter-spacing: 0.4em; margin-top: 8px;">KRIZIA TECHNOLOGIES</p>
                </div>
            </section>

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
      Wheel: true,
            transition: 'slide',
            backgroundTransition: 'fade',
        });
    </script>
</body>

</html>