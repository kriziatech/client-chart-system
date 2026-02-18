@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Back & Actions --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('work-orders.index') }}"
                class="p-3 bg-white dark:bg-slate-900 rounded-2xl text-slate-400 hover:text-brand-500 shadow-sm transition-all border border-slate-100 dark:border-dark-border">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex gap-3">
                <a href="{{ route('work-orders.edit', $workOrder) }}"
                    class="px-6 py-3 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm border border-slate-100 dark:border-dark-border hover:bg-slate-50 transition-all">Edit
                    Order</a>
                <button onclick="window.print()"
                    class="px-6 py-3 bg-brand-600 text-white rounded-xl font-black text-sm uppercase tracking-widest shadow-lg shadow-brand-500/20 hover:scale-105 transition-all">Download
                    PDF</button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-900/40 rounded-[40px] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
            {{-- Order Header --}}
            <div class="p-10 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/5">
                <div class="flex justify-between items-start">
                    <div>
                        <span
                            class="px-3 py-1 bg-brand-500 text-white text-[10px] font-black uppercase tracking-[2px] rounded-lg mb-4 inline-block">Work
                            Order</span>
                        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">{{
                            $workOrder->work_order_number }}</h1>
                        <p class="text-slate-500 font-bold mt-2">{{ $workOrder->title }}</p>
                    </div>
                    <div class="text-right">
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Status</span>
                        <span
                            class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest 
                            @if($workOrder->status == 'draft') bg-slate-100 text-slate-500 @elseif($workOrder->status == 'sent') bg-blue-100 text-blue-600 @elseif($workOrder->status == 'accepted') bg-emerald-100 text-emerald-600 @elseif($workOrder->status == 'completed') bg-indigo-100 text-indigo-600 @else bg-rose-100 text-rose-600 @endif">
                            {{ $workOrder->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-12">
                {{-- Details Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Issue
                            Date</span>
                        <div class="text-sm font-bold text-slate-700 dark:text-white px-1">{{
                            $workOrder->issue_date->format('d M, Y') }}</div>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Contractor</span>
                        <div class="text-sm font-bold text-slate-700 dark:text-white px-1">{{ $workOrder->vendor->name
                            ?? 'Internal Team' }}</div>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Project</span>
                        <div class="text-sm font-bold text-slate-700 dark:text-white px-1">{{
                            $workOrder->client->first_name }} {{ $workOrder->client->last_name }}</div>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Total
                            Value</span>
                        <div class="text-lg font-black text-brand-600 px-1">
                            ₹@indian_format($workOrder->total_amount/1000, 1)k</div>
                    </div>
                </div>

                {{-- Scope --}}
                <div>
                    <h3
                        class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-brand-500 rounded-full"></span>
                        Scope of Work
                    </h3>
                    <div
                        class="bg-slate-50 dark:bg-white/5 rounded-3xl p-6 text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium whitespace-pre-line border border-slate-100 dark:border-dark-border">
                        {{ $workOrder->description ?: 'No detailed scope provided.' }}
                    </div>
                </div>

                {{-- Payment Terms --}}
                <div>
                    <h3
                        class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                        Payment Terms & Conditions
                    </h3>
                    <div
                        class="bg-emerald-50/30 dark:bg-emerald-500/5 rounded-3xl p-6 text-sm text-emerald-700 dark:text-emerald-300 font-bold whitespace-pre-line border border-emerald-100/50 dark:border-emerald-500/20">
                        {{ $workOrder->payment_terms ?: 'Standard billing cycles apply.' }}
                    </div>
                </div>
            </div>

            {{-- Footer / Signature Area --}}
            <div class="p-10 bg-slate-50 dark:bg-white/5 border-t border-slate-100 dark:border-dark-border">
                <div class="flex justify-between items-end opacity-50">
                    <div>
                        <div class="h-px w-48 bg-slate-300 dark:bg-slate-700 mb-2"></div>
                        <span class="text-[8px] font-black uppercase tracking-widest">Authorized Signature</span>
                    </div>
                    <div>
                        <div class="h-px w-48 bg-slate-300 dark:bg-slate-700 mb-2"></div>
                        <span class="text-[8px] font-black uppercase tracking-widest">Contractor Acknowledgment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {

        .no-print,
        nav,
        button,
        a {
            display: none !important;
        }

        .min-h-screen {
            min-h-0 !important;
            py-0 !important;
        }

        .bg-[#F8FAFC] {
            background: white !important;
        }

        .shadow-premium {
            box-shadow: none !important;
        }

        .rounded-[40px] {
            border-radius: 0 !important;
        }

        .border {
            border-color: #eee !important;
        }
    }
</style>
@endsection