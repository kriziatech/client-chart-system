@extends('layouts.app')

@section('content')
<div class="h-full bg-gray-50 dark:bg-gray-900">
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
                    Quotations & BOQs
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Management of versioned estimates and client
                    approvals</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('quotations.create') }}"
                    class="px-6 py-2 text-sm font-bold text-white bg-brand-600 rounded-lg hover:bg-brand-700 shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New BOQ
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8">
        @if($quotations->isEmpty())
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
            <div
                class="w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center mx-auto mb-4 text-gray-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">No Quotations Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-xs mx-auto">Start by creating a new Bill of
                Quantities for your projects.</p>
            <div class="mt-6">
                <a href="{{ route('quotations.create') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-bold text-brand-600 border border-brand-200 hover:bg-brand-50 bg-white rounded-xl transition-all gap-2">
                    Create First BOQ
                </a>
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quotations as $quotation)
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all group flex flex-col h-full">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <span @class([ 'px-2 py-1 text-[10px] font-black uppercase rounded tracking-widest'
                            , 'bg-yellow-100 text-yellow-700'=> $quotation->status == 'draft',
                            'bg-green-100 text-green-700' => $quotation->status == 'accepted',
                            'bg-blue-100 text-blue-700' => $quotation->status == 'sent',
                            ])>
                            {{ $quotation->status }}
                        </span>
                        <span class="text-xs font-bold text-gray-400">v{{ $quotation->version }}</span>
                    </div>

                    <h3
                        class="text-lg font-black text-gray-900 dark:text-white group-hover:text-brand-600 transition-colors uppercase leading-tight">
                        {{ $quotation->quotation_number }}
                    </h3>

                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/40 flex items-center justify-center text-brand-700 dark:text-brand-400 text-xs font-bold">
                            @if($quotation->client)
                            {{ substr($quotation->client->first_name, 0, 1) }}
                            @elseif($quotation->lead)
                            {{ substr($quotation->lead->name, 0, 1) }}
                            @else
                            ?
                            @endif
                        </div>
                        <div>
                            @if($quotation->client)
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $quotation->client->first_name
                                }} {{ $quotation->client->last_name }}</p>
                            <p class="text-[10px] text-gray-500 uppercase">#{{ $quotation->client->file_number }}</p>
                            @elseif($quotation->lead)
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $quotation->lead->name }}</p>
                            <p class="text-[10px] text-gray-500 uppercase">#{{ $quotation->lead->lead_number }}</p>
                            @else
                            <p class="text-xs font-bold text-gray-900 dark:text-white">Unknown Entity</p>
                            @endif
                        </div>
                    </div>

                    <div
                        class="mt-6 flex justify-between items-end border-t border-gray-100 dark:border-gray-700/50 pt-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Amount
                            </p>
                            <p class="text-xl font-black text-brand-600 dark:text-brand-400">
                                ₹@indian_format($quotation->total_amount)</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Issued On</p>
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300">{{
                                $quotation->date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 dark:bg-gray-900/50 flex gap-2">
                    <a href="{{ route('quotations.show', $quotation->id) }}"
                        class="flex-1 px-3 py-2 text-xs font-bold text-center text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 transition-all">
                        View Details
                    </a>
                    <a href="{{ route('quotations.edit', $quotation->id) }}"
                        class="flex-1 px-3 py-2 text-xs font-bold text-center text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-all">
                        Revise
                    </a>
                    <button type="button"
                        @click="$dispatch('open-delete-modal', { id: {{ $quotation->id }}, number: '{{ $quotation->quotation_number }}' })"
                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Global Delete Modal -->
    <div x-data="{ 
            isOpen: false, 
            quotationId: null, 
            quotationNumber: '', 
            remark: '', 
            confirmation: '',
            get isValid() { return this.remark.length >= 5 && this.confirmation === 'DELETE' }
        }"
        @open-delete-modal.window="isOpen = true; quotationId = $event.detail.id; quotationNumber = $event.detail.number; remark = ''; confirmation = ''"
        x-show="isOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full p-8 overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Delete
                            Quotation</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
                    </div>
                </div>

                <div
                    class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-xl p-4 mb-6">
                    <p class="text-sm text-rose-800 dark:text-rose-300 leading-relaxed font-medium">
                        You are about to delete <span class="font-bold uppercase" x-text="quotationNumber"></span>.
                        Please provide a mandatory remark and type the confirmation word to proceed.
                    </p>
                </div>

                <form :action="`/quotations/${quotationId}`" method="POST" class="space-y-5">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest pl-1">Reason for
                            Deletion *</label>
                        <textarea name="remark" x-model="remark" rows="3" required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none"
                            placeholder="Please explain why this quotation is being deleted..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest pl-1">Type <span
                                class="text-rose-600">DELETE</span> to confirm *</label>
                        <input type="text" name="confirmation" x-model="confirmation" required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none"
                            placeholder="TYPE DELETE HERE">
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <button type="button" @click="isOpen = false"
                            class="px-6 py-3 text-sm font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="!isValid"
                            class="px-6 py-3 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-rose-500/20">
                            Confirm Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection