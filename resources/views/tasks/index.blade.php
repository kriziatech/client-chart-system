@extends('layouts.app')

@section('content')
<div class="animate-in fade-in duration-700 space-y-8">
    {{-- Header --}}
    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-dark-surface p-8 rounded-[32px] border border-slate-100 dark:border-dark-border shadow-premium">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-display uppercase tracking-tight">Active
                Work Units</h1>
            <p class="text-sm text-slate-500 dark:text-dark-muted font-medium mt-1">Global task monitoring & management
            </p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center gap-3">
                <select name="client_id" onchange="this.form.submit()"
                    class="bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl text-xs font-bold uppercase tracking-widest px-4 py-2.5 focus:ring-brand-500 transition-all">
                    <option value="">All Projects</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id')==$c->id ? 'selected' : '' }}>
                        {{ $c->first_name }} {{ $c->last_name }}
                    </option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()"
                    class="bg-slate-50 dark:bg-dark-bg border-slate-200 dark:border-dark-border rounded-xl text-xs font-bold uppercase tracking-widest px-4 py-2.5 focus:ring-brand-500 transition-all">
                    <option value="all">All Status</option>
                    <option value="Pending" {{ request('status')=='Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status')=='In Progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="Completed" {{ request('status')=='Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Task Dashboard --}}
    <div x-data="{ viewMode: 'time' }" class="space-y-10">
        {{-- View Selector --}}
        <div class="flex justify-center">
            <div
                class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-2xl border border-slate-200 dark:border-dark-border">
                <button @click="viewMode = 'time'"
                    :class="viewMode === 'time' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Timeline
                    View</button>
                <button @click="viewMode = 'trade'"
                    :class="viewMode === 'trade' ? 'bg-white dark:bg-slate-700 shadow-premium text-brand-600' : 'text-slate-500'"
                    class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Trade
                    Grouping</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-10">

                {{-- TIMELINE VIEW --}}
                <div x-show="viewMode === 'time'" class="space-y-10 animate-in fade-in duration-500">
                    {{-- Overdue Section --}}
                    @if($groupedTasks['overdue']->count() > 0)
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 px-2">
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                            <h3 class="text-xs font-black text-rose-500 uppercase tracking-[2px]">Overdue Units ({{
                                $groupedTasks['overdue']->count() }})</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($groupedTasks['overdue'] as $task)
                            <x-task-card :task="$task" priority="high" />
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Today Section --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 px-2">
                            <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                            <h3 class="text-xs font-black text-brand-500 uppercase tracking-[2px]">Executing Today ({{
                                $groupedTasks['today']->count() }})</h3>
                        </div>
                        @if($groupedTasks['today']->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($groupedTasks['today'] as $task)
                            <x-task-card :task="$task" priority="medium" />
                            @endforeach
                        </div>
                        @else
                        <div
                            class="p-12 text-center bg-white dark:bg-dark-surface rounded-[32px] border border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No units scheduled
                                for today.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Upcoming Section --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 px-2">
                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[2px]">Pipeline Units ({{
                                $groupedTasks['upcoming']->count() }})</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($groupedTasks['upcoming'] as $task)
                            <x-task-card :task="$task" priority="low" />
                            @empty
                            <div
                                class="col-span-2 p-8 text-center bg-slate-50/50 dark:bg-slate-900/50 rounded-[32px] border border-slate-100 dark:border-dark-border">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No upcoming
                                    units identified.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- No Deadline Section --}}
                    @if($groupedTasks['no_deadline']->count() > 0)
                    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-dark-border">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[2px] px-2">Unscheduled Backlog
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-70">
                            @foreach($groupedTasks['no_deadline'] as $task)
                            <x-task-card :task="$task" priority="none" />
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- TRADE VIEW --}}
                <div x-show="viewMode === 'trade'" x-cloak class="space-y-12 animate-in fade-in duration-500">
                    @foreach($groupedTasks['by_category'] as $category => $categoryTasks)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h3
                                class="text-xs font-black text-brand-600 dark:text-brand-400 uppercase tracking-[3px] flex items-center gap-3">
                                <span class="w-8 h-px bg-brand-500/30"></span>
                                {{ $category ?: 'General Trade' }} ({{ $categoryTasks->count() }})
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($categoryTasks as $task)
                            <x-task-card :task="$task" priority="none" />
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar Statistics & Recently Completed --}}
            <div class="space-y-8">
                {{-- Efficiency Card --}}
                <div
                    class="bg-slate-900 dark:bg-brand-500 p-8 rounded-[40px] text-white shadow-2xl relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-3xl -mr-16 -mt-16 rounded-full group-hover:scale-150 transition-transform duration-700">
                    </div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Execution Velocity</h4>
                    <div class="text-4xl font-bold font-display mb-4">
                        @php
                        $totalCount = $groupedTasks['overdue']->count() + $groupedTasks['today']->count() +
                        $groupedTasks['upcoming']->count() + $groupedTasks['completed']->count();
                        $percent = $totalCount > 0 ? round(($groupedTasks['completed']->count() / $totalCount) * 100) :
                        0;
                        @endphp
                        {{ $percent }}%
                    </div>
                    <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden mb-6">
                        <div class="bg-white h-full transition-all duration-1000 shadow-[0_0_10px_rgba(255,255,255,0.5)]"
                            style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-60">Completed</div>
                            <div class="text-lg font-bold">{{ $groupedTasks['completed']->count() }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-60">In Flight</div>
                            <div class="text-lg font-bold">{{ $groupedTasks['today']->count() +
                                $groupedTasks['overdue']->count() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Recently Completed --}}
                <div
                    class="bg-white dark:bg-dark-surface p-8 rounded-[40px] border border-slate-100 dark:border-dark-border shadow-premium">
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[2px] mb-6">Recently
                        Resolved</h3>
                    <div class="space-y-6">
                        @forelse($groupedTasks['completed']->take(5) as $task)
                        <div class="flex items-start gap-4 group">
                            <div
                                class="mt-1.5 w-5 h-5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 transition-transform group-hover:scale-110">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-700 dark:text-slate-200 truncate">{{
                                    $task->description }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Done for <span
                                        class="text-brand-500 font-bold uppercase">{{
                                        optional($task->client)->first_name }}</span></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center py-8">No
                            completion history found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection