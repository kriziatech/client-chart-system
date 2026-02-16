@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Search Results</h1>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
                Showing results for "<span class="text-brand-600 dark:text-brand-400">{{ $query }}</span>"
            </p>
        </div>
    </div>

    @if($clients->isEmpty() && $leads->isEmpty() && $vendors->isEmpty())
    <div
        class="bg-white dark:bg-dark-surface rounded-3xl p-12 text-center border border-slate-100 dark:border-dark-border shadow-premium">
        <div
            class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">No results found</h3>
        <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">We couldn't find any clients, leads, or vendors
            matching your search.</p>
    </div>
    @endif

    <!-- Clients -->
    @if($clients->isNotEmpty())
    <div>
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 pl-1">Clients</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($clients as $client)
            <a href="{{ route('clients.show', $client) }}"
                class="group bg-white dark:bg-dark-surface rounded-3xl p-6 border border-slate-100 dark:border-dark-border shadow-premium hover:shadow-premium-hover transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-black text-lg group-hover:scale-110 transition-transform">
                        {{ substr($client->first_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $client->first_name }} {{
                            $client->last_name }}</div>
                        <div
                            class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide mt-0.5">
                            {{ $client->project_name }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Leads -->
    @if($leads->isNotEmpty())
    <div>
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 pl-1">Leads</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($leads as $lead)
            <a href="{{ route('leads.index') }}?id={{ $lead->id }}"
                class="group bg-white dark:bg-dark-surface rounded-3xl p-6 border border-slate-100 dark:border-dark-border shadow-premium hover:shadow-premium-hover transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-lg group-hover:scale-110 transition-transform">
                        {{ substr($lead->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $lead->name }}</div>
                        <div
                            class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide mt-0.5">
                            {{ $lead->status }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Vendors -->
    @if($vendors->isNotEmpty())
    <div>
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 pl-1">Vendors</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vendors as $vendor)
            <div
                class="group bg-white dark:bg-dark-surface rounded-3xl p-6 border border-slate-100 dark:border-dark-border shadow-premium hover:shadow-premium-hover transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-lg group-hover:scale-110 transition-transform">
                        {{ substr($vendor->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $vendor->name }}</div>
                        <div
                            class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide mt-0.5">
                            {{ $vendor->category }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection