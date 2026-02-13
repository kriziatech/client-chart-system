@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Active Projects</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Manage and track your ongoing project
                charters.</p>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
        <a href="{{ route('clients.create') }}"
            class="bg-brand-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition-all shadow-xl shadow-brand-500/20 flex items-center gap-2 group transform active:scale-95">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Initialize Project
        </a>
        @endif
    </div>

    <div
        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                        <th class="py-5 px-8">File Ref</th>
                        <th class="py-5 px-8">Client Identity</th>
                        <th class="py-5 px-6">Timeline</th>
                        <th class="py-5 px-8 text-center">Status & Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                    @forelse($clients as $client)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                        <td class="py-6 px-8 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-bold leading-none">
                                {{ $client->file_number }}
                            </span>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center text-slate-400 font-bold text-sm uppercase">
                                    {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white">{{
                                        $client->first_name }} {{ $client->last_name }}</div>
                                    <div
                                        class="text-[10px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-0.5">
                                        {{ $client->mobile ?: 'No Contact' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-6">
                            <div class="flex items-center gap-4">
                                <div>
                                    <div
                                        class="text-[10px] uppercase font-bold text-slate-400 tracking-widest leading-none mb-1">
                                        Start</div>
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-300">{{
                                        $client->start_date ? \Carbon\Carbon::parse($client->start_date)->format('d M,
                                        Y') : '-' }}</div>
                                </div>
                                <div class="h-6 w-px bg-slate-200 dark:bg-dark-border"></div>
                                <div>
                                    <div
                                        class="text-[10px] uppercase font-bold text-slate-400 tracking-widest leading-none mb-1">
                                        Target</div>
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-300">{{
                                        $client->delivery_date ?
                                        \Carbon\Carbon::parse($client->delivery_date)->format('d M, Y') : '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center justify-center gap-2 transition-all duration-300">
                                <a href="{{ route('finance.analytics', $client) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="Financial Intel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="{{ route('clients.show', $client) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="View Dossier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z">
                                        </path>
                                    </svg>
                                </a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                                <a href="{{ route('clients.edit', $client) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="Modify Charter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ route('clients.print', $client) }}" target="_blank"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400 hover:bg-slate-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                    title="Export PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                </a>
                                @if(Auth::user()->isAdmin())
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                    onsubmit="return confirm('Archive this project charter permanently?');"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-400 dark:text-rose-500 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                        title="Archive Project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 dark:bg-dark-bg rounded-3xl flex items-center justify-center text-slate-200 mb-4 border border-slate-100 dark:border-dark-border border-dashed">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white">Void detected.</h3>
                                <p
                                    class="text-[11px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-1 italic">
                                    No active project dossiers found in database.</p>
                                @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                                <a href="{{ route('clients.create') }}"
                                    class="mt-6 text-xs font-black uppercase tracking-widest text-brand-600 hover:text-brand-700 underline underline-offset-8">Initialize
                                    First Project</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
        <div class="px-8 py-5 border-t border-ui-border dark:border-dark-border bg-slate-50/30 dark:bg-dark-bg/30">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection