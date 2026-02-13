@props(['href', 'active' => false, 'icon' => '', 'label' => ''])

<a href="{{ $href }}"
    class="group flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 
    {{ $active 
        ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 border-l-[3px] border-brand-600 rounded-l-none' 
        : 'text-ui-muted hover:bg-slate-50 hover:text-ui-primary dark:text-dark-muted dark:hover:bg-slate-800/50 dark:hover:text-white' }}">

    <div class="flex-shrink-0 transition-transform duration-200 {{ $active ? 'scale-110' : 'group-hover:scale-110' }}">
        <svg class="w-5 h-5 {{ $active ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-ui-primary dark:group-hover:text-white' }}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    </div>

    <span x-show="sidebarOpen" x-transition.opacity class="truncate {{ $active ? 'font-bold' : '' }}">
        {{ $label }}
    </span>

    @if(!$active)
    <div x-show="!sidebarOpen"
        class="absolute left-full ml-4 px-2 py-1 bg-slate-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap shadow-xl">
        {{ $label }}
    </div>
    @endif
</a>