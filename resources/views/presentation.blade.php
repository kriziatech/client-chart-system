<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interior Touch | Enterprise Project Management System</title>

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
            font-size: 2.8em !important;
        }

        .reveal h2 {
            font-size: 1.8em !important;
            margin-bottom: 30px !important;
        }

        .reveal h3 {
            font-size: 1.2em !important;
        }

        .reveal .slides section {
            padding: 20px;
        }

        .highlight {
            color: var(--brand);
        }

        .success {
            color: var(--emerald);
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
            border-radius: 20px;
            padding: 25px;
            margin: 10px 0;
            text-align: left;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 30px;
            text-align: left;
        }

        .avatar-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), #A78BFA);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            font-weight: 800;
            color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.1);
        }

        .tag {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(79, 112, 250, 0.1);
            border: 1px solid var(--brand);
            border-radius: 50px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .bullet-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .bullet-list li {
            font-size: 0.6em;
            margin-bottom: 12px;
            padding-left: 25px;
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
            font-size: 1.5em;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="reveal">
        <div class="slides">

            <!-- Slide 1: Welcome -->
            <section data-background-transition="zoom" class="center">
                <p class="tag" style="color:var(--brand)">Bespoke Enterprise Solution</p>
                <h1><span class="gradient-text">Interior Touch</span></h1>
                <h3>Project Management Redefined</h3>
                <div style="margin-top: 40px;">
                    <p
                        style="font-size: 0.5em; font-weight: 800; opacity: 0.6; letter-spacing: 0.3em; text-transform: uppercase;">
                        Engineered By <span class="highlight">Krizia Technologies</span> @ 2026
                    </p>
                </div>
            </section>

            <!-- Slide 2: Indresh Singh Profile -->
            <section>
                <div class="profile-container">
                    <div class="avatar-circle">IS</div>
                    <div>
                        <h2 style="font-size: 0.9em !important; margin-bottom: 5px !important; color: var(--brand);">
                            Architect & SME</h2>
                        <h1 style="font-size: 1.8em !important; margin-bottom: 15px !important;">Indresh Singh</h1>
                        <p style="font-size: 0.6em; color: #94A3B8; margin-bottom: 20px;">
                            Cloud Solution Architect | 7+ Years Experience<br>
                            Expertise in 100+ High-Scale IT Transformations
                        </p>
                        <ul class="bullet-list">
                            <li>Design Lead for the Interior Touch Core Engine.</li>
                            <li>Specialist in Workflow Automation & Digital Logistics.</li>
                            <li>Expert in building high-availability, secure architectures.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Slide 3: The Dashboard -->
            <section>
                <h2>01. COMMAND <span class="highlight">DASHBOARD</span></h2>
                <div class="grid-2">
                    <div class="card">
                        <div class="icon-box">📊</div>
                        <h3>Real-time Stats</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Instant visibility into Revenue, Expenses, Work
                            Execution, and Professional Profits.</p>
                    </div>
                    <div class="card">
                        <div class="icon-box">⚡</div>
                        <h3>Smart Alerts</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Automatic notifications for pending vendor
                            payments, overdue tasks, and milestone breaches.</p>
                    </div>
                </div>
            </section>

            <!-- Slide 4: Visual Timeline & Gantt -->
            <section>
                <h2>02. PROJECT <span class="highlight">TIMELINE</span></h2>
                <div class="card" style="border-left: 5px solid var(--brand); padding-left: 40px;">
                    <h3 class="highlight">Interactive Gantt Orchestration</h3>
                    <ul class="bullet-list" style="margin-top: 20px;">
                        <li><strong>Multi-Project View:</strong> Track all sites on a single visual plane.</li>
                        <li><strong>Dynamic Scaling:</strong> Toggle between Day, Week, and Month views.</li>
                        <li><strong>Deadlines:</strong> Visual indicators for project health and overdue tasks.</li>
                    </ul>
                </div>
                <div style="font-size: 0.4em; color: #64748B; margin-top: 20px;">Built on Frappe-Gantt Engine for
                    high-performance scheduling.</div>
            </section>

            <!-- Slide 5: Field Photo Journal -->
            <section>
                <h2>03. PHOTO <span class="highlight">JOURNAL</span></h2>
                <p style="font-size: 0.6em; color: #94A3B8; margin-bottom: 20px;">Eliminating site disputes through
                    Mandatory Visual Tracking.</p>
                <div class="grid-2">
                    <div class="card" style="text-align: center; border-color: rgba(255,255,255,0.1);">
                        <p style="font-size: 0.4em; color: #64748B;">[MANDATORY]</p>
                        <h4 style="font-size: 0.8em; margin: 10px 0;">BEFORE PHOTO</h4>
                        <div
                            style="height: 100px; background: rgba(255,255,255,0.02); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.5em;">
                            Captured at Sunrise</div>
                    </div>
                    <div class="card" style="text-align: center; border-color: var(--emerald);">
                        <p style="font-size: 0.4em; color: var(--emerald);">[MANDATORY]</p>
                        <h4 style="font-size: 0.8em; margin: 10px 0;">AFTER PHOTO</h4>
                        <div
                            style="height: 100px; background: rgba(16, 185, 129, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.5em;">
                            Captured at EOD</div>
                    </div>
                </div>
                <p style="font-size: 0.5em; margin-top: 20px; color: #CBD5E1;">Reports cannot be submitted without site
                    photo verification.</p>
            </section>

            <!-- Slide 6: Financial Integrity -->
            <section>
                <h2>04. FINANCIAL <span class="highlight">INTEGRITY</span></h2>
                <div class="grid-2">
                    <div class="card">
                        <h3 class="success">Vendor Vault</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Store Quotation Images, Txn IDs, and Mode of
                            Payment (Cash/Online/Cheque) per transaction.</p>
                    </div>
                    <div class="card">
                        <h3 class="highlight">Profit Locking</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Admins can LOCK financials to prevent leakage and
                            unauthorized modifications after budget approval.</p>
                    </div>
                </div>
            </section>

            <!-- Slide 7: HRMS & Geo-Attendance -->
            <section>
                <h2>05. TEAM <span class="highlight">GEOFENCING</span></h2>
                <div class="card" style="background: rgba(245, 158, 11, 0.05); border-color: var(--amber);">
                    <h3 style="color: var(--amber);">GPS-Tagged Attendance</h3>
                    <ul class="bullet-list" style="margin-top: 20px;">
                        <li><strong>Geo-Verification:</strong> Staff must be at the physical site location to Check-In.
                        </li>
                        <li><strong>Active Shift Tracking:</strong> Real-time duration monitor for every employee.</li>
                        <li><strong>Site-Specific Costs:</strong> Labor costs are automatically attributed to the
                            correct project.</li>
                    </ul>
                </div>
            </section>

            <!-- Slide 8: Inventory & procurement -->
            <section>
                <h2>06. SMART <span class="highlight">INVENTORY</span></h2>
                <div class="grid-2">
                    <div class="card">
                        <div class="icon-box">📦</div>
                        <h3>Material Inward</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Track materials arriving at site with photo proof
                            and quantity validation.</p>
                    </div>
                    <div class="card">
                        <div class="icon-box">📋</div>
                        <h3>Work Orders (WO)</h3>
                        <p style="font-size: 0.5em; color: #94A3B8;">Professional PDF Work Orders generated instantly to
                            standardize vendor contracts.</p>
                    </div>
                </div>
            </section>

            <!-- Slide 9: Communication & Security -->
            <section>
                <h2>07. SYSTEM <span class="highlight">FORTRESS</span></h2>
                <div class="grid-2">
                    <div class="card">
                        <h3 style="font-size: 0.9em !important;">Team Collaboration</h3>
                        <p style="font-size: 0.45em; color: #94A3B8;">Real-time Secure Chat, Reactions, and Project
                            Portfolio Gallery for visual assets.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 0.9em !important;">Data Integrity</h3>
                        <p style="font-size: 0.45em; color: #94A3B8;">Detailed Audit Logs for every action + Automated
                            System Backups for disaster recovery.</p>
                    </div>
                </div>
            </section>

            <!-- Slide 10: Conclusion -->
            <section data-background-color="#0F172A">
                <h1 style="font-size: 2.2em !important;">ELEVATING <span class="gradient-text">EXECUTION</span></h1>
                <div style="display: flex; justify-content: space-around; margin-top: 50px;">
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.2em !important; color: var(--emerald);">40%</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">Time Optimization</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.2em !important; color: var(--brand);">100%</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">Financial Visibility</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.2em !important; color: var(--rose);">ZERO</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">Site Disputes</p>
                    </div>
                </div>
                <div style="margin-top: 60px;">
                    <p style="font-size: 0.5em; opacity: 0.7;">Presented By <span class="highlight font-black">Indresh
                            Singh</span></p>
                    <p style="font-size: 0.4em; letter-spacing: 0.4em; margin-top: 10px;">KRIZIA TECHNOLOGIES</p>
                </div>
            </section>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.js"></script>
    <sc