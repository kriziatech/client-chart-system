@extends('layouts.app')

@section('content')
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        tr {
            page-break-inside: avoid;
        }
    }
</style>

<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    {{-- Project Header --}}
    <div
        class="bg-white dark:bg-dark-surface border-b border-slate-100 dark:border-dark-border px-8 py-5 flex flex-col md:flex-row justify-between items-center no-print transition-all rounded-t-[32px] gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-display">Project Dossier</h1>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $client->file_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}"
                class="px-4 py-2 text-slate-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white text-sm font-semibold transition font-display">Back</a>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.edit', $client) }}"
                class="bg-white dark:bg-slate-800 text-slate-700 dark:text-white border border-slate-200 dark:border-slate-700 px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-slate-50 transition">Edit</a>
            @endif
            <a href="{{ route('finance.analytics', $client) }}"
                class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-rose-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                    </path>
                </svg>
                Financial Analysis
            </a>
            <a href="{{ route('portal.show', $client->uuid) }}" target="_blank"
                class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20 transition">Portal</a>
            <a href="{{ route('clients.print', $client) }}" target="_blank"
                class="bg-slate-900 hover:bg-black text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg transition">Print</a>
        </div>
    </div>

    <div class="p-8 space-y-8 bg-white dark:bg-dark-surface rounded-b-[32px] shadow-premium">
        {{-- Project Lifecycle Visualization --}}
        <x-project-lifecycle :client="$client" />

        {{-- AI Insights & Health --}}
        @php
        $risk = $client->risk_analysis;
        $riskLevel = $risk['level'];
        @endphp
        <div
            class="bg-slate-50/50 dark:bg-slate-900/50 rounded-3xl p-8 border border-slate-100 dark:border-dark-border relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/5 blur-[100px] -mr-32 -mt-32 rounded-full"></div>
            <div class="flex flex-col md:flex-row items-start justify-between gap-8 relative z-10">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2.5 bg-brand-500/10 rounded-xl">
                            <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">AI Project
                                Intelligence</h3>
                            <p class="text-sm text-slate-500 dark:text-dark-muted font-medium mt-0.5">Predictive health
                                analysis & risk assessment</p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6 mt-8">
                        <div class="space-y-4">
                            <h4 class="text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Synthesized Risk Factors</h4>
                            <ul class="space-y-3">
                                @forelse($risk['reasons'] as $reason)
                                <li class="flex items-start gap-3">
                                    <div
                                        class="mt-1.5 w-1.5 h-1.5 rounded-full {{ $riskLevel == 'High' ? 'bg-rose-500' : ($riskLevel == 'Medium' ? 'bg-amber-500' : 'bg-emerald-500') }}">
                                    </div>
                                    <span
                                        class="text-[14px] text-slate-600 dark:text-slate-300 font-medium leading-relaxed">{{
                                        $reason }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-slate-400 italic">No critical risk dependencies identified.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="space-y-4">
                            <h4 class="text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Timeline Projections</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-white dark:bg-dark-bg p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">
                                        Target End</div>
                                    <div class="text-sm font-bold text-slate-800 dark:text-white">
                                        {{ now()->addDays($risk['projected_delay'] + ($client->delivery_date ?
                                        now()->diffInDays($client->delivery_date) : 0))->format('d M, Y') }}
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-dark-bg p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">
                                        Schedule Variance</div>
                                    <div
                                        class="text-sm font-bold {{ $risk['projected_delay'] > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                        {{ $risk['projected_delay'] > 0 ? '+' . round($risk['projected_delay']) . '
                                        Days' : 'On Track' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center p-8 bg-white dark:bg-dark-bg rounded-[40px] border border-slate-100 dark:border-dark-border min-w-[200px] shadow-sm">
                    <div class="relative flex items-center justify-center">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent"
                                class="text-slate-100 dark:text-slate-800" />
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent"
                                stroke-dasharray="351.85" stroke-dashoffset="{{ 351.85 * (1 - $risk['score']/100) }}"
                                class="{{ $riskLevel == 'High' ? 'text-rose-500' : ($riskLevel == 'Medium' ? 'text-amber-500' : 'text-emerald-500') }} transition-all duration-1000" />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white font-display">{{
                                $risk['score'] }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Health</span>
                        </div>
                    </div>
                    <div
                        class="mt-4 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $riskLevel == 'High' ? 'bg-rose-500 text-white' : ($riskLevel == 'Medium' ? 'bg-amber-500 text-white' : 'bg-emerald-500 text-white') }}">
                        {{ $riskLevel }} Risk
                    </div>
                </div>
            </div>
        </div>

        {{-- Financial Summary Cards --}}
        @php
        $approvedTotal = $client->quotations->where('status', 'approved')->sum('total_amount');
        $pendingTotal = $client->quotations->where('status', 'sent')->sum('total_amount');
        $paidTotal = $client->payments->sum('amount');
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print">
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium group">
                <div
                    class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600 mb-4 dark:bg-brand-500/10 dark:text-brand-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Authorized Budget</h4>
                <div class="text-2xl font-bold text-slate-900 dark:text-white mt-1 font-display">₹{{
                    number_format($approvedTotal) }}</div>
                <div class="mt-3 text-[12px] text-slate-500 font-medium">Work officially approved by client.</div>
            </div>
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <div
                    class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Collected Capital</h4>
                <div class="text-2xl font-bold text-emerald-600 mt-1 font-display">₹{{ number_format($paidTotal) }}
                </div>
                <div class="mt-3 text-[12px] text-slate-500 font-medium">Total payments cleared in ledger.</div>
            </div>
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium border-l-4 {{ $pendingTotal > 0 ? 'border-amber-500' : 'border-slate-100' }}">
                <div
                    class="w-10 h-10 {{ $pendingTotal > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-400' }} rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Awaiting Approval</h4>
                <div
                    class="text-2xl font-bold {{ $pendingTotal > 0 ? 'text-amber-600' : 'text-slate-300' }} mt-1 font-display">
                    ₹{{ number_format($pendingTotal) }}</div>
                @if($pendingTotal > 0)
                <div class="mt-3 text-[11px] text-amber-600 font-bold animate-pulse">ACTION REQUIRED: QUOTATION SENT
                </div>
                @else
                <div class="mt-3 text-[12px] text-slate-400 italic">No pending quotations.</div>
                @endif
            </div>
        </div>

        {{-- Client & Project Info Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Client Info --}}
            <div
                class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-brand-500/10 rounded-xl">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Client Configuration</h3>
                </div>
                <div class="space-y-4">
                    <div
                        class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-dark-border/50">
                        <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Identified
                            As</span>
                        <span class="text-[15px] font-bold text-slate-800 dark:text-white">{{ $client->first_name }} {{
                            $client->last_name }}</span>
                    </div>
                    <div
                        class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-dark-border/50">
                        <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Contact
                            Point</span>
                        <span class="text-[15px] font-bold text-slate-800 dark:text-white">{{ $client->mobile }}</span>
                    </div>
                    <div class="flex justify-between items-start py-3">
                        <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Site
                            Location</span>
                        <span
                            class="text-[14px] font-medium text-slate-600 dark:text-slate-300 text-right max-w-[200px] leading-relaxed">{{
                            $client->address }}</span>
                    </div>
                </div>
            </div>

            {{-- Project Schedule --}}
            <div
                class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-brand-500/10 rounded-xl">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Project Timeline</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div
                        class="p-5 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-slate-100 dark:border-dark-border">
                        <div class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Initialization
                        </div>
                        <div class="text-[15px] font-bold text-slate-800 dark:text-white">{{ $client->start_date ?
                            $client->start_date->format('d M, Y') : '-' }}</div>
                    </div>
                    <div
                        class="p-5 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-slate-100 dark:border-dark-border">
                        <div class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Target Turnover
                        </div>
                        <div class="text-[15px] font-bold text-slate-800 dark:text-white">{{ $client->delivery_date ?
                            $client->delivery_date->format('d M, Y') : '-' }}</div>
                    </div>
                    <div
                        class="col-span-2 p-5 bg-slate-50/50 dark:bg-dark-bg/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                        <div class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Execution Brief
                        </div>
                        <p class="text-[14px] font-medium text-slate-600 dark:text-slate-300 leading-relaxed">{{
                            $client->work_description }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Checklist and Authorizations --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div
                class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mb-6">Validation Checklist</h3>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($client->checklistItems as $item)
                    <div
                        class="flex items-center gap-3 p-3 rounded-xl border {{ $item->is_checked ? 'bg-brand-50 border-brand-100 dark:bg-brand-500/10 dark:border-brand-500/20' : 'bg-slate-50 border-slate-100 dark:bg-dark-bg dark:border-dark-border' }}">
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center {{ $item->is_checked ? 'bg-brand-500 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-400' }}">
                            @if($item->is_checked)<svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>@endif
                        </div>
                        <span
                            class="text-[13px] font-bold {{ $item->is_checked ? 'text-brand-900 dark:text-brand-100' : 'text-slate-500' }}">{{
                            $item->name }}</span>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-4 text-slate-400 italic text-sm">No checklist defined.</div>
                    @endforelse
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mb-6">Authorizations</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $client->permission && $client->permission->work_permit ? 'text-emerald-500' : 'text-slate-300' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <span class="text-[14px] font-bold text-slate-700 dark:text-slate-200">Site Work
                                Permit</span>
                        </div>
                        <span
                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $client->permission && $client->permission->work_permit ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $client->permission && $client->permission->work_permit ? 'Authenticated' : 'Pending' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $client->permission && $client->permission->gate_pass ? 'text-emerald-500' : 'text-slate-300' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                            <span class="text-[14px] font-bold text-slate-700 dark:text-slate-200">Gate Access
                                Clearance</span>
                        </div>
                        <span
                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $client->permission && $client->permission->gate_pass ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $client->permission && $client->permission->gate_pass ? 'Authenticated' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scope of Mandate --}}
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-brand-500/10 rounded-xl">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Scope of Mandate</h3>
                        <p class="text-sm text-slate-500 dark:text-dark-muted font-medium mt-0.5">Categorized work
                            distribution units</p>
                    </div>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                <button onclick="document.getElementById('add-scope-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition">Add
                    Unit</button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($client->scopeOfWork)
                @forelse($client->scopeOfWork->items as $work)
                <div
                    class="bg-white dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium group hover:border-brand-500/30 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="px-3 py-1 bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 rounded-lg text-[10px] font-bold uppercase tracking-widest">
                            {{ $work->area_name }}
                        </div>
                    </div>
                    <p class="text-[13px] text-slate-500 dark:text-dark-muted font-medium leading-relaxed">{{
                        $work->description }}</p>
                </div>
                @empty
                <div
                    class="col-span-full py-12 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-400 font-medium font-display">No scope units defined in version: {{
                        $client->scopeOfWork->version_name }}</p>
                </div>
                @endforelse
                @else
                <div
                    class="col-span-full py-12 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-400 font-medium font-display mb-4">No scope units initialized yet.</p>
                    @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                    <form action="{{ route('scope.store', $client) }}" method="POST">@csrf<button type="submit"
                            class="text-[10px] font-black uppercase text-brand-600 hover:underline">Initialize Version
                            1.0</button></form>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Tabbed Data Section --}}
        <div x-data="{ activeTab: 'payments' }" class="mb-12">
            <div class="flex items-center gap-2 mb-8 p-1.5 bg-slate-100/50 dark:bg-slate-800/50 rounded-2xl w-fit">
                <button @click="activeTab = 'payments'"
                    :class="activeTab === 'payments' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display">Payments</button>
                <button @click="activeTab = 'quotations'"
                    :class="activeTab === 'quotations' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display">Quotations</button>
                <button @click="activeTab = 'materials'"
                    :class="activeTab === 'materials' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display">Materials</button>
                <button @click="activeTab = 'tasks'"
                    :class="activeTab === 'tasks' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display">Timeline</button>
            </div>

            {{-- Payments Tab --}}
            <div x-show="activeTab === 'payments'" class="animate-in fade-in duration-500">
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Transaction ID</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Cleared On</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Settlement Mode</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->payments as $payment)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4 font-bold text-slate-900 dark:text-white text-[14px] font-display">
                                    #{{ $payment->id }}</td>
                                <td class="px-7 py-4 text-[14px] text-slate-500 dark:text-dark-muted font-medium">{{
                                    $payment->payment_date ? $payment->payment_date->format('d M, Y') : '-' }}</td>
                                <td class="px-7 py-4"><span
                                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[11px] font-bold uppercase text-slate-500">Digital
                                        Clearing</span></td>
                                <td class="px-7 py-4 text-right font-bold text-emerald-600 text-[15px] font-display">₹{{
                                    number_format($payment->amount) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-7 py-12 text-center text-slate-400 italic">Financial ledger
                                    initialization required.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quotations Tab --}}
            <div x-show="activeTab === 'quotations'" x-cloak class="animate-in fade-in duration-500">
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Doc Reference</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Submission</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Status</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Valuation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->quotations as $quotation)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4 font-bold text-slate-900 dark:text-white text-[14px] font-display">
                                    {{ $quotation->quotation_number }}</td>
                                <td class="px-7 py-4 text-[14px] text-slate-500 dark:text-dark-muted font-medium">{{
                                    $quotation->created_at->format('d M, Y') }}</td>
                                <td class="px-7 py-4">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest {{ $quotation->status == 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $quotation->status }}
                                    </span>
                                </td>
                                <td
                                    class="px-7 py-4 text-right font-bold text-slate-900 dark:text-white text-[15px] font-display">
                                    ₹{{ number_format($quotation->total_amount) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-7 py-12 text-center text-slate-400 italic">No quotation
                                    records generated.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Materials Tab --}}
            <div x-show="activeTab === 'materials'" x-cloak class="animate-in fade-in duration-500">
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Material SKU</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Inventory Status</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Qty Provisioned</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->projectMaterials as $mat)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4 font-bold text-slate-800 dark:text-white text-[14px] font-display">
                                    {{ $mat->inventoryItem->name ?? 'Unknown item' }}</td>
                                <td class="px-7 py-4"><span
                                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[11px] font-bold text-slate-500 uppercase">{{
                                        $mat->status }}</span></td>
                                <td
                                    class="px-7 py-4 text-right font-bold text-slate-900 dark:text-white text-[15px] font-display">
                                    {{ $mat->quantity_dispatched }} {{ $mat->inventoryItem->unit ?? '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">Inventory
                                    allocation pending.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tasks Tab --}}
            <div x-show="activeTab === 'tasks'" x-cloak class="animate-in fade-in duration-500">
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Operational Assignment</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Timeline</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    State</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->tasks as $task)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white text-[14px] font-display">{{
                                        $task->description }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Agent: {{
                                        $task->assigned_to ?: 'Unassigned' }}</div>
                                </td>
                                <td
                                    class="px-7 py-4 text-[13px] {{ $task->deadline && $task->deadline->isPast() && $task->status !== 'Completed' ? 'text-rose-500 font-bold' : 'text-slate-500' }}">
                                    {{ $task->deadline ? $task->deadline->format('d M, Y') : '-' }}
                                </td>
                                <td class="px-7 py-4 text-right">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest {{ $task->status == 'Completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">No operational
                                    tasks assigned.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Operational Log --}}
        <div class="mb-12">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display mb-6 px-2">Operational Execution
                Log</h3>
            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                        <tr>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Date</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Execution Agent</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Status Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->comments->sortByDesc('date') as $comment)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td
                                class="px-7 py-4 text-[14px] text-slate-500 font-bold font-display uppercase tracking-tight">
                                {{ $comment->date }}</td>
                            <td class="px-7 py-4"><span
                                    class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400">{{
                                    $comment->initials }}</span></td>
                            <td class="px-7 py-4">
                                <div class="text-[14px] font-bold text-slate-800 dark:text-white">{{ $comment->work }}
                                </div>
                                <div class="text-[13px] text-slate-500 mt-0.5">{{ $comment->comment }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">No operational logs
                                recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Visual Documentation Gallery --}}
        <div class="pb-12">
            <div class="flex items-center justify-between mb-8 px-2">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Visual Site Documentation</h3>
                @if(!auth()->user()->isViewer())
                <button onclick="document.getElementById('upload-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-brand-500/20 hover:bg-brand-700 transition">Upload
                    Media</button>
                @endif
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($client->galleries as $image)
                <div
                    class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border shadow-sm">
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                        class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 px-2 text-center">
                        <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank"
                            class="p-2 bg-white/20 backdrop-blur rounded-lg text-white hover:bg-white/40"><svg
                                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg></a>
                    </div>
                </div>
                @empty
                <div
                    class="col-span-full py-12 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-400 font-medium font-display">No visual documentation available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@if(!auth()->user()->isViewer())
<div id="add-scope-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 dark:border-dark-border">
        <div
            class="px-8 py-6 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Initialize Work Unit</h3>
            <button onclick="document.getElementById('add-scope-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route('scope.store', $client) }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Category /
                        Trade</label>
                    <input type="text" name="category" required placeholder="e.g. Electrical, Plumbing"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-4 py-3 text-sm focus:ring-brand-500 transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Unit
                        Name</label>
                    <input type="text" name="unit_name" required placeholder="e.g. Master Bedroom"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-4 py-3 text-sm focus:ring-brand-500 transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Technical
                        Brief</label>
                    <textarea name="description" rows="3" placeholder="Scope details..."
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-4 py-3 text-sm focus:ring-brand-500 transition-all"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="document.getElementById('add-scope-modal').classList.add('hidden')"
                    class="px-6 py-3 text-slate-400 font-bold hover:text-slate-600 transition">Cancel</button>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-brand-500/20 transition">Save
                    Unit</button>
            </div>
        </form>
    </div>
</div>

<div id="upload-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 dark:border-dark-border">
        <div
            class="px-8 py-6 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Upload Media</h3>
            <button onclick="document.getElementById('upload-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route('gallery.store', $client) }}" method="POST" enctype="multipart/form-data"
            class="p-8 space-y-6">
            @csrf
            <div
                class="p-8 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl flex flex-col items-center justify-center gap-4 bg-slate-50/50">
                <div
                    class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl shadow-sm flex items-center justify-center text-brand-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <input type="file" name="image" required
                    class="text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-brand-50 file:text-brand-600 cursor-pointer">
            </div>
            <button type="submit"
                class="w-full bg-brand-500 hover:bg-brand-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-brand-500/20 transition active:scale-95">Start
                Upload</button>
        </form>
    </div>
</div>
@endif

@endsection