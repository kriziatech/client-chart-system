<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Interior Touch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .input-field {
            transition: all 0.2s ease-in-out;
        }

        .input-field:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>

<body class="bg-white h-screen flex overflow-hidden">

    <!-- Left Section: Visual / Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative items-center justify-center p-12 overflow-hidden">
        <!-- Animated Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-900 opacity-90"></div>

        <!-- Dynamic Particles/Blobs -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div
                class="absolute top-[-20%] left-[-20%] w-[600px] h-[600px] bg-indigo-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob">
            </div>
            <div
                class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] bg-purple-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-32 left-[20%] w-[600px] h-[600px] bg-blue-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob animation-delay-4000">
            </div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-lg space-y-10">
            <!-- Header with Reveal Animation -->
            <div class="animate-fade-in-down">
                <h2 class="text-4xl font-bold text-white tracking-tight mb-3">Interior Touch</h2>
                <p class="text-indigo-200 text-xl font-light">Experience the future of project management.</p>
            </div>

            <div class="space-y-5">
                <!-- Feature 1 -->
                <div class="group flex gap-5 items-start p-4 rounded-2xl hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/10 opacity-0 animate-fade-in-up"
                    style="animation-delay: 100ms; animation-fill-mode: forwards;">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300 shadow-lg shadow-indigo-900/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-indigo-300 transition-colors">
                            End-to-End Project Lifecycle</h3>
                        <p class="text-slate-400 text-sm mt-1 leading-relaxed">Seamlessly manage every stage from
                            initial Lead to final Handover.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="group flex gap-5 items-start p-4 rounded-2xl hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/10 opacity-0 animate-fade-in-up"
                    style="animation-delay: 200ms; animation-fill-mode: forwards;">
                    <div
                        class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-lg shadow-purple-900/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-purple-300 transition-colors">
                            Client Collaboration Portal</h3>
                        <p class="text-slate-400 text-sm mt-1 leading-relaxed">Empower clients with a dedicated space
                            for approvals and updates.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group flex gap-5 items-start p-4 rounded-2xl hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/10 opacity-0 animate-fade-in-up"
                    style="animation-delay: 300ms; animation-fill-mode: forwards;">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300 shadow-lg shadow-blue-900/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-blue-300 transition-colors">
                            Integrated Financial Suite</h3>
                        <p class="text-slate-400 text-sm mt-1 leading-relaxed">Master your finances with built-in
                            Estimations, Billing, and Expense tracking.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="group flex gap-5 items-start p-4 rounded-2xl hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/10 opacity-0 animate-fade-in-up"
                    style="animation-delay: 400ms; animation-fill-mode: forwards;">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-lg shadow-emerald-900/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-emerald-300 transition-colors">Site
                            & Operations Control</h3>
                        <p class="text-slate-400 text-sm mt-1 leading-relaxed">Real-time oversight with Digital DPRs,
                            Attendance, and Material logs.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Right Section: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center bg-white h-full relative p-8">

        <!-- Centered Card Container for Form -->
        <div
            class="w-full max-w-md mx-auto space-y-8 bg-white p-10 rounded-2xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] border border-slate-100 relative z-10">

            <!-- Logo Card (Floating Top Center) -->
            <div class="flex flex-col items-center justify-center space-y-4">
                <div
                    class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-indigo-200 shadow-xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Interior Touch</h1>
            </div>

            <!-- Header Text -->
            <div class="text-center space-y-2">
                <p class="text-slate-500">Welcome back! Please login to your account.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-semibold text-slate-600 uppercase tracking-wider pl-1">Email
                        Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email"
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                            placeholder="name@company.com">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password"
                            class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password"
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                    <div class="flex justify-end pt-1">
                        <a href="#"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Forgot
                            password?</a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5 active:scale-95">
                    Sign In
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 text-center">
            <p class="text-xs text-slate-400 font-medium tracking-wide">Developed by Interior Touch</p>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -40px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.8s ease-out forwards;
        }
    </style>

</body>

</html>