@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">Work Orders
                </h1>
                <p class="text-slate-500 font-medium mt-1">Manage contractor contracts and job terms</p>
            </div>
            <a href="{{ route('work-orders.create') }}"
                class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-500/20 active:scale-95">
                New Work Order
            </a>
        </div>

        {{-- Work Orders Grid --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse($workOrders as $order)
            <div
                class="bg-white dark:bg-slate-900/40 p-6 rounded-[28px] border border-slate-100 dark:border-dark-border shadow-premium hover:shadow-2xl transition-all group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div
                            class="w-14 h-14 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center group-hover:bg-brand-500 transition-all">
                            <svg class="w-7 h-7 text-slate-400 group-hover:text-white transition-all" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-[10px] font-black text-brand-600 uppercase tracking-widest">{{
                                    $order->work_order_number }}</span>
                                <span
                                    class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest 
                                    @if($order->status == 'draft') bg-slate-100 text-slate-500 @elseif($order->status == 'sent') bg-blue-50 text-blue-600 @elseif($order->status == 'accepted') bg-emerald-50 text-emerald-600 @elseif($order->status == 'completed') bg-indigo-50 text-indigo-600 @else bg-rose-50 text-rose-600 @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ $order->title }}</h3>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    {{ $order->vendor->name ?? 'Direct Hire' }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    {{ $order->client->first_name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-8 px-6 border-l border-slate-100 dark:border-dark-border">
                        <div class="text-right">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] block mb-1">Contract
                                Value</span>
                            <span
                                class="text-xl font-black text-slate-900 dark:text-white">₹@indian_format($order->total_amount/1000,
                                1)k</span>
                        </div>
                        <a href="{{ route('work-orders.show', $order) }}"
                            class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl text-slate-400 hover:text-brand-500 hover:bg-brand-50 transition-all active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-20 text-center">
                <div
                    class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-400">No work orders issued</h3>
                <p class="text-slate-400 mt-2">Issue work orders to contractors to lock terms and costs.</p>
            </div>
            @endforelse

            <div class="mt-8">
                {{ $workOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection