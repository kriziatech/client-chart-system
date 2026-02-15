@props(['task', 'priority' => 'none'])

@php
$bgClass = [
'high' => 'bg-rose-50/50 dark:bg-rose-500/5 border-rose-100 dark:border-rose-500/20',
'medium' => 'bg-white dark:bg-dark-bg border-slate-100 dark:border-dark-border',
'low' => 'bg-slate-50/50 dark:bg-slate-900/50 border-slate-100 dark:border-dark-border',
'none' => 'bg-white/50 dark:bg-dark-bg/50 border-slate-100 dark:border-dark-border'
][$priority] ?? 'bg-white border-slate-100';

$dotClass = [
'high' => 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]',
'medium' => 'bg-brand-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]',
'low' => 'bg-slate-300',
'none' => 'bg-slate-200'
][$priority] ?? 'bg-slate-200';
@endphp

<div
    class="{{ $bgClass }} p-6 rounded-[24px] border shadow-sm hover:shadow-xl hover:scale-[1.01] transition-all duration-300 group">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex items-start gap-4">
            <div class="mt-1.5 w-2 h-2 rounded-full {{ $dotClass }}"></div>
            <div>
                <h4
                    class="text-sm font-bold text-slate-800 dark:text-white leading-snug group-hover:text-brand-500 transition-colors">
                    {{ optional($task)->description }}</h4>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-brand-600 dark:text-brand-400">
                        {{ optional(optional($task)->client)->first_name }} {{
                        optional(optional($task)->client)->last_name }}
                    </span>
                    <span class="text-[10px] text-slate-300">•</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        {{ optional($task)->category ?: 'General' }}
                    </span>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="p-1 px-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 py-2 z-30 animate-in fade-in zoom-in-95 duration-200">
                <form action="{{ $task ? route('tasks.status.update', $task) : '#' }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" name="status" value="Pending"
                        class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700">Mark
                        Pending</button>
                    <button type="submit" name="status" value="In Progress"
                        class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700">Mark
                        In Progress</button>
                    <button type="submit" name="status" value="Completed"
                        class="w-full text-left px-4 py-2 text-xs font-bold text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10">Mark
                        Completed</button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-dark-border/50">
        <div class="flex items-center gap-3">
            <div
                class="w-6 h-6 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-[10px] font-black text-brand-600 dark:text-brand-400 uppercase">
                {{ substr(optional($task)->assigned_to ?: 'U', 0, 1) }}
            </div>
            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight">
                {{ optional($task)->assigned_to ?: 'Unassigned' }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <svg class="w-3.5 h-3.5 {{ ($priority ?? '') === 'high' ? 'text-rose-500' : 'text-slate-400' }}" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
            </svg>
            <span
                class="text-[10px] font-black uppercase tracking-widest {{ ($priority ?? '') === 'high' ? 'text-rose-600' : 'text-slate-500' }}">
                {{ optional($task)->deadline ? optional($task->deadline)->format('d M') : 'TBD' }}
            </span>
        </div>
    </div>
</div>