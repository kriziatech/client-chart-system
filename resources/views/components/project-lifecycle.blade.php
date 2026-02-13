@props(['client'])

@php
// Determine the current stage based on project properties
// 1: Pitching, 2: Planning, 3: Execution, 4: Financial, 5: Handover

$currentStage = 1;

if ($client->feedback || ($client->handover && $client->handover->status === 'Completed')) {
$currentStage = 5;
} elseif ($client->payments->count() > 0 || $client->paymentRequests->count() > 0) {
$currentStage = 4;
} elseif ($client->tasks->count() > 0 || $client->dailyReports->count() > 0) {
$currentStage = 3;
} elseif ($client->quotations->where('status', 'approved')->count() > 0) {
$currentStage = 2;
}

$stages = [
[
'id' => 1,
'label' => 'Pitching Phase',
'subtext' => 'Portfolio shared, Estimate sent',
'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5
20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
'color' => 'blue',
'bgColor' => 'bg-blue-500',
'textColor' => 'text-blue-500',
'borderColor' => 'border-blue-500',
'glowColor' => 'shadow-blue-500/50'
],
[
'id' => 2,
'label' => 'Planning Phase',
'subtext' => 'Client approval, Roadmap locked',
'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0
01-2 2z',
'color' => 'indigo',
'bgColor' => 'bg-indigo-500',
'textColor' => 'text-indigo-500',
'borderColor' => 'border-indigo-500',
'glowColor' => 'shadow-indigo-500/50'
],
[
'id' => 3,
'label' => 'Execution Phase',
'subtext' => 'DPR updates, Change requests',
'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1
1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1
0 001-1V4z',
'color' => 'orange',
'bgColor' => 'bg-orange-500',
'textColor' => 'text-orange-500',
'borderColor' => 'border-orange-500',
'glowColor' => 'shadow-orange-500/50'
],
[
'id' => 4,
'label' => 'Financial Phase',
'subtext' => 'Invoices, Payments, P&L',
'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2
0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z',
'color' => 'green',
'bgColor' => 'bg-emerald-500',
'textColor' => 'text-emerald-500',
'borderColor' => 'border-emerald-500',
'glowColor' => 'shadow-emerald-500/50'
],
[
'id' => 5,
'label' => 'Handover Phase',
'subtext' => 'Warranty, Feedback, Closure',
'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
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
        $angle = ($index * 72) - 90; // Start from top
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