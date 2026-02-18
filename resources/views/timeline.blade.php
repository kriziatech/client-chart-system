@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] dark:bg-dark-bg py-12 px-4 overflow-hidden">
    <div class="max-w-[1600px] mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">Project
                    Timeline</h1>
                <p class="text-slate-500 font-medium mt-1">Gantt chart for multi-project schedule and resource
                    management
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="flex p-1 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-dark-border shadow-sm">
                    <button onclick="changeView('Day')"
                        class="view-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all hover:bg-white dark:hover:bg-brand-600/20 text-slate-500 dark:text-slate-400"
                        id="btn-day">Day</button>
                    <button onclick="changeView('Week')"
                        class="view-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all bg-white dark:bg-brand-600 shadow-sm text-slate-900 dark:text-white"
                        id="btn-week">Week</button>
                    <button onclick="changeView('Month')"
                        class="view-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all hover:bg-white dark:hover:bg-brand-600/20 text-slate-500 dark:text-slate-400"
                        id="btn-month">Month</button>
                </div>

                <div class="flex items-center gap-4 ml-4">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-brand-500"></span><span
                            class="text-[10px] font-black uppercase text-slate-400">Scheduled</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span
                            class="text-[10px] font-black uppercase text-slate-400">Completed</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span
                            class="text-[10px] font-black uppercase text-slate-400">Overdue</span></div>
                </div>
            </div>
        </div>

        {{-- Gantt Chart Container --}}
        <div
            class="bg-white dark:bg-slate-900/40 rounded-[40px] border border-slate-100 dark:border-dark-border shadow-premium overflow-hidden">
            <div id="gantt-target" class="w-full"></div>
        </div>
    </div>
</div>

{{-- Frappe Gantt Resources --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.min.js"></script>

<style>
    /* Premium Styling Overrides for Frappe Gantt */
    .gantt .grid-header {
        fill: transparent !important;
    }

    .gantt .grid-row {
        fill: transparent !important;
    }

    .gantt .grid-row:nth-child(even) {
        fill: rgba(0, 0, 0, 0.01) !important;
    }

    .dark .gantt .grid-row:nth-child(even) {
        fill: rgba(255, 255, 255, 0.02) !important;
    }

    .gantt .bar-wrapper .bar {
        fill: #6366f1 !important;
        rx: 8px;
        ry: 8px;
    }

    .gantt .bar-wrapper .bar.bar-completed {
        fill: #10b981 !important;
    }

    .gantt .bar-wrapper .bar.bar-overdue {
        fill: #ef4444 !important;
    }

    .gantt .bar-wrapper .bar.bar-project {
        fill: #f1f5f9 !important;
        stroke: #e2e8f0 !important;
        stroke-width: 1px;
    }

    .dark .gantt .bar-wrapper .bar.bar-project {
        fill: rgba(255, 255, 255, 0.05) !important;
        stroke: rgba(255, 255, 255, 0.1) !important;
    }

    .gantt .bar-label {
        font-size: 10px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        fill: #475569 !important;
    }

    .dark .gantt .bar-label {
        fill: #cbd5e1 !important;
    }

    .gantt .bar-label.big {
        fill: #fff !important;
    }

    .gantt .handle-group {
        display: none !important;
    }

    /* Disable resize handles for cleaner view */

    .gantt .lower-text {
        font-size: 10px !important;
        font-weight: 700 !important;
        fill: #94a3b8 !important;
        text-transform: uppercase;
    }

    .gantt .upper-text {
        font-size: 11px !important;
        font-weight: 900 !important;
        fill: #64748b !important;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .gantt .arrow {
        stroke: #e2e8f0 !important;
        stroke-width: 1.5px;
        opacity: 0.5;
    }

    .dark .gantt .arrow {
        stroke: rgba(255, 255, 255, 0.1) !important;
    }

    .gantt .grid-line {
        stroke: #f1f5f9 !important;
    }

    .dark .gantt .grid-line {
        stroke: rgba(255, 255, 255, 0.05) !important;
    }

    .gantt .today-highlight {
        fill: rgba(99, 102, 241, 0.05) !important;
    }

    /* Transitions */
    .bar-wrapper {
        transition: filter 0.3s ease;
    }

    .bar-wrapper:hover {
        filter: brightness(1.1);
    }

    /* Custom Scrollbar */
    #gantt-target {
        overflow-x: auto;
    }

    #gantt-target::-webkit-scrollbar {
        height: 6px;
    }

    #gantt-target::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .dark #gantt-target::-webkit-scrollbar-thumb {
        background: #334155;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tasks = [
            @foreach($clients as $client)
            @php
                    $pStartStr = ($cli en t->start_date ?: $cli en t->created_ at )->format('Y-m-d'           $pEndObj = ($ cl ient->delivery_date ?: ($ cl ient->start_date ? $ cl ient->star t_ date-> co py()->addMonths(3) : $ cl ient->crea te d_at-> co py()->addMonth s( 3)));
                   r = $pEn        m-             @endphp
                {
                      roject_{{ $client->id }}',
                    name: {!! json_encode($client->first_name . " " . $client                                          st                            }}',
                                         $pEndStr }}',
                      0,             custom_clas s:  'bar-project'
                      t->tasks as $tas k) 
                     @ php
                          $tStart Ob j = ($task->start        _at ?: $client -> created_at));
                           $t StartStr =  $ tStart Ob j->format('Y-m-d');
                              $tEndObj =  ( $task->deadline ?        task->start_date->copy()->add Days(7) : $c li ent->created_at-> co py()->addDays(7)));
           ndStr = $tEndObj->fo rm at('Y-m-d');
                        $isOverdue = $tEndObj->isPast() && $task->status !=                        ss            pleted' ? 'bar-completed' :                 ue' : 'bar-ongoing');
                                            {
                        id: 'Task_{{ $task->id }                                name: { !! json _e ncode($task->title) !!},
                        $tStartStr }}',
                        end                                    progress: {{ $t        let        ,
                    dependencies: 'Project_{{ $client->id }}',
                        custom_class: '{{ $statusClass }}'
                    },
                @endforeach
            @endforeach
        ];

        window.gantt = new Gantt("#gantt-target", tasks, {
            view_mode: 'Week',
            date_format: 'YYYY-MM-DD',
             bar_height: 35,
            padding: 18,
            on_click: function (task) {
                console.log(task);
            }
        });
    });

    function changeView(mode)   if(window.gantt) {
            window.gantt.change_view_mode(mode);
            
            // Update Buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'dark:bg-brand-600', 'shadow-sm', 'text-slate-900', 'dark:tex);
                btn.classList.add('text-slate-500', 'dark:text-slate-400');
            });
            
            const activeBtn = document.getElementById('btn-' + mode.toLowerCase());
            if(activeBtn) {
                activeBtn.classList.add('bg-white', 'dark:bg- 'shadow-sm', 'text-slate-900', 'dark:text-white');
                activeBtn.classList.remove('text-slate-500', 'dark:text-slate-400');
            }
        }
    }
</script>
@endsection