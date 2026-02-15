@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700 max-w-4xl mx-auto">
    <div class="mb-12">
        <a href="{{ route('pitch.leads.index') }}"
            class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-600 transition-colors mb-6 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 16l-4-4m0 0l4-4m-4 4h18">
                </path>
            </svg>
            Back to Dossier list
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Initiate New Lead</h1>
        <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Capture critical client data to start the
            pitching and design phase.</p>
    </div>

    <div
        class="bg-white dark:bg-dark-surface p-10 md:p-14 rounded-[3rem] border border-ui-border dark:border-dark-border shadow-premium relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-brand-50/30 dark:bg-brand-500/5 rounded-full -mr-32 -mt-32 blur-3xl">
        </div>

        <form action="{{ route('pitch.leads.store') }}" method="POST" class="relative">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                <div class="space-y-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Lead Full
                            Identity</label>
                        <input type="text" name="name" required placeholder="e.g. Alexander Pierce"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Digital Address
                            (Email)</label>
                        <input type="email" name="email" placeholder="alex@client.com"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Secure Line
                            (Phone)</label>
                        <input type="text" name="phone" placeholder="+91 98765 43210"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all">
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Acquisition
                            Source</label>
                        <input type="text" name="source" placeholder="Referral, Instagram, Website, etc."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Assign Project
                            Manager</label>
                        <select name="assigned_to_id"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all">
                            <option value="">Select an Elite Manager</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->description ??
                                $user->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Brief Work
                            Description</label>
                        <textarea name="work_description" rows="4"
                            placeholder="Describe the scope, aesthetic preferences, and site conditions..."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-10 border-t border-slate-50 dark:border-dark-border flex justify-end">
                <button type="submit"
                    class="bg-brand-600 text-white px-12 py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.3em] shadow-2xl shadow-brand-500/30 hover:bg-brand-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Authorize Lead Activation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection