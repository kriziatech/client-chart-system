@extends('layouts.app')

@section('content')
<div class="px-8 py-6" x-data="backupManager()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight font-display">System
                Backups</h1>
            <p class="text-slate-400 text-sm font-medium uppercase tracking-[2px] mt-1">Database & File Snapshots</p>
        </div>
        <div>
            <div class="flex items-center gap-3">
                <!-- Upload Backup Form -->
                <form action="{{ route('backups.upload') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-2">
                    @csrf
                    <label
                        class="cursor-pointer bg-slate-100 hover:bg-slate-200 dark:bg-dark-bg dark:hover:bg-dark-bg/80 text-slate-700 dark:text-slate-300 px-4 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition flex items-center gap-2 border border-slate-200 dark:border-dark-border">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Upload Local Backup
                        <input type="file" name="backup_file" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

                <button @click="startBackup" :disabled="isLoading"
                    class="bg-brand-600 text-white px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-brand-700 transition shadow-lg shadow-brand-500/20 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span x-text="isLoading ? 'Running Backup...' : 'Create New Backup'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Storage Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div
            class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-slate-200 dark:border-dark-border shadow-sm flex items-start gap-4">
            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-1">Backup
                    Storage
                    Location</h3>
                <p
                    class="text-xs text-slate-500 dark:text-slate-400 font-mono bg-slate-100 dark:bg-dark-bg px-2 py-1 rounded inline-block border border-slate-200 dark:border-dark-border">
                    {{ $backupPath }}</p>
                <p class="text-[11px] text-slate-400 mt-2">Backups are stored securely in this directory.</p>
            </div>
        </div>

        <div
            class="bg-rose-50 dark:bg-rose-900/10 p-6 rounded-2xl border border-rose-100 dark:border-rose-900/30 shadow-sm flex items-start gap-4">
            <div class="p-3 bg-rose-500/20 rounded-xl text-rose-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-rose-900 dark:text-rose-400 uppercase tracking-wider mb-1">Critical
                    Note</h3>
                <p class="text-[11px] text-rose-700 dark:text-rose-300 font-bold leading-relaxed">Restoring a backup
                    will <b>delete all current data</b> and replace it with the snapshot. This action cannot be undone.
                    Always take a manual backup before restoring.</p>
            </div>
        </div>
    </div>

    <!-- Live Log Console -->
    <div x-show="showConsole" x-collapse
        class="mb-8 bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-2 bg-slate-800 border-b border-slate-700">
            <span class="text-xs font-mono text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Backup Process Output
            </span>
            <button @click="showConsole = false" class="text-slate-500 hover:text-white"><svg class="w-4 h-4"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>
        <div class="p-4 font-mono text-xs text-emerald-400 h-64 overflow-y-auto" id="log-container">
            <pre x-text="logContent" class="whitespace-pre-wrap font-mono"></pre>
        </div>
    </div>

    @if(session('success'))
    <div
        class="mb-6 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div
        class="mb-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-200 dark:border-rose-800 p-4 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="w-8 h-8 rounded-full bg-rose-500/20 flex items-center justify-center text-rose-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
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
                                <form action="{{ route('backups.restore', $backup['file_name']) }}" method="POST"
                                    onsubmit="return confirm('CRITICAL WARNING: This will overwrite your current database with this backup. This cannot be undone. Are you absolutely sure?');">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40 rounded-lg transition-all"
                                        title="Restore this Snapshot">
                                        RESTORE
                                    </button>
                                </form>

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

<script>
    function backupManager() {
        return {
            isLoading: false,
            showConsole: false,
            logContent: 'Initializing...',
            pollInterval: null,

            async startBackup() {
                this.isLoading = true;
                this.showConsole = true;
                this.logContent = "Requesting backup start...\n";

                try {
                    const response = await fetch("{{ route('backups.create') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.logContent += "Backup process started in background.\nStreaming logs...\n";
                        this.startPolling();
                    } else {
                        this.logContent += "Error starting backup: " + data.message;
                        this.isLoading = false;
                    }
                } catch (error) {
                    this.logContent += "Fatal Error: " + error;
                    this.isLoading = false;
                }
            },

            startPolling() {
                this.pollInterval = setInterval(async () => {
                    try {
                        const res = await fetch("{{ route('backups.stream') }}");
                        const data = await res.json();
                        this.logContent = data.log;

                        // Auto scroll to bottom
                        const container = document.getElementById('log-container');
                        container.scrollTop = container.scrollHeight;

                        // Check for completion keyword in log
                        if (this.logContent.includes('Backup completed!') || this.logContent.includes('Backup failed')) {
                            clearInterval(this.pollInterval);
                            this.isLoading = false;

                            // Reload page after delay to show new file
                            if (this.logContent.includes('Backup completed!')) {
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                }, 1000); // Poll every second
            }
        }
    }
</script>
@endsection