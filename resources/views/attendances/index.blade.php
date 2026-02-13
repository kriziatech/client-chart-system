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
                class="text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-200 dark:border-dark-border px-4 py-3 rounded-2xl flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                System: Online & Tracking
            </div>
        </div>
    </div>

    <!-- Smart Filtration -->
    <div
        class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden mb-8">
        <form method="GET" action="{{ route('attendances.index') }}"
            class="px-8 py-5 flex flex-wrap gap-6 items-center bg-slate-50/30 dark:bg-dark-bg/30">
            <div class="space-y-1">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted ml-0.5">Chronology</p>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="bg-white dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2 text-[11px] font-bold focus:ring-4 focus:ring-brand-500/10">
            </div>

            <div class="space-y-1">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-ui-muted ml-0.5">Global Filter</p>
                <select name="user_id"
                    class="bg-white dark:bg-dark-bg border-ui-border dark:border-dark-border rounded-xl px-4 py-2 text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 appearance-none cursor-pointer pr-10">
                    <option value="">All Personnel</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name
                        }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3 pt-4">
                <button type="submit"
                    class="bg-slate-900 text-white text-[10px] px-6 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                    Sync Registry
                </button>
                <a href="{{ route('attendances.index') }}"
                    class="text-[10px] font-black uppercase tracking-widest text-ui-muted hover:text-slate-900 transition-colors">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                        <th class="py-5 px-8">Calendar Date</th>
                        <th class="py-5 px-8">Personnel Identity</th>
                        <th class="py-5 px-6">Check In Event</th>
                        <th class="py-5 px-6">Departure Event</th>
                        <th class="py-5 px-6">Shift Duration</th>
                        <th class="py-5 px-8 text-right">Geolocation Intel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border text-slate-600 dark:text-dark-muted">
                    @forelse($attendances as $att)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                        <td class="py-6 px-8 whitespace-nowrap">
                            <span
                                class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{
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
                            @if($att->check_in_time)
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-black text-emerald-600 uppercase">{{
                                    $att->check_in_time->format('h:i A') }}</span>
                            </div>
                            @else
                            <span class="text-xs font-medium text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="py-6 px-6">
                            @if($att->check_out_time)
                            <span class="text-xs font-black text-slate-400 uppercase">{{
                                $att->check_out_time->format('h:i A') }}</span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded bg-brand-50 text-brand-600 text-[9px] font-black uppercase tracking-widest animate-pulse italic">In
                                Progress</span>
                            @endif
                        </td>
                        <td class="py-6 px-6">
                            <span
                                class="text-xs font-black text-slate-900 dark:text-white font-mono tracking-tighter">{{
                                $att->duration ?: 'Active' }}</span>
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
                            <span class="text-[10px] font-bold text-slate-300 uppercase italic">Not Pinned</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 dark:bg-dark-bg rounded-3xl flex items-center justify-center text-slate-200 mb-4 border border-slate-100 dark:border-dark-border border-dashed text-brand-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white">Void Chronology.</h3>
                                <p
                                    class="text-[11px] text-ui-muted dark:text-dark-muted font-bold uppercase tracking-widest mt-1 italic">
                                    No personnel activity detected for the selected parameters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($attendances->hasPages())
    <div class="px-8 py-5">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection