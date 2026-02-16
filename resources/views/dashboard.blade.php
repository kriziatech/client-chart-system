@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: 'all' }"
    class="h-[calc(100vh-theme(spacing.24))] overflow-hidden flex flex-col gap-4 animate-in fade-in duration-500">

    <!-- Compact Header & Stats Rail -->
    <div class="flex-shrink-0 grid grid-cols-12 gap-4">
        <!-- Brand/Header (Col 2) -->
        <div class="col-span-2 flex flex-col justify-center">
            <h1 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white font-display">
                CMD<span class="text-brand-500">CNTR</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Live</span>
            </div>
        </div>

        <!-- KPI Cards (Col 10) -->
        <div class="col-span-10 grid grid-cols-4 gap-3">
            <div
                class="bg-white dark:bg-dark-surface px-4 py-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex items-center gap-3">
                <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0" />
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Income</div>
                    <div class="text-lg font-black text-slate-900 dark:text-white leading-none">
                        ₹@indian_format($totalRevenue/1000, 1)k</div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-dark-surface px-4 py-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex items-center gap-3">
                <div class="p-2 bg-rose-50 dark:bg-rose-500/10 rounded-xl text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Expense</div>
                    <div class="text-lg font-black text-slate-900 dark:text-white leading-none">
                        ₹@indian_format($totalExpenses/1000, 1)k</div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-dark-surface px-4 py-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex items-center gap-3">
                <div
                    class="p-2 {{ $netProfit >= 0 ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600' }} rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Net Profit</div>
                    <div class="text-lg font-black text-slate-900 dark:text-white leading-none">
                        ₹@indian_format($netProfit/1000, 1)k</div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-dark-surface px-4 py-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex items-center gap-3">
                <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active Jobs</div>
                    <div class="text-lg font-black text-slate-900 dark:text-white leading-none">{{ $totalProjects }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid (Auto Height) -->
    <div class="flex-grow grid grid-cols-12 gap-4 min-h-0">

        <!-- Col 1: Active Projects List (Span 3) -->
        <div
            class="col-span-3 bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium flex flex-col p-5 overflow-hidden">
            <div class="flex items-center justify-between mb-4 flex-shrink-0">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Active Sites</h3>
                <span class="bg-brand-50 text-brand-600 text-[10px] font-bold px-2 py-0.5 rounded-md">{{ $totalProjects
                    }}</span>
            </div>
            <div class="overflow-y-auto pr-2 space-y-3 flex-grow custom-scrollbar">
                @forelse($recentProjects as $project)
                <a href="{{ route('clients.show', $project) }}"
                    class="block group p-3 rounded-2xl bg-slate-50 dark:bg-dark-bg/50 hover:bg-brand-500 hover:text-white transition-all border border-transparent hover:border-brand-400 relative overflow-hidden">
                    <div class="flex items-center gap-3 relative z-10">
                        <div
                            class="w-8 h-8 rounded-lg bg-white/50 flex items-center justify-center font-black text-[10px] text-slate-500 group-hover:text-brand-600 shadow-sm">
                            {{ substr($project->first_name, 0, 1) }}{{ substr($project->last_name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-xs truncate">{{ $project->first_name }} {{ $project->last_name }}
                            </div>
                            <div
                                class="text-[9px] opacity-60 font-medium uppercase tracking-wider group-hover:text-white/80">
                                Active Execution</div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-10 text-xs text-slate-400">No active projects</div>
                @endforelse

                @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                <a href="{{ route('clients.create') }}"
                    class="block p-3 border-2 border-dashed border-slate-200 dark:border-dark-border rounded-xl text-center text-xs font-bold text-slate-400 hover:border-brand-500 hover:text-brand-500 transition-all uppercase tracking-widest mt-2">
                    + Deploy New Site
                </a>
                @endif
            </div>
        </div>

        <!-- Col 2: Pulse Feed & Financial Chart (Span 6) -->
        <div class="col-span-6 grid grid-rows-2 gap-4 min-h-0">
            <!-- Top: Financial Chart -->
            <div
                class="bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium p-5 relative overflow-hidden flex flex-col">
                <div class="flex justify-between items-center mb-2 flex-shrink-0">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Cashflow Velocity</h3>
                    <div class="flex gap-2 text-[9px] font-bold uppercase">
                        <span class="flex items-center gap-1 text-emerald-500"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> In</span>
                        <span class="flex items-center gap-1 text-rose-500"><span
                                class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Out</span>
                    </div>
                </div>
                <div class="flex-grow w-full relative min-h-0">
                    <canvas id="projectsChart"></canvas>
                </div>
            </div>

            <!-- Bottom: Live Feed -->
            <div
                class="bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium p-5 flex flex-col overflow-hidden">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Live Operations</h3>
                    <div class="animate-pulse w-1.5 h-1.5 bg-brand-500 rounded-full"></div>
                </div>
                <div class="overflow-y-auto pr-2 space-y-3 custom-scrollbar flex-grow">
                    @forelse($recentReports as $report)
                    <div class="flex gap-3 items-start p-3 rounded-xl bg-slate-50/50 dark:bg-dark-bg/30">
                        <div class="w-1.5 h-1.5 rounded-full bg-brand-500 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-brand-600 mb-0.5">{{
                                $report->client->first_name }}</div>
                            <div class="text-xs text-slate-700 dark:text-slate-300 line-clamp-1 font-medium">{{
                                $report->content }}</div>
                            <div class="text-[9px] text-slate-400 mt-1">{{ $report->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    @forelse($recentPayments as $payment)
                    <div class="flex gap-3 items-start p-3 rounded-xl bg-emerald-50/30 dark:bg-emerald-500/5">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1.5 flex-shrink-0"></div>
                        <div class="w-full">
                            <div class="flex justify-between w-full">
                                <div class="text-[10px] font-black uppercase text-emerald-600 mb-0.5">{{
                                    $payment->client->first_name }}</div>
                                <div class="text-[10px] font-black text-emerald-600">+₹@indian_format($payment->amount)
                                </div>
                            </div>
                            <div class="text-[9px] text-slate-400 mt-1">Payment Received • {{ $payment->date->format('d
                                M') }}</div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Col 3: Logistics & Pipeline (Span 3) -->
        <div class="col-span-3 flex flex-col gap-4 min-h-0">
            <!-- Pipeline -->
            <div
                class="flex-grow bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium p-5 flex flex-col overflow-hidden h-1/2">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Pipeline</h3>
                    <span class="text-xs font-bold text-slate-400">{{ $recentQuotations->count() }}</span>
                </div>
                <div class="space-y-2 overflow-y-auto flex-grow custom-scrollbar">
                    @forelse($recentQuotations as $quote)
                    <div
                        class="p-2.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-500/10 border border-transparent hover:border-indigo-200 transition-colors">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[9px] font-black text-indigo-500 uppercase">{{ $quote->client ?
                                $quote->client->first_name : 'New' }}</span>
                            <span class="text-[9px] font-bold text-slate-400">₹@indian_format($quote->total_amount/1000,
                                1)k</span>
                        </div>
                        <div class="text-[10px] text-slate-600 dark:text-slate-300 font-bold truncate">#{{
                            $quote->quotation_number }}</div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-[10px] text-slate-300">No active quotes</div>
                    @endforelse
                </div>
            </div>

            <!-- Logistics -->
            <div
                class="flex-grow bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium p-5 flex flex-col overflow-hidden h-1/2">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Logistics</h3>
                    <span class="text-xs font-bold text-slate-400">{{ $recentMaterials->count() }}</span>
                </div>
                <div class="space-y-2 overflow-y-auto flex-grow custom-scrollbar">
                    @forelse($recentMaterials as $mat)
                    <div
                        class="p-2.5 rounded-xl bg-slate-50 dark:bg-dark-bg border border-slate-100 dark:border-dark-border">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[9px] font-black text-slate-600 uppercase">{{ $mat->supplier_name
                                }}</span>
                            <span class="text-[9px] font-bold text-slate-400">{{ $mat->quantity }}{{ $mat->unit
                                }}</span>
                        </div>
                        <div class="text-[10px] text-slate-800 dark:text-white font-bold truncate">{{ $mat->item_name }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-[10px] text-slate-300">No movement</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // Financial Performance Chart (Income vs Expense)
        const cts = document.getElementById('projectsChart');
        if (ctxProjects) {
            const ctx = ctxProjects.getContext('2d');
            const gradientIncome = ctx.createLinearGradient(0, 0, 0, 400);
            gradientIncome.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Emerald
            gradientIncome.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const gradientExpense = ctx.createLinearGradient(0, 0, 0, 400);
            gradientExpense.addColorStop(0, 'rgba(244, 63, 94, 0.5)'); // Rose
            gradientExpense.addColorStop(1, 'rgba(244, 63, 94, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Income',
                            data: @json($incomeData),
                            borderColor: '#10b981', // Emerald 500
                            backgroundColor: gradientIncome,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Expense',
                            data: @json($expenseData),
                            borderColor: '#f43f5e', // Rose 500
                            backgroundColor: gradientExpense,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000) return '₹' + (value / 1000) + 'k';
                                    return '₹' + value;
                                },
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection