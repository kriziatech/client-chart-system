@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700 print:p-0">
    <!-- Action Header (Non-Printable) -->
    <div class="max-w-4xl mx-auto mb-8 no-print">
        <div
            class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
            <div class="px-8 py-5 flex justify-between items-center bg-slate-50/50 dark:bg-dark-bg/50">
                <div class="flex items-center gap-4">
                    <h2 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white">Transaction
                        Acknowledgement</h2>
                    <span
                        class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">Settled</span>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()"
                        class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition-all shadow-lg shadow-brand-500/20 active:scale-95 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Export PDF
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Discard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Receipt Layout -->
    <div
        class="max-w-4xl mx-auto bg-white p-16 shadow-2xl print:shadow-none print:p-0 min-h-[900px] border border-slate-100 relative overflow-hidden flex flex-col scale-[0.98] origin-top transition-transform hover:scale-100 duration-500">
        <!-- Floating Status Label -->
        <div
            class="absolute top-0 right-0 px-10 py-4 bg-slate-900 text-white font-black text-[10px] uppercase tracking-[4px]">
            Official Receipt
        </div>

        <!-- Corporate Header -->
        <div class="flex justify-between items-start mb-20">
            <div class="space-y-3">
                <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter">{{ config('app.name') }}</h1>
                <p class="text-[11px] font-black text-brand-600 uppercase tracking-[0.3em]">Premium Interior Logistics &
                    Design</p>
                <div class="pt-6 space-y-1.5 font-bold text-xs text-slate-500">
                    <p class="uppercase tracking-widest">GSTIN: <span class="text-slate-900">27AAACH1234F1Z5</span></p>
                    <p class="uppercase tracking-widest">Reg: <span class="text-slate-900">MH/PR-912837/2026</span></p>
                </div>
            </div>
            <div class="text-right">
                <div class="space-y-6">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Receipt
                            ID</span>
                        <span class="text-xl font-black text-slate-900 font-mono tracking-widest">#{{
                            $payment->receipt_number ?: str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Emission
                            Date</span>
                        <span class="text-sm font-black text-slate-900 uppercase">
                            {{ \Carbon\Carbon::parse($payment->date)->format('d F, Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Manifest -->
        <div class="flex-grow space-y-12">
            <div class="flex items-baseline gap-6 border-b-2 border-slate-100 pb-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest w-56">Remitter
                    Identity</span>
                <span class="text-2xl font-black text-slate-900 flex-grow tracking-tight">{{
                    $payment->client->first_name }} {{ $payment->client->last_name }}</span>
            </div>

            <div class="flex items-baseline gap-6 border-b-2 border-slate-100 pb-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest w-56">Valuation Basis
                    (Net)</span>
                <span class="text-xl font-black text-brand-600 flex-grow tracking-widest">INR
                    @indian_format($payment->amount, 2)</span>
            </div>

            <div class="flex items-baseline gap-6 border-b-2 border-slate-100 pb-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest w-56">Dossier / Account
                    Ref</span>
                <span class="text-xs font-black text-slate-900 flex-grow uppercase tracking-widest italic">
                    {{ $payment->purpose ?: 'General Project Mobilization / Advance Payment' }}
                </span>
            </div>

            <!-- Total Block -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-16">
                <div class="bg-slate-50 p-10 rounded-[2.5rem] border-2 border-slate-100">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] block mb-4">Total
                        Settled Amount</span>
                    <div class="text-5xl font-black text-slate-900 tracking-tighter">₹{{ number_format($payment->amount,
                        2) }}</div>
                    <div class="mt-8 flex items-center gap-3">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Instrument:</span>
                        <span
                            class="text-[10px] font-black text-emerald-600 uppercase tracking-widest px-3 py-1 bg-emerald-50 rounded-lg">{{
                            $payment->payment_method ?: 'Digital Liquidation' }}</span>
                    </div>
                </div>

                <div class="flex flex-col justify-end items-center pb-10">
                    <div class="w-full space-y-4">
                        <div class="h-16 flex items-end justify-center">
                            <!-- Placeholder for digital stamp/sig -->
                            <div class="w-32 h-32 opacity-10 flex items-center justify-center rotate-12">
                                <svg class="w-full h-full text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v2h-2zm1-13c3.87 0 7 3.13 7 7 0 1.25-.32 2.43-.88 3.46l-1.42-1.42C16.89 12.56 17 11.8 17 11c0-2.76-2.24-5-5-5s-5 2.24-5 5c0 .8.11 1.56.3 2.04l-1.42 1.42C5.32 13.43 5 12.25 5 11c0-3.87 3.13-7 7-7zm-4.34 11.66c-.66-1.1-1-2.35-1-3.66h2c0 .91.24 1.77.67 2.53l-1.67 1.13zm10.68 0l-1.67-1.13c.43-.76.67-1.62.67-2.53h2c0 1.31-.34 2.56-1 3.66z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="border-t-2 border-slate-900 pt-4 text-center">
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-[0.25em]">Authorized
                                Signatory</p>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 italic tracking-widest">Interior Touch PM
                                Enterprise Node</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div
            class="mt-24 pt-10 border-t border-slate-100 flex justify-between items-center text-[9px] font-black text-slate-300 uppercase tracking-widest">
            <span>Verified System Chronology</span>
            <span>Ref: {{ hash('crc32', $payment->id . time()) }}</span>
            <span>Generation: {{ now()->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>
</div>

<style>
    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .max-w-4xl {
            max-width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .scale-\[0\.98\] {
            scale: 1 !important;
            transform: none !important;
        }
    }
</style>
@endsection