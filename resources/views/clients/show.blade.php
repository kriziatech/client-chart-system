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
$currentJourneyStage = $client->journey_stage;
$journeyStageName = match($currentJourneyStage) {
1 => 'New Client',
2 => 'Site Visit',
3 => 'Quotation',
4 => 'Credit',
5 => 'Work Assigned',
6 => 'Timeline',
7 => 'Work Completed',
8 => 'Final Payment',
default => 'New Client'
};

$journeyColor = match($currentJourneyStage) {
8 => 'green',
7 => 'green',
default => ($riskScore > 60 ? 'red' : 'blue')
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
default => 'Proceed to next phase.'
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
default => 'Next Step'
};

$ctaAction = match((int)$currentJourneyStage) {
1 => "activeTab = 'overview'",
2 => "activeTab = 'quotations'",
3 => "activeTab = 'quotations'",
4 => "activeTab = 'tasks'",
5 => "activeTab = 'overview'", // Stage 5: Set Timeline (Timeline is in Overview)
6 => "window.location = '" . route('reports.index', $client->id) . "'", // Stage 6: Track Execution (Navigate to
Reports)
7 => "activeTab = 'payments'",
8 => "activeTab = 'handover'",
default => "activeTab = 'overview'"
};

$initialTab = match((int)$currentJourneyStage) {
1, 2 => 'overview',
3 => 'quotations',
4 => 'payments',
5 => 'overview', // Current stage is Set Timeline
6 => 'tasks', // Current stage is Track Execution
7 => 'payments',
8 => 'handover',
default => 'overview'
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

                {{-- Chat Tab --}}
                <button @click="activeTab = 'chat'"
                    :class="activeTab === 'chat' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600 dark:text-brand-400' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 font-display whitespace-nowrap flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Chat
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
                <a href="{{ route('reports.index', $client->id) }}"
                    class="px-5 py-2.5 bg-brand-50 text-brand-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-100 transition flex items-center gap-2">Daily
                    Reports (DPR)</a>
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

        {{-- 4. Inventory Tab --}}
        <div x-show="activeTab === 'materials'" x-cloak class="animate-in fade-in duration-500">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display mb-6">Material Allocation</h3>
            <div
                class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
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

        {{-- 5. Financials Tab --}}
        <div x-show="activeTab === 'payments'" x-cloak class="animate-in fade-in duration-500 space-y-8">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Capital Management</h3>
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
                                class="px-7 py-4 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        @forelse($client->payments as $payment)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all">
                            <td class="px-7 py-4 font-bold text-slate-900 dark:text-white text-[14px]">#PAY-{{
                                $payment->id }}</td>
                            <td class="px-7 py-4 text-[14px] text-slate-500 font-medium">{{ $payment->payment_date ?
                                $payment->payment_date->format('d M, Y') : '-' }}</td>
                            <td class="px-7 py-4 text-right font-bold text-emerald-600 text-[15px]">₹{{
                                number_format($payment->amount) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-7 py-12 text-center text-slate-400 italic">No payment logs
                                found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('workspaceChat', (config) => ({
            messages: [],
            newMessage: '',
            isSending: false,
            projectId: config.project_id,
            attachmentFile: null,
            attachmentName: '',
            attachmentPreview: false,

            init() {
                this.fetchMessages();
                setInterval(() => {
                    if (this.$store.activeTab === 'chat' || document.querySelector('[x-data]').__x.$data.activeTab === 'chat') {
                        this.fetchMessages();
                    }
                }, 5000);
            },

            fetchMessages() {
                fetch('/chat/fetch?project_id=' + this.projectId)
                    .then(res => res.json())
                    .then(data => {
                        this.messages = data;
                        this.$nextTick(() => this.scrollToBottom());
                    });
            },

            sendMessage() {
                if (!this.newMessage.trim() && !this.attachmentFile) return;
                this.isSending = true;

                const formData = new FormData();
                formData.append('message', this.newMessage);
                formData.append('project_id', this.projectId);
                if (this.attachmentFile) formData.append('attachment', this.attachmentFile);

                fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                })
                    .then(() => {
                        this.newMessage = '';
                        this.clearAttachment();
                        this.fetchMessages();
                    })
                    .finally(() => this.isSending = false);
            },

            handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.attachmentFile = file;
                this.attachmentName = file.name;
                this.attachmentPreview = true;
            },

            clearAttachment() {
                this.attachmentFile = null;
                this.attachmentName = '';
                this.attachmentPreview = false;
                if (this.$refs.wsFileInput) this.$refs.wsFileInput.value = '';
            },

            formatDate(d) {
                if (!d) return '';
                const date = new Date(d);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            },

            scrollToBottom() {
                const c = document.getElementById('workspaceChatContainer');
                if (c) c.scrollTop = c.scrollHeight;
            }
        }));
    });
</script>
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
        <form action="{{ route('handover.item.store', $client->handover ?? 0) }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Requirement
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