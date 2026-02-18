@extends('layouts.app')

@section('content')
<!-- Custom Dashboard Styles -->
<style>
    :root {
        --dash-bg: #f8fafc;
        --card-bg: #ffffff;
        --card-border: #f1f5f9;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --brand-primary: #6366f1;
    }

    .dark {
        --dash-bg: #0f172a;
        --card-bg: #1e293b;
        --card-border: #334155;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
    }

    .dashboard-container {
        height: calc(100vh - 80px);
        /* Adjust based on navbar height */
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        padding: 1.25rem;
        background: var(--dash-bg);
        font-family: 'Inter', sans-serif;
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        flex-shrink: 0;
    }

    .main-grid {
        display: grid;
        grid-template-columns: 280px 1fr 280px;
        gap: 1.25rem;
        flex-grow: 1;
        min-height: 0;
        /* Critical for inner scrolling */
    }

    .center-section {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        min-height: 0;
    }

    .chart-zone {
        flex-grow: 2;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
        min-height: 0;
    }

    .intel-row {
        height: 140px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        flex-shrink: 0;
    }

    .side-panel {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        overflow: hidden;
    }

    .scrollable-list {
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .scrollable-list::-webkit-scrollbar {
        width: 4px;
    }

    .scrollable-list::-webkit-scrollbar-thumb {
        background: var(--card-border);
        border-radius: 10px;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        line-height: 1;
    }

    .status-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .alert-card {
        padding: 1rem;
        border-left: 4px solid #ef4444;
        background: rgba(239, 68, 68, 0.05);
    }

    .progress-ring {
        transition: stroke-dashoffset 0.35s;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
</style>

<div class="dashboard-container">

    <!-- Top KPI Section -->
    <div class="kpi-grid">
        <!-- Income -->
        <div class="glass-card p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Income</span>
                <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value text-emerald-500">₹@indian_format($totalRevenue/1000, 1)k</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-2">↑ 12% Growth</div>
        </div>

        <!-- Expense -->
        <div class="glass-card p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Expense</span>
                <div class="p-2 bg-rose-500/10 text-rose-500 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value text-rose-500">₹@indian_format($totalExpenses/1000, 1)k</div>
            <div class="text-[10px] text-rose-400 font-bold mt-2">Locked Budget: ₹@indian_format($totalRevenue/1000, 0)k
            </div>
        </div>

        <!-- Net Profit -->
        <div class="glass-card p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Net Profit</span>
                <div class="p-2 bg-indigo-500/10 text-indigo-500 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value text-indigo-500">₹@indian_format($netProfit/1000, 1)k</div>
            <div class="text-[10px] text-indigo-400 font-bold mt-2">Margin: {{ number_format($profitMargin, 1) }}%</div>
        </div>

        <!-- Active Projects -->
        <div class="glass-card p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active Execution</span>
                <div class="p-2 bg-amber-500/10 text-amber-500 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value text-slate-900 dark:text-white">{{ $activeProjects }}</div>
            <div class="text-[10px] text-slate-400 font-bold mt-2">Avg. Completion: {{ number_format($completionRate, 0)
                }}%</div>
        </div>

        <!-- Overdue -->
        <div
            class="glass-card p-5 flex flex-col justify-between border-rose-100 dark:border-rose-900 {{ $overduePayments > 0 ? 'bg-rose-50/50 dark:bg-rose-950/20' : '' }}">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-rose-500">Overdue Recovery</span>
                <div class="p-2 bg-rose-500/10 text-rose-500 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value text-rose-600">₹@indian_format($overduePayments/1000, 1)k</div>
            <div class="text-[10px] text-rose-400 font-bold mt-2 font-mono">CRITICAL ACTION REQ</div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-grid">

        <!-- Left Panel: Client Snapshot -->
        <div class="side-panel">
            <div class="glass-card flex flex-col h-full p-5 overflow-hidden">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[2px] mb-4">Top Portfolio</h3>
                <div class="scrollable-list flex-grow">
                    @foreach($topClients as $client)
                    <div
                        class="p-3 mb-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-brand-500 hover:text-white transition-all group cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center font-black text-[10px] group-hover:text-brand-600">
                                {{ substr($client->first_name, 0, 1 )}}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold truncate">{{ $client->first_name }} {{ $client->last_name
                                    }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $client->risk_analysis['level'] == 'Low' ? 'bg-emerald-500' : ($client->risk_analysis['level'] == 'Medium' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    <span
                                        class="text-[9px] font-bold uppercase opacity-60 dark:text-slate-400 font-mono">{{
                                        $client->risk_analysis['level'] }} Risk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('clients.index') }}"
                    class="mt-4 text-[10px] font-black text-center text-brand-500 dark:text-brand-400 uppercase tracking-widest hover:underline">View
                    All Projects →</a>
            </div>
        </div>

        <!-- Center: Mixed Visualizations -->
        <div class="center-section">
            <div class="chart-zone glass-card">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Cashflow
                            & Completion Velocity</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Real-time performance analytics (Last 6
                            Months)</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2"><span
                                class="w-2 h-2 rounded-full bg-indigo-500"></span><span
                                class="text-[9px] font-black uppercase text-slate-400">Income</span></div>
                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span
                                class="text-[9px] font-black uppercase text-slate-400">Expense</span></div>
                        <div class="flex items-center gap-2"><span
                                class="w-2 h-2 rounded-full bg-emerald-500"></span><span
                                class="text-[9px] font-black uppercase text-slate-400">Completion</span></div>
                    </div>
                </div>
                <div class="flex-grow">
                    <canvas id="mainDashboardChart"></canvas>
                </div>
            </div>

            <!-- Bottom Intel Row -->
            <div class="intel-row">
                <div class="glass-card p-4 flex items-center gap-4">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <svg class="w-12 h-12">
                            <circle class="text-slate-100 dark:text-slate-800" stroke-width="4" stroke="currentColor"
                                fill="transparent" r="20" cx="24" cy="24" />
                            <circle class="text-indigo-500 progress-ring" stroke-width="4" stroke-dasharray="125.6"
                                stroke-dashoffset="{{ 125.6 * (1 - $profitMargin/100) }}" stroke-linecap="round"
                                stroke="currentColor" fill="transparent" r="20" cx="24" cy="24" />
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-[9px] font-black">{{
                            number_format($profitMargin, 0) }}%</span>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Profit Margin</h4>
                        <div class="text-sm font-black dark:text-white">Healthy</div>
                    </div>
                </div>

                <div class="glass-card p-4 flex flex-col justify-center">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Resource Cost</h4>
                    <div class="flex gap-1 h-2 rounded-full overflow-hidden">
                        @php
                        $totalEx = array_sum($expenseDist);
                        $vP = $totalEx > 0 ? ($expenseDist['Vendor'] / $totalEx) * 100 : 0;
                        $mP = $totalEx > 0 ? ($expenseDist['Material'] / $totalEx) * 100 : 0;
                        $gP = $totalEx > 0 ? ($expenseDist['General'] / $totalEx) * 100 : 0;
                        @endphp
                        <div class="bg-indigo-500" style="width: {{ $vP }}%"></div>
                        <div class="bg-emerald-500" style="width: {{ $mP }}%"></div>
                        <div class="bg-amber-500" style="width: {{ $gP }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] font-bold uppercase text-slate-400">
                        <span>Vendor</span><span>Material</span><span>Gen</span>
                    </div>
                </div>

                <div class="glass-card p-4 flex flex-col justify-center">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Avg Output</h4>
                    <div class="text-lg font-black dark:text-white">4.2 <span
                            class="text-[10px] text-emerald-500">Tasks/Day</span></div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 mt-2 rounded-full overflow-hidden">
                        <div class="bg-brand-500 h-full" style="width: 75%"></div>
                    </div>
                </div>

                <div class="glass-card p-4 flex items-center gap-3">
                    <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Inflow</h4>
                        <div class="text-xs font-bold dark:text-white">
                            ₹@if($recentPayments->first())@indian_format($recentPayments->first()->amount/1000, 1)k
                            @else 0 @endif</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Alerts & Actions -->
        <div class="side-panel">
            <div class="glass-card flex flex-col h-full p-5 overflow-hidden">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[2px] mb-4">Critical Intelligence</h3>
                <div class="scrollable-list flex-grow space-y-3">
                    @forelse($alerts as $alert)
                    <div
                        class="alert-card rounded-xl {{ $alert['type'] == 'critical' ? 'border-rose-500 bg-rose-500/5' : ($alert['type'] == 'danger' ? 'border-amber-500 bg-amber-500/5' : 'border-indigo-500 bg-indigo-500/5') }}">
                        <div
                            class="text-[10px] font-black uppercase {{ $alert['type'] == 'critical' ? 'text-rose-600' : ($alert['type'] == 'danger' ? 'text-amber-600' : 'text-indigo-600') }}">
                            {{ $alert['title'] }}</div>
                        <div class="text-xs font-bold mt-1 dark:text-white leading-tight">{{ $alert['desc'] }}</div>
                        <a href="{{ route('clients.show', $alert['client_id']) }}"
                            class="text-[9px] font-black uppercase text-slate-400 mt-2 block hover:text-brand-500 transition-colors">Take
                            Action →</a>
                    </div>
                    @empty
                    <div class="text-center py-20">
                        <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest">All Systems Normal
                        </div>
                    </div>
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
        const isDark = document.documentElement.classList.contains('dark');
        Chart.defaults.color = isDark ? "#94a3b8" : "#64748b";

        const ctx = document.getElementById('mainDashboardChart').getContext('2d');

        // Income Gradient
        const gradientInc = ctx.createLinearGradient(0, 0, 0, 400);
        gradientInc.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
        gradientInc.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        type: 'line',
                        label: 'Inflow',
                        data: @json($incomeData),
                        borderColor: '#6366f1',
                        borderWidth: 4,
                        fill: true,
                        backgroundColor: gradientInc,
                        tension: 0.4,
                        pointRadius: 0,
                        order: 1
                    },
                    {
                        type: 'line',
                        label: 'Outflow',
                        data: @json($expenseData),
                        borderColor: '#f43f5e',
                        borderWidth: 3,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.4,
                        pointRadius: 0,
                        order: 2
                    },
                    {
                        type: 'bar',
                        label: 'Completed',
                        data: @json($completionData),
                        backgroundColor: '#10b981',
                        borderRadius: 10,
                        order: 3,
                        yAxisID: 'y1',
                        barThickness: 20
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 12,
                        bodyFont: { weight: 'bold' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.05)', drawBorder: false },
                        ticks: {
                            callback: value => '₹' + (value / 1000) + 'k',
                            font: { size: 9, weight: '900' }
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: { display: false },
                        ticks: { font: { size: 9, weight: '900' } },
                        title: { display: true, text: 'Completion Count', font: { size: 9, weight: '900' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '900' } }
                    }
                }
            }
        });
    });
</script>
@endsection