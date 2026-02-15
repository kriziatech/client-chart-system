@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: 'all' }" class="space-y-8 animate-in fade-in duration-700">

    <!-- Header Area -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
        <div class="space-y-1">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white font-display">
                Terminal <span class="text-brand-500">Dossier</span>
            </h1>
            <p class="text-[15px] text-slate-500 dark:text-dark-muted font-medium flex items-center gap-2">
                <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Real-time monitoring across {{ $totalProjects }} active workstreams.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <div
                class="flex items-center p-1.5 bg-slate-100 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-dark-border shadow-inner">
                <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600' : 'text-slate-500'"
                    class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all">Overview</button>
                <button @click="activeTab = 'stats'"
                    :class="activeTab === 'stats' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600' : 'text-slate-500'"
                    class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all">Intelligence</button>
            </div>

            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.create') }}"
                class="flex items-center gap-3 px-7 py-3.5 bg-brand-500 hover:bg-brand-600 text-white rounded-2xl text-[12px] font-black uppercase tracking-widest transition-all shadow-2xl shadow-brand-500/30 group transform active:scale-95">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Deploy Site
            </a>
            @endif
        </div>
    </div>

    <!-- Global Stats (Always Visible) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card label="Total Revenue" value="₹{{ number_format($totalRevenue) }}"
            trend="{{ round($revenueGrowth, 1) }}" :trendUp="$revenueGrowth >= 0" :sparkline="$sparklineQuery"
            icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0" />
        <x-stat-card label="Active Projects" value="{{ $totalProjects }}" trend="5.2" :trendUp="true"
            icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        <x-stat-card label="Approval Rate"
            value="{{ $totalQuoted > 0 ? round($totalApproved/$totalQuoted*100, 1) : 0 }}%" trend="2.1" :trendUp="true"
            icon="M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 2.021M9 3.557a3.352 3.352 0 01-2.903-1.536m12.522 7.618a3.303 3.303 0 00-4.704-2.583m0 0a3.303 3.303 0 00-2.583 4.704m-12.222 6.643a3.303 3.303 0 014.704 2.583m0 0a3.303 3.303 0 012.583-4.704m9.222-6.222a3.303 3.303 0 01-4.704 2.583m0 0a3.303 3.303 0 01-2.583-4.704" />
        <x-stat-card label="Pending Actions" value="{{ $activeTasks }}" trend="12" :trendUp="false"
            icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </div>

    <!-- Overview Tab Content -->
    <div x-show="activeTab === 'all'" class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Operational Pulse Feed -->
            <div class="xl:col-span-2 space-y-8">
                <div
                    class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <div
                        class="px-8 py-7 border-b border-slate-50 dark:border-dark-border flex items-center justify-between bg-slate-50/30">
                        <div>
                            <h3
                                class="text-xl font-black text-slate-800 dark:text-white font-display uppercase tracking-tight">
                                Live Operations Pulse</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Real-time
                                signals from ongoing sites</p>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3 py-1 bg-brand-500/10 rounded-full border border-brand-500/20">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                            <span class="text-[9px] font-black text-brand-600 uppercase tracking-widest">Active
                                Stream</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Progress Logs -->
                            <div class="space-y-5">
                                <h4
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] flex items-center gap-2">
                                    <div class="w-1.5 h-4 bg-brand-500 rounded-full"></div>
                                    Progress Signal
                                </h4>
                                @forelse($recentReports as $report)
                                <div
                                    class="group p-5 bg-slate-50 dark:bg-dark-bg/50 rounded-3xl border border-transparent hover:border-brand-500/30 hover:bg-white dark:hover:bg-dark-bg transition-all duration-300 shadow-sm hover:shadow-premium">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-brand-500/5 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 flex-shrink-0 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[14px] font-black text-slate-900 dark:text-white truncate">{{
                                                $report->client->first_name }}</p>
                                            <p
                                                class="text-[12px] text-slate-500 dark:text-dark-muted line-clamp-2 mt-1 leading-relaxed">
                                                {{ $report->content }}</p>
                                            <div class="flex items-center gap-2 mt-3">
                                                <span
                                                    class="text-[9px] font-black text-slate-400 uppercase bg-slate-200/50 dark:bg-dark-border px-2 py-0.5 rounded-md">{{
                                                    $report->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div
                                    class="py-12 border-2 border-dashed border-slate-100 dark:border-dark-border rounded-3xl text-center">
                                    <p
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-loose">
                                        No active worklogs<br><span class="opacity-50 font-medium">awaiting site
                                            updates</span></p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Financial Intel -->
                            <div class="space-y-5">
                                <h4
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] flex items-center gap-2">
                                    <div class="w-1.5 h-4 bg-emerald-500 rounded-full"></div>
                                    Revenue Signal
                                </h4>
                                @forelse($recentPayments as $payment)
                                <div
                                    class="group p-5 bg-emerald-50/30 dark:bg-emerald-500/5 rounded-3xl border border-transparent hover:border-emerald-500/30 hover:bg-white dark:hover:bg-dark-bg transition-all duration-300 shadow-sm hover:shadow-premium">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 group-hover:rotate-12 transition-all duration-500 shadow-lg shadow-emerald-500/20">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[14px] font-black text-slate-900 dark:text-white">{{
                                                    $payment->client->first_name }}</p>
                                                <p
                                                    class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest mt-1">
                                                    Confirmed Credit</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-black text-emerald-600 tabular-nums">₹{{
                                                number_format($payment->amount) }}</p>
                                            <p
                                                class="text-[9px] font-black text-slate-400 ml-auto uppercase opacity-60">
                                                {{ $payment->date->format('d M') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div
                                    class="py-12 border-2 border-dashed border-slate-100 dark:border-dark-border rounded-3xl text-center">
                                    <p
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-loose">
                                        No income feed<br><span class="opacity-50 font-medium">awaiting payment
                                            entry</span></p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Repository -->
                <div
                    class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                    <div
                        class="px-8 py-7 border-b border-slate-50 dark:border-dark-border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/20">
                        <div>
                            <h3
                                class="text-xl font-black text-slate-800 dark:text-white font-display uppercase tracking-tight">
                                Active Repositories</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Unified site
                                performance dossier</p>
                        </div>
                        <div class="relative group">
                            <input type="text" placeholder="Access project dossier..."
                                class="pl-11 pr-5 py-3 bg-slate-100 dark:bg-slate-800 border-transparent rounded-2xl text-xs font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-slate-700 transition-all w-64 shadow-inner">
                            <svg class="w-4.5 h-4.5 absolute left-4 top-3.5 text-slate-400 group-focus-within:text-brand-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead
                                class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                                <tr>
                                    <th
                                        class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                                        Identity</th>
                                    <th
                                        class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                                        Current Phase</th>
                                    <th
                                        class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 text-right">
                                        Access</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                                @forelse($recentProjects as $project)
                                <tr
                                    class="hover:bg-slate-50/40 dark:hover:bg-dark-bg/30 transition-all duration-300 group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-5">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center font-black text-slate-400 border border-slate-200/50 dark:border-dark-border group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition-all transform group-hover:-translate-y-1 group-hover:shadow-xl group-hover:shadow-brand-500/20 font-display text-lg">
                                                {{ substr($project->first_name, 0, 1) }}{{ substr($project->last_name,
                                                0, 1) }}
                                            </div>
                                            <div>
                                                <div
                                                    class="font-black text-slate-900 dark:text-white text-[16px] font-display uppercase tracking-tight">
                                                    {{ $project->first_name }} {{ $project->last_name }}</div>
                                                <div
                                                    class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">
                                                    {{ $project->file_number ?: 'TERMINAL-'.$project->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2.5 h-2.5 rounded-full bg-brand-500 animate-pulse shadow-[0_0_8px_rgba(var(--brand-500),0.8)]">
                                            </div>
                                            <span
                                                class="text-[11px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Active
                                                Execution</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('clients.show', $project) }}"
                                            class="inline-flex items-center gap-3 px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-brand-500 hover:text-white text-brand-600 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                            Open Dossier
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="3"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-16 text-center text-slate-400 font-black uppercase tracking-widest text-xs opacity-50 italic">
                                        No dossiers found in archive</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Intelligence Rail -->
            <div class="space-y-8">
                <!-- Logistics Intel -->
                <div
                    class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium relative overflow-hidden group">
                    <div
                        class="absolute -top-12 -right-12 w-48 h-48 bg-brand-500/5 rounded-full blur-3xl group-hover:bg-brand-500/10 transition-all duration-700">
                    </div>
                    <div class="flex items-center justify-between mb-8 relative">
                        <h3
                            class="text-xs font-black text-slate-400 dark:text-dark-muted uppercase tracking-[0.3em] font-display">
                            Logistics Signals</h3>
                        <div
                            class="w-10 h-10 rounded-2xl bg-brand-500/5 dark:bg-brand-500/10 flex items-center justify-center text-brand-500 border border-brand-500/10 shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4 relative">
                        @forelse($recentMaterials as $material)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg/50 rounded-2xl border border-transparent hover:border-brand-500/20 hover:bg-white transition-all shadow-sm">
                            <div class="min-w-0">
                                <p class="text-[13px] font-black text-slate-800 dark:text-white truncate">{{
                                    $material->inventoryItem->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{
                                    $material->client->first_name }}</p>
                            </div>
                            <span
                                class="px-2.5 py-1 bg-brand-500 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-lg shadow-brand-500/20">{{
                                $material->status }}</span>
                        </div>
                        @empty
                        <p
                            class="text-[11px] text-slate-400 font-bold text-center py-10 uppercase tracking-widest italic opacity-40">
                            Dormant flow</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pipeline Intel -->
                <div
                    class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium relative overflow-hidden group">
                    <div
                        class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all duration-700">
                    </div>
                    <div class="flex items-center justify-between mb-8 relative">
                        <h3
                            class="text-xs font-black text-slate-400 dark:text-dark-muted uppercase tracking-[0.3em] font-display">
                            Pipeline Intel</h3>
                        <div
                            class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 border border-indigo-500/10 shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4 relative">
                        @forelse($recentQuotations as $quote)
                        <div
                            class="p-5 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-3xl border border-transparent hover:border-indigo-500/30 hover:bg-white transition-all shadow-sm">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-[14px] font-black text-slate-900 dark:text-white">{{
                                    $quote->quotation_number }}</p>
                                <span class="text-[11px] font-black text-indigo-600">₹{{
                                    number_format($quote->total_amount) }}</span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest opacity-60">{{
                                $quote->client ? $quote->client->first_name : 'New Account' }}</p>
                        </div>
                        @empty
                        <p
                            class="text-[11px] text-slate-400 font-bold text-center py-10 uppercase tracking-widest italic opacity-40">
                            Quiet pipeline</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Intelligence Tab Content -->
    <div x-show="activeTab === 'stats'" class="space-y-8 animate-in slide-in-from-right-4 duration-500">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Growth Analytics -->
            <div
                class="lg:col-span-2 bg-white dark:bg-dark-surface p-10 rounded-[3rem] border border-slate-100 dark:border-dark-border shadow-premium relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-64 h-64 bg-brand-500/5 rounded-full blur-3xl"></div>
                <div class="flex items-center justify-between mb-12 relative">
                    <div>
                        <h3
                            class="text-2xl font-black text-slate-800 dark:text-white font-display uppercase tracking-tight">
                            Growth Analytics</h3>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">Registration
                            velocity dossier</p>
                    </div>
                    <div
                        class="px-5 py-2.5 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                        <span
                            class="text-[11px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Last
                            6 Months</span>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="projectsChart"></canvas>
                </div>
            </div>

            <!-- Distribution Matrix -->
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[3rem] border border-slate-100 dark:border-dark-border shadow-premium flex flex-col items-center">
                <div class="w-full mb-10">
                    <h3
                        class="text-2xl font-black text-slate-800 dark:text-white font-display uppercase tracking-tight">
                        Status Matrix</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">Workload
                        distribution profile</p>
                </div>
                <div class="relative w-full h-[260px] flex items-center justify-center">
                    <canvas id="tasksChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pt-4">
                        <span class="text-5xl font-black text-slate-900 dark:text-white tabular-nums">{{
                            array_sum($taskData) }}</span>
                        <span class="text-[10px] uppercase font-black text-slate-400 tracking-[0.3em] mt-1 pl-1">Total
                            Units</span>
                    </div>
                </div>
                <div class="mt-12 grid grid-cols-2 gap-5 w-full">
                    @foreach($taskLabels as $index => $label)
                    <div
                        class="flex items-center gap-4 bg-slate-50 dark:bg-dark-bg p-4 rounded-3xl border border-transparent hover:border-brand-500/20 transition-all shadow-sm">
                        <div class="w-3 h-3 rounded-full shadow-[0_0_8px_rgba(37,99,235,0.4)]"
                            style="background-color: {{ ['#2563EB', '#6366f1', '#16a34a', '#F59E0B'][$index] }}"></div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="text-[16px] font-black text-slate-900 dark:text-white">{{ $taskData[$index] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Performance Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-8">
            <div
                class="p-8 bg-brand-500 rounded-[2.5rem] shadow-2xl shadow-brand-500/20 text-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-4 opacity-80">Profit Signal</h4>
                <div class="flex items-end gap-3 mb-6">
                    <span class="text-4xl font-black tabular-nums">14.8%</span>
                    <span class="text-xs font-bold bg-white/20 px-2 py-1 rounded-lg mb-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 15l7-7 7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        2.1%
                    </span>
                </div>
                <p class="text-xs font-medium leading-relaxed opacity-70">Projected yield across all active workstreams
                    for the current fiscal terminal.</p>
            </div>

            <div
                class="p-8 bg-slate-900 dark:bg-brand-500/10 border border-transparent dark:border-brand-500/20 rounded-[2.5rem] shadow-premium text-white relative overflow-hidden group">
                <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-4 text-slate-400">Velocity Profile</h4>
                <div class="flex items-end gap-3 mb-6">
                    <span class="text-4xl font-black tabular-nums">92%</span>
                    <span class="text-xs font-bold text-emerald-400 mb-1.5 flex items-center gap-1">Optimal</span>
                </div>
                <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden mb-6">
                    <div class="h-full bg-emerald-500 w-[92%] rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                    </div>
                </div>
                <p class="text-xs font-medium leading-relaxed text-slate-400">Task completion velocity exceeds baseline
                    expectations by 8%.</p>
            </div>

            <div
                class="p-8 bg-white dark:bg-dark-surface rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium relative overflow-hidden group">
                <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-4 text-slate-400">Risk Assessment</h4>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-4 h-4 rounded-full bg-rose-500 animate-ping"></div>
                    <span
                        class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Nominal</span>
                </div>
                <div class="space-y-3">
                    <div
                        class="flex items-center justify-between text-[11px] font-bold uppercase tracking-widest text-slate-500">
                        <span>High Risk Units</span>
                        <span class="text-rose-500 font-black">01</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(15, 23, 42, 0.05)';
        const textColor = isDark ? '#94A3B8' : '#64748B';

        // Velocity Chart
        const ctxProjects = document.getElementById('projectsChart').getContext('2d');
        const grad = ctxProjects.createLinearGradient(0, 0, 0, 400);
        grad.addColorStop(0, 'rgba(0, 180, 216, 0.3)');
        grad.addColorStop(1, 'rgba(0, 180, 216, 0)');

        new Chart(ctxProjects, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'New Registrations',
                    data: @json($projectCounts),
                    borderColor: '#00B4D8',
                    backgroundColor: grad,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00B4D8',
                    pointBorderWidth: 3,
                    pointHoverRadius: 9,
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        grid: { color: gridColor, borderDash: [5, 5] },
                        ticks: { color: textColor, font: { weight: '800', size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { weight: '800', size: 10 } }
                    }
                }
            }
        });

        // Matrix Chart
        const ctxTasks = document.getElementById('tasksChart').getContext('2d');
        new Chart(ctxTasks, {
            type: 'doughnut',
            data: {
                labels: @json($taskLabels),
                datasets: [{
                    data: @json($taskData),
                    backgroundColor: ['#03045E', '#0077B6', '#00B4D8', '#90E0EF'],
                    borderWidth: 8,
                    borderColor: isDark ? '#1a1b2e' : '#fff',
                    hoverOffset: 25,
                    borderRadius: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '82%',
                plugins: { legend: { display: false } },
                animation: { animateScale: true, animateRotate: true }
            }
        });
    });
</script>
@endsection