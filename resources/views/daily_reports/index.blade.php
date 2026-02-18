@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-12">
            <div>
                <a href="{{ route('clients.show', $client->id) }}"
                    class="text-brand-600 dark:text-brand-400 text-xs font-black uppercase tracking-widest flex items-center gap-2 mb-2 hover:opacity-70 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Back to Project
                </a>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">Daily
                    Progress Reports</h1>
                <p class="text-slate-500 font-medium mt-1">Timeline for {{ $client->first_name }} {{ $client->last_name
                    }}</p>
            </div>
        </div>

        {{-- New Report Form --}}
        <div
            class="bg-white dark:bg-slate-900/40 rounded-[32px] border border-slate-100 dark:border-dark-border shadow-premium p-8 mb-12">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-2 h-6 bg-brand-500 rounded-full"></span>
                Log Today's Progress
            </h2>
            <form action="{{ route('reports.store', $client->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Report
                            Date</label>
                        <input type="date" name="report_date" value="{{ date('Y-m-d') }}" required
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">What happened
                        today?</label>
                    <textarea name="content" rows="4" required
                        placeholder="Describe work done, site visits, or updates..."
                        class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all"></textarea>
                </div>

                @if($pendingTasks->count() > 0)
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Completion
                        Check (Link Tasks)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($pendingTasks as $task)
                        <label
                            class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-bg rounded-xl border border-transparent hover:border-brand-500/30 cursor-pointer transition-all">
                            <input type="checkbox" name="tasks[]" value="{{ $task->id }}"
                                class="rounded text-brand-600 focus:ring-brand-500/20">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $task->title }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="p-4 bg-rose-50/30 dark:bg-rose-500/5 rounded-2xl border border-rose-100 dark:border-rose-500/20">
                        <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest mb-2">Before
                            Work (Mandatory)</label>
                        <input type="file" name="images_before[]" multiple accept="image/*" required
                            class="w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-rose-500 file:text-white hover:file:bg-rose-600 transition-all cursor-pointer">
                    </div>
                    <div
                        class="p-4 bg-emerald-50/30 dark:bg-emerald-500/5 rounded-2xl border border-emerald-100 dark:border-emerald-500/20">
                        <label
                            class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2">After
                            Work (Mandatory)</label>
                        <input type="file" name="images_after[]" multiple accept="image/*" required
                            class="w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-emerald-500 file:text-white hover:file:bg-emerald-600 transition-all cursor-pointer">
                    </div>
                    <div
                        class="p-4 bg-brand-50/30 dark:bg-brand-500/5 rounded-2xl border border-brand-100 dark:border-brand-500/20">
                        <label
                            class="block text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">In-Progress
                            / Others</label>
                        <input type="file" name="images_progress[]" multiple accept="image/*"
                            class="w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-brand-500 file:text-white hover:file:bg-brand-600 transition-all cursor-pointer">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-slate-900 dark:bg-brand-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-premium hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Publish Report
                    </button>
                </div>
            </form>
        </div>

        {{-- Timeline Feed --}}
        <div
            class="relative space-y-12 before:content-[''] before:absolute before:left-[17px] before:top-2 before:bottom-0 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
            @forelse($reports as $report)
            <div class="relative pl-12 group transition-all">
                {{-- Node --}}
                <div
                    class="absolute left-0 top-1 w-9 h-9 bg-white dark:bg-slate-900 rounded-full border-4 border-slate-100 dark:border-slate-800 flex items-center justify-center z-10 group-hover:border-brand-500 transition-all">
                    <div class="w-2 h-2 bg-slate-400 rounded-full group-hover:bg-brand-500"></div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900/40 p-8 rounded-[32px] border border-slate-100 dark:border-dark-border shadow-premium hover:shadow-2xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-black text-brand-600 dark:text-brand-400 uppercase tracking-widest">{{
                            $report->report_date->format('l, d M Y') }}</span>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                onsubmit="return confirm('Delete this report?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-slate-700 dark:text-slate-300 font-medium leading-relaxed whitespace-pre-line">{{
                        $report->content }}</p>

                    @if($report->tasks->count() > 0)
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($report->tasks as $task)
                        <div
                            class="flex items-center gap-2 px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 rounded-full">
                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span
                                class="text-[10px] font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest">{{
                                $task->title }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($report->images->count() > 0)
                    <div class="mt-8 space-y-6">
                        @php
                        $beforeImages = $report->images->where('label', 'before');
                        $afterImages = $report->images->where('label', 'after');
                        $progressImages = $report->images->where('label', 'progress');
                        @endphp

                        {{-- Before vs After Comparison Card --}}
                        @if($beforeImages->count() > 0 && $afterImages->count() > 0)
                        <div
                            class="bg-slate-50 dark:bg-dark-bg/50 rounded-[24px] p-6 border border-slate-100 dark:border-dark-border">
                            <h4
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                Transformation Comparison
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <span class="text-[9px] font-black text-rose-500 uppercase tracking-tighter">Initial
                                        Condition (Before)</span>
                                    <div
                                        class="aspect-video rounded-2xl overflow-hidden shadow-sm border border-rose-100 bg-white">
                                        <img src="{{ Storage::url($beforeImages->first()->image_path) }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter">Work
                                        Result (After)</span>
                                    <div
                                        class="aspect-video rounded-2xl overflow-hidden shadow-sm border border-emerald-100 bg-white">
                                        <img src="{{ Storage::url($afterImages->first()->image_path) }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Full Gallery --}}
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Complete
                                Journal Photos</h4>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                                @foreach($report->images as $image)
                                <div
                                    class="group/img relative aspect-square rounded-xl overflow-hidden shadow-sm border border-slate-100 dark:border-dark-border">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="Progress Image"
                                        class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
                                    <div class="absolute top-1.5 left-1.5">
                                        <span
                                            class="px-1.5 py-0.5 rounded-lg text-[7px] font-black uppercase tracking-widest shadow-lg 
                                            {{ $image->label == 'before' ? 'bg-rose-500 text-white' : ($image->label == 'after' ? 'bg-emerald-500 text-white' : 'bg-brand-500 text-white') }}">
                                            {{ $image->label }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-20 text-center">
                <div
                    class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-400">No reports logged yet</h3>
                <p class="text-slate-400 mt-2">Start documenting the progress of this project.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection