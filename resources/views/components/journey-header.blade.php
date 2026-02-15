@props(['stage', 'nextStep', 'progress' => 0, 'statusColor' => 'blue', 'ctaLabel' => null, 'ctaAction' => null])

@php
$colors = [
'blue' => 'bg-blue-600',
'green' => 'bg-emerald-600',
'red' => 'bg-rose-600',
];
$bgColors = [
'blue' => 'bg-blue-50 dark:bg-blue-900/10',
'green' => 'bg-emerald-50 dark:bg-emerald-900/10',
'red' => 'bg-rose-50 dark:bg-rose-900/10',
];
$textColors = [
'blue' => 'text-blue-600 dark:text-blue-400',
'green' => 'text-emerald-600 dark:text-emerald-400',
'red' => 'text-rose-600 dark:text-rose-400',
];

$selectedBg = $bgColors[$statusColor] ?? $bgColors['blue'];
$selectedText = $textColors[$statusColor] ?? $textColors['blue'];
$selectedBar = $colors[$statusColor] ?? $colors['blue'];
@endphp

<div
    class="{{ $selectedBg }} border-b border-white/10 px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-6 no-print">
    <div class="flex flex-col md:flex-row items-center gap-6 flex-1">
        <!-- Journey Stage -->
        <div class="flex items-center gap-4 min-w-[200px]">
            <div
                class="w-10 h-10 rounded-2xl {{ $selectedBar }} flex items-center justify-center text-white shadow-lg shadow-black/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[2px] opacity-60 {{ $selectedText }}">Current
                    Journey
                    Stage</p>
                <h2 class="text-sm font-black uppercase tracking-tight {{ $selectedText }}">{{ $stage }}</h2>
            </div>
        </div>

        <!-- Progress Tracker -->
        <div class="flex-1 w-full max-w-md">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Project Completion</span>
                <span class="text-[10px] font-black {{ $selectedText }}">{{ $progress }}%</span>
            </div>
            <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full {{ $selectedBar }} transition-all duration-1000" style="width: {{ $progress }}%">
                </div>
            </div>
        </div>
    </div>

    <!-- Next Step CTA -->
    <div class="flex flex-col sm:flex-row items-center gap-4">
        <div
            class="flex items-center gap-4 bg-white dark:bg-dark-surface p-3 rounded-2xl border border-slate-200/50 dark:border-white/5 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="max-w-[200px]">
                <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Ab Kya Karna Hai?</p>
                <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200 leading-tight">{{ $nextStep }}</p>
            </div>
        </div>

        @if($ctaLabel && $ctaAction)
        <button @click="{!! $ctaAction !!}"
            class="{{ $selectedBar }} text-white px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-brand-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2 group">
            <span>{{ $ctaLabel }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
        @endif
    </div>
</div>