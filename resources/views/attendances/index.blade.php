@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Attendance Archives</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Geographic check-in/out logs for
                field and office personnel.</p>
        </div>

        <div class="flex items-center gap-3">
            <div
                class="flex bg-slate-100 dark:bg-dark-surface p-1 rounded-xl border border-slate-200 dark:border-dark-border">
                <a href="{{ route('attendances.index', ['view' => 'list']) }}"
                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $view === 'list' ? 'bg-white dark:bg-slate-800 text-brand-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                    List View
                </a>
                <a href="{{ route('attendances.index', ['view' => 'calendar']) }}"
                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $view === 'calendar' ? 'bg-white dark:bg-slate-800 text-brand-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                    Calendar
                </a>
            </div>
            <div
                class="text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-200 dark:border-dark-border px-4 py-3 rounded-2xl flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                System: Online & Tracking
            </div>
        </div>
    </div>

    <!-- Smart Filtration -->
    <form method="GET" action="{{ route('attendances.index') }}"
        class="px-8 py-5 flex flex-wrap gap-6 items-center bg-slate-50/30 dark:bg-dark-bg/30">
        <input type="hidden" name="view" value="{{ $view }}">

        @if($view === 'calendar')
        <div class="space-y-1">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted ml-0.5">Month</p>
            <select name="month" onchange="this.form.submit()"
                class="bg-white dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2 text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 appearance-none cursor-pointer pr-10">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $month==$m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}
                </option>
                @endforeach
            </select>
        </div>
        @else
        <div class="space-y-1">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted ml-0.5">Chronology</p>
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                class="bg-white dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2 text-[11px] font-bold focus:ring-4 focus:ring-brand-500/10">
        </div>
        @endif

        <div class="space-y-1">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted ml-0.5">Global Filter</p>
            <select name="user_id" onchange="this.form.submit()"
                class="bg-white dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2 text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 appearance-none cursor-pointer pr-10">
                <option value="">All Personnel</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="ml-auto flex items-center gap-6">
            <div class="text-right">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted">Monthly Labor Cost</p>
                <p class="text-lg font-black text-rose-600">₹{{ number_format($analytics['total_cost'], 0) }}</p>
            </div>
            <div class="w-px h-8 bg-slate-200 dark:bg-dark-border"></div>
            <div class="text-right">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted">Operational Load</p>
                <p class="text-lg font-black text-emerald-600">{{ $analytics['total_present'] }} <span
                        class="text-[10px] text-slate-400">Days</span></p>
            </div>
        </div>
    </form>

    @if($view === 'calendar')
    <div class="p-8">
        <div class="space-y-8">
            @php
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $groupedByUser = $attendances->groupBy('user_id');
            @endphp

            @forelse($groupedByUser as $userId => $userAtts)
            @php $user = $userAtts->first()->user; @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-brand-500 text-white flex items-center justify-center text-[10px] font-black uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{
                                $user->name }}</h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Rate: ₹{{
                                $user->daily_rate ?? '0' }}/day</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">₹{{
                            number_format($userAtts->sum('daily_cost'), 0) }}</p>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">This Month</p>
                    </div>
                </div>

                <div class="flex gap-1 overflow-x-auto pb-2 no-scrollbar">
                    @foreach(range(1, $daysInMonth) as $day)
                    @php
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $att = $userAtts->firstWhere('date', $dateStr);
                    $isWeekend = in_array(date('w', strtotime($dateStr)), [0, 6]);
                    @endphp
                    <div class="flex-shrink-0 w-8 text-center space-y-1">
                        <div class="text-[8px] font-bold text-slate-400 uppercase">{{ $day }}</div>
                        <div @class([ 'h-8 w-8 rounded-lg flex items-center justify-center text-[10px] transition-all'
                            , 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'=> $att && $att->status ===
                            'present',
                            'bg-amber-400 text-white shadow-lg shadow-amber-500/20' => $att && $att->status ===
                            'half-day',
                            'bg-rose-100 text-rose-400 border border-rose-200' => $att && $att->status === 'absent',
                            'bg-slate-50 dark:bg-slate-800 text-slate-300' => !$att && !$isWeekend,
                            'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-400' => $isWeekend && !$att,
                            ]) title="{{ $att ? ucfirst($att->status) : ($isWeekend ? 'Weekend' : 'No Data') }}">
                            @if($att && $att->status === 'present') ✅
                            @elseif($att && $att->status === 'half-day') ½
                            @elseif($att && $att->status === 'absent') ❌
                            @elseif($isWeekend) ⚪
                            @else —
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Project Allocation Breakdown --}}
                @php
                $projectCosts = $userAtts->whereNotNull('client_id')->groupBy('client_id');
                @endphp
                @if($projectCosts->count() > 0)
                <div class="flex flex-wrap gap-2 px-2">
                    @foreach($projectCosts as $clientId => $pAtts)
                    <div
                        class="px-2 py-1 bg-slate-100 dark:bg-dark-bg rounded-md flex items-center gap-2 border border-slate-200 dark:border-dark-border">
                        <div class="w-1 h-1 rounded-full bg-brand-500"></div>
                        <span class="text-[8px] font-black text-slate-500 uppercase">{{
                            $pAtts->first()->client->first_name }}: ₹{{ number_format($pAtts->sum('daily_cost'), 0)
                            }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="h-px bg-slate-100 dark:bg-dark-border"></div>
            @empty
            <div class="py-12 text-center text-slate-400 italic text-xs font-bold uppercase tracking-widest">No active
                personnel dossiers found.</div>
            @endforelse
        </div>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                    <th class="py-5 px-8">Calendar Date</th>
                    <th class="py-5 px-8">Personnel Identity</th>
                    <th class="py-5 px-6">Deployment Site</th>
                    <th class="py-5 px-6">Event Cycle</th>
                    <th class="py-5 px-6">Labor Cost</th>
                    <th class="py-5 px-8 text-right">Intel</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ui-border dark:divide-dark-border text-slate-600 dark:text-dark-muted">
                @forelse($attendances as $att)
                <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                    <td class="py-6 px-8 whitespace-nowrap">
                        <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{
                            $att->date->format('d M, Y') }}</span>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-dark-bg flex items-center justify-center text-slate-400 font-black text-xs">
                                {{ substr($att->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $att->user->name
                                }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-6">
                        @if($att->client)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest">
                            {{ $att->client->first_name }}
                        </span>
                        @else
                        <span class="text-[10px] font-bold text-slate-300 uppercase italic">Unassigned</span>
                        @endif
                    </td>
                    <td class="py-6 px-6">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">{{
                                    $att->check_in_time ? $att->check_in_time->format('H:i') : '--:--' }} IN</span>
                            </div>
                            @if($att->check_out_time)
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div>
                                <span class="text-[10px] font-black text-slate-400">{{
                                    $att->check_out_time->format('H:i') }} OUT</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="py-6 px-6">
                        <span class="text-xs font-black text-slate-900 dark:text-white font-mono tracking-tighter">₹{{
                            number_format($att->daily_cost, 0) }}</span>
                    </td>
                    <td class="py-6 px-8 text-right">
                        @if($att->check_in_lat)
                        <a href="https://maps.google.com/?q={{ $att->check_in_lat }},{{ $att->check_in_lng }}"
                            target="_blank"
                            class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-brand-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>
                        @else
                        <span class="text-[10px] font-bold text-slate-300 uppercase italic">Offline</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="py-20 text-center text-slate-400 italic text-xs font-black uppercase tracking-widest">No
                        records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>

@if($view === 'list' && $attendances->hasPages())
<div class="px-8 py-5">
    {{ $attendances->links() }}
</div>
@endif
</div>

@if($attendances->hasPages())
<div class="px-8 py-5">
    {{ $attendances->links() }}
</div>
@endif
</div>
@endsection