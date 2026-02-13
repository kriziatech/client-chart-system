@extends('layouts.app')

@section('content')
<div
    class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:bg-dark-surface dark:border-dark-border transition-colors duration-200 mt-8 mb-16">
    <div
        class="bg-gray-50 border-b border-gray-200 dark:bg-slate-800 dark:border-dark-border px-8 py-6 flex justify-between items-center transition-colors duration-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Project Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Real-time progress tracking</p>
        </div>
        <div>
            <a href="javascript:window.print()"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-medium shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Report
            </a>
        </div>
    </div>

    <div x-data="{ activeTab: 'overview', showWelcome: true }" class="p-8 space-y-8">

        <!-- Welcome Modal -->
        <div x-show="showWelcome"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-cloak>
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[3rem] w-full max-w-2xl shadow-2xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-600/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-brand-600 rounded-3xl flex items-center justify-center text-white mb-6 shadow-xl shadow-brand-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z">
                            </path>
                        </svg>
                    </div>

                    <h2 class="text-3xl font-black text-ui-primary dark:text-white leading-tight">Welcome to <br><span
                            class="text-brand-600">The InteriorTouch Experience</span></h2>
                    <p class="text-ui-muted mt-4 text-base font-medium">We're thrilled to begin your project. Here's a
                        quick guide to how we work together through this portal.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">01.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Planning</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">View your roadmap and approve designs
                                via the 'Roadmap' tab.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">02.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Execution</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">Track daily site updates and material
                                procurement in real-time.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">03.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Delivery</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">Formal handover and quality checks are
                                logged here for your records.</p>
                        </div>
                    </div>

                    <button @click="showWelcome = false"
                        class="mt-12 w-full py-4 bg-brand-600 text-white rounded-2xl font-black text-sm uppercase tracking-[3px] shadow-xl shadow-brand-500/30 hover:bg-brand-700 transition-all active:scale-95">
                        Get Started
                    </button>
                </div>
            </div>
        </div>

        <!-- Portal Tabs -->
        <div class="flex gap-1 bg-slate-100 dark:bg-dark-bg p-1 rounded-2xl w-fit">
            <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Overview</button>
            <button @click="activeTab = 'scope'"
                :class="activeTab === 'scope' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Scope
                of Work</button>
            <button @click="activeTab = 'roadmap'"
                :class="activeTab === 'roadmap' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Project
                Roadmap</button>
            <button @click="activeTab = 'execution'"
                :class="activeTab === 'execution' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Execution
                Feed</button>
            <button @click="activeTab = 'inventory'"
                :class="activeTab === 'inventory' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Site
                Materials</button>
            <button @click="activeTab = 'handover'"
                :class="activeTab === 'handover' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Handover
                & Warranty</button>
        </div>

        <!-- Overview Content -->
        <div x-show="activeTab === 'overview'" class="space-y-8 animate-in slide-in-from-bottom duration-500">
            {{-- Project Lifecycle Visualization --}}
            <x-project-lifecycle :client="$client" />

            {{-- Progress Overview --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[11px] font-black text-ui-muted uppercase tracking-[1.5px]">Project Health</span>
                    <span class="text-3xl font-black text-brand-600">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-brand-600 h-full rounded-full transition-all duration-1000 ease-out shadow-lg shadow-brand-500/20"
                        style="width: {{ $progress }}%"></div>
                </div>
                @if($client->delivery_date)
                <div class="mt-4 flex justify-between text-[11px] font-bold uppercase tracking-wider text-ui-muted">
                    <div>Started: <span class="text-ui-primary dark:text-white ml-2">{{ $client->start_date ?
                            $client->start_date->format('d M, Y') : '-' }}</span></div>
                    <div>Target: <span class="text-ui-primary dark:text-white ml-2">{{ $client->delivery_date->format('d
                            M, Y') }}</span></div>
                </div>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                {{-- Client Info --}}
                <div
                    class="bg-slate-50 dark:bg-dark-bg p-7 rounded-3xl border border-ui-border dark:border-dark-border">
                    <h3 class="text-[10px] font-black uppercase text-brand-600 mb-6 tracking-[2px]">Core Details</h3>
                    <div class="space-y-5">
                        <div class="flex flex-col border-b border-ui-border dark:border-dark-border pb-4">
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-1">Project Name</span>
                            <span class="font-black text-ui-primary dark:text-white text-base">{{ $client->first_name }}
                                {{ $client->last_name }}</span>
                        </div>
                        <div class="flex flex-col border-b border-ui-border dark:border-dark-border pb-4">
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-1">File Reference</span>
                            <span class="font-bold text-ui-primary dark:text-white text-sm">#{{ $client->file_number
                                }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-2 block">Scope Analysis</span>
                            <p class="text-ui-primary dark:text-white font-medium text-sm leading-relaxed">{{
                                $client->work_description }}</p>
                        </div>
                    </div>
                </div>

                {{-- Milestones (Checklist) --}}
                <div
                    class="bg-slate-50 dark:bg-dark-bg p-7 rounded-3xl border border-ui-border dark:border-dark-border">
                    <h3 class="text-[10px] font-black uppercase text-brand-600 mb-6 tracking-[2px]">Phase Approvals</h3>
                    <div class="space-y-3 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                        @php
                        $checklistItems = $client->checklistItems;
                        @endphp
                        @forelse($checklistItems as $item)
                        <div
                            class="flex items-center p-3 rounded-2xl bg-white dark:bg-dark-surface border border-ui-border transition-all">
                            <span
                                class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded-xl text-[10px] mr-4 {{ $item->is_checked ? 'bg-ui-success text-white shadow-lg shadow-green-500/20' : 'border-2 border-slate-200 dark:border-slate-700 text-transparent' }}">✓</span>
                            <span
                                class="text-sm {{ $item->is_checked ? 'text-ui-primary dark:text-white font-bold' : 'text-ui-muted font-medium' }}">{{
                                $item->name }}</span>
                        </div>
                        @empty
                        <p class="text-ui-muted text-xs italic text-center py-10 uppercase tracking-widest font-bold">No
                            milestones logged.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Roadmap Content -->
        <div x-show="activeTab === 'roadmap'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div
                class="bg-slate-50 dark:bg-dark-bg p-8 rounded-[2.5rem] border border-ui-border dark:border-dark-border">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Project Visual
                            Roadmap</h3>
                        <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest italic">Phase-wise
                            execution timeline</p>
                    </div>
                </div>

                <div
                    class="space-y-6 relative before:absolute before:inset-y-0 before:left-3 before:w-1 before:bg-brand-600/10 before:rounded-full">
                    @forelse($client->tasks->sortBy('deadline') as $task)
                    <div class="relative pl-12 group">
                        <div
                            class="absolute left-0 top-1.5 w-7 h-7 bg-white dark:bg-dark-surface rounded-full border-4 border-brand-600 shadow-premium z-10 flex items-center justify-center transition-transform group-hover:scale-110">
                            @if($task->status === 'Completed')
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <div class="w-2 h-2 bg-brand-600 rounded-full animate-pulse"></div>
                            @endif
                        </div>

                        <div
                            class="bg-white dark:bg-dark-surface p-5 rounded-2xl border border-ui-border shadow-premium transition-all hover:border-brand-300">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-brand-600 uppercase tracking-widest">{{
                                            $task->status }}</span>
                                        <span class="w-1 h-1 bg-ui-border rounded-full"></span>
                                        <span class="text-[11px] font-bold text-ui-muted">Target: {{ $task->deadline ?
                                            $task->deadline->format('M d, Y') : 'TBD' }}</span>
                                    </div>
                                    <h4 class="text-sm font-black text-ui-primary dark:text-white leading-snug">{{
                                        $task->description }}</h4>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="px-3 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg text-[10px] font-black text-ui-muted uppercase tracking-widest border border-ui-border">
                                        {{ $task->start_date ? $task->start_date->format('M d') : 'Day 0' }} → {{
                                        $task->deadline ? $task->deadline->format('M d') : 'End' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-20">
                        <p class="text-ui-muted font-bold text-xs uppercase tracking-[3px]">Timeline not yet
                            established.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Handover Content -->
    <div x-show="activeTab === 'handover'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>

        {{-- Warranty Card --}}
        @if($client->handover && $client->handover->status === 'completed')
        <div
            class="relative overflow-hidden bg-slate-900 text-white p-8 rounded-[2.5rem] border border-slate-700 shadow-2xl">
            <div class="absolute top-0 right-0 p-10 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-xs font-black text-green-400 uppercase tracking-[3px]">Warranty Active</span>
                    </div>
                    <h2 class="text-3xl font-black tracking-tight mb-2">Service Warranty Certificate</h2>
                    <p class="text-slate-400 font-medium max-w-md">This project is covered under our premium service
                        warranty against manufacturing defects.</p>

                    <div class="mt-8 grid grid-cols-2 gap-8">
                        <div>
                            <span
                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1">Valid
                                Until</span>
                            <span class="text-xl font-mono font-bold">{{ $client->handover->warranty_expiry->format('d
                                M, Y') }}</span>
                        </div>
                        <div>
                            <span
                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1">Coverage</span>
                            <span class="text-xl font-bold">{{ $client->handover->warranty_years }} Years</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 text-center">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-300 block mb-2">Reference
                        ID</span>
                    <div class="font-mono text-2xl font-bold tracking-wider mb-4">{{ $client->file_number }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-amber-800">Handover in Progress</h3>
                <p class="text-sm text-amber-700">The warranty certificate will be generated once the official handover
                    is complete.</p>
            </div>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Checklist -->
            <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">Handover
                    Checklist</h3>
                @if($client->handover)
                <div class="space-y-3">
                    @forelse($client->handover->checklistItems as $item)
                    <label
                        class="flex items-center p-4 rounded-2xl bg-slate-50 dark:bg-dark-bg border border-ui-border cursor-pointer hover:border-brand-300 transition-all select-none">
                        <input type="checkbox" disabled {{ $item->is_completed ? 'checked' : '' }} class="w-5 h-5
                        text-brand-600 rounded-lg border-gray-300 focus:ring-brand-500">
                        <span
                            class="ml-3 text-sm font-bold text-ui-primary dark:text-white {{ $item->is_completed ? 'line-through opacity-50' : '' }}">{{
                            $item->item_name }}</span>
                    </label>
                    @empty
                    <p class="text-xs text-ui-muted font-bold uppercase tracking-widest text-center py-8">Checklist
                        being prepared.</p>
                    @endforelse
                </div>
                @else
                <p class="text-xs text-ui-muted font-bold uppercase tracking-widest text-center py-8">Handover process
                    not started.</p>
                @endif
            </div>

            <!-- Feedback -->
            <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">Your
                    Feedback</h3>

                @if($client->feedback)
                <div class="text-center py-8">
                    <div class="inline-flex gap-1 text-amber-400 mb-4">
                        @for($i=1; $i<=5; $i++) <svg
                            class="w-8 h-8 {{ $i <= $client->feedback->rating ? 'fill-current' : 'text-slate-200' }}"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            @endfor
                    </div>
                    <p class="text-ui-primary dark:text-white font-medium italic">"{{ $client->feedback->comment }}"</p>
                    <p class="mt-4 text-xs font-black text-brand-600 uppercase tracking-widest">Submitted on {{
                        $client->feedback->created_at->format('d M, Y') }}</p>
                </div>
                @else
                <form action="{{ route('feedback.store', $client) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-black uppercase text-ui-muted mb-2 tracking-widest">Rate your
                            experience</label>
                        <div class="flex flex-row-reverse justify-end gap-2 group">
                            <input type="radio" name="rating" value="5" id="star5" class="peer/5 hidden" checked />
                            <label for="star5"
                                class="cursor-pointer text-slate-200 peer-checked/5:text-amber-400 hover:text-amber-400 peer-hover/5:text-amber-400 transition-colors">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </label>
                            <input type="radio" name="rating" value="4" id="star4" class="peer/4 hidden" />
                            <label for="star4"
                                class="cursor-pointer text-slate-200 peer-checked/4:text-amber-400 hover:text-amber-400 peer-hover/4:text-amber-400 transition-colors">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </label>
                            <input type="radio" name="rating" value="3" id="star3" class="peer/3 hidden" />
                            <label for="star3"
                                class="cursor-pointer text-slate-200 peer-checked/3:text-amber-400 hover:text-amber-400 peer-hover/3:text-amber-400 transition-colors">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </label>
                            <input type="radio" name="rating" value="2" id="star2" class="peer/2 hidden" />
                            <label for="star2"
                                class="cursor-pointer text-slate-200 peer-checked/2:text-amber-400 hover:text-amber-400 peer-hover/2:text-amber-400 transition-colors">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </label>
                            <input type="radio" name="rating" value="1" id="star1" class="peer/1 hidden" />
                            <label for="star1"
                                class="cursor-pointer text-slate-200 peer-checked/1:text-amber-400 hover:text-amber-400 peer-hover/1:text-amber-400 transition-colors">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-ui-muted mb-2 tracking-widest">Comments
                            (Optional)</label>
                        <textarea name="comment" rows="3"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-500"
                            placeholder="How was your journey with us?"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-brand-500/20 transition-all transform active:scale-95">Submit
                        Review</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 dark:bg-slate-800 px-8 py-4 border-t border-gray-200 dark:border-slate-700 text-center">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        For support or queries, please contact your project manager. <br>
        Reference ID: #{{ $client->uuid }}
    </p>
</div>
</div>
@endsection <div
    class="px-6 py-4 bg-slate-50 dark:bg-dark-bg border-b border-ui-border flex justify-between items-center">
    <span class="text-xs font-black text-brand-600 uppercase tracking-widest">{{ $report->report_date ?
        $report->report_date->format('l, d M Y') : '-' }}</span>
    <span
        class="px-2 py-1 bg-white dark:bg-dark-surface rounded-lg text-[10px] font-bold text-ui-muted border border-ui-border">Site
        Verified</span>
</div>
<div class="p-6">
    <p class="text-sm text-ui-primary dark:text-white leading-relaxed mb-6 font-medium">{{ $report->content }}</p>
    @if($report->images->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($report->images as $img)
        <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank"
            class="aspect-square rounded-2xl overflow-hidden border border-ui-border hover:opacity-90 transition-opacity">
            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="Site Update">
        </a>
        @endforeach
    </div>
    @endif
</div>
</div>
@empty
<div class="py-20 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-ui-border">
    <p class="text-xs font-black text-ui-muted uppercase tracking-widest">No daily reports logged yet.</p>
</div>
@endforelse
</div>

<!-- Change Requests Side -->
<div class="space-y-6">
    <h3 class="text-sm font-black text-ui-primary dark:text-white uppercase tracking-[2px] mb-4">Change Requests</h3>
    <div class="space-y-4">
        @forelse($client->changeRequests->sortByDesc('created_at') as $cr)
        <div
            class="bg-white dark:bg-dark-surface p-6 rounded-2xl border-2 {{ $cr->status === 'pending' ? 'border-amber-100 bg-amber-50/20' : ($cr->status === 'approved' ? 'border-green-100 bg-green-50/20' : 'border-red-100 bg-red-50/20') }}">
            <div class="flex justify-between items-start mb-3">
                <span
                    class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $cr->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($cr->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                    {{ $cr->status }}
                </span>
                <span class="text-[10px] font-bold text-ui-muted">{{ $cr->created_at->format('M d, Y') }}</span>
            </div>
            <h4 class="text-xs font-black text-ui-primary dark:text-white uppercase tracking-wider mb-2 leading-snug">{{
                $cr->title }}</h4>
            <p class="text-[11px] text-ui-muted leading-relaxed mb-4 line-clamp-3">{{ $cr->description }}</p>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-ui-muted uppercase tracking-widest">Budget Impact</span>
                    <span class="text-sm font-black text-ui-primary dark:text-white">₹{{ number_format($cr->cost_impact)
                        }}</span>
                </div>

                @if($cr->status === 'pending')
                <div class="flex gap-2">
                    <form action="{{ route('execution.change-request.update', $cr) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit"
                            class="p-1.5 bg-green-600 text-white rounded-lg shadow-lg shadow-green-500/20 hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </form>
                    <form action="{{ route('execution.change-request.update', $cr) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit"
                            class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p
            class="text-[10px] font-bold text-ui-muted text-center py-10 uppercase tracking-widest border border-ui-border rounded-2xl">
            No modifications requested.</p>
        @endforelse
    </div>
</div>
</div>
</div>

<!-- Site Materials Content -->
<div x-show="activeTab === 'inventory'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
    <div class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 dark:bg-dark-bg border-b border-ui-border flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Material Log</h3>
                <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Procurement & delivery
                    status</p>
            </div>
            <div class="px-4 py-2 bg-brand-50 dark:bg-brand-500/10 rounded-xl border border-brand-100">
                <span class="text-xs font-black text-brand-600 uppercase tracking-widest">Site Inventory</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] border-b border-ui-border">
                        <th class="px-8 py-5">Material Description</th>
                        <th class="px-8 py-5">Quantity</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Arrival Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border">
                    @forelse($client->projectMaterials as $material)
                    <tr class="hover:bg-slate-50 dark:hover:bg-dark-bg transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-ui-primary dark:text-white">{{
                                    $material->inventoryItem->name }}</span>
                                <span class="text-[10px] text-ui-muted font-bold uppercase tracking-wider mt-1">{{
                                    $material->inventoryItem->category }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-black text-ui-primary dark:text-white">
                                {{ $material->quantity_dispatched }} {{ $material->inventoryItem->unit }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $material->status === 'Delivered' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $material->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold text-ui-muted">
                            {{ $material->delivery_date ? $material->delivery_date->format('M d, Y') : 'Pending' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4"
                            class="px-8 py-20 text-center font-bold text-xs text-ui-muted uppercase tracking-[3px]">No
                            materials logged for this site yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="bg-gray-50 dark:bg-slate-800 px-8 py-4 border-t border-gray-200 dark:border-slate-700 text-center">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        For support or queries, please contact your project manager. <br>
        Reference ID: #{{ $client->uuid }}
    </p>
</div>
</div>
@endsection <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Material Log</h3>
<p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Procurement & delivery
    status</p>
</div>
<div class="px-4 py-2 bg-brand-50 dark:bg-brand-500/10 rounded-xl border border-brand-100">
    <span class="text-xs font-black text-brand-600 uppercase tracking-widest">Site Inventory</span>
</div>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] border-b border-ui-border">
                <th class="px-8 py-5">Material Description</th>
                <th class="px-8 py-5">Quantity</th>
                <th class="px-8 py-5">Status</th>
                <th class="px-8 py-5">Arrival Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ui-border">
            @forelse($client->projectMaterials as $material)
            <tr class="hover:bg-slate-50 dark:hover:bg-dark-bg transition-colors">
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-ui-primary dark:text-white">{{
                            $material->inventoryItem->name }}</span>
                        <span class="text-[10px] text-ui-muted font-bold uppercase tracking-wider mt-1">{{
                            $material->inventoryItem->category }}</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span
                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-black text-ui-primary dark:text-white">
                        {{ $material->quantity_dispatched }} {{ $material->inventoryItem->unit }}
                    </span>
                </td>
                <td class="px-8 py-6">
                    <span
                        class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $material->status === 'Delivered' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $material->status }}
                    </span>
                </td>
                <td class="px-8 py-6 text-sm font-bold text-ui-muted">
                    {{ $material->delivery_date ? $material->delivery_date->format('M d, Y') : 'Pending' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-8 py-20 text-center font-bold text-xs text-ui-muted uppercase tracking-[3px]">
                    No
                    materials logged for this site yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<div class="bg-gray-50 dark:bg-slate-800 px-8 py-4 border-t border-gray-200 dark:border-slate-700 text-center">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        For support or queries, please contact your project manager. <br>
        Reference ID: #{{ $client->uuid }}
    </p>
</div>
</div>
@endsection