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
            <div class="flex items-center gap-3">
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
            </div>
        </div>

        <!-- Nav Links -->
        <nav class="flex-grow py-4 px-2 space-y-0.5">
            @auth
            <x-nav-link href="{{ route('dashboard') }}" :active="Request::is('dashboard')"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                label="Workspace Overview" />
            <x-nav-link href="{{ route('clients.index') }}" :active="Request::is('clients*')"
                icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                label="Workspaces" />

            <x-nav-link href="{{ route('chat.index') }}" :active="Request::is('chat*')"
                icon="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                label="Team Chat" />

            <x-nav-link href="{{ route('quotations.index') }}" :active="Request::is('quotations*')"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                label="Quotations" />

            <div class="pt-5 pb-2 px-3">
                <p x-show="sidebarOpen"
                    class="text-[16px] font-bold text-ui-muted dark:text-dark-muted uppercase tracking-[1px]">Sales &
                    Pitching</p>
                <div x-show="!sidebarOpen" class="h-px bg-ui-border dark:bg-dark-border mx-1"></div>
            </div>
            <x-nav-link href="{{ route('portfolio.index') }}" :active="Request::is('portfolio*')"
                icon="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                label="Portfolio" />
            <x-nav-link href="{{ route('estimate-builder.index') }}" :active="Request::is('estimate-builder*')"
                icon="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v14a2 2 0 002 2z"
                label="Estimate Builder" />
            <div class="pt-3 pb-1 px-3">
                <p x-show="sidebarOpen"
                    class="text-[15px] font-bold text-slate-400 dark:text-ui-muted uppercase tracking-widest">Management
                </p>
                <div x-show="!sidebarOpen" class="h-px bg-slate-200 dark:bg-ui-border mx-1"></div>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <x-nav-link href="{{ route('inventory.index') }}" :active="Request::is('inventory*')"
                icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" label="Inventory" />
            <x-nav-link href="{{ route('audit-logs.index') }}" :active="Request::is('audit-logs*')"
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                label="Audit Logs" />
            @endif

            @if(Auth::user()->isAdmin())
            <x-nav-link href="{{ route('users.index') }}" :active="Request::is('users*')"
                icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                label="Team" />
            <x-nav-link href="{{ route('attendances.index') }}" :active="Request::is('attendances*')"
                icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" label="Attendance" />
            @endif
            @endauth
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-slate-200 dark:border-dark-border">
            <button @click="sidebarOpen = !sidebarOpen"
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
        <header class="h-14 glass sticky top-0 z-40 flex items-center justify-between px-6 no-print">
            <!-- Search Bar -->
            <div class="relative w-80 group">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" placeholder="Search (⌘K)"
                    class="w-full bg-slate-100 dark:bg-slate-800/50 border-transparent focus:border-brand-500/50 focus:ring-0 rounded-xl pl-9 pr-4 py-1.5 text-xs transition-all">
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                {{-- Attendance Badge --}}
                <div id="attendance-status-badge" class="flex items-center">
                    <!-- Loaded via JS -->
                </div>

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
                    <button @click="open = !open"
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
                            @forelse(auth()->user()->unreadNotifications as $notification)
                            <div
                                class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-colors border-b border-slate-50 dark:border-dark-border last:border-0">
                                <div class="text-[17px] font-bold text-slate-900 dark:text-white">{{
                                    $notification->data['message'] ?? 'New Notification' }}</div>
                                <div class="text-[15px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{
                                    $notification->created_at->diffForHumans() }}</div>
                            </div>
                            @empty
                            <div class="p-3 text-center text-xs text-slate-400 italic">No new notifications</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-3 p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <div
                            class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-xs ring-2 ring-white dark:ring-dark-surface">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-xs font-bold leading-none">{{ Auth::user()->name }}</div>
                            <div class="text-[15px] text-slate-500 uppercase font-black tracking-tighter mt-1">{{
                                Auth::user()->role->description }}</div>
                        </div>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-3 w-56 bg-white dark:bg-dark-surface rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border ring-1 ring-black ring-opacity-5">
                        <div class="p-3 border-b border-slate-100 dark:border-dark-border">
                            <div class="text-xs text-slate-500">Logged in as</div>
                            <div class="text-sm font-bold truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="p-1">
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Account Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-grow p-8">
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
            <div>© {{ date('Y') }} Krizia Technologies</div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand-500 transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-500 transition-colors">API Docs</a>
                <a href="#" class="hover:text-brand-500 transition-colors text-brand-500">System v2.4</a>
            </div>
        </footer>
    </div>

    <!-- Attendance Status Script -->
    <script>
        function updateDarkMode() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.getElementById('icon-sun')?.classList.remove('hidden');
                document.getElementById('icon-moon')?.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                document.getElementById('icon-sun')?.classList.add('hidden');
                document.getElementById('icon-moon')?.classList.remove('hidden');
            }
        }

        function toggleDarkMode() {
            if (localStorage.theme === 'dark') {
                localStorage.theme = 'light';
            } else {
                localStorage.theme = 'dark';
            }
            updateDarkMode();
        }

        // Attendance System
        document.addEventListener('DOMContentLoaded', () => {
            fetchAttendanceStatus();
        });

        function fetchAttendanceStatus() {
            fetch('{{ route('attendance.status') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('attendance-status-badge');
                    if (data.status === 'checked_in') {
                        container.innerHTML = `
                            <button onclick="markAttendance('out')" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-800 text-xs font-bold hover:bg-green-100 transition-colors group">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span>On Shift (${data.duration})</span>
                                <span class="hidden group-hover:inline ml-1 text-red-500">Check Out</span>
                            </button>
                        `;
                    } else {
                        container.innerHTML = `
                            <button onclick="markAttendance('in')" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Check In</span>
                            </button>
                        `;
                    }
                })
                .catch(err => console.error('Error fetching status:', err));
        }

        function markAttendance(type) {
            const btn = document.getElementById('attendance-status-badge').querySelector('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Locating...</span>`;
            btn.disabled = true;

            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

            navigator.geolocation.getCurrentPosition((position) => {
                const url = type === 'in' ? '{{ route('attendance.check-in') }}' : '{{ route('attendance.check-out') }}';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        fetchAttendanceStatus();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error marking attendance');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });

            }, (error) => {
                alert('Unable to retrieve your location: ' + error.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>