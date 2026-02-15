@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight font-display">Project
                Initiation</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1 uppercase tracking-[2px]">Onboarding
                new project lifecycle</p>
        </div>

        <a href="{{ route('clients.index') }}"
            class="group flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl text-xs font-black uppercase tracking-widest text-slate-500 hover:text-brand-600 hover:border-brand-500 transition-all shadow-sm">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Abort & Return
        </a>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div
        class="mb-8 p-6 bg-rose-50 dark:bg-rose-500/5 border-2 border-rose-100 dark:border-rose-500/20 rounded-[2rem] animate-in shake duration-500">
        <div class="flex items-center gap-3 mb-4">
            <div
                class="w-8 h-8 rounded-full bg-rose-500 flex items-center justify-center text-white font-black text-sm">
                !</div>
            <h4 class="text-sm font-black uppercase tracking-widest text-rose-800 dark:text-rose-400">Onboarding
                Blockers Detected</h4>
        </div>
        <ul
            class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 list-none text-[11px] font-bold text-rose-600 dark:text-rose-500 uppercase tracking-wide ml-1">
            @foreach ($errors->all() as $error)
            <li class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST" class="space-y-8">
        @include('clients._form')
    </form>
</div>
@endsection