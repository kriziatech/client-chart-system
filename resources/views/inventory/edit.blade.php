@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Adjust Material Specs</h1>
                <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Modify properties or standard
                    pricing for {{ $item?->name }}.</p>
            </div>
            <a href="{{ route('inventory.index') }}"
                class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Catalog
            </a>
        </div>

        <div
            class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
            <div class="px-8 py-5 border-b border-ui-border dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50">
                <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
                    <span
                        class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </span>
                    Modify Entry
                </h3>
            </div>

            <form action="{{ route('inventory.update', $item?->id ?? 0) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Nomenclature
                            (Material Name) <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ $item?->name }}"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-black text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-400">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Logistic
                            Category</label>
                        <select name="category"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all appearance-none cursor-pointer font-bold text-slate-700 dark:text-slate-300">
                            <option value="" {{ ($item?->category ?? '') == '' ? 'selected' : '' }}>-- Generic --
                            </option>
                            <option value="Civil" {{ ($item?->category ?? '') == 'Civil' ? 'selected' : '' }}>Civil
                            </option>
                            <option value="Wood/Interior" {{ ($item?->category ?? '') == 'Wood/Interior' ? 'selected' :
                                ''
                                }}>Wood/Interior</option>
                            <option value="Electrical" {{ ($item?->category ?? '') == 'Electrical' ? 'selected' : ''
                                }}>Electrical</option>
                            <option value="Plumbing" {{ ($item?->category ?? '') == 'Plumbing' ? 'selected' : ''
                                }}>Plumbing
                            </option>
                            <option value="Paint" {{ ($item?->category ?? '') == 'Paint' ? 'selected' : '' }}>Paint
                            </option>
                            <option value="Hardware" {{ ($item?->category ?? '') == 'Hardware' ? 'selected' : ''
                                }}>Hardware
                            </option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Measurement
                            Unit <span class="text-rose-500">*</span></label>
                        <input type="text" name="unit" required value="{{ $item?->unit }}"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Standard
                            Liquidity (Price)</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-ui-muted dark:text-dark-muted font-bold">₹</span>
                            <input type="number" step="0.01" name="unit_price" value="{{ $item?->unit_price }}"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl pl-9 pr-4 py-3 text-sm font-black text-brand-600 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Total
                            Warehouse Stock</label>
                        <input type="number" name="total_stock" value="{{ $item?->total_stock }}"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm font-black text-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all">
                        <p class="text-[10px] text-ui-muted dark:text-dark-muted mt-1 italic">Current total quantity
                            held in stock.</p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Procurement
                            Threshold (Alert)</label>
                        <input type="number" name="stock_alert_level" value="{{ $item?->stock_alert_level }}"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all">
                        <p class="text-[10px] text-ui-muted dark:text-dark-muted mt-1 italic">Triggers visual alerts
                            when stock falls below this value. (0 = Disabled)</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-ui-border dark:border-dark-border flex justify-end">
                    <button type="submit"
                        class="bg-brand-600 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-brand-700 transition-all shadow-2xl shadow-brand-500/30 flex items-center gap-3 transform active:scale-95 group">
                        Update Material Dossier
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