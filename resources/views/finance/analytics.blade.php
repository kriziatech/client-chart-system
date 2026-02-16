@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-ui-primary dark:text-white tracking-tight">Financial Analysis</h1>
            <p class="text-ui-muted text-sm font-medium uppercase tracking-widest mt-1">Project Profit & Loss Dashboard
            </p>
        </div>
        <div class="text-right">
            <span class="text-[10px] font-black text-ui-muted uppercase tracking-widest">Client Name</span>
            <div class="text-lg font-black text-brand-600">{{ $client->first_name }} {{ $client->last_name }}</div>
        </div>
    </div>

    <!-- P&L Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-dark-surface p-6 rounded-[2rem] border border-ui-border shadow-premium">
            <span class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] block mb-2">Total Revenue</span>
            <div class="text-2xl font-black text-ui-success tracking-tight">₹{{ number_format($totalRevenue) }}</div>
            <p class="text-[10px] text-ui-muted mt-2 font-bold uppercase">Payments Received</p>
        </div>
        <div class="bg-white dark:bg-dark-surface p-6 rounded-[2rem] border border-ui-border shadow-premium">
            <span class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] block mb-2">Operational
                Costs</span>
            <div class="text-2xl font-black text-ui-danger tracking-tight">₹{{ number_format($totalExpenses) }}</div>
            <p class="text-[10px] text-ui-muted mt-2 font-bold uppercase">Labor & Site Costs</p>
        </div>
        <div class="bg-white dark:bg-dark-surface p-6 rounded-[2rem] border border-ui-border shadow-premium">
            <span class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] block mb-2">Material Costs</span>
            <div class="text-2xl font-black text-amber-600 tracking-tight">₹{{ number_format($materialCosts) }}</div>
            <p class="text-[10px] text-ui-muted mt-2 font-bold uppercase">Inventory Dispatched</p>
        </div>
        <div
            class="p-6 rounded-[2rem] border-2 border-brand-600 shadow-xl shadow-brand-500/10 {{ $netProfit >= 0 ? 'bg-brand-50/50' : 'bg-red-50' }}">
            <span class="text-[10px] font-black text-brand-600 uppercase tracking-[2px] block mb-2">
                {{ $netProfit >= 0 ? 'Net Project Profit' : 'Net Project Loss' }}
            </span>
            <div class="text-3xl font-black {{ $netProfit >= 0 ? 'text-brand-600' : 'text-red-600' }} tracking-tight">
                ₹{{ number_format($netProfit) }}</div>
            <div class="text-[10px] font-bold uppercase mt-2 {{ $netProfit >= 0 ? 'text-brand-400' : 'text-red-400' }}">
                Margin Analysis </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Log New Expense -->
        <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
            <h3 class="text-lg font-black text-ui-primary dark:text-white mb-6 uppercase tracking-widest">Log Project
                Expense</h3>
            <form action="{{ route('finance.expense.store', $client) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-black uppercase text-ui-muted mb-2 block">Category</label>
                        <select name="category"
                            class="w-full bg-slate-50 dark:bg-dark-bg border border-ui-border rounded-xl px-4 py-2.5 text-sm font-bold">
                            <option>Labor</option>
                            <option>Contractor Fee</option>
                            <option>Site Visit</option>
                            <option>Logistics</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-black uppercase text-ui-muted mb-2 block">Amount (₹)</label>
                        <input type="number" name="amount" required step="0.01"
                            class="w-full bg-slate-50 dark:bg-dark-bg border border-ui-border rounded-xl px-4 py-2.5 text-sm font-bold"
                            placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase text-ui-muted mb-2 block">Description</label>
                    <input type="text" name="description" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border border-ui-border rounded-xl px-4 py-2.5 text-sm font-bold"
                        placeholder="Reason for expense...">
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase text-ui-muted mb-2 block">Date</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        class="w-full bg-slate-50 dark:bg-dark-bg border border-ui-border rounded-xl px-4 py-2.5 text-sm font-bold">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-ui-primary dark:bg-brand-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-transform active:scale-95 shadow-xl shadow-brand-500/10">Register
                    Expense</button>
            </form>
        </div>

        <!-- Expense Ledger -->
        <div
            class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden">
            <h3 class="text-lg font-black text-ui-primary dark:text-white mb-6 uppercase tracking-widest">Expense Ledger
            </h3>
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($client->expenses->sortByDesc('date') as $expense)
                <div
                    class="flex items-center justify-between p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-white dark:bg-dark-surface rounded-xl flex items-center justify-center text-xs font-black text-brand-600 border border-ui-border">
                            {{ $expense->category[0] }}
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-ui-muted uppercase tracking-wider block">{{
                                $expense->date->format('d M, Y') }}</span>
                            <span class="text-xs font-black text-ui-primary dark:text-white">{{ $expense->description
                                }}</span>
                        </div>
                    </div>
                    <div class="text-sm font-black text-ui-danger">- ₹{{ number_format($expense->amount) }}</div>
                </div>
                @empty
                <div class="text-center py-20 bg-slate-50/50 rounded-3xl border-2 border-dashed border-ui-border">
                    <p class="text-[10px] font-black text-ui-muted uppercase tracking-widest">No expenses logged yet.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection