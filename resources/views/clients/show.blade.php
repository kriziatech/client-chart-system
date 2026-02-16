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

@php
$risk = $client->risk_analysis;
$riskLevel = $risk['level'];
$riskScore = $risk['score'];

// Journey Mapping (8 Stages)
$currentJourneyStage = (int) $client->journey_stage;
$journeyStageName = match($currentJourneyStage) {
1 => 'New Client',
2 => 'Site Visit',
3 => 'Quotation',
4 => 'Credit',
5 => 'Work Assigned',
6 => 'Timeline',
7 => 'Work Completed',
8 => 'Final Payment',
default => 'New Client',
};

$journeyColor = match($currentJourneyStage) {
8 => 'green',
7 => 'green',
default => ($riskScore > 60 ? 'red' : 'blue'),
};

$journeyProgress = ($currentJourneyStage / 8) * 100;

$journeyNextStep = match($currentJourneyStage) {
1 => 'Schedule a site visit to understand requirements.',
2 => 'Create a BOQ (Quotation) for the client.',
3 => 'Follow up for advance payment (Credit).',
4 => 'Assign team and create initial tasks.',
5 => 'Lock the project timeline/delivery date.',
6 => 'Monitor execution and daily DPRs.',
7 => 'Collect final payment and initiate handover.',
8 => 'Project successfully closed. Collect feedback.',
default => 'Proceed to next phase.',
};

$ctaLabel = match($currentJourneyStage) {
1 => 'Set Site Info',
2 => 'Create BOQ',
3 => 'View Estimates',
4 => 'Assign Team',
5 => 'Set Timeline',
6 => 'Track Execution',
7 => 'Collect Final',
8 => 'Issue Handover',
default => 'Next Step',
};

$ctaAction = match($currentJourneyStage) {
1 => "activeTab = 'overview'",
2 => "activeTab = 'quotations'",
3 => "activeTab = 'quotations'",
4 => "activeTab = 'tasks'",
5 => "activeTab = 'overview'",
6 => "window.location = '" . route('reports.index', $client->id) . "'",
7 => "activeTab = 'payments'",
8 => "activeTab = 'handover'",
default => "activeTab = 'overview'",
};

$initialTab = match($currentJourneyStage) {
1, 2 => 'overview',
3 => 'quotations',
4 => 'payments',
5 => 'overview',
6 => 'tasks',
7 => 'payments',
8 => 'handover',
default => 'overview',
};
@endphp

<div class="animate-in fade-in slide-in-from-bottom-4 duration-700" x-data="{ activeTab: '{{ $initialTab }}' }"
    x-on:switch-tab.window="activeTab = $event.detail">
    <x-journey-header :stage="'Journey: ' . $journeyStageName" :nextStep="$journeyNextStep" :progress="$journeyProgress"
        :statusColor="$journeyColor" :ctaLabel="$ctaLabel" :ctaAction="$ctaAction" />

    {{-- Project Dossier Header --}}
    <div
        class="bg-white dark:bg-dark-surface border-b border-slate-100 dark:border-dark-border px-8 py-5 flex flex-col md:flex-row justify-between items-center no-print transition-all gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-display">{{ $client->first_name }}
                    {{ $client->last_name }}</h1>
                <span @class([ 'px-2 py-0.5 text-[10px] font-black rounded uppercase tracking-widest'
                    , 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'=> $client->status ==
                    'Sales',
                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' =>
                    $client->status == 'Work in Progress',
                    'bg-slate-100 text-slate-700 dark:bg-slate-900/30 dark:text-slate-400' => $client->status ==
                    'Completed',
                    ])>
                    {{ $client->status ?? 'Sales' }}
                </span>
            </div>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">FILE REF: {{
                $client->file_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}"
                class="px-4 py-2 text-slate-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white text-sm font-semibold transition font-display">Back</a>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.edit', $client) }}"
                class="bg-white dark:bg-slate-800 text-slate-700 dark:text-white border border-slate-200 dark:border-slate-700 px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-slate-50 transition">Edit
                Details</a>
            @endif
            <a href="{{ route('clients.print', $client) }}" target="_blank"
                class="bg-slate-900 hover:bg-black text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg transition">Print
                Brief</a>
        </div>
    </div>

    <div class="p-8 space-y-8 bg-white dark:bg-dark-surface rounded-b-[32px] shadow-premium">

        {{-- Financial Control Room Summary --}}
        @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
        <div
            class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-dark-border mb-8 relative">
            {{-- Download Ledger Button --}}
            <a href="{{ route('finance.ledger.download', $client) }}" target="_blank"
                class="absolute top-4 right-4 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-500 transition flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ledger PDF
            </a>

            <div class="space-y-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Client Received</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white">₹{{
                    number_format($client->total_client_received) }}</div>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Vendor Paid</span>
                <div class="text-2xl font-black text-rose-500">₹{{ number_format($client->total_vendor_paid) }}</div>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Material Cost</span>
                <div class="text-2xl font-black text-amber-500">₹{{ number_format($client->total_material_cost) }}</div>
            </div>
            <div class="space-y-1 pl-4 border-l border-slate-200 dark:border-slate-700">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Real-Time Profit</span>
                <div class="text-3xl font-black text-emerald-600">₹{{ number_format($client->real_time_profit) }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    @if($client->real_time_profit > 0) <span class="text-emerald-500">Safe 🟢</span>
                    @elseif($client->real_time_profit < 0) <span class="text-rose-500">Loss 🔴</span>
                        @else <span class="text-amber-500">Neutral 🟡</span> @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Workspace Tab Control --}}
        <div
            class="flex items-center gap-2 mb-2 p-1.5 bg-slate-100/50 dark:bg-slate-800/50 rounded-2xl w-fit overflow-x-auto no-scrollbar border border-slate-200 dark:border-dark-border">

            {{-- Overview Tab --}}
            <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap">Overview</button>

            {{-- Estimates Tab --}}
            <button @click="activeTab = 'quotations'"
                :class="activeTab === 'quotations' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap">Estimates</button>

            {{-- Execution Tab (Locked until Stage 4) --}}
            @php $tasksLocked = $currentJourneyStage < 4; @endphp <button @if(!$tasksLocked)
                @click="activeTab = 'tasks'" @endif
                :class="activeTab === 'tasks' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2 {{ $tasksLocked ? 'opacity-40 cursor-not-allowed grayscale' : '' }}">
                @if($tasksLocked)
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                @endif
                Execution
                </button>



                {{-- Inventory Tab (Locked until Stage 5) --}}
                @php $inventoryLocked = $currentJourneyStage < 5; @endphp <button @if(!$inventoryLocked)
                    @click="activeTab = 'materials'" @endif
                    :class="activeTab === 'materials' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2 {{ $inventoryLocked ? 'opacity-40 cursor-not-allowed grayscale' : '' }}">
                    @if($inventoryLocked)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    @endif
                    Inventory
                    </button>

                    {{-- Financials Tab (Locked until Stage 3) --}}
                    @php $paymentsLocked = $currentJourneyStage < 3; @endphp <button @if(!$paymentsLocked)
                        @click="activeTab = 'payments'" @endif
                        :class="activeTab === 'payments' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                        class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2 {{ $paymentsLocked ? 'opacity-40 cursor-not-allowed grayscale' : '' }}">
                        @if($paymentsLocked)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        @endif
                        Financials
                        </button>

                        {{-- Attendance Tab (Locked until Stage 5) --}}
                        @php $attendanceLocked = $currentJourneyStage < 5; @endphp <button @if(!$attendanceLocked)
                            @click="activeTab = 'attendance'" @endif
                            :class="activeTab === 'attendance' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                            class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2 {{ $attendanceLocked ? 'opacity-40 cursor-not-allowed grayscale' : '' }}">
                            @if($attendanceLocked)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            @endif
                            Attendance
                            </button>

                            {{-- Handover Tab (Locked until Stage 5) --}}
                            @php $handoverLocked = $currentJourneyStage < 5; @endphp <button @if(!$handoverLocked)
                                @click="activeTab = 'handover'" @endif
                                :class="activeTab === 'handover' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2 {{ $handoverLocked ? 'opacity-40 cursor-not-allowed grayscale' : '' }}">
                                @if($handoverLocked)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                @endif
                                Handover
                                </button>
        </div>

        {{-- Tab Content Containers --}}

        {{-- 1. Overview Tab --}}
        <div x-show="activeTab === 'overview'" x-cloak class="animate-in fade-in duration-500 space-y-8">
            {{-- Project Lifecycle --}}
            <x-project-lifecycle :client="$client" />

            {{-- AI Project Intelligence --}}
            <div
                class="bg-slate-50/50 dark:bg-slate-900/50 rounded-3xl p-8 border border-slate-100 dark:border-dark-border relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/5 blur-[100px] -mr-32 -mt-32 rounded-full">
                </div>
                <div class="flex flex-col md:flex-row items-start justify-between gap-8 relative z-10">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-brand-500/10 rounded-xl">
                                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">AI Project
                                    Intelligence</h3>
                                <p class="text-sm text-slate-500 dark:text-dark-muted font-medium mt-0.5">Predictive
                                    health analysis & risk assessment</p>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6 mt-8">
                            <div class="space-y-4">
                                <h4
                                    class="text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
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
                                    <li class="text-sm text-slate-400 italic">No critical risk dependencies
                                        identified.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="space-y-4">
                                <h4
                                    class="text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Timeline Projections</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="bg-white dark:bg-dark-bg p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                                        <div
                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">
                                            Target End</div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-white">
                                            {{ now()->addDays($risk['projected_delay'] + ($client->delivery_date ?
                                            now()->diffInDays($client->delivery_date) : 0))->format('d M, Y') }}
                                        </div>
                                    </div>
                                    <div
                                        class="bg-white dark:bg-dark-bg p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                                        <div
                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">
                                            Schedule Variance</div>
                                        <div
                                            class="text-sm font-bold {{ $risk['projected_delay'] > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                            {{ $risk['projected_delay'] > 0 ? '+' . round($risk['projected_delay'])
                                            . ' Days' : 'On Track' }}
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
                                    stroke-dasharray="351.85"
                                    stroke-dashoffset="{{ 351.85 * (1 - $risk['score']/100) }}"
                                    class="{{ $riskLevel == 'High' ? 'text-rose-500' : ($riskLevel == 'Medium' ? 'text-amber-500' : 'text-emerald-500') }} transition-all duration-1000" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span class="text-3xl font-bold text-slate-900 dark:text-white font-display">{{
                                    $risk['score'] }}</span>
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Health</span>
                            </div>
                        </div>
                        <div
                            class="mt-4 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $riskLevel == 'High' ? 'bg-rose-500 text-white' : ($riskLevel == 'Medium' ? 'bg-amber-500 text-white' : 'bg-emerald-500 text-white') }}">
                            {{ $riskLevel }} Risk
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lock Toggle UI Concept --}}
            <div class="mt-8 p-6 bg-white/5 rounded-3xl border border-white/10 flex items-center justify-between">
                <div>
                    <div class="font-bold text-lg mb-1">Project Lock Status</div>
                    <div class="text-sm text-slate-400">Locking prevents further unauthorized expenses.</div>
                </div>
                <div>
                    <button onclick="document.getElementById('profit-lock-modal').classList.remove('hidden')"
                        class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg transition hover:scale-105 {{ $client->financials->is_locked ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white' }}">
                        {{ $client->financials->is_locked ? 'LOCKED 🔐' : 'UNLOCKED 🔓' }}
                    </button>
                </div>
            </div>

            {{-- Client & Site Details --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div
                    class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mb-6">Site
                        Configuration</h3>
                    <div class="space-y-4">
                        <div
                            class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-dark-border/50">
                            <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Owner</span>
                            <span class="text-[15px] font-bold text-slate-800 dark:text-white">{{
                                $client->first_name }} {{ $client->last_name }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-dark-border/50">
                            <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Contact</span>
                            <span class="text-[15px] font-bold text-slate-800 dark:text-white">{{ $client->mobile
                                }}</span>
                        </div>
                        <div class="flex justify-between items-start py-3">
                            <span class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Location</span>
                            <span
                                class="text-[14px] font-medium text-slate-600 dark:text-slate-300 text-right max-w-[200px] leading-relaxed">{{
                                $client->address }}</span>
                        </div>
                    </div>
                </div>

                {{-- Project Resources (Team) --}}
                <div
                    class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Project Resources</h3>
                        <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Team
                            Active</span>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl">
                            <div
                                class="w-12 h-12 rounded-xl bg-brand-500 text-white flex items-center justify-center font-bold text-lg">
                                {{ substr($client->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Project
                                    Manager</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $client->user->name
                                    ?? 'Not Assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mb-6">Schedule & Timeline
                    </h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Delivery Date
                                </p>
                                <p class="text-[15px] font-bold text-slate-800 dark:text-white mt-1">
                                    {{ $client->delivery_date ? $client->delivery_date->format('d M, Y') : 'Not
                                    Scheduled' }}
                                </p>
                            </div>
                            @if(!$client->delivery_date)
                            <a href="{{ route('clients.edit', $client) }}"
                                class="text-[10px] font-black text-brand-600 hover:text-brand-700 uppercase tracking-widest underline decoration-brand-500/30 underline-offset-4">
                                Set Date
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mb-6">Authorizations
                    </h3>
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
                                {{ $client->permission && $client->permission->work_permit ? 'Authenticated' :
                                'Pending' }}
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
                                {{ $client->permission && $client->permission->gate_pass ? 'Authenticated' :
                                'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scope Mandate --}}
            <div
                class="bg-white dark:bg-slate-900/40 p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Scope of Mandate</h3>
                    <button @click="document.getElementById('add-scope-modal').classList.remove('hidden')"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-white rounded-xl text-xs font-bold hover:bg-slate-200 transition">Add
                        Unit</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if($client->scopeOfWork)
                    @foreach($client->scopeOfWork->items as $work)
                    <div
                        class="p-6 bg-slate-50 dark:bg-dark-bg/60 rounded-3xl border border-slate-100 dark:border-dark-border group hover:border-brand-500/30 transition-all">
                        <div class="text-[10px] font-black uppercase tracking-widest text-brand-600 mb-2">{{
                            $work->area_name }}</div>
                        <p class="text-[13px] text-slate-600 dark:text-slate-300 font-medium leading-relaxed">{{
                            $work->description }}</p>
                    </div>
                    @endforeach
                    @else
                    <div
                        class="col-span-full py-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
                        <p class="text-slate-400 italic">No scope units initialized yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2. Estimates Tab --}}
        <div x-show="activeTab === 'quotations'" x-cloak class="animate-in fade-in duration-500 space-y-6">
            {{-- Material Inward Section --}}
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Material Procurement
                        (Inward)</h3>
                    <button onclick="document.getElementById('material-inward-modal').classList.remove('hidden')"
                        class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 p-2 rounded-xl hover:scale-105 transition shadow-lg shadow-slate-900/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Financial Estimates (BOQ)
                </h3>
                <a href="{{ route('quotations.create', ['client_id' => $client->id]) }}"
                    class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 hover:bg-brand-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    New Quotation
                </a>
            </div>
            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                        <tr>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Doc Reference</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Created At</th>
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
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all cursor-pointer"
                            onclick="window.location='{{ route('quotations.show', $quotation->id) }}'">
                            <td class="px-7 py-4 font-bold text-slate-900 dark:text-white text-[14px] font-display">
                                {{ $quotation->quotation_number }} <span
                                    class="ml-2 text-[10px] bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-slate-500">v{{
                                    $quotation->version }}</span>
                            </td>
                            <td class="px-7 py-4 text-[14px] text-slate-500 font-medium">{{
                                $quotation->created_at->format('d M, Y') }}</td>
                            <td class="px-7 py-4">
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest @if($quotation->status == 'approved') bg-emerald-50 text-emerald-600 @else bg-amber-50 text-amber-600 @endif">
                                    {{ $quotation->status }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right font-bold text-slate-900 dark:text-white text-[15px]">
                                ₹{{ number_format($quotation->total_amount) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-7 py-12 text-center text-slate-400 italic">No records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. Execution Tab --}}
        <div x-show="activeTab === 'tasks'" x-cloak class="animate-in fade-in duration-500 space-y-8">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Operational Execution</h3>
                <div class="flex gap-2">
                    @if(!auth()->user()->isViewer() && !auth()->user()->isClient() && !auth()->user()->isSales())
                    <button onclick="document.getElementById('add-dpr-modal').classList.remove('hidden')"
                        class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition flex items-center gap-2 shadow-lg shadow-brand-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Log Progress
                    </button>
                    @endif
                    <a href="{{ route('reports.index', $client->id) }}"
                        class="px-5 py-2.5 bg-brand-50 text-brand-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-100 transition flex items-center gap-2">
                        View Dossier
                    </a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                        <tr>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Task Description</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Deadline</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->tasks as $task)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td class="px-7 py-4">
                                <div class="font-bold text-slate-800 dark:text-white text-[14px]">{{
                                    $task->description }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Agent: {{
                                    $task->assigned_to ?: 'Unassigned' }}</div>
                            </td>
                            <td class="px-7 py-4 text-[13px] text-slate-500">{{ $task->deadline ?
                                $task->deadline->format('d M, Y') : '-' }}</td>
                            <td class="px-7 py-4 text-right">
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest @if($task->status == 'Completed') bg-emerald-50 text-emerald-600 @else bg-slate-100 text-slate-500 @endif">
                                    {{ $task->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">No tasks assigned.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Materials Tab --}}
        <div x-show="activeTab === 'materials'" x-cloak class="animate-in fade-in duration-500 space-y-12">

            {{-- Material Inward Section --}}
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Material Procurement
                        (Inward)</h3>
                    <button onclick="document.getElementById('material-inward-modal').classList.remove('hidden')"
                        class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 p-2 rounded-xl hover:scale-105 transition shadow-lg shadow-slate-900/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
                <!-- Inward Table -->
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Date</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Supplier</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Item</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Total</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Paid</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->materialInwards as $inward)
                            @php
                            $paid = $inward->payments->sum('amount_paid');
                            $pending = $inward->total_amount - $paid;
                            @endphp
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4 text-[13px] text-slate-500">{{ $inward->inward_date->format('d M,
                                    Y') }}</td>
                                <td class="px-7 py-4 text-[14px] font-bold text-slate-800 dark:text-white">{{
                                    $inward->supplier_name }}</td>
                                <td class="px-7 py-4 text-[13px] text-slate-500">{{ $inward->item_name }} ({{
                                    $inward->quantity }} {{ $inward->unit }})</td>
                                <td class="px-7 py-4 text-right font-bold text-slate-800 dark:text-gray-300">₹{{
                                    number_format($inward->total_amount) }}</td>
                                <td class="px-7 py-4 text-right font-bold text-emerald-600">₹{{ number_format($paid) }}
                                </td>
                                <td class="px-7 py-4 text-right">
                                    @if($pending > 0.1)
                                    <button
                                        onclick="openMaterialPaymentModal({{ $inward->id }}, '{{ $inward->supplier_name }}', {{ $pending }})"
                                        class="px-3 py-1 bg-slate-900 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-slate-700 transition">
                                        Pay
                                    </button>
                                    @else
                                    <span class="text-[10px] font-bold text-emerald-500 uppercase">Paid ✔</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-7 py-12 text-center text-slate-400 italic">No material inward
                                    records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Site Allocation Section (Existing) --}}
            <div class="space-y-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Inventory Allocation (Site)
                </h3>
                <div
                    class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                            <tr>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Item SKU</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                    Status</th>
                                <th
                                    class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                    Qty Dispatched</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                            @forelse($client->projectMaterials as $mat)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                                <td class="px-7 py-4 font-bold text-slate-800 dark:text-white text-[14px]">{{
                                    $mat->inventoryItem->name ?? 'Unknown' }}</td>
                                <td class="px-7 py-4"><span
                                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[11px] font-bold uppercase text-slate-500">{{
                                        $mat->status }}</span></td>
                                <td class="px-7 py-4 text-right font-bold text-slate-900 dark:text-white text-[15px]">{{
                                    $mat->quantity_dispatched }} {{ $mat->inventoryItem->unit ?? '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">No inventory
                                    records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 5. Financials Tab --}}
        <div x-show="activeTab === 'payments'" x-cloak class="animate-in fade-in duration-500 space-y-8">
            {{-- Capital Management --}}
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Capital Management</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Client Cash Flow &
                        Budget Tracking</p>
                </div>
                @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
                <button onclick="document.getElementById('client-payment-modal').classList.remove('hidden')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-emerald-500/20">
                    Record Client Payment
                </button>
                @endif
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Total
                        Project Budget</span>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">₹{{
                        number_format($client->total_budget) }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase mt-2">Sum of approved quotations</div>
                </div>
                <div
                    class="bg-white dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Total
                        Received</span>
                    <div class="text-2xl font-black text-emerald-600">₹{{ number_format($client->total_client_received)
                        }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase mt-2">Internal ledger credits</div>
                </div>
                <div
                    class="bg-white dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Outstanding
                        Balance</span>
                    <div
                        class="text-2xl font-black {{ $client->outstanding_balance > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                        ₹{{ number_format($client->outstanding_balance) }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase mt-2">Amount yet to be paid</div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                        <tr>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Journal Ref</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Date</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Purpose</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->payments as $payment)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td class="px-7 py-4">
                                <div class="font-bold text-slate-900 dark:text-white text-[14px]">#PAY-{{ $payment->id
                                    }}</div>
                                <div class="text-[10px] text-slate-400 font-black uppercase">{{ $payment->receipt_number
                                    }}</div>
                            </td>
                            <td class="px-7 py-4 text-[14px] text-slate-500 font-medium">{{ $payment->date ?
                                $payment->date->format('d M, Y') : '-' }}</td>
                            <td class="px-7 py-4 text-[13px] text-slate-600 dark:text-slate-400 font-bold">{{
                                $payment->purpose }}</td>
                            <td class="px-7 py-4 text-right font-bold text-emerald-600 text-[15px]">₹{{
                                number_format($payment->amount) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-7 py-12 text-center text-slate-400 italic">No payment logs
                                found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
            {{-- Vendor Ledger --}}
            <div class="flex items-center justify-between mt-12">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display uppercase tracking-widest">
                    Vendor Ledger</h3>
                <button onclick="document.getElementById('vendor-payment-modal').classList.remove('hidden')"
                    class="bg-slate-900 hover:bg-black text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg">
                    Record Vendor Payment
                </button>
            </div>
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
                                Vendor</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Type</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->vendorPayments as $vp)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td class="px-7 py-4 text-[13px] font-medium text-slate-500">{{
                                $vp->payment_date->format('d M, Y') }}</td>
                            <td class="px-7 py-4">
                                <div class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $vp->vendor->name
                                    }}
                                </div>
                                <div class="text-[10px] text-slate-400 uppercase font-black">{{ $vp->vendor->category }}
                                </div>
                            </td>
                            <td class="px-7 py-4"><span
                                    class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-[10px] font-black uppercase text-slate-500">{{
                                    $vp->work_type }}</span></td>
                            <td class="px-7 py-4 text-right font-bold text-rose-500 text-[14px]">₹{{
                                number_format($vp->amount) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-slate-400 italic text-sm">No vendor
                                payments recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Material Procurement --}}
            <div class="flex items-center justify-between mt-12">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display uppercase tracking-widest">
                    Material Procurement</h3>
                <button onclick="document.getElementById('material-inward-modal').classList.remove('hidden')"
                    class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-brand-500/20">
                    Record Material Inward
                </button>
            </div>
            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                        <tr>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Prop Date</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                                Supplier & Item</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                Total Bill</th>
                            <th
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-center">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->materialInwards as $mi)
                        @php
                        $paid = $mi->payments->sum('amount_paid');
                        $isPaid = $paid >= $mi->total_amount;
                        @endphp
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td class="px-7 py-4 text-[13px] font-medium text-slate-500">{{
                                $mi->inward_date->format('d M, Y') }}</td>
                            <td class="px-7 py-4">
                                <div class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $mi->supplier_name
                                    }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $mi->item_name }} ({{
                                    (float)$mi->quantity }} {{ $mi->unit }})</div>
                            </td>
                            <td class="px-7 py-4 text-right font-bold text-slate-900 dark:text-white text-[14px]">₹{{
                                number_format($mi->total_amount) }}</td>
                            <td class="px-7 py-4 text-center">
                                @if($isPaid)
                                <span class="text-emerald-500 font-black text-[10px] uppercase tracking-widest">Paid
                                    ✔</span>
                                @else
                                <button
                                    onclick="openMaterialPaymentModal({{ $mi->id }}, '{{ $mi->supplier_name }}', {{ $mi->total_amount - $paid }})"
                                    class="bg-slate-900 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-black transition">
                                    Pay
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-slate-400 italic text-sm">No material
                                inwards recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- 7. Handover Tab --}}
        <div x-show="activeTab === 'handover'" x-cloak class="animate-in fade-in duration-500 space-y-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- Checklist --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3
                            class="text-xl font-bold text-slate-900 dark:text-white font-display uppercase tracking-widest">
                            Handover Checklist</h3>
                        <button onclick="document.getElementById('add-handover-item-modal').classList.remove('hidden')"
                            class="text-[10px] font-black uppercase tracking-widest text-brand-600 hover:text-brand-700">+
                            Add Requirement</button>
                    </div>

                    <div
                        class="bg-white dark:bg-slate-900/40 rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium p-8 space-y-4">
                        @php
                        $totalHandover = $client->handover?->items?->count() ?? 0;
                        $completedHandover = $client->handover?->items?->where('is_completed', true)->count() ?? 0;
                        $allHandoverDone = $totalHandover > 0 && $totalHandover === $completedHandover;
                        @endphp

                        @forelse($client->handover?->items ?? [] as $item)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50/50 dark:bg-dark-bg/30 rounded-2xl border border-slate-100 dark:border-dark-border group">
                            <form action="{{ route('handover.item.update', $item) }}" method="POST"
                                class="flex items-center gap-4 flex-1">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all {{ $item->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-200 dark:border-slate-700 hover:border-brand-500' }}">
                                    @if($item->is_completed)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    @endif
                                </button>
                                <span
                                    class="text-sm font-bold {{ $item->is_completed ? 'text-slate-400 line-through' : 'text-slate-700 dark:text-slate-200' }}">{{
                                    $item->item_name }}</span>
                            </form>
                        </div>
                        @empty
                        <div class="py-10 text-center text-slate-400 italic text-sm">No handover requirements
                            indexed.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Completion Form --}}
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display uppercase tracking-widest">
                        Finalization</h3>

                    <div
                        class="bg-slate-900 dark:bg-dark-surface rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 blur-[100px] -mr-16 -mt-16 rounded-full">
                        </div>

                        @if($client->handover?->status === 'completed')
                        <div class="text-center py-8">
                            <div
                                class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/20">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h4 class="text-2xl font-black text-white mb-2 font-display">Project Decommissioned</h4>
                            <p class="text-emerald-400 text-xs font-bold uppercase tracking-widest">Warranty
                                Certificate Issued</p>

                            <div class="mt-8 pt-8 border-t border-white/10 grid grid-cols-2 gap-4">
                                <div class="text-left">
                                    <span
                                        class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Handover
                                        Date</span>
                                    <div class="text-sm font-bold text-white">{{
                                        $client->handover->handover_date->format('d M, Y') }}</div>
                                </div>
                                <div class="text-left">
                                    <span
                                        class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Warranty
                                        Expiry</span>
                                    <div class="text-sm font-bold text-white">{{
                                        $client->handover->warranty_expiry->format('d M, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @else
                        <form action="{{ route('handover.complete', $client) }}" method="POST"
                            class="space-y-6 relative z-10">
                            @csrf
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-white/50 uppercase tracking-widest">Warranty
                                        Period (Years)</label>
                                    <select name="warranty_years"
                                        class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-white text-sm font-bold focus:ring-brand-500 transition-all">
                                        <option value="1">1 Year Limited Warranty</option>
                                        <option value="2">2 Year Standard Warranty</option>
                                        <option value="5">5 Year Premium Warranty</option>
                                        <option value="10">10 Year Lifetime Structure</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black text-white/50 uppercase tracking-widest">Client
                                        Acknowledgement</label>
                                    <input type="text" name="client_signature" required
                                        placeholder="Type full name as digital signature"
                                        class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-white text-sm font-bold focus:ring-brand-500 transition-all placeholder:text-white/20">
                                </div>
                            </div>

                            <div class="pt-4">
                                @if($allHandoverDone)
                                <button type="submit"
                                    class="w-full bg-brand-500 hover:bg-brand-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-brand-500/30 transition hover:scale-[1.02] active:scale-95">Finalize
                                    Portfolio Maturity</button>
                                @else
                                <div
                                    class="w-full bg-white/5 border border-white/10 text-white/40 py-4 rounded-2xl font-black uppercase tracking-widest text-center cursor-not-allowed text-[11px]">
                                    Complete Checklist to Unlock Finalization
                                </div>
                                @endif
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Universal Modals for Project --}}
    @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
    {{-- Add Scope Unit Modal --}}
    <div id="add-scope-modal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-dark-surface rounded-[40px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 dark:border-dark-border">
            <div
                class="px-8 py-6 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Add Scope Unit</h3>
                <button onclick="document.getElementById('add-scope-modal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition">&times;</button>
            </div>
            <form action="{{ route('scope.store', $client) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Area
                            / Category</label>
                        <input type="text" name="area_name" required placeholder="e.g. Living Room, Master Toilet"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-3 text-sm focus:ring-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Scope
                            Details</label>
                        <textarea name="description" rows="4" required
                            placeholder="Describe the specific work to be done..."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-3 text-sm focus:ring-brand-500 transition-all"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="document.getElementById('add-scope-modal').classList.add('hidden')"
                        class="px-6 py-2 text-slate-400 font-bold hover:text-slate-600 transition uppercase text-[10px] tracking-widest">Cancel</button>
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 transition active:scale-95">Save
                        Unit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Add Handover Item Modal --}}
    @if(!auth()->user()->isViewer() && !auth()->user()->isClient())
    <div id="add-handover-item-modal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-dark-surface rounded-[40px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 dark:border-dark-border">
            <div
                class="px-8 py-6 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Add Handover Requirement
                </h3>
                <button onclick="document.getElementById('add-handover-item-modal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition">&times;</button>
            </div>
            <form action="{{ route('handover.item.store', $client->handover ?? 0) }}" method="POST"
                class="p-8 space-y-6">
                @csrf
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Requirement
                        Name</label>
                    <input type="text" name="item_name" required placeholder="e.g. Balcony Waterproofing Certificate"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-3 text-sm focus:ring-brand-500 transition-all">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button"
                        onclick="document.getElementById('add-handover-item-modal').classList.add('hidden')"
                        class="px-6 py-2 text-slate-400 font-bold hover:text-slate-600 transition uppercase text-[10px] tracking-widest">Cancel</button>
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 transition active:scale-95">Add
                        to Checklist</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>


{{-- MODALS FOR FINANCIAL CONTROL ROOM --}}
@if(!auth()->user()->isViewer() && !auth()->user()->isClient())

{{-- 1. Add Material Inward Modal --}}
<div id="material-inward-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 dark:border-dark-border">
        <div
            class="px-8 py-5 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Record Material Inward</h3>
            <button onclick="document.getElementById('material-inward-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">&times;</button>
        </div>
        <form action="{{ route('finance.material-inward.store', $client) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Date</label>
                    <input type="date" name="inward_date" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Supplier</label>
                    <input type="text" name="supplier_name" required placeholder="Vendor Name"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Item
                    Details</label>
                <input type="text" name="item_name" required placeholder="E.g. Cement, Plywood 18mm"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Quantity</label>
                    <input type="number" step="0.01" name="quantity" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Unit</label>
                    <select name="unit"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                        <option value="pcs">Pcs</option>
                        <option value="kg">Kg</option>
                        <option value="mtr">Mtr</option>
                        <option value="sqft">SqFt</option>
                        <option value="bags">Bags</option>
                        <option value="ltr">Ltr</option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Rate</label>
                    <input type="number" step="0.01" name="rate" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
            </div>
            <button type="submit"
                class="w-full bg-brand-500 hover:bg-brand-600 text-white rounded-xl py-3 font-bold uppercase tracking-widest shadow-lg shadow-brand-500/20 transition">
                Save Record
            </button>
        </form>
    </div>
</div>

{{-- 2. Add Vendor Payment Modal --}}
<div id="vendor-payment-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 dark:border-dark-border">
        <div
            class="px-8 py-5 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Record Vendor Payment</h3>
            <button onclick="document.getElementById('vendor-payment-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">&times;</button>
        </div>
        <form action="{{ route('finance.vendor.store', $client) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Vendor</label>
                <select name="vendor_id" required
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                    @foreach(App\Models\Vendor::all() as $v)
                    <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->category }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Date</label>
                    <input type="date" name="payment_date" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Amount</label>
                    <input type="number" step="0.01" name="amount" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Work Type /
                    Description</label>
                <input type="text" name="work_type" required placeholder="E.g. Kitchen electrical labor advance"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
            </div>
            <button type="submit"
                class="w-full bg-brand-500 hover:bg-brand-600 text-white rounded-xl py-3 font-bold uppercase tracking-widest shadow-lg shadow-brand-500/20 transition">
                Record Payment
            </button>
        </form>
    </div>
</div>

{{-- 3. Profit Lock Confirm Modal --}}
@if(auth()->user()->isAdmin())
<div id="profit-lock-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 dark:border-dark-border text-center p-8">
        <div class="mb-4">
            <div
                class="w-16 h-16 bg-brand-100 dark:bg-brand-500/20 rounded-full flex items-center justify-center mx-auto text-brand-600 dark:text-brand-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Toggle Profit Lock?</h3>
        <p class="text-sm text-slate-500 mb-6">Locking will prevent adding new vendor payments or material costs without
            admin override.</p>
        <form action="{{ route('finance.profit.lock', $client) }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('profit-lock-modal').classList.add('hidden')"
                    class="flex-1 px-6 py-3 bg-slate-100 dark:bg-dark-bg text-slate-600 dark:text-slate-400 rounded-xl font-bold uppercase tracking-widest text-xs transition hover:bg-slate-200">Cancel</button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-brand-600 text-white rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-brand-500/20 transition hover:bg-brand-700">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- 4. Add Material Payment Modal --}}
<div id="material-payment-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-dark-surface rounded-[32px] shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 dark:border-dark-border">
        <div
            class="px-8 py-5 border-b border-slate-50 dark:border-dark-border flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Record Payment</h3>
            <button onclick="document.getElementById('material-payment-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">&times;</button>
        </div>
        <form action="{{ route('finance.material-payment.store', $client) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <input type="hidden" name="material_inward_id" id="modal_inward_id">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Paying
                    To</label>
                <input type="text" id="modal_supplier_name" readonly
                    class="w-full bg-slate-100 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm text-slate-500 font-bold">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Date</label>
                    <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Amount</label>
                    <input type="number" step="0.01" name="amount_paid" id="modal_pending_amount" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                </div>
            </div>
            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3 font-bold uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition">
                Confirm Payment
            </button>
        </form>
    </div>
</div>
@endif

</div>

<script>
    function openMaterialPaymentModal(id, supplier, pending) {
        document.getElementById('modal_inward_id').value = id;
        document.getElementById('modal_supplier_name').value = document.getElementById('modal_pnt').value = pending;
        document.getElementById('material-payment-modal').classList.remove('hidden');
    }
</script>

@endsection