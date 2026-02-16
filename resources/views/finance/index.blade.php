@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-10 animate-in fade-in duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight font-display">Global Portfolio
                Analytics</h1>
            <p class="text-slate-400 text-sm font-medium uppercase tracking-[2px] mt-1">Real-time Financial Maturity &
                Profitability</p>
        </div>
        <div
            class="bg-white dark:bg-dark-surface px-6 py-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-premium">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">System wide Data</span>
            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ count($projectsData) }} Active Projects
            </div>
        </div>
    </div>

    <!-- Global Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-900 dark:bg-brand-600 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-3xl -mr-16 -mt-16 rounded-full group-hover:scale-150 transition-transform duration-700">
            </div>
            <span class="text-[10px] font-black text-white/60 uppercase tracking-[2px] block mb-2">Aggregate
                Revenue</span>
            <div class="text-3xl font-black text-white tracking-tight">₹@indian_format($globalStats['total_revenue'])
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[10px] font-bold text-white/40 uppercase">Payments Realized</span>
            </div>
        </div>

        <div
            class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium group">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] block mb-2">Direct
                Expenses</span>
            <div class="text-3xl font-black text-rose-500 tracking-tight">
                ₹@indian_format($globalStats['total_expenses'])</div>
            <div class="mt-4 h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="bg-rose-500 h-full w-[45%]"></div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-slate-100 dark:border-dark-border shadow-premium">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] block mb-2">Material
                Value</span>
            <div class="text-3xl font-black text-amber-500 tracking-tight">
                ₹@indian_format($globalStats['total_material'])</div>
            <p class="text-[10px] text-slate-400 mt-4 font-bold uppercase tracking-widest">Inventory Dispatched</p>
        </div>

        <div
            class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border-2 border-brand-500 shadow-premium group hover:bg-brand-50/30 transition-colors">
            <span class="text-[10px] font-black text-brand-600 uppercase tracking-[2px] block mb-2">Net Portfolio
                Profit</span>
            <div class="text-3xl font-black text-brand-600 tracking-tight">₹@indian_format($globalStats['total_profit'])
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-black text-brand-400 uppercase tracking-widest">Margin Analysis 📈</span>
            </div>
        </div>
    </div>

    <!-- Project-wise Ledger -->
    <div
        class="bg-white dark:bg-dark-surface rounded-[3rem] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 dark:border-dark-border flex justify-between items-center">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest">Project Maturity
                Ledger</h3>
            <div class="flex gap-2">
                <button
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500">Export
                    PDF</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[2px] text-slate-400 border-b border-slate-100 dark:border-dark-border">
                        <th class="py-5 px-10">Project Name</th>
                        <th class="py-5 px-8">Inflow (Revenue)</th>
                        <th class="py-5 px-8">Outflow (Costs)</th>
                        <th class="py-5 px-8">Material Cost</th>
                        <th class="py-5 px-10 text-right">Net Maturity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                    @foreach($projectsData as $data)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/20 transition-all duration-300">
                        <td class="py-6 px-10">
                            <a href="{{ route('finance.analytics', $data['client']) }}"
                                class="flex items-center gap-4 group/link">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center text-slate-500 font-bold group-hover/link:bg-brand-500 group-hover/link:text-white transition-all">
                                    {{ substr($data['client']->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-black text-slate-900 dark:text-white group-hover/link:text-brand-600 transition-colors">
                                        {{ $data['client']->first_name }} {{ $data['client']->last_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">#{{
                                        $data['client']->file_number }}</div>
                                </div>
                            </a>
                        </td>
                        <td class="py-6 px-8 text-sm font-bold text-emerald-600">₹@indian_format($data['revenue'])
                        </td>
                        <td class="py-6 px-8 text-sm font-bold text-rose-500">₹@indian_format($data['expenses'])
                        </td>
                        <td class="py-6 px-8 text-sm font-bold text-amber-500">₹@indian_format($data['material_costs'])
                        </td>
                        <td class="py-6 px-10 text-right">
                            <span
                                class="text-lg font-black {{ $data['profit'] >= 0 ? 'text-brand-600' : 'text-rose-600' }}">
                                ₹@indian_format($data['profit'])
                            </span>
                            <p
                                class="text-[10px] font-bold uppercase {{ $data['profit'] >= 0 ? 'text-emerald-500' : 'text-rose-400' }}">
                                {{ $data['revenue'] > 0 ? round(($data['profit'] / $data['revenue']) * 100) : 0 }}%
                                Margin
                            </p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>
@endsection