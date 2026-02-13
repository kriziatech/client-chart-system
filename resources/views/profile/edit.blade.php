@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="mb-10">
        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Profile Identity</h1>
        <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Configure your personalized settings and
            security protocols.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Sidebar / Selection -->
        <div class="lg:col-span-4 space-y-4">
            <div
                class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium sticky top-8">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="w-16 h-16 rounded-3xl bg-brand-600 flex items-center justify-center text-white text-2xl font-black shadow-xl shadow-brand-500/30">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">{{
                            auth()->user()->name }}</h3>
                        <span class="text-[10px] font-black uppercase tracking-widest text-brand-600">{{
                            auth()->user()->role->description }}</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div
                        class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white">Core
                            Identity</span>
                    </div>
                    <div
                        class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Security Credentials</span>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-ui-border dark:border-dark-border">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">System
                        Account initialized on {{ auth()->user()->created_at->format('d M, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Sections -->
        <div class="lg:col-span-8 space-y-10">
            <!-- Profile Info -->
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                <div class="mb-10">
                    <h3 class="text-xs font-bold uppercase tracking-[3px] text-brand-600">Update Identity</h3>
                    <p class="text-xs text-ui-muted dark:text-dark-muted font-medium mt-2">Modify your global display
                        name and corporate communication address.</p>
                </div>
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Security -->
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                <div class="mb-10">
                    <h3 class="text-xs font-bold uppercase tracking-[3px] text-slate-900 dark:text-white">Security
                        Credentials</h3>
                    <p class="text-xs text-ui-muted dark:text-dark-muted font-medium mt-2">Ensure your account remains
                        secure by utilizing long, randomized passphrases.</p>
                </div>
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Zone -->
            <div
                class="bg-rose-50/30 dark:bg-rose-500/5 p-10 rounded-[2.5rem] border border-rose-100 dark:border-rose-500/20 shadow-sm overflow-hidden">
                <div class="mb-10">
                    <h3 class="text-xs font-bold uppercase tracking-[3px] text-rose-600">Irreversible Action Zone</h3>
                    <p class="text-xs text-rose-500/70 font-medium mt-2">Immediately purge your entire identity and
                        historical data from the system.</p>
                </div>
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection