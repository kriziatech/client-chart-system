@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('work-orders.index') }}"
                class="p-3 bg-white dark:bg-slate-900 rounded-2xl text-slate-400 hover:text-brand-500 shadow-sm transition-all border border-slate-100 dark:border-dark-border">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white font-display tracking-tight">Issue New Work
                Order</h1>
        </div>

        <form action="{{ route('work-orders.store') }}" method="POST" class="space-y-6">
            @csrf
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
                                <option value="">Choose Workspace</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ (isset($selectedClient) && $selectedClient->id ==
                                    $client->id) ? 'selected' : '' }}>{{ $client->first_name }} {{ $client->last_name }}
                                    ({{ $client->file_number }})</option>
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
                                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->category }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Job
                            Title</label>
                        <input type="text" name="title" placeholder="e.g. Electrical Concealing - Ground Floor" required
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Detailed
                            Description of Work</label>
                        <textarea name="description" rows="4"
                            placeholder="Specify technical details, materials included, and scope..."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Contract
                                Amount (₹)</label>
                            <input type="number" name="total_amount" required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-black text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Issue
                                Date</label>
                            <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Payment
                            Terms</label>
                        <textarea name="payment_terms" rows="3"
                            placeholder="e.g. 50% Advance, 25% at conceal, 25% after testing..."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-medium text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Current
                                Status</label>
                            <select name="status"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                                <option value="draft">Draft (Negotiating)</option>
                                <option value="sent">Sent to Contractor</option>
                                <option value="accepted">Accepted (LOCKED)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    class="p-8 bg-slate-50 dark:bg-white/5 border-t border-slate-100 dark:border-dark-border flex justify-end">
                    <button type="submit"
                        class="bg-brand-600 hover:bg-brand-700 text-white px-12 py-4 rounded-2xl font-black uppercase tracking-widest transition-all shadow-xl shadow-brand-500/20 active:scale-95">
                        Lauch Work Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection