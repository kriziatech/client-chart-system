@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white font-display">Manage Slides</h1>
                <p class="text-slate-500 font-medium">Add, Edit or Delete presentation slides dynamically</p>
            </div>
            <button @click="document.getElementById('add-slide-modal').classList.remove('hidden')"
                class="bg-brand-500 hover:bg-brand-600 text-white px-6 py-3 rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 transition-all">
                Add New Slide
            </button>
        </div>

        {{-- Slides List --}}
        <div class="space-y-4">
            @foreach($slides as $slide)
            <div
                class="bg-white dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-dark-border shadow-premium flex items-center justify-between group">
                <div class="flex items-center gap-6">
                    <div
                        class="w-12 h-12 bg-slate-100 dark:bg-white/5 rounded-xl flex items-center justify-center text-lg font-bold text-brand-500">
                        {{ $slide->order }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $slide->title }}</h3>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-widest">{{ $slide->layout_type
                            }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all">
                    <button onclick="editSlide({{ json_encode($slide) }})"
                        class="p-3 text-brand-500 hover:bg-brand-50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </button>
                    <form action="{{ route('presentation.slides.destroy', $slide) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Add Slide Modal --}}
<div id="add-slide-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden border border-white/10">
        <div class="p-8 border-b border-slate-100 dark:border-dark-border flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-900 dark:text-white font-display uppercase tracking-tight">Add New
                Slide</h2>
            <button @click="document.getElementById('add-slide-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route('presentation.slides.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Slide
                        Title</label>
                    <input type="text" name="title" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"
                        placeholder="e.g. Visual Timeline">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Layout
                        Type</label>
                    <select name="layout_type"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                        <option value="standard">Standard Card</option>
                        <option value="center">Centered Large</option>
                        <option value="grid">2-Column Grid</option>
                        <option value="profile">Profile Layout</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Content (HTML
                    Supported)</label>
                <textarea name="content" rows="6"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"
                    placeholder="Slide body content..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Display
                        Order</label>
                    <input type="number" name="order" value="0"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Background
                        Color</label>
                    <input type="text" name="bg_color" placeholder="#0F172A" value="#0F172A"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>
            </div>

            <div class="flex items-center gap-3 px-2">
                <input type="checkbox" name="is_active" value="1" id="is_active_add" checked
                    class="w-5 h-5 rounded border-slate-300 dark:border-dark-border text-brand-500 focus:ring-brand-500/20 transition-all">
                <label for="is_active_add" class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Active
                    Display</label>
            </div>

            <div class="pt-4 flex justify-end gap-4">
                <button type="button" @click="document.getElementById('add-slide-modal').classList.add('hidden')"
                    class="px-8 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</button>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-brand-500/40 transition-all active:scale-95">Save
                    Slide</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Slide Modal --}}
<div id="edit-slide-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden border border-white/10">
        <div class="p-8 border-b border-slate-100 dark:border-dark-border flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-900 dark:text-white font-display uppercase tracking-tight">Edit
                Slide</h2>
            <button onclick="document.getElementById('edit-slide-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form id="edit-slide-form" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Slide
                        Title</label>
                    <input type="text" name="title" id="edit-title" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Layout
                        Type</label>
                    <select name="layout_type" id="edit-layout"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                        <option value="standard">Standard Card</option>
                        <option value="center">Centered Large</option>
                        <option value="grid">2-Column Grid</option>
                        <option value="profile">Profile Layout</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Subtitle</label>
                <input type="text" name="subtitle" id="edit-subtitle"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Content (HTML
                    Support)</label>
                <textarea name="content" id="edit-content" rows="6"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Order</label>
                    <input type="number" name="order" id="edit-order"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Background
                        Color</label>
                    <input type="text" name="bg_color" id="edit-bg"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>
            </div>

            <div class="flex items-center gap-3 px-2">
                <input type="checkbox" name="is_active" value="1" id="edit-active"
                    class="w-5 h-5 rounded border-slate-300 dark:border-dark-border text-brand-500 focus:ring-brand-500/20 transition-all">
                <label for="edit-active" class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Active
                    Display</label>
            </div>

            <div class="pt-4 flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('edit-slide-modal').classList.add('hidden')"
                    class="px-8 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</button>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-brand-500/40 transition-all active:scale-95">Update
                    Slide</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.editSlide = function (slide) {
        const modal = document.getElementById('edit-slide-modal');
        const form = document.getElementById('edit-slide-form');

        // Set action URL reliably
        form.action = window.location.origin + '/presentation/slides/' + slide.id;

        // Populate fields
        document.getElementById('edit-title').value = slide.title;
        document.getElementById('edit-subtitle').value = slide.subtitle || '';
        document.getElementById('edit-content').value = slide.content || '';
        document.getElementById('edit-layout').value = slide.layout_type;
        document.getElementById('edit-order').value = slide.order;
        document.getElementById('edit-bg').value = slide.bg_color || '#0F172A';
        document.getElementById('edit-active').checked = !!slide.is_active;

        modal.classList.remove('hidden');
    }
</script>
@endsection