@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Financial Requisition</h1>
                <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Initialize advance payment
                    requests or stage-wise invoicing.</p>
            </div>
            <a href="{{ route('clients.index') }}"
                class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Abort
            </a>
        </div>

        <div
            class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
            <div class="px-10 py-6 border-b border-ui-border dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50">
                <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
                    <span
                        class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </span>
                    Requisition Details
                </h3>
            </div>

            <form action="{{ route('payment-requests.store') }}" method="POST" class="p-10 space-y-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Target Client
                            Identity <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="client_id" required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-black appearance-none cursor-pointer focus:ring-4 focus:ring-brand-500/10 transition-all"
                                onchange="window.location.href='?client_id='+this.value">
                                <option value="">-- Choose Account --</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $selectedClientId==$client->id ? 'selected' : ''
                                    }}>
                                    {{ $client->first_name }} {{ $client->last_name }} (#{{ $client->file_number }})
                                </option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Linked
                            Valuation Record</label>
                        <div class="relative">
                            <select name="quotation_id"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-black appearance-none cursor-pointer focus:ring-4 focus:ring-brand-500/10 transition-all">
                                <option value="">-- Generic Requisition --</option>
                                @foreach($quotations as $quotation)
                                <option value="{{ $quotation->id }}">{{ $quotation->quotation_number }} (₹{{
                                    number_format($quotation->total_amount) }})</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Requisition
                            Nomenclature <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. 50% Mobilization Advance"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Total
                            Valuation (₹) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-brand-600 font-black">₹</span>
                            <input type="number" name="amount" step="0.01" required placeholder="0.00"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl pl-10 pr-5 py-4 text-lg font-black text-brand-600 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Proposed
                            Settlement Date</label>
                        <input type="date" name="due_date"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Technical
                        Manifesto / Client Notes</label>
                    <textarea name="description" rows="4"
                        placeholder="Clarify stage-wise completion or specify banking protocols..."
                        class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-3xl px-6 py-5 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300"></textarea>
                </div>

                <div class="pt-6 border-t border-ui-border dark:border-dark-border flex justify-end">
                    <button type="submit"
                        class="bg-brand-600 text-white px-10 py-5 rounded-3xl text-[11px] font-black uppercase tracking-[0.25em] hover:bg-brand-700 transition-all shadow-2xl shadow-brand-500/30 active:scale-95 group flex items-center gap-4">
                        Transmit Requisition
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection