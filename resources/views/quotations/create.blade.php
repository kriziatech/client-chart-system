@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">New Quote / Estimate</h1>
                <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Configure line items and terms
                    for project approval.</p>
            </div>
            <a href="{{ route('quotations.index') }}"
                class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cancel & Exit
            </a>
        </div>

        <form action="{{ route('quotations.store') }}" method="POST" id="quotation-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Item Configuration -->
                <div class="lg:col-span-8 space-y-8">
                    <div
                        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                        <div
                            class="px-8 py-5 border-b border-ui-border dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50 flex justify-between items-center">
                            <h3
                                class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
                                <span
                                    class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">01</span>
                                Bill of Quantities (Items)
                            </h3>
                            <button type="button" id="add-item"
                                class="px-4 py-2 bg-white dark:bg-dark-surface border border-brand-200 dark:border-brand-500/30 text-brand-600 dark:text-brand-400 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-brand-50 dark:hover:bg-brand-900 transition-all shadow-sm flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Append Line Item
                            </button>
                        </div>

                        <div class="p-8">
                            <div id="items-container" class="space-y-4">
                                <!-- Item Header (Desktop) -->
                                <div
                                    class="hidden md:grid grid-cols-12 gap-4 mb-4 px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <div class="col-span-4">Service / Material Description</div>
                                    <div class="col-span-2 text-center">Category</div>
                                    <div class="col-span-1 text-center">Qty</div>
                                    <div class="col-span-1 text-center">Unit</div>
                                    <div class="col-span-3 text-right pr-4">Unit Rate (₹)</div>
                                    <div class="col-span-1"></div>
                                </div>

                                <!-- Initial Item Row -->
                                <div
                                    class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-slate-50 dark:bg-dark-bg/50 rounded-2xl border border-transparent hover:border-brand-500/20 hover:bg-white dark:hover:bg-dark-surface transition-all group relative">
                                    <div class="col-span-4">
                                        <input type="text" name="items[0][description]" required
                                            placeholder="Description of work..."
                                            class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-300 focus:ring-0">
                                    </div>
                                    <div class="col-span-2">
                                        <select name="items[0][type]" required
                                            class="w-full bg-brand-50/50 dark:bg-brand-500/5 border-none rounded-lg text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-brand-500/20 cursor-pointer py-1.5">
                                            <option value="material">Material</option>
                                            <option value="labour">Labour</option>
                                            <option value="work">Work/Service</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <input type="number" step="0.01" name="items[0][quantity]" value="1" required
                                            class="w-full bg-transparent border-none p-1 text-sm font-black text-center text-slate-700 dark:text-slate-300 focus:ring-0 qty">
                                    </div>
                                    <div class="col-span-1">
                                        <input type="text" name="items[0][unit]" placeholder="unit"
                                            class="w-full bg-transparent border-none p-1 text-[11px] font-bold text-center text-ui-muted dark:text-dark-muted focus:ring-0 uppercase tracking-tighter">
                                    </div>
                                    <div class="col-span-3 pr-4">
                                        <input type="number" step="0.01" name="items[0][rate]" required
                                            placeholder="0.00"
                                            class="w-full bg-transparent border-none p-1 text-sm font-black text-right text-brand-600 focus:ring-0 rate">
                                    </div>
                                    <div
                                        class="col-span-1 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button"
                                            class="remove-item text-rose-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium p-8">
                        <h3
                            class="text-xs font-bold uppercase tracking-[2px] text-brand-600 mb-6 flex items-center gap-3">
                            <span
                                class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">02</span>
                            Standard Terms & Manifestos
                        </h3>
                        <textarea name="notes" rows="5"
                            placeholder="Include payment terms, validity, and site protocols..."
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-400 font-medium"></textarea>
                    </div>
                </div>

                <!-- Right Column: Meta & Calculations -->
                <div class="lg:col-span-4 space-y-8">
                    <div
                        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium p-8 sticky top-8">
                        <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 mb-8">Quote Intelligence
                        </h3>

                        <div class="space-y-6">
                            <!-- Client Selection -->
                            <div class="space-y-2">
                                <label
                                    class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Linked
                                    Client Charter</label>
                                <select name="client_id" required
                                    class="w-full bg-brand-50/50 dark:bg-brand-500/5 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all appearance-none cursor-pointer font-bold">
                                    <option value="">-- Select Identity --</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $selectedClientId==$client->id ? 'selected' :
                                        '' }}>
                                        {{ $client->first_name }} {{ $client->last_name }} ({{ $client->file_number }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quote ID -->
                            <div class="space-y-2">
                                <label
                                    class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Quotation
                                    Sequence #</label>
                                <input type="text" name="quotation_number" value="{{ $quotationNumber }}" required
                                    class="w-full bg-slate-100 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm font-black tracking-widest text-slate-500 focus:ring-0 cursor-not-allowed uppercase">
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                        class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl p-3 text-xs font-bold focus:ring-4 focus:ring-brand-500/10">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Expiring</label>
                                    <input type="date" name="valid_until"
                                        value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                        class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl p-3 text-xs font-bold focus:ring-4 focus:ring-brand-500/10">
                                </div>
                            </div>

                            <!-- Summary Block -->
                            <div class="mt-8 pt-8 border-t border-ui-border dark:border-dark-border space-y-4">
                                <div class="flex justify-between items-center text-xs font-bold">
                                    <span class="text-ui-muted dark:text-dark-muted uppercase tracking-widest">Base
                                        Total</span>
                                    <span class="text-slate-900 dark:text-white" id="subtotal">₹0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-bold">
                                    <span class="text-ui-muted dark:text-dark-muted uppercase tracking-widest">GST Levy
                                        (18%)</span>
                                    <span class="text-slate-900 dark:text-white" id="tax">₹0.00</span>
                                </div>
                                <div
                                    class="flex justify-between items-center pt-4 border-t border-ui-border dark:border-dark-border">
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-brand-600">Grand
                                        Valuation</span>
                                    <span class="text-xl font-black text-brand-600" id="total">₹0.00</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-600 text-white px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-brand-700 transition-all shadow-2xl shadow-brand-500/30 flex items-center justify-center gap-3 transform active:scale-95 group mt-8">
                                Deploy Quotation
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemsContainer = document.getElementById('items-container');
        const addItemBtn = document.getElementById('add-item');
        let itemIndex = 1;

        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const rate = parseFloat(row.querySelector('.rate').value) || 0;
                subtotal += qty * rate;
            });

            const tax = subtotal * 0.18;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = '₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2 });
            document.getElementById('tax').textContent = '₹' + tax.toLocaleString('en-IN', { minimumFractionDigits: 2 });
            document.getElementById('total').textContent = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        }

        addItemBtn.addEventListener('click', function () {
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-slate-50 dark:bg-dark-bg/50 rounded-2xl border border-transparent hover:border-brand-500/20 hover:bg-white dark:hover:bg-dark-surface transition-all group relative';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <input type="text" name="items[${itemIndex}][description]" required placeholder="Description..." class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-300 focus:ring-0">
                </div>
                <div class="col-span-2">
                    <select name="items[${itemIndex}][type]" required class="w-full bg-brand-50/50 dark:bg-brand-500/5 border-none rounded-lg text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-brand-500/20 cursor-pointer py-1.5">
                        <option value="material">Material</option>
                        <option value="labour">Labour</option>
                        <option value="work">Work/Service</option>
                    </select>
                </div>
                <div class="col-span-1">
                    <input type="number" step="0.01" name="items[${itemIndex}][quantity]" value="1" required class="w-full bg-transparent border-none p-1 text-sm font-black text-center text-slate-700 dark:text-slate-300 focus:ring-0 qty">
                </div>
                <div class="col-span-1">
                    <input type="text" name="items[${itemIndex}][unit]" placeholder="unit" class="w-full bg-transparent border-none p-1 text-[11px] font-bold text-center text-ui-muted dark:text-dark-muted focus:ring-0 uppercase tracking-tighter">
                </div>
                <div class="col-span-3 pr-4">
                    <input type="number" step="0.01" name="items[${itemIndex}][rate]" required placeholder="0.00" class="w-full bg-transparent border-none p-1 text-sm font-black text-right text-brand-600 focus:ring-0 rate">
                </div>
                <div class="col-span-1 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <button type="button" class="remove-item text-rose-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            `;
            itemsContainer.appendChild(newRow);
            itemIndex++;
            calculateTotals();
        });

        itemsContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                    calculateTotals();
                }
            }
        });

        itemsContainer.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty') || e.target.classList.contains('rate')) {
                calculateTotals();
            }
        });

        calculateTotals();
    });
</script>
@endsection