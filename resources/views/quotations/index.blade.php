@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Estimates & Quotations</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Generate and track commercial project
                proposals.</p>
        </div>

        <a href="{{ route('quotations.create') }}"
            class="bg-brand-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition-all shadow-xl shadow-brand-500/20 flex items-center gap-2 group transform active:scale-95">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Draft New Quote
        </a>
    </div>

    <div
        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                        <th class="py-5 px-8">Serial Ref</th>
                        <th class="py-5 px-8">Project / Client Identity</th>
                        <th class="py-5 px-6">Issue Date</th>
                        <th class="py-5 px-6 text-right">Gross Amount</th>
                        <th class="py-5 px-8 text-center">Status</th>
                        <th class="py-5 px-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                    @forelse($quotations as $quotation)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                        <td class="py-6 px-8 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 dark:bg-dark-bg text-slate-600 dark:text-slate-400 text-xs font-bold font-mono">
                                #{{ $quotation->quotation_number }}
                            </span>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 font-bold text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white">{{
                                        $quotation->client->first_name }} {{ $quotation->client->last_name }}</div>
                                    <div
                                        class="text-[10px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-0.5">
                                        Ref: {{ $quotation->client->file_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-6">
                            <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                {{ $quotation->date->format('d M, Y') }}
                            </div>
                        </td>
                        <td class="py-6 px-6 text-right">
                            <div class="text-sm font-black text-brand-600">
                                ₹{{ number_format($quotation->total_amount, 2) }}
                            </div>
                        </td>
                        <td class="py-6 px-8 text-center">
                            <span
                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                {{ $quotation->status == 'approved' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' : 
                                   ($quotation->status == 'rejected' ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10' : 
                                   ($quotation->status == 'sent' ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10' : 'bg-slate-50 text-slate-500 dark:bg-slate-500/10')) }}">
                                {{ $quotation->status }}
                            </span>
                        </td>
                        <td class="py-6 px-8 text-right">
                            <div
                                class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('quotations.show', $quotation) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="View Quotation">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('quotations.destroy', $quotation) }}" method="POST"
                                    onsubmit="return confirm('Purge this quotation record permanently?');"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-400 dark:text-rose-500 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                        title="Delete Record">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white">Quotations Void.</h3>
                                <p
                                    class="text-[11px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-1 italic">
                                    No project estimates have been issued yet.</p>
                                <a href="{{ route('quotations.create') }}"
                                    class="mt-6 text-xs font-black uppercase tracking-widest text-brand-600 hover:text-brand-700 underline underline-offset-8">Draft
                                    First Quote</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotations->hasPages())
        <div class="px-8 py-5 border-t border-ui-border dark:border-dark-border bg-slate-50/30 dark:bg-dark-bg/30">
            {{ $quotations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection