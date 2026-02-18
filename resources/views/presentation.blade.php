<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interior Touch | Project Management System Presentation</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/theme/black.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --brand: #4F70FA;
            --emerald: #10B981;
            --rose: #F43F5E;
            --dark: #0F172A;
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

        .reveal .slides section {
            padding: 40px;
        }

        .highlight {
            color: var(--brand);
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
            border-radius: 30px;
            padding: 30px;
            margin: 20px 0;
            text-align: left;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--brand);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(79, 112, 250, 0.3);
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 40px;
            text-align: left;
        }

        .avatar-circle {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), #A78BFA);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            font-weight: 800;
            color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 5px solid rgba(255, 255, 255, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-cols: 2;
            gap: 20px;
        }

        .tag {
            display: inline-block;
            padding: 5px 15px;
            background: rgba(79, 112, 250, 0.1);
            border: 1px solid var(--brand);
            border-radius: 50px;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 800;
            margin-right: 10px;
        }

        .reveal .controls,
        .reveal .progress {
            color: var(--brand);
        }
    </style>
</head>

<body>
    <div class="reveal">
        <div class="slides">

            <!-- Slide 1: Welcome -->
            <section data-background-transition="zoom">
                <p class="tag" style="color:var(--brand)">Proprietary Solution</p>
                <h1 style="font-size: 3.5em;"><span class="gradient-text">Interior Touch</span></h1>
                <h3>Next-Gen Project Management System</h3>
                <div style="margin-top: 50px; opacity: 0.8;">
                    <p style="font-size: 0.8em; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;">
                        Developed Exclusively By <span class="highlight">Krizia Technologies</span> @ 2026
                    </p>
                </div>
            </section>

            <!-- Slide 2: The Architect -->
            <section>
                <div class="profile-container">
                    <div class="avatar-circle">IS</div>
                    <div>
                        <h2 style="font-size: 1.2em; margin-bottom: 10px;">The Mastermind</h2>
                        <h1 style="font-size: 2em; margin-bottom: 20px;">Indresh <span class="highlight">Singh</span>
                        </h1>
                        <p style="font-size: 0.7em; margin-bottom: 30px;">
                            Cloud Solution Architect & SME<br>
                            <span style="color: #94A3B8;">7+ Years Experience | 100+ Enterprise Projects</span>
                        </p>
                        <ul style="font-size: 0.6em; line-height: 1.8; color: #CBD5E1;">
                            <li>Architecting high-scale digital transformations</li>
                            <li>Specialized in Operation Automation & Fintech Logistics</li>
                            <li>Designed this bespoke engine for Interior Excellence</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Slide 3: The Problem -->
            <section data-auto-animate>
                <h2 class="fragment">The Industry Blindspots</h2>
                <div class="fragment card" style="border-left: 5px solid var(--rose);">
                    <h3 style="font-size: 1.2em; color: var(--rose);">Budget Leaks</h3>
                    <p style="font-size: 0.8em;">Manual ledger entries leading to unrecognized overheads and vendor
                        disputes.</p>
                </div>
                <div class="fragment card" style="border-left: 5px solid var(--rose);">
                    <h3 style="font-size: 1.2em; color: var(--rose);">Timeline Delays</h3>
                    <p style="font-size: 0.8em;">Disconnected labor schedules causing resource idle-time and missed
                        delivery dates.</p>
                </div>
                <div class="fragment card" style="border-left: 5px solid var(--rose);">
                    <h3 style="font-size: 1.2em; color: var(--rose);">Accountability Void</h3>
                    <p style="font-size: 0.8em;">Zero visual tracking of site progress from remote locations.</p>
                </div>
            </section>

            <!-- Slide 4: The Solution -->
            <section data-background-color="#0F172A">
                <h2>The Interior Touch <span class="highlight">Engine</span></h2>
                <p style="font-size: 0.7em; color: #94A3B8;">A unified ecosystem for end-to-end site orchestration.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 40px;">
                    <div class="card" style="margin: 0;">
                        <span class="highlight font-black">01.</span>
                        <p style="font-weight: 800; font-size: 0.8em;">FINANCIAL CONTROL Room</p>
                    </div>
                    <div class="card" style="margin: 0;">
                        <span class="highlight font-black">02.</span>
                        <p style="font-weight: 800; font-size: 0.8em;">VISUAL TIMELINE (GANTT)</p>
                    </div>
                    <div class="card" style="margin: 0;">
                        <span class="highlight font-black">03.</span>
                        <p style="font-weight: 800; font-size: 0.8em;">FIELD PHOTO JOURNAL</p>
                    </div>
                    <div class="card" style="margin: 0;">
                        <span class="highlight font-black">04.</span>
                        <p style="font-weight: 800; font-size: 0.8em;">SMART INVENTORY & TEAM</p>
                    </div>
                </div>
            </section>

            <!-- Slide 5: Visual Timeline -->
            <section>
                <h2>01. Visual <span class="highlight">Timeline</span></h2>
                <p style="font-size: 0.7em;">Multi-Project Gantt Orchestration</p>

                <div class="card" style="background: rgba(79, 112, 250, 0.1); border-color: var(--brand);">
                    <ul style="font-size: 0.7em; line-height: 2;">
                        <li>🚀 <span style="font-weight: 800;">Resource Sync:</span> Visualize overlaps instantly.</li>
                        <li>📅 <span style="font-weight: 800;">Real-time Adjustments:</span> Day/Week/Month
                            perspectives.</li>
                        <li>⚠️ <span style="font-weight: 800;">Risk Detection:</span> Automatic highlighting of overdue
                            tasks.</li>
                    </ul>
                </div>
                <div style="font-size: 0.5em; color: #64748B;">Powered by Frappe-Gantt & High-Performance Scripts</div>
            </section>

            <!-- Slide 6: Financial Control -->
            <section>
                <h2>02. Financial <span class="highlight">Control Room</span></h2>
                <p style="font-size: 0.7em;">Protect Labs, Profits, and Liquidity</p>

                <div style="display: flex; gap: 20px; margin-top: 30px;">
                    <div class="card" style="flex: 1;">
                        <h4 style="color: var(--brand);">Profit Locking</h4>
                        <p style="font-size: 0.5em;">Lock financials to prevent unauthorized expense entry after budget
                            finalization.</p>
                    </div>
                    <div class="card" style="flex: 1;">
                        <h4 style="color: var(--emerald);">Vendor Vault</h4>
                        <p style="font-size: 0.5em;">Integrated payment modes, txn trackers, and quotation image
                            storage.</p>
                    </div>
                </div>
            </section>

            <!-- Slide 7: Photo Journal -->
            <section>
                <h2>03. Field Photo <span class="highlight">Journal</span></h2>
                <p style="font-size: 0.7em;">Radical Accountability</p>

                <div style="display: flex; align-items: center; gap: 30px; margin-top: 40px;">
                    <div
                        style="flex: 1; border: 2px dashed #334155; height: 300px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.5em; color: #475569;">
                        [BEFORE PHOTO]</div>
                    <div style="font-size: 2em; color: var(--brand);">➔</div>
                    <div
                        style="flex: 1; border: 2px dashed var(--emerald); height: 300px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.5em; color: var(--emerald);">
                        [AFTER PHOTO]</div>
                </div>
                <p style="font-size: 0.6em; margin-top: 30px; color: #94A3B8;">Mandatory 2-stage verification for every
                    Daily Progress Report (DPR).</p>
            </section>

            <!-- Slide 8: The Conclusion -->
            <section data-background-color="#0F172A">
                <h1 style="font-size: 2.5em;">Transforming <span class="gradient-text">Execution</span></h1>
                <div style="display: flex; justify-content: space-around; margin-top: 60px;">
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.5em; color: var(--emerald);">40%</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">FASTER DELIVERY</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.5em; color: var(--brand);">100%</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">PROFIT VISIBILITY</p>
                    </div>
                    <div style="text-align: center;">
                        <h2 style="font-size: 1.5em; color: var(--rose);">ZERO</h2>
                        <p style="font-size: 0.4em; font-weight: 800;">VENDOR DISPUTES</p>
                    </div>
                </div>
            </section>

            <!-- Slide 9: Closing -->
            <section>
                <h3>Presented By</h3>
                <h2 class="highlight">Indresh Singh</h2>
                <p style="font-size: 0.7em; font-weight: 600;">SME & Lead Architect</p>
                <div style="margin-top: 60px;">
                    <p style="font-size: 0.6em; color: #94A3B8;">Crafted with Precision by</p>
                    <h4 style="letter-spacing: 0.3em;">KRIZIA TECHNOLOGIES</h4>
                </div>
                <div style="margin-top: 40px;">
                    <p style="font-size: 0.5em; color: var(--brand);">www.krizia.in | interior-touch.com</p>
                </div>
            </section>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.js"></script>
    <script>
        Reveal.initialize({
            hash: true,
            center: true,
            controls: true,
            progress: true,
            mouseWheel: true,
            transition: 'convex', // none/fade/slide/convex/concave/zoom
            backgroundTransition: 'fade',
        });
    </script>
</body>

</html>