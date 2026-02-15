@props(['client'])

@php
$currentStage = $client->journey_stage;

$stages = [
[
'id' => 1,
'label' => 'New Client',
'subtext' => 'Lead received, Initial contact',
'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7
20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
'color' => 'blue',
'bgColor' => 'bg-blue-500',
'textColor' => 'text-blue-500',
'borderColor' => 'border-blue-500',
'glowColor' => 'shadow-blue-500/50'
],
[
'id' => 2,
'label' => 'Site Visit',
'subtext' => 'Physical inspection, Discovery',
'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0
016 0z',
'color' => 'cyan',
'bgColor' => 'bg-cyan-500',
'textColor' => 'text-cyan-500',
'borderColor' => 'border-cyan-500',
'glowColor' => 'shadow-cyan-500/50'
],
[
'id' => 3,
'label' => 'Quotation',
'subtext' => 'BOQ created, Estimate shared',
'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0
01-2 2z',
'color' => 'indigo',
'bgColor' => 'bg-indigo-500',
'textColor' => 'text-indigo-500',
'borderColor' => 'border-indigo-500',
'glowColor' => 'shadow-indigo-500/50'
],
[
'id' => 4,
'label' => 'Credit',
'subtext' => 'Advance received, Financial lock',
'icon' => 'M9 8h6m4 2a2 2 0 110 4m0 4a2 2 0 110-4m0 4v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2m4 0h14M5 22V10a2 2 0 012-2h12a2 2
0 012 2v10',
'color' => 'emerald',
'bgColor' => 'bg-emerald-500',
'textColor' => 'text-emerald-500',
'borderColor' => 'border-emerald-500',
'glowColor' => 'shadow-emerald-500/50'
],
[
'id' => 5,
'label' => 'Work Assigned',
'subtext' => 'Team allocated, Execution start',
'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0
4a2 2 0 110-4m0 4v2m0-6V4',
'color' => 'orange',
'bgColor' => 'bg-orange-500',
'textColor' => 'text-orange-500',
'borderColor' => 'border-orange-500',
'glowColor' => 'shadow-orange-500/50'
],
[
'id' => 6,
'label' => 'Timeline',
'subtext' => 'Gantt Chart locked, Schedule fixed',
'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
'color' => 'amber',
'bgColor' => 'bg-amber-500',
'textColor' => 'text-amber-500',
'borderColor' => 'border-amber-500',
'glowColor' => 'shadow-amber-500/50'
],
[
'id' => 7,
'label' => 'Work Completed',
'subtext' => 'Physical site completion',
'icon' => 'M5 13l4 4L19 7',
'color' => 'rose',
'bgColor' => 'bg-rose-500',
'textColor' => 'text-rose-500',
'borderColor' => 'border-rose-500',
'glowColor' => 'shadow-rose-500/50'
],
[
'id' => 8,
'label' => 'Final Payment',
'subtext' => 'Handover, Settlement, Feedback',
'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0
0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
'color' => 'purple',
'bgColor' => 'bg-purple-500',
'textColor' => 'text-purple-500',
'borderColor' => 'border-purple-500',
'glowColor' => 'shadow-purple-500/50'
],
];

$activeStageData = $stages[$currentStage - 1];
$progressPercent = ($currentStage / count($stages)) * 100;
@endphp

<div x-data="{ 
     current: {{ $currentStage }}, 
     hovered: null,
     progress: 0,
     init() {
         setTimeout(() => { this.progress = {{ $progressPercent }}; }, 500);
     }
}" class="relative w-full max-w-4xl mx-auto py-12 px-4 select-none">

    <!-- Desktop Circular Layout -->
    <div class="hidden md:block relative aspect-square max-w-[500px] mx-auto">
        <!-- Progress Ring SVG -->
        <svg class="absolute inset-0 w-full h-full -rotate-90 transform" viewBox="0 0 100 100">
            <!-- Background Ring -->
            <circle cx="50" cy="50" r="45" fill="none" class="stroke-slate-100 dark:stroke-slate-800"
                stroke-width="0.5" />
            <!-- Animated Progress Path -->
            <circle cx="50" cy="50" r="45" fill="none"
                class="transition-all duration-1000 ease-out {{ $activeStageData['textColor'] }}" stroke-width="1.5"
                stroke-linecap="round" stroke-dasharray="282.7" :stroke-dashoffset="282.7 - (282.7 * progress / 100)" />
        </svg>

        <!-- Center Phase Information -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center animate-in zoom-in duration-700">
                <div class="relative inline-block">
                    <div class="absolute inset-0 blur-2xl opacity-20 {{ $activeStageData['bgColor'] }}"></div>
                    <div
                        class="relative w-24 h-24 rounded-full bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border shadow-2xl flex items-center justify-center transition-all duration-500 transform hover:scale-110">
                        <svg class="w-10 h-10 {{ $activeStageData['textColor'] }}" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="{{ $activeStageData['icon'] }}"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{
                        $activeStageData['label'] }}</h4>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">{{
                        $activeStageData['subtext'] }}</p>
                </div>
            </div>
        </div>

        <!-- Stage Nodes -->
        @foreach($stages as $index => $stage)
        @php
        $angle = ($index * 45) - 90; // Spacing for 8 nodes (360/8 = 45)
        $x = 50 + 45 * cos(deg2rad($angle));
        $y = 50 + 45 * sin(deg2rad($angle));

        $isCompleted = $stage['id'] < $currentStage; $isActive=$stage['id']===$currentStage; $isUpcoming=$stage['id']>
            $currentStage;
            @endphp

            <div class="absolute cursor-pointer transition-all duration-500 group"
                style="left: {{ $x }}%; top: {{ $y }}%; transform: translate(-50%, -50%);"
                @mouseenter="hovered = {{ $stage['id'] }}" @mouseleave="hovered = null">

                <div class="relative">
                    <!-- Glow effect for active -->
                    @if($isActive)
                    <div class="absolute inset-0 rounded-full animate-ping opacity-20 {{ $stage['bgColor'] }}"></div>
                    @endif

                    <!-- Node Circle -->
                    <div
                        class="relative w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 transform group-hover:scale-125
                        {{ $isCompleted ? $stage['bgColor'] . ' ' . $stage['glowColor'] : '' }}
                        {{ $isActive ? 'bg-white dark:bg-dark-surface border-4 ' . $stage['borderColor'] . ' shadow-2xl scale-110' : '' }}
                        {{ $isUpcoming ? 'bg-white dark:bg-dark-surface border border-slate-200 dark:border-slate-700 opacity-60' : '' }}">

                        @if($isCompleted)
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        @elseif($isActive)
                        <span class="text-[10px] font-black {{ $stage['textColor'] }}">Active</span>
                        @else
                        <svg class="w-5 h-5 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="{{ $stage['icon'] }}"></path>
                        </svg>
                        @endif
                    </div>

                    <!-- Tooltip -->
                    <div x-show="hovered === {{ $stage['id'] }}" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute top-full mt-4 left-1/2 -translate-x-1/2 w-48 bg-slate-900 text-white p-3 rounded-2xl shadow-2xl z-50 text-center pointer-events-none">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">{{
                            $stage['label'] }}</p>
                        <p class="text-[11px] font-medium leading-tight">{{ $stage['subtext'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
    </div>

    <!-- Mobile Vertical Timeline -->
    <div class="md:hidden space-y-8 relative">
        <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-slate-100 dark:bg-slate-800"></div>

        @foreach($stages as $stage)
        @php
        $isCompleted = $stage['id'] < $currentStage; $isActive=$stage['id']===$currentStage; $isUpcoming=$stage['id']>
            $currentStage;
            @endphp
            <div class="relative flex items-center gap-6 group">
                <div
                    class="relative z-10 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300
                    {{ $isCompleted ? $stage['bgColor'] : '' }}
                    {{ $isActive ? 'bg-white dark:bg-dark-surface border-4 ' . $stage['borderColor'] . ' shadow-lg' : '' }}
                    {{ $isUpcoming ? 'bg-white dark:bg-dark-surface border border-slate-200 dark:border-slate-700' : '' }}">

                    @if($isCompleted)
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    @else
                    <svg class="w-6 h-6 {{ $isActive ? $stage['textColor'] : 'text-slate-300' }}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="{{ $stage['icon'] }}"></path>
                    </svg>
                    @endif
                </div>
                <div>
                    <h4
                        class="text-sm font-black {{ $isActive ? 'text-slate-900 dark:text-white' : 'text-slate-500' }} uppercase tracking-widest">
                        {{ $stage['label'] }}</h4>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5 leading-relaxed">{{ $stage['subtext'] }}</p>
                </div>
            </div>
            @endforeach
    </div>
</div>