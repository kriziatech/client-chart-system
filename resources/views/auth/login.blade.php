<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Krivia</title>
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
                    Krivia: All-in-One Business Operating System
                </div>

                <h1 class="text-4xl lg:text-5xl font-[900] text-white leading-[1.1] mb-8 lg:text-left text-center">
                    Empower Your Business with <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-600">Intelligence.</span>
                </h1>

                <p class="text-slate-400 text-lg lg:text-xl leading-relaxed mb-6 lg:text-left text-center">
                    Krivia is an <span class="text-white font-black italic underline decoration-teal-500/50">All-in-One
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

                <!-- Product Preview Float -->
                <div class="relative group hidden lg:block">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-teal-500 to-transparent rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000">
                    </div>
                    <div class="relative bg-[#1C2637] border border-white/5 p-6 rounded-2xl shadow-2xl float-anim">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                                <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                                <div class="w-2 h-2 rounded-full bg-green-500/50"></div>
                            </div>
                            <div class="h-4 w-32 bg-white/5 rounded"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div
                                class="h-20 rounded-lg bg-teal-500/10 border border-teal-500/20 flex flex-col justify-center items-center">
                                <span class="text-teal-400 font-black text-lg">24</span>
                                <span class="text-[8px] text-teal-600 uppercase font-black">Active Projects</span>
                            </div>
                            <div class="h-20 rounded-lg bg-white/5 border border-white/10"></div>
                            <div class="h-20 rounded-lg bg-white/5 border border-white/10"></div>
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
                <h2 class="text-4xl font-[900] text-white tracking-tight mb-2 uppercase">Krivia</h2>
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