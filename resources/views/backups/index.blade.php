@extends('layouts.app')

@section('content')
<div class="px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight font-display">System
                Backups</h1>
            <p class="text-slate-400 text-sm font-medium uppercase tracking-[2px] mt-1">Database & File Snapshots</p>
        </div>
        <form action="{{ route('backups.create') }}" method="POST">
            @csrf
            <button type="submit"
                class="bg-brand-600 text-white px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-brand-700 transition shadow-lg shadow-brand-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Create New Backup
            </button>
        </form>
    </div>

    @if(session('success'))
    <div
        class="mb-6 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div
        class="mb-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-200 dark:border-rose-800 p-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold text-rose-700 dark:text-rose-400">{{ session('error') }}</span>
    </div>
    @endif

    <div
        class="bg-white dark:bg-dark-surface rounded-[2rem] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[2px] text-slate-400 border-b border-slate-100 dark:border-dark-border">
                        <th class="py-5 px-8">Backup File</th>
                        <th class="py-5 px-8">Size</th>
                        <th class="py-5 px-8">Created At</th>
                        <th class="py-5 px-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                    @forelse($backups as $backup)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/20 transition-all duration-300">
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white font-mono">{{
                                    $backup['file_name'] }}</span>
                            </div>
                        </td>
                        <td class="py-6 px-8 text-sm font-bold text-slate-500">{{ $backup['file_size'] }}</td>
                        <td class="py-6 px-8 text-sm font-bold text-slate-500">{{
                            \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->diffForHumans() }}</td>
                        <td class="py-6 px-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('backups.download', $backup['file_name']) }}"
                                    class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-900/20 rounded-lg transition-all"
                                    title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('backups.destroy', $backup['file_name']) }}" method="POST"
                                    onsubmit="return confirm('Are you sure and want to delete this backup?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all"
                                        title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="text-sm font-bold uppercase tracking-widest">No backups found</p>
                                <p class="text-xs mt-1">Create your first backup to see it here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection