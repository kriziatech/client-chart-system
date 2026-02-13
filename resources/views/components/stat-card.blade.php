@props(['label', 'value', 'trend' => null, 'trendUp' => true, 'sparkline' => [], 'icon' => ''])

<div
    class="bg-white dark:bg-dark-surface p-5 rounded-2xl border border-ui-border dark:border-dark-border shadow-premium hover:shadow-premium-hover hover:-translate-y-0.5 transition-all duration-300 group">
    <div class="flex justify-between items-start mb-4">
        <div
            class="p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl group-hover:bg-brand-600 group-hover:text-white transition-all">
            <svg class="w-5 h-5 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
            </svg>
        </div>
        @if($trend !== null)
        <div
            class="flex items-center gap-1.5 {{ $trendUp ? 'text-ui-success bg-green-50 dark:bg-green-500/10' : 'text-ui-danger bg-red-50 dark:bg-red-500/10' }} text-[12px] font-bold py-1 px-2.5 rounded-lg">
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
        <h3 class="text-ui-muted dark:text-dark-muted text-[11px] font-bold uppercase tracking-[1px] mb-1.5">{{ $label
            }}</h3>
        <div class="text-[26px] font-bold text-ui-primary dark:text-white tracking-tight leading-none">{{ $value }}
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