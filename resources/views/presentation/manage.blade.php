@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Populate chart data if exists
        const chartDataInput = document.getElementById('edit-chart_data');
        if (chartDataInput) {
            chartDataInput.value = slide.chart_data ? JSON.stringify(slide.chart_data, null, 2) : '';
        }

        modal.classList.remove('hidden');
        if (window.toggleChartSection) window.toggleChartSection('edit');
        if (window.forceUpdatePreview) window.forceUpdatePreview('edit');
    }

    window.suggestWithAI = function (type) {
        const titleInput = document.getElementById(type + '-title');
        const topic = titleInput.value || 'interior design'; // Use the current title as topic, or a default

        // Show loading state
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="animate-spin">🌀</span> Generating...';
        btn.disabled = true;

        fetch('{{ route("presentation.slides.suggest") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ topic: topic })
        })
            .then(response => response.json())
            .then(data => {
                if (data.title) {
                    document.getElementById(type + '-title').value = data.title;
                    document.getElementById(type + '-subtitle').value = data.subtitle || '';
                    document.getElementById(type + '-content').value = data.content || '';
                    if (window.forceUpdatePreview) window.forceUpdatePreview(type);
                }
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    }

    window.toggleChartSection = function (type) {
        const layout = document.getElementById(type + '-layout').value;
        const section = document.getElementById(type + '-chart-section');
        if (layout === 'chart') {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Live Preview Logic
        const types = ['add', 'edit'];
        types.forEach(type => {
            const fields = ['title', 'subtitle', 'content', 'bg', 'chart_data'];
            fields.forEach(field => {
                const input = document.getElementById(type + '-' + (field === 'bg' ? 'bg' : field));
                if (input) {
                    input.addEventListener('input', () => updatePreview(type));
                }
            });
            // Layout select listener
            const layoutSelect = document.getElementById(type + '-layout');
            if (layoutSelect) {
                layoutSelect.addEventListener('change', () => {
                    window.toggleChartSection(type);
                    updatePreview(type);
                });
            }
        });

        const previewCharts = {};

        function updatePreview(type) {
            const title = document.getElementById(type + '-title').value;
            const subtitle = document.getElementById(type + '-subtitle').value;
            const content = document.getElementById(type + '-content').value;
            const bg = document.getElementById(type + '-bg').value || '#0F172A';
            const layout = document.getElementById(type + '-layout').value;

            const previewTitle = document.getElementById(type + '-preview-title');
            const previewSubtitle = document.getElementById(type + '-preview-subtitle');
            const previewContent = document.getElementById(type + '-preview-content');
            const previewContainer = document.getElementById(type + '-preview-container');

            previewTitle.innerText = title || 'Slide Title';
            previewSubtitle.innerText = subtitle || 'Subtitle Goes Here';
            previewContent.innerHTML = content || 'Content will appear here as you type.';
            previewContainer.style.backgroundColor = bg;

            // Simple layout preview adjustments
            const innerDiv = previewContainer.querySelector('div');
            const previewSection = document.getElementById(type + '-preview-content');

            if (layout === 'chart') {
                previewSection.innerHTML = `<canvas id="${type}-preview-chart-canvas" style="max-height: 250px;"></canvas>`;
                renderPreviewChart(type);
            } else {
                previewSection.innerHTML = content || 'Content will appear here as you type.';
                if (innerDiv) {
                    if (layout === 'center') {
                        innerDiv.classList.add('items-center', 'text-center');
                    } else {
                        innerDiv.classList.remove('items-center', 'text-center');
                    }
                }
            }
        }

        function renderPreviewChart(type) {
            const canvas = document.getElementById(type + '-preview-chart-canvas');
            if (!canvas) return;

            const chartDataRaw = document.getElementById(type + '-chart_data').value;
            let chartData;

            try {
                chartData = JSON.parse(chartDataRaw);
            } catch (e) {
                return; // Invalid JSON
            }

            if (previewCharts[type]) {
                previewCharts[type].destroy();
            }

            const ctx = canvas.getContext('2d');

            // Handle both full and simple formats
            const config = {
                type: chartData.type || 'bar',
                data: chartData.data || {
                    labels: chartData.labels || [],
                    datasets: chartData.datasets || []
                },
                options: chartData.options || {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: '#fff', font: { size: 10 } } } }
                }
            };

            previewCharts[type] = new Chart(ctx, config);
        }

        // Global exposing for manual calls (like after AI suggest)
        window.forceUpdatePreview = updatePreview;

        const el = document.getElementById('slides-list');
        if (el) {
            Sortable.create(el, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'bg-brand-50',
                onEnd: function () {
                    const order = Array.from(el.querySelectorAll('[data-id]')).map(item => item.dataset.id);
                    fetch('{{ route("presentation.slides.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    }).then(response => {
                        if (response.ok) {
                            // Optional: Show a subtle toast
                        }
                    });
                }
            });
        }
    });
</script>
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white font-display">Manage Slides</h1>
                <p class="text-slate-500 font-medium">Add, Edit or Delete presentation slides dynamically</p>
            </div>
            <button
                @click="document.getElementById('add-slide-modal').classList.remove('hidden'); window.forceUpdatePreview('add');"
                class="bg-brand-500 hover:bg-brand-600 text-white px-6 py-3 rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 transition-all">
                Add New Slide
            </button>
        </div>

        {{-- Slides List --}}
        <div id="slides-list" class="space-y-4">
            @foreach($slides as $slide)
            <div data-id="{{ $slide->id }}"
                class="group bg-white dark:bg-dark-surface p-6 rounded-3xl border border-slate-200 dark:border-dark-border hover:border-brand-500 dark:hover:border-brand-500 transition-all flex items-center justify-between shadow-sm hover:shadow-xl hover:shadow-brand-500/10 active:scale-[0.99]">
                <div class="flex items-center gap-6">
                    <div
                        class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-brand-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16">
                            </path>
                        </svg>
                    </div>
                    <div
                        class="w-12 h-12 bg-slate-100 dark:bg-dark-bg rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-brand-50 group-hover:text-brand-500 transition-colors">
                        <span class="text-xs font-black">#{{ $slide->order }}</span>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">
                            {{ $slide->title }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{
                                $slide->layout_type }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span
                                class="text-[10px] font-bold {{ $slide->is_active ? 'text-emerald-500' : 'text-slate-400' }} uppercase tracking-widest">
                                {{ $slide->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
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
                        onsubmit="return confirm('Are you sure you want to delete this slide?')">
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
        class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[40px] shadow-2xl overflow-hidden border border-white/10 flex">
        {{-- Left: Form --}}
        <div class="w-1/2 border-r border-slate-100 dark:border-dark-border">
            <div class="p-8 border-b border-slate-100 dark:border-dark-border flex items-center justify-between">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white font-display uppercase tracking-tight">Add
                    New
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
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Slide
                            Title</label>
                        <button type="button" onclick="suggestWithAI('add')"
                            class="text-[10px] font-bold text-brand-500 hover:text-brand-600 flex items-center gap-1 uppercase tracking-widest bg-brand-50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            AI Suggest
                        </button>
                    </div>
                    <input type="text" name="title" id="add-title" required
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Subtitle</label>
                        <input type="text" name="subtitle" id="add-subtitle"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                    </div>
                    <div class="space-y-4">
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Layout</label>
                        <select name="layout_type" id="add-layout"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                            <option value="standard">Standard (Split)</option>
                            <option value="center">Center Focused</option>
                            <option value="grid">Data Grid</option>
                            <option value="profile">Profile/Portfolio</option>
                            <option value="chart">Interactive Chart</option>
                        </select>
                    </div>
                </div>

                <div id="add-chart-section"
                    class="hidden space-y-4 p-6 rounded-2xl bg-brand-50/30 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20">
                    <label class="text-[10px] font-black uppercase tracking-widest text-brand-500 ml-2">Chart Data
                        (JSON)</label>
                    <textarea name="chart_data" id="add-chart_data" rows="3"
                        placeholder='{"labels": ["Jan", "Feb", "Mar"], "datasets": [{"label": "Sales", "data": [10, 20, 15]}]}'
                        class="w-full bg-white dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 text-xs font-mono focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"></textarea>
                    <p class="text-[9px] text-slate-400 italic">Enter Chart.js formatted data. Labels and datasets are
                        required.</p>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Slide Content
                        (HTML/Markdown)</label>
                    <textarea name="content" id="add-content" rows="4"
                        class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"></textarea>
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
                        <input type="text" name="bg_color" id="add-bg" placeholder="#0F172A" value="#0F172A"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white">
                    </div>
                </div>

                <div class="flex items-center gap-3 px-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active_add" checked
                        class="w-5 h-5 rounded border-slate-300 dark:border-dark-border text-brand-500 focus:ring-brand-500/20 transition-all">
                    <label for="is_active_add"
                        class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Active
                        Display</label>
                </div>

                <div class="pt-4 flex justify-end gap-4">
                    <button type="button" onclick="document.getElementById('add-slide-modal').classList.add('hidden')"
                        class="px-8 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</button>
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-brand-500/40 transition-all active:scale-95">Save
                        Slide</button>
                </div>
            </form>
        </div>

        {{-- Right: Preview --}}
        <div class="w-1/2 bg-slate-50 dark:bg-dark-bg p-8 flex flex-col">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Live Preview</label>
            <div id="add-preview-container"
                class="flex-1 rounded-3xl overflow-hidden border border-slate-200 dark:border-dark-border bg-[#0F172A] p-8 shadow-inner relative">
                {{-- Mock Slide Content --}}
                <div class="h-full flex flex-col justify-center">
                    <h1 id="add-preview-title" class="text-3xl font-black text-white mb-2 leading-tight">Slide Title
                    </h1>
                    <h3 id="add-preview-subtitle"
                        class="text-lg font-bold text-brand-400 mb-6 uppercase tracking-wider">Subtitle Goes Here</h3>
                    <div id="add-preview-content"
                        class="text-slate-300 text-sm leading-relaxed prose prose-invert max-w-none">
                        Content will appear here as you type.
                    </div>
                </div>
            </div>
            <div
                class="mt-6 p-4 rounded-2xl bg-brand-50/50 dark:bg-brand-500/5 text-brand-600 dark:text-brand-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                Real-time Sync Active
            </div>
        </div>
    </div>
</div>

{{-- Edit Slide Modal --}}
<div id="edit-slide-modal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[40px] shadow-2xl overflow-hidden border border-white/10 flex">
        <div class="w-1/2 border-r border-slate-100 dark:border-dark-border">
            <div class="p-8 border-b border-slate-100 dark:border-dark-border flex items-center justify-between">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white font-display uppercase tracking-tight">
                    Edit
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
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Slide
                                Title</label>
                            <button type="button" onclick="suggestWithAI('edit')"
                                class="text-[10px] font-bold text-brand-500 hover:text-brand-600 flex items-center gap-1 uppercase tracking-widest bg-brand-50 px-2 py-1 rounded-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                AI Suggest
                            </button>
                        </div>
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
                            <option value="chart">Interactive Chart</option>
                        </select>
                    </div>
                </div>

                <div id="edit-chart-section"
                    class="hidden space-y-4 p-6 rounded-2xl bg-brand-50/30 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20">
                    <label class="text-[10px] font-black uppercase tracking-widest text-brand-500 ml-2">Chart Data
                        (JSON)</label>
                    <textarea name="chart_data" id="edit-chart_data" rows="3"
                        class="w-full bg-white dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 text-xs font-mono focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-700 dark:text-white"></textarea>
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
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Order</label>
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

        {{-- Right: Preview --}}
        <div class="w-1/2 bg-slate-50 dark:bg-dark-bg p-8 flex flex-col">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Live Preview</label>
            <div id="edit-preview-container"
                class="flex-1 rounded-3xl overflow-hidden border border-slate-200 dark:border-dark-border bg-[#0F172A] p-8 shadow-inner relative">
                <div class="h-full flex flex-col justify-center">
                    <h1 id="edit-preview-title" class="text-3xl font-black text-white mb-2 leading-tight">Slide Title
                    </h1>
                    <h3 id="edit-preview-subtitle"
                        class="text-lg font-bold text-brand-400 mb-6 uppercase tracking-wider">Subtitle Goes Here</h3>
                    <div id="edit-preview-content"
                        class="text-slate-300 text-sm leading-relaxed prose prose-invert max-w-none">
                        Content will appear here as you type.
                    </div>
                </div>
            </div>
            <div
                class="mt-6 p-4 rounded-2xl bg-brand-50/50 dark:bg-brand-500/5 text-brand-600 dark:text-brand-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                Real-time Sync Active
            </div>
        </div>
    </div>
</div>

@endsection