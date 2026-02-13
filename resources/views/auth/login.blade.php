<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Interior Touch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            500: '#1F8A8A',
                            600: '#197373',
                            700: '#166464',
                        },
                        brand: {
                            card: 'rgba(28, 38, 55, 0.7)',
                            overlay: 'rgba(10, 18, 32, 0.9)',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0A1220;
        }

        .glass-card {
            background: rgba(28, 38, 55, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
        }

        .primary-button {
            background: linear-gradient(135deg, #1F8A8A, #166464);
            box-shadow: 0 8px 20px rgba(31, 138, 138, 0.3);
        }

        .input-dark {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s ease;
        }

        .input-dark:focus {
            background: rgba(15, 23, 42, 0.6);
            border-color: #1F8A8A;
            box-shadow: 0 0 0 1px rgba(31, 138, 138, 0.5);
        }

        .badge-pill {
            background: rgba(31, 138, 138, 0.15);
            border: 1px solid rgba(31, 138, 138, 0.3);
            color: #4DB6B6;
        }

        .dashboard-blur {
            filter: blur(40px);
            transform: scale(1.1);
            object-fit: cover;
            opacity: 0.4;
        }

        .fancy-gradient {
            background: radial-gradient(circle at 30% 30%, rgba(31, 138, 138, 0.15) 0%, transparent 70%);
        }

        /* Animations */
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

        .float-anim {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen relative overflow-hidden bg-[#0A1220]">

    <!-- Background Analytics Layer -->
    <div class="absolute inset-0 z-0 select-none pointer-events-none">
        <img src="{{ asset('images/login-bg.png') }}" class="w-full h-full dashboard-blur" alt="Background Analytics">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0A1220]/95 via-[#0A1220]/90 to-transparent"></div>
        <div class="absolute inset-0 fancy-gradient"></div>
    </div>

    <!-- Main Content Split Layout -->
    <div
        class="relative z-10 w-full min-h-screen flex flex-col lg:flex-row items-center justify-center p-6 lg:p-20 lg:gap-20">

        <!-- ROW-1: Left Side (Fancy Content / MVP) -->
        <div
            class="w-full lg:w-1/2 flex flex-col items-center lg:items-start animate-in fade-in slide-in-from-left-8 duration-1000 mb-16 lg:mb-0">
            <div class="max-w-[560px]">
                <div
                    class="badge-pill inline-flex px-4 py-1.5 rounded-full text-[10px] font-[800] tracking-[2px] mb-6 uppercase">
                    Interior Touch: All-in-One Business Operating System
                </div>

                <h1 class="text-4xl lg:text-5xl font-[900] text-white leading-[1.1] mb-8 lg:text-left text-center">
                    Empower Your Business with <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-600">Intelligence.</span>
                </h1>

                <p class="text-slate-400 text-lg lg:text-xl leading-relaxed mb-6 lg:text-left text-center">
                    Interior Touch is an <span
                        class="text-white font-black italic underline decoration-teal-500/50">All-in-One
                        Business Operating System</span> engineered to centralize project lifecycles, operational
                    finances, and organizational transparency.
                </p>
                <p class="text-slate-500 text-sm mb-10 leading-relaxed font-medium lg:text-left text-center">
                    Providing high-performance SaaS solutions tailored for modern enterprise needs. Manage your entire
                    operation from a single, secure intelligence node.
                </p>

                <!-- Feature Matrix -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-12">
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Project Tracking</span>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Client
                            Management</span>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Financial
                            Control</span>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V5"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Inventory System</span>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Reports & Audit</span>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-widest">Quotations</span>
                    </div>
                </div>

                <!-- Enhanced Product Preview Float -->
                <div
                    class="relative group hidden lg:block perspective-1000 transform scale-90 hover:scale-95 transition-all duration-700">
                    <!-- Glow Effect -->
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-teal-500 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 animate-pulse">
                    </div>

                    <!-- Main Interface Container -->
                    <div
                        class="relative bg-[#0F172A] border border-slate-700/50 p-5 rounded-2xl shadow-2xl float-anim overflow-hidden">

                        <!-- App Header Mockup -->
                        <div class="flex items-center justify-between mb-6 border-b border-slate-800 pb-4">
                            <div class="flex gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500/80"></div>
                            </div>
                            <div class="flex gap-3">
                                <div class="h-2 w-20 bg-slate-700/50 rounded-full"></div>
                                <div class="h-5 w-5 rounded-full bg-teal-500/20 border border-teal-500/50"></div>
                            </div>
                        </div>

                        <!-- Dashboard Grid -->
                        <div class="grid grid-cols-12 gap-4">

                            <!-- Left col: Stats & Chart -->
                            <div class="col-span-4 space-y-3">
                                <!-- Stat Card 1 -->
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/50">
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-wider mb-1">
                                        Active Projects</div>
                                    <div class="text-2xl font-black text-white">24</div>
                                    <div class="w-full bg-slate-700 h-1 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-teal-500 h-full w-[70%]"></div>
                                    </div>
                                </div>

                                <!-- Stat Card 2 (Mini Chart) -->
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/50">
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-wider mb-2">
                                        Revenue Flow</div>
                                    <div class="flex items-end gap-1 h-10 w-full justify-between px-1">
                                        <div class="w-1.5 bg-slate-600/50 h-[30%] rounded-sm"></div>
                                        <div class="w-1.5 bg-slate-600/50 h-[50%] rounded-sm"></div>
                                        <div class="w-1.5 bg-teal-500/50 h-[70%] rounded-sm"></div>
                                        <div class="w-1.5 bg-teal-500 h-[100%] rounded-sm"></div>
                                        <div class="w-1.5 bg-slate-600/50 h-[60%] rounded-sm"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right col: Kanban Board Simulation -->
                            <div class="col-span-8 flex gap-3">
                                <!-- Column 1 -->
                                <div class="flex-1 space-y-2">
                                    <div class="text-[8px] font-bold text-slate-500 uppercase">To Do</div>
                                    <div class="bg-slate-800 p-2 rounded-lg border-l-2 border-orange-500 shadow-sm">
                                        <div class="h-1.5 w-10 bg-slate-600 rounded mb-1.5"></div>
                                        <div class="h-1 w-full bg-slate-700 rounded mb-1"></div>
                                        <div class="h-1 w-2/3 bg-slate-700 rounded"></div>
                                    </div>
                                    <div
                                        class="bg-slate-800 p-2 rounded-lg border-l-2 border-orange-500 shadow-sm opacity-60">
                                        <div class="h-1.5 w-6 bg-slate-600 rounded mb-1.5"></div>
                                        <div class="flex -space-x-1 mt-1">
                                            <div class="w-3 h-3 rounded-full bg-slate-600 border border-slate-800">
                                            </div>
                                            <div class="w-3 h-3 rounded-full bg-slate-500 border border-slate-800">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2 -->
                                <div class="flex-1 space-y-2">
                                    <div class="text-[8px] font-bold text-slate-500 uppercase">In Progress</div>
                                    <div
                                        class="bg-slate-800 p-2 rounded-lg border-l-2 border-blue-500 shadow-lg transform -rotate-2 hover:rotate-0 transition-transform duration-300 translate-y-1 z-10 ring-1 ring-blue-500/50">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="h-1.5 w-8 bg-slate-500 rounded"></div>
                                            <div
                                                class="h-3 w-3 rounded bg-blue-500/20 text-[6px] flex items-center justify-center text-blue-400 font-bold">
                                                IP</div>
                                        </div>
                                        <div class="h-1 w-full bg-slate-700/50 rounded mb-1"></div>
                                        <div class="h-1 w-3/4 bg-slate-700/50 rounded"></div>
                                    </div>
                                </div>

                                <!-- Column 3 -->
                                <div class="flex-1 space-y-2">
                                    <div class="text-[8px] font-bold text-slate-500 uppercase">Done</div>
                                    <div
                                        class="bg-slate-800 p-2 rounded-lg border-l-2 border-emerald-500 shadow-sm opacity-80">
                                        <div class="h-1.5 w-12 bg-emerald-500/40 rounded mb-1.5"></div>
                                        <div
                                            class="w-4 h-4 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mt-2">
                                            <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Floating Elements for Depth -->
                    <div class="absolute -right-8 top-12 bg-[#1e293b] p-3 rounded-xl shadow-2xl border border-slate-700/80 w-36 animate-bounce"
                        style="animation-duration: 3s;">
                        <div class="flex items-center gap-2 mb-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                            <div class="text-[8px] text-green-400 font-bold uppercase tracking-wider">System Online
                            </div>
                        </div>
                        <div class="h-1 w-full bg-slate-700 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full w-[94%]"></div>
                        </div>
                        <div class="text-[8px] text-slate-500 mt-1 font-mono text-right">99.9% Uptime</div>
                    </div>

                    <div
                        class="absolute -left-4 -bottom-4 bg-[#1e293b] p-2 rounded-lg shadow-xl border border-slate-700/80 flex items-center gap-2 animate-pulse">
                        <div class="w-6 h-6 rounded bg-brand-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="h-1 w-12 bg-slate-600 rounded mb-1"></div>
                            <div class="h-1 w-8 bg-slate-700 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW-2: Right Side (Login Form) -->
        <div
            class="w-full lg:w-1/2 flex flex-col items-center max-w-[520px] animate-in fade-in slide-in-from-right-8 duration-1000">

            <!-- Branding -->
            <div class="flex flex-col items-center mb-10 text-center">
                <div
                    class="w-16 h-16 bg-teal-500 rounded-[18px] flex items-center justify-center text-white mb-5 shadow-2xl shadow-teal-500/20">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h2 class="text-4xl font-[900] text-white tracking-tight mb-2 uppercase">Interior Touch</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">by Krizia Technologies</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card w-full rounded-[24px] p-8 lg:p-10 border border-white/5 relative overflow-hidden">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-[1.5px] mb-3 ml-1">Corporate
                            Identity</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="name@company.com"
                                class="input-dark w-full h-[54px] rounded-xl pl-12 pr-4 text-white text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-3 px-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-[1.5px]">Access
                                Key</label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[11px] font-bold text-teal-500 hover:text-teal-400 transition-colors">Recover?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" name="password" required placeholder="••••••••••••"
                                class="input-dark w-full h-[54px] rounded-xl pl-12 pr-4 text-white text-sm focus:outline-none">
                        </div>
                    </div>

                    <button type="submit"
                        class="primary-button w-full h-[58px] text-white rounded-[16px] font-bold text-base transition-all transform active:scale-[0.98] hover:-translate-y-0.5">
                        Initialize Session
                    </button>

                    <div class="text-center pt-2">
                        <span class="text-slate-500 text-xs">Trouble logging in? </span>
                        <a href="https://wa.me/911234567890" target="_blank"
                            class="text-teal-500 font-bold text-xs hover:underline">WhatsApp Support</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Tawk.to Live Chat (Login Page Only) -->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/679de91d319623190b200b21/1iiusev9q';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
</body>

</html>