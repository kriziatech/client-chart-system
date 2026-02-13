<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-brand-500 border
    border-transparent rounded-2xl font-display font-semibold text-xs text-white uppercase tracking-widest
    hover:bg-brand-600 focus:bg-brand-600 active:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-500/10
    transition ease-in-out duration-200 shadow-lg shadow-brand-500/20']) }}>
    {{ $slot }}
</button>