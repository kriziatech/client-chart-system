@extends('layouts.app')

@section('content')
<div class="h-full bg-gray-50 dark:bg-gray-900" x-data="boqBuilder()">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    {{ isset($quotation) ? 'Edit BOQ / Quotation' : 'Create New BOQ / Quotation' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Estimation, Versioned Quotations & Costing</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('quotations.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                    Cancel
                </a>
                <button type="submit" form="boqForm"
                    class="px-6 py-2 text-sm font-bold text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                        </path>
                    </svg>
                    Save BOQ
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8">
        <form id="boqForm" @keydown.enter.prevent="$event.target.tagName !== 'TEXTAREA'"
            action="{{ isset($quotation) && $quotation ? route('quotations.update', $quotation->id) : route('quotations.store') }}"
            method="POST">
            @csrf
            @if(isset($quotation) && $quotation)
            @method('PUT')
            @if($quotation->status !== 'draft')
            <input type="hidden" name="create_version" value="1">
            <div
                class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl flex items-center gap-3 text-amber-800 dark:text-amber-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium">This quotation is already <strong>{{ strtoupper($quotation?->status ??
                        '')
                        }}</strong>. Saving changes will automatically create <strong>Version {{ ($quotation?->version
                        ?? 0) + 1
                        }}</strong>.</p>
            </div>
            @endif
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: BOQ Builder -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Client & Date Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                x-data="{ targetType: '{{ (isset($quotation) && $quotation?->lead_id) || isset($selectedLeadId) ? 'lead' : 'client' }}' }">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assign
                                    To</label>
                                <div class="flex bg-gray-100 dark:bg-gray-900 p-1 rounded-xl mb-3">
                                    <button type="button" @click="targetType = 'client'"
                                        :class="targetType === 'client' ? 'bg-white dark:bg-gray-800 shadow text-indigo-600' : 'text-gray-500'"
                                        class="flex-1 py-1.5 rounded-lg text-xs font-bold transition-all">Existing
                                        Project</button>
                                    <button type="button" @click="targetType = 'lead'"
                                        :class="targetType === 'lead' ? 'bg-white dark:bg-gray-800 shadow text-indigo-600' : 'text-gray-500'"
                                        class="flex-1 py-1.5 rounded-lg text-xs font-bold transition-all">New
                                        Lead</button>
                                </div>

                                <div x-show="targetType === 'client'">
                                    <select name="client_id" :required="targetType === 'client'"
                                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 transition-all outline-none">
                                        <option value="">-- Choose Project --</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ (isset($quotation) && $quotation?->
                                            client_id
                                            == $client->id) || old('client_id') == $client->id ||
                                            (isset($selectedClientId) && $selectedClientId == $client->id) ? 'selected'
                                            : '' }}>
                                            {{ $client?->first_name }} {{ $client?->last_name }} (#{{
                                            $client?->file_number
                                            }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="targetType === 'lead'">
                                    <select name="lead_id" :required="targetType === 'lead'"
                                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 transition-all outline-none">
                                        <option value="">-- Choose Lead --</option>
                                        @foreach($leads as $lead)
                                        <option value="{{ $lead->id }}" {{ (isset($quotation) && $quotation->lead_id ==
                                            $lead->id) || old('lead_id') == $lead->id || (isset($selectedLeadId) &&
                                            $selectedLeadId == $lead->id) ? 'selected' : '' }}>
                                            {{ $lead->name }} ({{ $lead->location }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Project Type Selection -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Type</label>
                                    <select name="project_type"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                        <option value="RES" {{ (isset($quotation) && $quotation->project_type == 'RES')
                                            ? 'selected' : '' }}>Residential</option>
                                        <option value="COM" {{ (isset($quotation) && $quotation->project_type == 'COM')
                                            ? 'selected' : '' }}>Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Quotation
                                    Date</label>
                                <input type="date" name="date" required
                                    value="{{ isset($quotation) ? $quotation->date->format('Y-m-d') : date('Y-m-d') }}"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                Bill of Quantities (BOQ)
                            </h3>
                            <button type="button" @click="addItem()"
                                class="px-3 py-1.5 text-xs font-bold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-lg transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Card Loop -->
                            <template x-for="(item, index) in items" :key="index">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 p-5 relative group">

                                    <!-- Header: Category & Total -->
                                    <div
                                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <div class="w-full md:w-1/3">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Category</label>
                                            <select :name="`items[${index}][category]`" x-model="item.category"
                                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm font-bold text-gray-900 dark:text-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition-all">
                                                <option value="Carpentry">Carpentry</option>
                                                <option value="Electrical">Electrical</option>
                                                <option value="Civil">Civil / Tiling</option>
                                                <option value="Painting">Painting</option>
                                                <option value="Plumbing">Plumbing</option>
                                                <option value="False Ceiling">False Ceiling</option>
                                                <option value="Labour Work">Labour Work</option>
                                                <option value="Miscellaneous">Miscellaneous</option>
                                            </select>
                                        </div>
                                        <div
                                            class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                                            <div class="text-right">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                    Total Amount</p>
                                                <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                                                    ₹<span
                                                        x-text="item.amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                </p>
                                            </div>
                                            <button @click="removeItem(index)"
                                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all"
                                                title="Remove Item">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Body: Description & Metrics -->
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                        <!-- Description Column (Left) -->
                                        <div class="md:col-span-7">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                Item Description <span
                                                    class="text-xs font-normal normal-case text-gray-300 ml-1">(Use
                                                    **text** for bold)</span>
                                            </label>
                                            <textarea :name="`items[${index}][description]`" x-model="item.description"
                                                placeholder="Enter detailed description of the work item..." rows="4"
                                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none resize-none transition-all"></textarea>
                                        </div>

                                        <!-- Metrics Column (Right) -->
                                        <div class="md:col-span-5 grid grid-cols-2 gap-4">
                                            <!-- Row 1: Unit & Nos -->
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Unit</label>
                                                <select :name="`items[${index}][unit]`" x-model="item.unit"
                                                    class="w-full bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-gray-900 dark:text-white focus:border-brand-500 outline-none">
                                                    <option value="sqft">Sq.ft</option>
                                                    <option value="sqmt">Sq.mt</option>
                                                    <option value="rft">Rft</option>
                                                    <option value="rmt">Rmt</option>
                                                    <option value="cum">Cu.m</option>
                                                    <option value="cft">Cu.ft</option>
                                                    <option value="nos">Nos</option>
                                                    <option value="bags">Bags</option>
                                                    <option value="kgs">Kgs</option>
                                                    <option value="ltrs">Ltrs</option>
                                                    <option value="box">Box</option>
                                                    <option value="set">Set</option>
                                                    <option value="ls">Lump Sum</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nos</label>
                                                <input type="number" step="1" :name="`items[${index}][no_of_units]`"
                                                    x-model="item.no_of_units" @input="calculateItemTotal(item)"
                                                    class="w-full bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-lg px-3 py-2 text-sm font-black text-center text-gray-900 dark:text-white focus:border-brand-500 outline-none">
                                            </div>

                                            <!-- Row 2: Area & Rate -->
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Area
                                                    / Qty</label>
                                                <input type="number" step="0.01" :name="`items[${index}][area]`"
                                                    x-model="item.area" @input="calculateItemTotal(item)"
                                                    class="w-full bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-lg px-3 py-2 text-sm font-black text-center text-gray-900 dark:text-white focus:border-brand-500 outline-none">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rate
                                                    (₹)</label>
                                                <input type="number" step="0.01" :name="`items[${index}][rate]`"
                                                    x-model="item.rate" @input="calculateItemTotal(item)"
                                                    class="w-full bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-lg px-3 py-2 text-sm font-black text-center text-emerald-600 dark:text-emerald-400 focus:border-emerald-500 outline-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="items.length === 0" class="p-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <p>No items added. Click "Add Item" to start building your BOQ.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Totals -->
                <div class="space-y-6">
                    <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-xl sticky top-24">
                        <h3
                            class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-6 flex items-center gap-2">
                            Costing Summary
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-gray-400">
                                <span class="text-sm">Subtotal</span>
                                <span class="font-bold text-white" x-text="formatCurrency(subtotal)"></span>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm text-gray-400">GST Percentage (%)</label>
                                    <input type="number" name="gst_percentage" x-model="gst_percentage"
                                        @input="calculateFinalTotal()"
                                        class="w-16 bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-xs text-right text-white outline-none">
                                </div>
                                <div class="flex justify-between items-center text-brand-400">
                                    <span class="text-xs italic">Tax Amount</span>
                                    <span class="text-sm font-bold" x-text="formatCurrency(taxAmount)"></span>
                                </div>
                            </div>

                            <div class="space-y-2 pt-4 border-t border-gray-800">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm text-gray-400">Fixed Discount (₹)</label>
                                    <input type="number" name="discount_amount" x-model="discount_amount"
                                        @input="calculateFinalTotal()"
                                        class="w-24 bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-xs text-right text-white outline-none">
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-800 mt-6">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimated
                                        Total</span>
                                    <span class="text-3xl font-black text-brand-500"
                                        x-text="formatCurrency(totalAmount)"></span>
                                </div>
                                <p class="text-[10px] text-gray-500 text-right leading-tight">Incl. of GST & Discount
                                </p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label class="block text-xs font-bold text-brand-400 uppercase tracking-wider mb-2">Internal
                                Notes</label>
                            <textarea name="notes" rows="4"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:ring-1 focus:ring-brand-500 outline-none transition-all">{{ isset($quotation) ? $quotation->notes : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

@php
$initialItems = [];
$initialGst = 18;
$initialDiscount = 0;

if (isset($quotation)) {
$initialItems = $quotation->items->map(function($i) {
return [
'description' => $i->description,
'category' => $i->category,
'unit' => $i->unit,
'no_of_units' => (int)$i->no_of_units,
'area' => (float)$i->area,
'rate' => (float)$i->rate,
'amount' => (float)$i->amount
];
});
$initialGst = $quotation->gst_percentage;
$initialDiscount = $quotation->discount_amount;
}
@endphp

<script>
    function boqBuilder() {
        return {
            items: @json($initialItems),
            gst_percentage: @json($initialGst),
            discount_amount: @json($initialDiscount),
            subtotal: 0,
            taxAmount: 0,
            totalAmount: 0,

            init() {
                if (this.items.length === 0) {
                    this.addItem();
                }
                this.calculateFinalTotal();
            },

            addItem() {
                this.items.push({
                    category: 'Carpentry',
                    description: '',
                    unit: 'sqft',
                    no_of_units: 1,
                    area: 0,
                    rate: 0,
                    amount: 0
                });
            },

            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateFinalTotal();
            },

            calculateItemTotal(item) {
                item.amount = (parseFloat(item.area) || 0) * (parseFloat(item.no_of_units) || 1) * (parseFloat(item.rate) || 0);
                this.calculateFinalTotal();
            },
            calculateFinalTotal() {
                this.subtotal = this.items.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                let afterDiscount = this.subtotal - (parseFloat(this.discount_amount) || 0);
                this.taxAmount = afterDiscount * (parseFloat(this.gst_percentage) / 100);
                this.totalAmount = afterDiscount + this.taxAmount;
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 2
                }).format(value);
            }
        };
    }
</script>
@endsection