@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: 'all' }" class="space-y-8 animate-in fade-in duration-700">

    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white font-display">Workspace Overview
            </h1>
            <p class="text-[15px] text-slate-500 dark:text-dark-muted mt-1 font-medium">Monitoring site efficiency
                across
                {{ $totalProjects }} active projects.</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl text-[13px] font-semibold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-premium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Export
            </button>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.create') }}"
                class="flex items-center gap-2.5 px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-2xl text-[13px] font-bold transition-all shadow-xl shadow-brand-500/20 transform active:scale-95">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Workspace
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card label="Total Revenue" value="₹{{ number_format($totalRevenue) }}"
            trend="{{ round($revenueGrowth, 1) }}" :trendUp="$revenueGrowth >= 0" :sparkline="$sparklineQuery"
            icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0" />
        <x-stat-card label="Active Workspaces" value="{{ $totalProjects }}" trend="5.2" :trendUp="true"
            icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        <x-stat-card label="Success Rate"
            value="{{ $totalQuoted > 0 ? round($totalApproved/$totalQuoted*100, 1) : 0 }}%" trend="2.1" :trendUp="true"
            icon="M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 2.021M9 3.557a3.352 3.352 0 01-2.903-1.536m12.522 7.618a3.303 3.303 0 00-4.704-2.583m0 0a3.303 3.303 0 00-2.583 4.704m-12.222 6.643a3.303 3.303 0 014.704 2.583m0 0a3.303 3.303 0 012.583-4.704m9.222-6.222a3.303 3.303 0 01-4.704 2.583m0 0a3.303 3.303 0 01-2.583-4.704" />
        <x-stat-card label="Pending Tasks" value="{{ $activeTasks }}" trend="12" :trendUp="false"
            icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </div>

    <!-- Charts & Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Chart -->
        <div
            class="lg:col-span-2 bg-white dark:bg-dark-surface p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white font-display">Workspace
                        Growth</h3>
                    <p class="text-sm text-slate-500 dark:text-dark-muted font-medium">New workspace registration trends
                        over time</p>
                </div>
                <select
                    class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold focus:ring-brand-600 py-2 px-4 cursor-pointer shadow-premium">
                    <option>Last 6 Months</option>
                    <option>Year to Date</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="projectsChart"></canvas>
            </div>
        </div>

        <!-- Task Distribution -->
        <div
            class="bg-white dark:bg-dark-surface p-7 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium flex flex-col">
            <div class="mb-4">
                <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white font-display">Workload
                    Distribution</h3>
                <p class="text-sm text-slate-500 dark:text-dark-muted font-medium">Current tasks by status</p>
            </div>
            <div class="relative flex-grow flex flex-col items-center justify-center min-h-[220px]">
                <canvas id="tasksChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                    <span class="text-[32px] font-black text-ui-primary dark:text-white">{{ array_sum($taskData)
                        }}</span>
                    <span
                        class="text-[11px] uppercase font-bold text-ui-muted dark:text-dark-muted tracking-[1px]">Tasks
                        Total</span>
                </div>
            </div>
            <!-- Custom Legend -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                @foreach($taskLabels as $index => $label)
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full"
                        style="background-color: {{ ['#2563EB', '#6366f1', '#16a34a', '#F59E0B'][$index] }}"></div>
                    <span class="text-xs font-semibold text-ui-muted">{{ $label }}: <span
                            class="text-ui-primary dark:text-white">{{ $taskData[$index] }}</span></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Datagrid Section -->
    <div
        class="bg-white dark:bg-dark-surface rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
        <div
            class="px-7 py-6 border-b border-slate-50 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/20">
            <div>
                <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white font-display">Active
                    Workspace
                    Portfolio</h3>
                <p class="text-[15px] text-slate-500 dark:text-dark-muted font-medium">Manage and track live site
                    performance</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="Filter projects..."
                        class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-transparent rounded-xl text-xs focus:ring-2 focus:ring-brand-600 focus:bg-white dark:focus:bg-slate-700 transition-all w-48 font-medium">
                    <svg class="w-4 h-4 absolute left-3.5 top-2.5 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button
                    class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-dark-bg/50 border-b border-slate-50 dark:border-dark-border">
                    <tr>
                        <th
                            class="px-7 py-5 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                            Client Identity</th>
                        <th
                            class="px-7 py-5 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                            Current Stage</th>
                        <th
                            class="px-7 py-5 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                            Risk Profile</th>
                        <th
                            class="px-7 py-5 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display">
                            Timeline</th>
                        <th
                            class="px-7 py-5 text-[11px] font-bold uppercase tracking-[1.5px] text-slate-400 font-display text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                    @forelse($recentProjects as $project)
                    <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-bg/20 transition-all duration-300 group">
                        <td class="px-7 py-5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center font-bold text-slate-400 border border-slate-200/50 dark:border-dark-border group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition-all transform group-hover:scale-105 font-display">
                                    {{ substr($project->first_name, 0, 1) }}{{ substr($project->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-[15px] font-display">{{
                                        $project->first_name }} {{ $project->last_name }}</div>
                                    <div class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        {{
                                        $project->file_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-5">
                            @php
                            $currentStage = 'Pitching';
                            $color = 'blue';
                            if ($project->feedback || ($project->handover && $project->handover->status ===
                            'Completed')) {
                            $currentStage = 'Handover'; $color = 'purple';
                            } elseif ($project->payments->count() > 0 || $project->paymentRequests->count() > 0) {
                            $currentStage = 'Financial'; $color = 'emerald';
                            } elseif ($project->tasks->count() > 0 || $project->dailyReports->count() > 0) {
                            $currentStage = 'Execution'; $color = 'orange';
                            } elseif ($project->quotations->where('status', 'approved')->count() > 0) {
                            $currentStage = 'Planning'; $color = 'indigo';
                            }
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-{{ $color }}-500 animate-pulse"></div>
                                <span class="text-xs font-black text-{{ $color }}-600 uppercase tracking-widest">{{
                                    $currentStage }}</span>
                            </div>
                        </td>
                        <td class="px-7 py-5">
                            @php $risk = $project->risk_analysis; @endphp
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-3 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-tight border shadow-sm
                                    {{ $risk['level'] == 'High' ? 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20' : ($risk['level'] == 'Medium' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-500/10 dark:border-amber-500/20' : 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20') }}">
                                    {{ $risk['level'] }} Profile
                                </span>
                            </div>
                        </td>
                        <td class="px-7 py-5">
                            <div class="flex flex-col gap-2">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{
                                    $project->start_date
                                    ? $project->start_date->format('M d, Y') : 'Pending' }}</span>
                                <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="bg-brand-500 h-full w-2/3 rounded-full"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-5 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                <a href="{{ route('finance.analytics', $project) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-rose-500 border border-slate-100 dark:border-slate-700 hover:bg-rose-500 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm hover:shadow-rose-500/20"
                                    title="Financial Analytics">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="{{ route('clients.show', $project) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-brand-500 border border-slate-100 dark:border-slate-700 hover:bg-brand-500 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm hover:shadow-brand-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center opacity-40">
                                <p class="text-xs font-bold uppercase tracking-widest italic">No Site Portfolios Found
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Secondary Intelligence Feed -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Recent Logistics/Materials -->
            <div
                class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden relative">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-brand-500/5 rounded-full blur-3xl"></div>
                <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">Logistics
                    Signal</h3>
                <div class="space-y-4 relative">
                    @forelse($recentMaterials as $material)
                    <div
                        class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border hover:border-brand-300 transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-white dark:bg-dark-surface flex items-center justify-center border border-ui-border text-brand-500 shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-black text-ui-primary dark:text-white">{{
                                    $material->inventoryItem->name }}</p>
                                <p class="text-[10px] text-ui-muted font-bold uppercase tracking-widest mt-0.5">{{
                                    $material->client->first_name }}'s Workspace</p>
                            </div>
                        </div>
                        <span
                            class="px-2.5 py-1 bg-white dark:bg-dark-surface rounded-lg text-[9px] font-black uppercase tracking-widest text-brand-600 border border-ui-border">
                            {{ $material->status }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-ui-muted font-bold text-center py-10 uppercase tracking-widest">No recent
                        procurement</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Quotations -->
            <div
                class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden relative">
                <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">Proposal
                    Pipeline</h3>
                <div class="space-y-4 relative">
                    @forelse($recentQuotations as $quote)
                    <div
                        class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border hover:border-indigo-300 transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-white dark:bg-dark-surface flex items-center justify-center border border-ui-border text-indigo-500 shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-black text-ui-primary dark:text-white">{{
                                    $quote->quotation_number }}</p>
                                <p class="text-[10px] text-ui-muted font-bold uppercase tracking-widest mt-0.5">₹{{
                                    number_format($quote->total_amount) }} • {{ $quote->client ?
                                    $quote->client->first_name : ($quote->lead ? $quote->lead->name : 'Unknown') }}</p>
                            </div>
                        </div>
                        <span
                            class="px-2.5 py-1 bg-white dark:bg-dark-surface rounded-lg text-[9px] font-black uppercase tracking-widest {{ $quote->status === 'approved' ? 'text-emerald-600' : 'text-indigo-600' }} border border-ui-border">
                            {{ $quote->status }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-ui-muted font-bold text-center py-10 uppercase tracking-widest">No active
                        proposals</p>
                    @endforelse
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

            const ctxProjects = document.getElementById('projectsChart').getContext('2d');
            const grad = ctxProjects.createLinearGradient(0, 0, 0, 300);
            grad.addColorStop(0, 'rgba(0, 180, 216, 0.2)');
            grad.addColorStop(1, 'rgba(0, 180, 216, 0)');

            new Chart(ctxProjects, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'New Workspaces',
                        data: @json($projectCounts),
                        borderColor: '#00B4D8',
                        backgroundColor: grad,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#00B4D8',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            grid: { color: gridColor, borderDash: [4, 4] },
                            ticks: { color: textColor, font: { weight: '600', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { weight: '600', size: 10 } }
                        }
                    }
                }
            });

            const ctxTasks = document.getElementById('tasksChart').getContext('2d');
            new Chart(ctxTasks, {
                type: 'doughnut',
                data: {
                    labels: @json($taskLabels),
                    datasets: [{
                        data: @json($taskData),
                        backgroundColor: ['#03045E', '#0077B6', '#00B4D8', '#90E0EF'],
                        borderWidth: 5,
                        borderColor: isDark ? '#03045E' : '#fff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endsection