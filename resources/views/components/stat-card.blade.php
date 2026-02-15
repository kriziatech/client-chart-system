@props(['label', 'value', 'trend' => null, 'trendUp' => true, 'sparkline' => [], 'icon' => '', 'iconClass' =>
'text-slate-500'])

<div
    class="bg-white dark:bg-dark-surface p-6 rounded-3xl border border-ui-border dark:border-dark-border shadow-premium hover:shadow-premium-hover transition-all duration-500 group">
    <div class="flex justify-between items-start mb-6">
        <div
            class="p-3 bg-slate-50 dark:bg-slate-800 rounded-2xl group-hover:bg-brand-500 group-hover:text-white transition-all duration-500 shadow-sm group-hover:shadow-brand-500/20">
            <svg class="w-5 h-5 {{ $iconClass }} group-hover:text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
            </svg>
        </div>
        @if($trend !== null)
        <div
            class="flex items-center gap-1.5 {{ $trendUp ? 'text-ui-success bg-green-50 dark:bg-green-500/10' : 'text-ui-danger bg-red-50 dark:bg-red-500/10' }} text-[12px] font-bold py-1.5 px-3 rounded-xl border border-current/5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($trendUp)
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
                @endif
            </svg>
            {{ $trend }}%
        </div>
        @endif
    </div>

    <div>
        <h3
            class="text-slate-400 dark:text-dark-muted text-[11px] font-bold uppercase tracking-[1.5px] mb-2 font-display">
            {{ $label
            }}</h3>
        <div class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight leading-none font-display">{{
            $value }}
        </div>
    </div>

    @if(!empty($sparkline))
    <div class="mt-4 h-10 w-full flex items-end gap-1.5">
        @php $max = max($sparkline) ?: 1; @endphp
        @foreach($sparkline as $point)
        <div class="flex-grow bg-brand-600/10 group-hover:bg-brand-600/20 transition-colors rounded-t-md"
            style="height: {{ max(10, ($point / $max) * 100) }}%"></div>
        @endforeach
    </div>
    @endif
</div>