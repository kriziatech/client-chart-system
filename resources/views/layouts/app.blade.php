<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Interior Touch') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F0F5FF',
                            100: '#E1E9FF',
                            200: '#C8D7FF',
                            300: '#A4BCFF',
                            400: '#7594FF',
                            500: '#4F70FA', // Modern Professional Blue
                            600: '#3D56D6',
                            700: '#3144B0',
                            800: '#2C398E',
                            900: '#283273',
                            950: '#171C44',
                        },
                        ui: {
                            bg: '#F8FAFC',
                            surface: '#FFFFFF',
                            border: '#F1F5F9',
                            muted: '#94A3B8',
                            primary: '#0F172A',
                            danger: '#EF4444',
                            success: '#10B981',
                            warning: '#F59E0B'
                        },
                        dark: {
                            bg: '#0F172A',
                            surface: '#1E293B',
                            border: '#334155',
                            muted: '#94A3B8'
                        }
                    },
                    spacing: {
                        '18': '4.5rem',
                    },
                    borderRadius: {
                        'xl': '14px',
                        '2xl': '20px',
                        '3xl': '24px',
                    },
                    boxShadow: {
                        'premium': '0 10px 30px -10px rgba(0,0,0,0.04), 0 4px 12px -4px rgba(0,0,0,0.02)',
                        'premium-hover': '0 20px 40px -15px rgba(0,0,0,0.08), 0 8px 20px -6px rgba(0,0,0,0.04)',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --brand-50: #CAF0F8;
            --brand-100: #ADE8F4;
            --brand-200: #90E0EF;
            --brand-300: #48CAE4;
            --brand-400: #00B4D8;
            --brand-500: #0096C7;
            --brand-600: #0077B6;
            --brand-700: #023E8A;
            --brand-800: #03045E;
            --brand-900: #020344;
            --brand-950: #010222;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.011em;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #1F242C;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }

        .glass {
            background: rgba(18, 21, 27, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
</head>

<body x-data="{ sidebarOpen: true, notificationsOpen: false, profileOpen: false, searchOpen: false }"
    class="h-full bg-slate-50 dark:bg-dark-bg text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-56' : 'w-16'"
        class="fixed inset-y-0 left-0 z-50 transition-all duration-300 bg-white dark:bg-dark-surface border-r border-slate-200 dark:border-dark-border flex flex-col no-print">

        <!-- Logo Area -->
        <div class="h-14 flex items-center px-5 border-b border-slate-200 dark:border-dark-border">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline">
                <div
                    class="w-7 h-7 bg-brand-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity
                    class="font-bold text-base tracking-tight whitespace-nowrap text-ui-primary dark:text-white">Interior
                    Touch</span>
            </a>
        </div>

        <!-- Nav Links -->
        <nav class="flex-grow py-4 px-2 space-y-0.5 overflow-y-auto">
            @auth

            {{-- ═══════ DASHBOARD ═══════ --}}
            @if(!auth()->user()->isSales())
            <x-nav-link href="{{ route('dashboard') }}" :active="Request::is('dashboard')"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                label="Workspace Overview" />
            @endif

            {{-- ═══════ JOURNEY: SALES ═══════ --}}
            @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen" class="text-[11px] font-bold text-slate-400 uppercase tracking-[1.5px]">Journey:
                    Sales</p>
                <div x-show="!sidebarOpen" class="h-px bg-slate-100 dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="{{ route('leads.index') }}"
                :active="Request::routeIs('leads.index') && request('view') !== 'board'"
                icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                label="Lead Selection" />
            <x-nav-link href="{{ route('leads.index', ['view' => 'board']) }}" :active="request('view') === 'board'"
                icon="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"
                label="Sales Pipeline" />
            <x-nav-link href="{{ route('quotations.index') }}" :active="Request::is('quotations*')"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                label="Estimates / BOQ" />
            @endif

            {{-- ═══════ JOURNEY: EXECUTION ═══════ --}}
            @if(!auth()->user()->isSales())
            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen" class="text-[11px] font-bold text-slate-400 uppercase tracking-[1.5px]">Journey:
                    Work</p>
                <div x-show="!sidebarOpen" class="h-px bg-slate-100 dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="{{ route('clients.index') }}" :active="Request::is('clients*')"
                icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                label="Workspaces" />
            <x-nav-link href="{{ route('tasks.index') }}" :active="Request::is('tasks*')"
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                label="Tasks" />
            <x-nav-link href="{{ route('chat.index') }}" :active="Request::is('chat*')"
                icon="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                label="Team Chat" />
            <x-nav-link href="{{ route('inventory.index') }}" :active="Request::is('inventory*')"
                icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" label="Inventory" />
            <x-nav-link href="{{ route('attendances.index') }}" :active="Request::is('attendances*')"
                icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" label="Team & Attendance" />
            @endif

            {{-- ═══════ JOURNEY: REPORTS ═══════ --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen" class="text-[11px] font-bold text-slate-400 uppercase tracking-[1.5px]">Reports
                    & Logs</p>
                <div x-show="!sidebarOpen" class="h-px bg-slate-100 dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="{{ route('finance.summary') }}" :active="Request::is('finance*')"
                icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                label="Analytics & History" />
            <x-nav-link href="{{ route('audit-logs.index') }}" :active="Request::is('audit-logs*')"
                icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" label="Audit Logs" />
            @endif

            @if(auth()->user()->isAdmin())
            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen" class="text-[11px] font-bold text-slate-400 uppercase tracking-[1.5px]">Settings
                </p>
                <div x-show="!sidebarOpen" class="h-px bg-slate-100 dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="{{ route('users.index') }}" :active="Request::is('users*')"
                icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                label="User Controls" />
            @endif

            {{-- ═══════ SETTINGS ═══════ --}}
            @if(auth()->user()->isAdmin())
            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen"
                    class="text-[11px] font-bold text-ui-muted dark:text-dark-muted uppercase tracking-[1.5px]">
                    Settings</p>
                <div x-show="!sidebarOpen" class="h-px bg-ui-border dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="#" :active="Request::is('settings*')"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                label="Settings" />
            <x-nav-link href="#" :active="Request::is('integrations*')"
                icon="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"
                label="Integrations" />
            <x-nav-link href="{{ route('backups.index') }}" :active="Request::is('backups*')"
                icon="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" label="Backups" />
            @endif

            @endauth
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-slate-200 dark:border-dark-border">
            <button x-on:click="sidebarOpen = !sidebarOpen"
                class="w-full h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors">
                <svg :class="sidebarOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7">
                    </path>
                </svg>
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div :class="sidebarOpen ? 'pl-56' : 'pl-16'" class="flex flex-col min-h-screen transition-all duration-300">

        <!-- Top Header -->
        <header :class="sidebarOpen ? 'left-56' : 'left-16'"
            class="h-14 glass fixed top-0 right-0 z-40 flex items-center justify-between px-6 no-print transition-all duration-300">
            <!-- Search Bar -->
            <div class="relative w-80 group">
                <form action="{{ route('search') }}" method="GET">
                    <div
                        class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="q" placeholder="Search Clients, Leads, Vendors..."
                        value="{{ request('q') }}"
                        class="w-full bg-slate-100 dark:bg-slate-800/50 border-transparent focus:border-brand-500/50 focus:ring-0 rounded-xl pl-9 pr-4 py-1.5 text-xs transition-all">
                </form>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">


                {{-- Dark Mode Toggle --}}
                <button onclick="toggleDarkMode()"
                    class="p-2 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg id="icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg id="icon-moon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                {{-- Notifications --}}
                <div class="relative" x-data="{ open: false }">
                    <button x-on:click="open = !open"
                        class="p-2 text-slate-400 hover:text-brand-500 transition-colors relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-2 right-2 flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        @endif
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-3 w-80 bg-white dark:bg-dark-surface rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border overflow-hidden ring-1 ring-black ring-opacity-5">
                        <div
                            class="p-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                            <span class="font-bold text-sm">Notifications</span>
                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                @csrf
                                <button type="submit"
                                    class="text-[16px] text-brand-500 font-bold uppercase tracking-widest hover:text-brand-600 transition-colors">Mark
                                    All Read</button>
                            </form>
                        </div>
                        <div class="max-h-64 overflow-y-auto p-2">
                            @if(auth()->user()->unreadNotifications->isNotEmpty())
                            @foreach(auth()->user()->unreadNotifications as $notification)
                            <div
                                class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-colors border-b border-slate-50 dark:border-dark-border last:border-0">
                                <div class="text-[17px] font-bold text-slate-900 dark:text-white">{{
                                    $notification->data['message'] ?? 'New Notification' }}</div>
                                <div class="text-[15px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{
                                    $notification->created_at->diffForHumans() }}</div>
                            </div>
                            @endforeach
                            @else
                            <div class="p-3 text-center text-xs text-slate-400 italic">No new notifications</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Profile (Google-style) --}}
                <div class="relative" x-data="{ open: false }">
                    {{-- Avatar-only trigger --}}
                    <button x-on:click="open = !open" id="profile-avatar-btn"
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold text-sm ring-2 ring-transparent hover:ring-brand-200 dark:hover:ring-brand-800 transition-all duration-200 focus:outline-none focus:ring-brand-300 dark:focus:ring-brand-700 shadow-sm hover:shadow-md cursor-pointer"
                        title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open" @click.outside="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-2 w-[320px] origin-top-right bg-white dark:bg-dark-surface rounded-3xl shadow-[0_10px_50px_-12px_rgba(0,0,0,0.25)] border border-slate-200/80 dark:border-dark-border overflow-hidden z-[60]">

                        {{-- User Info Card --}}
                        <div class="p-5 pb-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-400 to-brand-700 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg shadow-brand-500/20">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 pt-1">
                                    <div class="text-[15px] font-semibold text-slate-900 dark:text-white truncate">{{
                                        auth()->user()->name }}</div>
                                    <div class="text-[13px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{
                                        auth()->user()->email }}</div>
                                    @if(auth()->user()->role)
                                    <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase
                                        @if(auth()->user()->isSuperAdmin()) bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                        @elseif(auth()->user()->isAdmin()) bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400
                                        @else bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400
                                        @endif">
                                        @if(auth()->user()->isSuperAdmin())
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @endif
                                        {{ auth()->user()->role->name ?? 'User' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="h-px bg-slate-100 dark:bg-dark-border mx-4"></div>

                        {{-- Menu Items --}}
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" id="profile-settings-link"
                                class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/70 rounded-xl transition-all duration-150 group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-brand-50 dark:group-hover:bg-brand-900/20 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                Manage Account
                            </a>
                        </div>

                        {{-- Divider --}}
                        <div class="h-px bg-slate-100 dark:bg-dark-border mx-4"></div>

                        {{-- Sign Out --}}
                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" id="profile-signout-btn"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all duration-150 group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-red-100 dark:group-hover:bg-red-900/20 group-hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                    </div>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-grow p-8 mt-14">
            @if(session('success'))
            <div
                class="max-w-4xl mx-auto mb-6 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 p-4 rounded-2xl flex items-center gap-3 text-teal-800 dark:text-teal-300 no-print animate-in fade-in slide-in-from-top-4 duration-500">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm font-bold">{{ session('success') }}</div>
            </div>
            @endif

            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer
            class="p-8 text-[16px] text-slate-400 dark:text-ui-muted font-medium uppercase tracking-[0.2em] flex justify-between items-center no-print">
            <div>© {{ date('Y') }} Developed By Krizia Technologies @ 2026</div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand-500 transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-500 transition-colors">API Docs</a>
                <a href="#" class="hover:text-brand-500 transition-colors text-brand-500">System v2.4</a>
            </div>
        </footer>
    </div>

    <!-- Attendance Status Script -->
    <script>
        Mode: IST day / night auto + manual override-- -
            function getISTHour() {
                const now = new Date();
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                const ist = new Date(utc + (5.5 * 3600000));
                return ist.getHours();
            }

        function isNightInIST() {
            const h = getISTHour();
            return h >= 18 || h < 6; // Dark from 6 PM to 6 AM IST
        }

        function applyTheme(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                document.getElementById('icon-sun')?.classList.remove('hidden');
                document.getElementById('icon-moon')?.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                document.getElementById('icon-sun')?.classList.add('hidden');
                document.getElementById('icon-moon')?.classList.remove('hidden');
            }
        }

        function updateDarkMode() {
            const mode = localStorage.getItem('themeMode'); // 'dark', 'light', or null (auto)
            if (mode === 'dark' || mode === 'light') {
                applyTheme(mode === 'dark');
            } else {
                // Auto: follow IST day/night
                applyTheme(isNightInIST());
            }
        }

        function toggleDarkMode() {
            const currentMode = localStorage.getItem('themeMode');
            const currentlyDark = document.documentElement.classList.contains('dark');

            if (currentMode === null) {
                // Auto → manual override to opposite
                localStorage.setItem('themeMode', currentlyDark ? 'light' : 'dark');
            } else if ((currentMode === 'dark' && isNightInIST()) || (currentMode === 'light' && !isNightInIST())) {
                // Manual matches what auto would be → go back to auto
                localStorage.removeItem('themeMode');
            } else {
                // Manual override → toggle to opposite
                localStorage.setItem('themeMode', currentlyDark ? 'light' : 'dark');
            }
            updateDarkMode();
        }

        // Apply immediately to prevent flash
        updateDarkMode();

        // Re-check every 10 minutes so it transitions live at sunset/sunrise in auto mode
        setInterval(() => { updateDarkMode(); }, 600000);

        document.addEventListener('DOMContentLoaded', () => {
            updateAttendanceWidget();
            // Update duration every minute if checked in
            setInterval(updateAttendanceWidget, 60000);
        });

        async function updateAttendanceWidget() {
            try {
                const response = await fetch('{{ route("attendance.status") }}');
                const data = await response.json();
                const widget = document.getElementById('attendance-widget');
                if (!widget) return;

                if (data.status === 'not_checked_in') {
                    let projectOptions = data.projects.map(p => `<option value="${p.id}">${p.first_name}</option>`).join('');
                    widget.innerHTML = `
                        <div class="flex items-center gap-3 bg-white dark:bg-dark-surface p-2 pl-4 rounded-2xl border border-slate-200 dark:border-dark-border shadow-premium">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Project Site</span>
                                <select id="att-project-id" class="text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0">
                                    <option value="">General Work</option>
                                    ${projectOptions}
                                </select>
                            </div>
                            <button onclick="attendanceAction('check-in')" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-500/20 active:scale-95">Check In</button>
                        </div>
                    `;
                } else if (data.status === 'checked_in') {
                    widget.innerHTML = `
                        <div class="flex items-center gap-4 bg-white dark:bg-dark-surface p-2 pl-4 rounded-2xl border border-slate-200 dark:border-dark-border shadow-premium">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500 animate-pulse">● Active Shift</span>
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase">${data.client || 'General'} • ${data.duration}</span>
                            </div>
                            <button onclick="attendanceAction('check-out')" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-rose-500/20 active:scale-95">Check Out</button>
                        </div>
                    `;
                } else {
                    widget.innerHTML = `
                        <div class="flex items-center gap-3 bg-slate-50 dark:bg-dark-bg/50 p-2 px-4 rounded-2xl border border-slate-200 dark:border-dark-border">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shift Completed: ${data.duration}</span>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Attendance status failed:', error);
            }
        }

        function attendanceAction(type) {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser.");
                return;
            }

            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = 'Locating...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(async (position) => {
                try {
                    const projectId = document.getElementById('att-project-id')?.value;
                    const response = await fetch(`/attendance/${type}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            client_id: projectId
                        })
                    });
                    const data = await response.json();
                    if (response.ok) {
                        updateAttendanceWidget();
                    } else {
                        alert(data.message || 'Action failed');
                        btn.innerText = originalText;
                        btn.disabled = false;
                    }
                } catch (error) {
                    alert('Request failed');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            }, (error) => {
                alert("Error getting location: " + error.message);
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }
    </script>

    {{-- Floating Widget Container --}}
    <div class="fixed bottom-8 right-8 z-[100] no-print" id="attendance-widget"></div>
</body>

</html>