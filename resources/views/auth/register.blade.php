<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Interior Touch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Interior Touch</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">All-in-One Business Operating System</p>
        </div>

        {{-- Register Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-200">
            @if($errors->any())
            <div class="bg-red-50 text-red-700 text-sm px-4 py-3 rounded-lg mb-4 border border-red-200">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-5">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-slate-700"
                        placeholder="Your name">
                </div>

                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-slate-700"
                        placeholder="you@example.com">
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-slate-700"
                        placeholder="••••••••">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirm
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-slate-700"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-brand-600 text-white py-3 rounded-xl font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 text-sm">
                    Create Account
                </button>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-slate-100">
                <p class="text-slate-500 text-sm">Already have an account?
                    <a href="{{ route('login') }}"
                        class="text-brand-600 font-semibold hover:text-brand-700 transition">Sign In</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">© {{ date('Y') }} Developed By Krizia Technologies @ 2026</p>
    </div>
</body>

</html>