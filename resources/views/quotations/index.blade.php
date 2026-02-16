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
                            <p class="text-xl font-black text-brand-600 dark:text-brand-400">₹{{
                                @indian_format($quotation->total_amount)</p>
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
                        Revise/Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </main>
</div>
@endsection