@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Material Logistics</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Manage global inventory inventory,
                standard pricing, and stock levels.</p>
        </div>

        <a href="{{ route('inventory.create') }}"
            class="bg-brand-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition-all shadow-xl shadow-brand-500/20 flex items-center gap-2 group transform active:scale-95">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Catalog New Item
        </a>
    </div>

    <div
        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                        <th class="py-5 px-8">Material Identity</th>
                        <th class="py-5 px-8">Logistics Category</th>
                        <th class="py-5 px-6">Measurement Unit</th>
                        <th class="py-5 px-6 text-right">Market Price (₹)</th>
                        <th class="py-5 px-8 text-center">Procurement Alert</th>
                        <th class="py-5 px-8 text-right">Edit/Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                    @forelse($items as $item)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                        <td class="py-6 px-8 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center text-slate-400 font-bold text-xs">
                                    {{ substr($item->name, 0, 2) }}
                                </div>
                                <div class="text-sm font-black text-slate-900 dark:text-white">{{ $item->name }}</div>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[10px] font-black uppercase tracking-widest">
                                {{ $item->category ?: 'General Logistics' }}
                            </span>
                        </td>
                        <td class="py-6 px-6">
                            <div class="text-xs font-bold text-slate-400 tracking-tighter uppercase">
                                {{ $item->unit }}
                            </div>
                        </td>
                        <td class="py-6 px-6 text-right">
                            <div class="text-sm font-black text-slate-900 dark:text-white">
                                ₹{{ number_format($item->unit_price, 2) }}
                            </div>
                        </td>
                        <td class="py-6 px-8 text-center">
                            @if($item->stock_alert_level > 0)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-600 font-black text-[10px] tracking-widest uppercase">
                                <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                                Level: {{ $item->stock_alert_level }}
                            </div>
                            @else
                            <span
                                class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Standard</span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-right">
                            <div
                                class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('inventory.edit', $item) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="Modify Specs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('inventory.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Purge this material from the global catalog?');"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-400 dark:text-rose-500 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                        title="Delete Item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 dark:bg-dark-bg rounded-3xl flex items-center justify-center text-slate-200 mb-4 border border-slate-100 dark:border-dark-border border-dashed">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white">Catalog Depleted.</h3>
                                <p
                                    class="text-[11px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-1 italic">
                                    No materials have been indexed in your configuration.</p>
                                <a href="{{ route('inventory.create') }}"
                                    class="mt-6 text-xs font-black uppercase tracking-widest text-brand-600 hover:text-brand-700 underline underline-offset-8">Draft
                                    First Material</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
        <div class="px-8 py-5 border-t border-ui-border dark:border-dark-border bg-slate-50/30 dark:bg-dark-bg/30">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection