@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('work-orders.show', $workOrder) }}"
                class="p-3 bg-white dark:bg-slate-900 rounded-2xl text-slate-400 hover:text-brand-500 shadow-sm transition-all border border-slate-100 dark:border-dark-border">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white font-display tracking-tight">Edit Work Order
            </h1>
        </div>

        <form action="{{ route('work-orders.update', $workOrder) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div
                class="bg-white dark:bg-slate-900/40 rounded-[32px] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
                <div class="p-8 space-y-8">
                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Select
                                Project</label>
                            <select name="client_id" required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $workOrder->client_id == $client->id ? 'selected' :
                                    '' }}>{{ $client->first_name }} {{ $client->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Contractor
                                / Vendor</label>
                            <select name="vendor_id"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                                <option value="">Direct / Self-Managed</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ $workOrder->vendor_id == $vendor->id ? 'selected' :
                                    '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Job
                            Title</label>
                        <input type="text" name="title" value="{{ $workOrder->title }}" required
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Detailed
                            Description of Work</label>
                        <textarea name="description" rows="4"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">{{ $workOrder->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Contract
                                Amount (₹)</label>
                            <input type="number" name="total_amount" value="{{ $workOrder->total_amount }}" required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-black text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Issue
                                Date</label>
                            <input type="date" name="issue_date" value="{{ $workOrder->issue_date->format('Y-m-d') }}"
                                required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Payment
                            Terms</label>
                        <textarea name="payment_terms" rows="3"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">{{ $workOrder->payment_terms }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Current
                                Status</label>
                            <select name="status"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                                <option value="draft" {{ $workOrder->status == 'draft' ? 'selected' : '' }}>Draft
                                    (Negotiating)</option>
                                <option value="sent" {{ $workOrder->status == 'sent' ? 'selected' : '' }}>Sent to
                                    Contractor</option>
                                <option value="accepted" {{ $workOrder->status == 'accepted' ? 'selected' : ''
                                    }}>Accepted (LOCKED)</option>
                                <option value="completed" {{ $workOrder->status == 'completed' ? 'selected' : ''
                                    }}>Completed</option>
                                <option value="cancelled" {{ $workOrder->status == 'cancelled' ? 'selected' : ''
                                    }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    class="p-8 bg-slate-50 dark:bg-white/5 border-t border-slate-100 dark:border-dark-border flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-4 rounded-2xl font-black uppercase tracking-widest transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                        Update Work Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection