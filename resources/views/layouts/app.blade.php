<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Interior Touch PM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .print-container {
                width: 210mm;
                margin: 0 auto;
                padding: 0;
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800">
    <nav class="bg-teal-700 text-white p-4 no-print shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('clients.index') }}" class="text-xl font-bold tracking-wide flex items-center gap-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                Interior Touch PM
            </a>
            <div class="flex items-center gap-4">
                @auth
                <span class="text-teal-200 text-sm hidden md:inline">
                    {{ Auth::user()->name }}
                    <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full ml-1 uppercase">{{
                        Auth::user()->role }}</span>
                </span>
                @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                <a href="{{ route('clients.create') }}"
                    class="bg-white text-teal-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-50 transition shadow">
                    + New Client
                </a>
                @endif
                @if(Auth::user()->isAdmin())
                <a href="{{ route('users.index') }}"
                    class="text-teal-200 hover:text-white text-sm font-medium transition">
                    Users
                </a>
                <a href="{{ route('audit-logs.index') }}"
                    class="text-teal-200 hover:text-white text-sm font-medium transition">
                    Audit Logs
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-teal-200 hover:text-white text-sm font-medium transition">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center text-xs text-gray-400 py-4 no-print">
        © {{ date('Y') }} Interior Touch PM — All rights reserved.
    </footer>
</body>

</html>