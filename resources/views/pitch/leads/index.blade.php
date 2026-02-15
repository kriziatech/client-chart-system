@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Project Pitching</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Manage early-stage leads and convert
                them into active projects.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pitch.leads.create') }}"
                class="bg-brand-600 text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-brand-700 transition-all shadow-xl shadow-brand-500/20 active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                New Pitch Lead
            </a>
        </div>
    </div>

    @if(session('success'))
    <div
        class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-8 animate-in fade-in zoom-in duration-500">
        <p class="text-xs font-bold uppercase tracking-widest text-center">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leads as $lead)
        <div
            class="group bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6">
                @php
                $statusClasses = [
                'New' => 'bg-blue-50 text-blue-600 ring-blue-100',
                'In Progress' => 'bg-amber-50 text-amber-600 ring-amber-100',
                'Won' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                'Converted' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                'Lost' => 'bg-slate-50 text-slate-600 ring-slate-100',
                'Archived' => 'bg-slate-50 text-slate-600 ring-slate-100',
                ];
                $currentClasses = $statusClasses[$lead->status] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                @endphp
                <span
                    class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full ring-1 {{ $currentClasses }}">
                    {{ $lead->status }}
                </span>
            </div>

            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400 group-hover:bg-brand-50 group-hover:text-brand-600 transition-colors duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3
                        class="text-lg font-black text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">
                        {{ $lead->name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $lead->source ??
                        'Unknown Source' }}</p>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                @if($lead->phone)
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-dark-muted font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    {{ $lead->phone }}
                </div>
                @endif

                <p class="text-xs text-ui-muted dark:text-dark-muted line-clamp-2 leading-relaxed italic">
                    "{{ $lead->work_description ?? 'No description provided.' }}"
                </p>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-50 dark:border-dark-border">
                <div class="flex -space-x-2">
                    @if($lead->assignedTo)
                    <div class="w-8 h-8 rounded-full bg-brand-50 border-2 border-white dark:border-dark-surface flex items-center justify-center text-[10px] font-black text-brand-600 uppercase"
                        title="Assigned to {{ $lead->assignedTo->name }}">
                        {{ substr($lead->assignedTo->name, 0, 1) }}
                    </div>
                    @endif
                </div>
                <a href="{{ route('pitch.leads.show', $lead->id) }}"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-600 transition-colors flex items-center gap-1.5 group/btn">
                    View Dossier
                    <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div
            class="lg:col-span-3 p-20 text-center bg-white dark:bg-dark-surface rounded-[3rem] border-2 border-dashed border-slate-100 dark:border-dark-border">
            <div
                class="w-20 h-20 bg-slate-50 dark:bg-dark-bg rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">No Active Pitch Leads</h3>
            <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto">Your pitching funnel is currently empty. Start
                by entering a new professional lead to begin the conversion process.</p>
            <a href="{{ route('pitch.leads.create') }}"
                class="bg-brand-600 text-white px-10 py-4 rounded-[2rem] text-xs font-black uppercase tracking-[0.25em] shadow-2xl shadow-brand-500/30 hover:bg-brand-700 transition-all inline-flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Initiate First Lead
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection