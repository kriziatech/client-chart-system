@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">System Activity Logs</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1"> Chronological view of all system
                actions. (Total: {{ number_format($totalLogs) }} logs)</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Cleanup Button -->
            <form action="{{ route('audit-logs.cleanup') }}" method="POST"
                onsubmit="return confirm('This will permanently delete all logs older than 7 days. This action is immutable. Proceed?')">
                @csrf
                <button type="submit"
                    class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition flex items-center gap-2 border border-rose-200 shadow-sm active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clean > 7 Days
                </button>
            </form>

            <div
                class="px-4 py-2.5 bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 rounded-xl flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Live Monitoring</span>
            </div>
        </div>
    </div>

    <!-- Simplified Filters -->
    <div
        class="bg-white dark:bg-dark-surface rounded-2xl border border-ui-border dark:border-dark-border shadow-sm overflow-hidden mb-8">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="p-6 flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px] space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Search Module</label>
                <input type="text" name="model" value="{{ request('model') }}" placeholder="e.g. Quotation, User..."
                    class="w-full bg-slate-50 dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-4 focus:ring-brand-500/10 placeholder:text-slate-300 transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Action Type</label>
                <select name="action"
                    class="bg-slate-50 dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-4 focus:ring-brand-500/10 cursor-pointer pr-10 min-w-[160px]">
                    <option value="">All Actions</option>
                    <option value="Created" {{ request('action')=='Created' ? 'selected' : '' }}>Created</option>
                    <option value="Updated" {{ request('action')=='Updated' ? 'selected' : '' }}>Updated</option>
                    <option value="Deleted" {{ request('action')=='Deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="Login" {{ request('action')=='Login' ? 'selected' : '' }}>Login</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Stakeholder</label>
                <select name="user_id"
                    class="bg-slate-50 dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-4 focus:ring-brand-500/10 cursor-pointer pr-10 min-w-[180px]">
                    <option value="">Everyone</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name
                        }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="bg-slate-900 dark:bg-brand-600 text-white text-sm px-6 py-2.5 rounded-xl font-bold hover:bg-slate-800 dark:hover:bg-brand-700 transition-all shadow-sm active:scale-95">
                    Filter Logs
                </button>
                <a href="{{ route('audit-logs.index') }}"
                    class="px-4 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-dark-bg/50 border-b border-ui-border dark:border-dark-border">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Timestamp</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Stakeholder</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Operation</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Activity &
                            Integrity Audit</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">
                            Telemetry</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                    @forelse($logs as $log)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-200"
                        x-data="{ open: false }">
                        <td class="py-5 px-6 whitespace-nowrap align-top">
                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $log->created_at->format('d
                                M, Y') }}</div>
                            <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-tight">{{
                                $log->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="py-5 px-6 align-top">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-bg border border-slate-200 dark:border-dark-border flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                    {{ substr($log->user_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $log->user_name
                                        }}</div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-brand-600 mt-0.5">
                                        {{ is_string($log->user_role) ? (str_starts_with($log->user_role, '{') ?
                                        'Personnel' : $log->user_role) : 'Personnel' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 align-top">
                            @php
                            $colors = [
                            'Created' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/20 dark:text-brand-300',
                            'Updated' => 'bg-brand-500 text-white dark:bg-brand-400/20 dark:text-brand-300',
                            'Deleted' => 'bg-brand-900 text-brand-50 dark:bg-brand-900/40 dark:text-brand-100',
                            'Login' => 'bg-brand-100 text-brand-600 dark:bg-brand-700/20 dark:text-brand-400',
                            ];
                            $colorClass = $colors[$log->action] ?? 'bg-slate-50 text-slate-500';
                            @endphp
                            <div class="flex flex-col items-start gap-1.5">
                                <span
                                    class="{{ $colorClass }} px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                    {{ $log->action }}
                                </span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white tracking-tight">{{
                                    $log->module }}</span>
                                @if($log->model_id)
                                <span class="text-[10px] font-bold text-slate-400">UID: #{{ $log->model_id }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-5 px-6 align-top">
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $log->description }}
                            </div>

                            @if($log->failure_reason)
                            <div
                                class="mt-2 text-xs font-bold text-rose-500 bg-rose-50 dark:bg-rose-500/5 px-3 py-2 rounded-xl border border-rose-100 dark:border-rose-500/10">
                                <span class="text-[10px] uppercase tracking-widest block mb-1">Terminal Error</span>
                                {{ $log->failure_reason }}
                            </div>
                            @endif

                            @if($log->old_values || $log->new_values)
                            <div class="mt-4">
                                <button @click="open = !open"
                                    class="flex items-center gap-2 text-[11px] font-bold text-brand-600 hover:text-brand-700 transition-all uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5 transition-transform duration-300"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <span x-text="open ? 'Minimize Audit' : 'Inspect Old vs New Values'"></span>
                                </button>

                                <div x-show="open" x-collapse x-cloak class="mt-3">
                                    <div
                                        class="rounded-xl border border-ui-border dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50 overflow-hidden shadow-sm">
                                        <table class="w-full text-left text-[11px]">
                                            <thead>
                                                <tr
                                                    class="bg-slate-100/50 dark:bg-dark-bg text-[10px] font-black uppercase text-slate-500 tracking-widest border-b border-ui-border dark:border-dark-border">
                                                    <th class="py-2.5 px-4 w-1/4">Field Key</th>
                                                    <th class="py-2.5 px-4 text-rose-600 dark:text-rose-400">Previous
                                                        (Old)</th>
                                                    <th class="py-2.5 px-4 text-emerald-600 dark:text-emerald-400">
                                                        Current (New)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                                                @if($log->action === 'Updated' && is_array($log->new_values))
                                                @foreach($log->new_values as $key => $val)
                                                <tr>
                                                    <td
                                                        class="py-2.5 px-4 font-bold text-slate-500 uppercase tracking-tighter">
                                                        {{ $key }}</td>
                                                    <td
                                                        class="py-2.5 px-4 font-medium text-slate-400 line-through decoration-rose-300/50">
                                                        {{ is_array($log->old_values[$key] ?? '-') ?
                                                        json_encode($log->old_values[$key]) : ($log->old_values[$key] ??
                                                        '-') }}
                                                    </td>
                                                    <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">
                                                        {{ is_array($val) ? json_encode($val) : $val }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @elseif(is_array($log->new_values))
                                                @foreach($log->new_values as $key => $val)
                                                <tr>
                                                    <td
                                                        class="py-2.5 px-4 font-bold text-slate-500 uppercase tracking-tighter">
                                                        {{ $key }}</td>
                                                    <td class="py-2.5 px-4 text-slate-300 italic text-[10px]">VOID</td>
                                                    <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">
                                                        {{ is_array($val) ? json_encode($val) : $val }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="py-5 px-6 align-top text-right">
                            <div class="flex flex-col items-end gap-2">
                                <div
                                    class="px-2 py-1 rounded bg-slate-100 dark:bg-dark-bg text-[10px] font-black text-slate-500 font-mono tracking-widest border border-slate-200 dark:border-dark-border">
                                    {{ $log->ip_address }}
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    @if($log->device_type)<span
                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{
                                        $log->device_type }}</span>@endif
                                    @if($log->browser)<span class="text-[9px] font-bold text-slate-400 italic">{{
                                        $log->browser }}</span>@endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">No logs found</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
    <div class="px-4 py-4">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection