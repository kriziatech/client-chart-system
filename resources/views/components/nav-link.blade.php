@props(['href', 'active' => false, 'icon' => '', 'label' => ''])

<a href="{{ $href }}"
    class="group flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-xl transition-all duration-300 
    {{ $active 
        ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/30' 
        : 'text-slate-500 hover:bg-slate-50 hover:text-brand-600 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-brand-400' }}">

    <div class="flex-shrink-0 transition-transform duration-300 {{ $active ? 'scale-105' : 'group-hover:scale-110' }}">
        <svg class="w-5 h-5 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    </div>

    <span x-show="sidebarOpen" x-transition.opacity
        class="truncate font-display {{ $active ? 'font-bold' : 'font-medium' }}">
        {{ $label }}
    </span>

    @if(!$active)
    <div x-show="!sidebarOpen"
        class="absolute left-full ml-4 px-2 py-1 bg-slate-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap shadow-xl">
        {{ $label }}
    </div>
    @endif
</a>