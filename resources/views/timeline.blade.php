@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4 overflow-hidden">
    <div class="max-w-[1600px] mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">Visual
                    Timeline</h1>
                <p class="text-slate-500 font-medium mt-1">Cross-project labor planning and deadline monitoring</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-brand-500"></span><span
                        class="text-[10px] font-black uppercase text-slate-400">Scheduled</span></div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span
                        class="text-[10px] font-black uppercase text-slate-400">Completed</span></div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span
                        class="text-[10px] font-black uppercase text-slate-400">Overdue</span></div>
            </div>
        </div>

        {{-- Timeline Container --}}
        <div
            class="bg-white dark:bg-slate-900/40 rounded-[40px] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[1200px] p-8">
                    {{-- Calendar Header (Months) --}}
                    <div class="grid grid-cols-12 border-b border-slate-100 dark:border-dark-border mb-8 pb-4">
                        @for($i = 0; $i < 12; $i++) <div class="text-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{
                                now()->startOfYear()->addMonths($i)->format('M Y') }}</span>
                    </div>
                    @endfor
                </div>

                {{-- Projects Row --}}
                <div class="space-y-12">
                    @foreach($clients as $client)
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-4">
                            <span
                                class="px-3 py-1 bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[10px] font-black uppercase tracking-widest rounded-lg">P-{{
                                $client->id }}</span>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white">{{ $client->first_name }} {{
                                $client->last_name }}</h3>
                        </div>

                        {{-- Project Duration Bar --}}
                        <div class="relative h-12 bg-slate-50 dark:bg-white/5 rounded-2xl overflow-hidden group">
                            @php
                            $yearStart = now()->startOfYear();
                            $totalDays = 365;

                            $start = $client->start_date ?: $client->created_at;
                            $end = $client->delivery_date ?: $start->copy()->addMonths(3);

                            $left = (($start->diffInDays($yearStart, false) * -1) / $totalDays) * 100;
                            $width = ($start->diffInDays($end) / $totalDays) * 100;
                            @endphp
                            <div class="absolute h-full bg-slate-200 dark:bg-slate-700/50 rounded-2xl border border-slate-300 dark:border-slate-600"
                                style="left: {{ $left }}%; width: {{ $width }}%">
                                <div class="flex items-center justify-between px-4 h-full">
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Project
                                        Span</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{
                                        $start->format('M d') }} - {{ $end->format('M d') }}</span>
                                </div>
                            </div>

                            {{-- Tasks Inside Project --}}
                            @foreach($client->tasks as $task)
                            @php
                            $taskStart = $task->start_date ?: ($task->created_at ?: $start);
                            $taskEnd = $task->deadline ?: $taskStart->copy()->addDays(7);

                            $tLeft = (($taskStart->diffInDays($yearStart, false) * -1) / $totalDays) * 100;
                            $tWidth = ($taskStart->diffInDays($taskEnd) / $totalDays) * 100;

                            $color = $task->status == 'Completed' ? 'bg-emerald-500' : ($taskEnd->isPast() ?
                            'bg-rose-500' : 'bg-brand-500');
                            @endphp
                            <div class="absolute top-3 bottom-3 rounded-lg shadow-sm {{ $color }} cursor-pointer hover:scale-y-110 transition-all z-20 group/task"
                                style="left: {{ $tLeft }}%; width: {{ max($tWidth, 0.5) }}%" title="{{ $task->title }}">
                                {{-- Tooltip --}}
                                <div
                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/task:block w-48 bg-slate-900 text-white p-3 rounded-xl text-xs z-50">
                                    <div class="font-black mb-1 uppercase text-[8px] tracking-widest opacity-60">Task
                                        Details</div>
                                    <div class="font-bold mb-2">{{ $task->title }}</div>
                                    <div class="flex justify-between text-[8px] font-black uppercase">
                                        <span>Status</span>
                                        <span
                                            class="{{ $color == 'bg-rose-500' ? 'text-rose-400' : 'text-emerald-400' }}">{{
                                            $task->status }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Grid Marks (Yearly) --}}
                <div class="absolute inset-0 pointer-events-none flex py-8">
                    @for($i = 0; $i < 13; $i++) <div
                        class="flex-1 border-r border-slate-100 dark:border-white/5 last:border-0">
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
</div>
</div>

<style>
    /* Custom Scrollbar for Timeline */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #334155;
    }
</style>
@endsection