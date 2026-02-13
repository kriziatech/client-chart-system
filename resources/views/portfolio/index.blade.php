@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8 animate-in fade-in duration-1000">

    <!-- Hero Section -->
    <div class="relative h-[400px] rounded-[3rem] overflow-hidden group shadow-2xl">
        <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&q=80&w=2000"
            class="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110" alt="Hero">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        <div class="absolute bottom-12 left-12 right-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <span
                    class="px-4 py-1.5 bg-brand-600 text-white text-[10px] font-black uppercase tracking-[2px] rounded-full shadow-lg">Showcase
                    Board</span>
                <h1 class="text-5xl font-black text-white mt-4 tracking-tighter">Inspiration Portfolio</h1>
                <p class="text-slate-300 font-medium text-lg mt-2 max-w-xl">Curated designs and real-site
                    accomplishments precisely executed for our premium clients.</p>
            </div>
            <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/20">
                <div class="text-center px-4">
                    <div class="text-2xl font-black text-white">{{ $portfolioItems->count() }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Designs</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center px-4">
                    <div class="text-2xl font-black text-white">100%</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Quality</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-2">
        @forelse($portfolioItems as $item)
        <div
            class="group relative bg-white dark:bg-dark-surface rounded-[2rem] overflow-hidden border border-ui-border dark:border-dark-border shadow-premium hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <!-- Image Container -->
            <div class="aspect-[4/5] overflow-hidden relative">
                <img src="{{ asset('storage/' . $item->image_path) }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    alt="{{ $item->caption }}">

                <!-- Overlay Actions -->
                <div
                    class="absolute inset-0 bg-brand-600/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-3">
                    <button
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-brand-600 shadow-xl hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </button>
                    <button
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-brand-600 shadow-xl hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span
                        class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded text-[9px] font-black uppercase tracking-wider">
                        {{ $item->type }}
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium">Ref: #{{ $item->client->file_number }}</span>
                </div>
                <h3 class="text-sm font-bold text-ui-primary dark:text-white truncate">{{ $item->caption ?? 'Premier
                    Interior Design' }}</h3>
                <p class="text-[11px] text-ui-muted mt-1 font-medium">Site: {{ $item->client->first_name }}'s Residence
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div
                class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-ui-primary dark:text-white">Start Building Your Portfolio</h3>
            <p class="text-ui-muted max-w-sm mx-auto mt-2">Upload site photos and 3D designs in project galleries to see
                them featured here.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection