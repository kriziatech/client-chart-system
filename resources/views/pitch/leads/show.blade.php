@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700" x-data="{ activeTab: 'intel' }">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12">
        <div>
            <a href="{{ route('pitch.leads.index') }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-600 transition-colors mb-4 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Pitch Dossiers
            </a>
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $lead->name }}</h1>
                @php
                $statusClasses = [
                'New' => 'bg-blue-50 text-blue-600 ring-blue-100',
                'In Progress' => 'bg-amber-50 text-amber-600 ring-amber-100',
                'Won' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                'Converted' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                'Lost' => 'bg-slate-50 text-slate-600 ring-slate-100',
                'Archived' => 'bg-slate-50 text-slate-600 ring-slate-100',
                ];
                $currentClasses = $statusClasses[$lead->status] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                @endphp
                <span
                    class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-2xl ring-1 shadow-sm {{ $currentClasses }}">
                    {{ $lead->status }}
                </span>
                @if($lead->is_converted)
                <span
                    class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-2xl bg-slate-900 text-white shadow-premium">
                    Handoff Successful
                </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($lead->is_converted)
            <a href="{{ route('clients.show', $lead->converted_client_id) }}"
                class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl flex items-center gap-2 group">
                Go to Project Portal
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
            @elseif($lead->status === 'Won')
            <form action="{{ route('pitch.leads.convert', $lead->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-emerald-600 text-white px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Convert to Project
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div
        class="flex items-center gap-8 mb-10 border-b border-slate-100 dark:border-dark-border overflow-x-auto pb-4 scrollbar-hide">
        <button x-on:click="activeTab = 'intel'"
            :class="activeTab === 'intel' ? 'text-brand-600 border-b-2 border-brand-600' : 'text-slate-400 hover:text-slate-600'"
            class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 transition-all flex items-center gap-2 whitespace-nowrap">
            Core Intel
        </button>
        <button x-on:click="activeTab = 'design'"
            :class="activeTab === 'design' ? 'text-brand-600 border-b-2 border-brand-600' : 'text-slate-400 hover:text-slate-600'"
            class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 transition-all flex items-center gap-2 whitespace-nowrap">
            Design & Concept Approval
        </button>
        <button x-on:click="activeTab = 'logistics'"
            :class="activeTab === 'logistics' ? 'text-brand-600 border-b-2 border-brand-600' : 'text-slate-400 hover:text-slate-600'"
            class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 transition-all flex items-center gap-2 whitespace-nowrap">
            On-Site Logistics
        </button>
    </div>

    @if(session('success'))
    <div
        class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-8 animate-in fade-in zoom-in duration-500">
        <p class="text-xs font-bold uppercase tracking-widest text-center">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div
        class="bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-2xl mb-8 animate-in fade-in zoom-in duration-500">
        <p class="text-xs font-bold uppercase tracking-widest text-center">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Core Intel Tab -->
    <div x-show="activeTab === 'intel'" class="animate-in fade-in duration-500">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8 space-y-10">
                <div
                    class="bg-white dark:bg-dark-surface p-10 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium relative overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[3px] text-brand-600 mb-6">Contractor
                                Intelligence</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email
                                            Address</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $lead->email ??
                                            'Not available' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Secure
                                            Line</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $lead->phone ??
                                            'Not available' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[3px] text-brand-600 mb-6">Source &
                                Engagement</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                            Discovery Channel</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $lead->source ??
                                            'Direct / Unknown' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                            Project Manager</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{
                                            $lead->assignedTo->name ?? 'Unassigned' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 pt-10 border-t border-slate-50 dark:border-dark-border">
                        <h3 class="text-[9px] font-black uppercase tracking-[3px] text-slate-400 mb-4">Project Briefing
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-dark-muted font-medium leading-relaxed max-w-3xl">
                            {{ $lead->work_description ?? 'No specific briefing recorded.' }}
                        </p>
                    </div>
                </div>

                <!-- Event Horizon (Activity) -->
                <div
                    class="bg-white dark:bg-dark-surface p-10 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                    <h3 class="text-xs font-bold uppercase tracking-[3px] text-brand-600 mb-10">Event Horizon (Logs)
                    </h3>
                    <div class="space-y-8 relative">
                        <div class="absolute top-0 bottom-0 left-5 w-px bg-slate-100 dark:bg-dark-border"></div>
                        @foreach($lead->activities->sortByDesc('created_at') as $activity)
                        <div class="relative flex gap-8">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-50 dark:bg-dark-bg border-4 border-white dark:border-dark-surface z-10 flex items-center justify-center">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand-500"></div>
                            </div>
                            <div class="flex-grow pt-1.5">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <h4
                                        class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                        {{ $activity->action }}</h4>
                                    <span class="text-[10px] font-bold text-slate-400">{{
                                        $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-dark-muted font-medium">{{ $activity->notes
                                    }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-10">
                @if(!$lead->is_converted || !auth()->user()->isSales())
                <div
                    class="bg-slate-900 dark:bg-white p-8 rounded-[2.5rem] shadow-premium relative overflow-hidden group">
                    <h3 class="text-xs font-black uppercase tracking-[3px] text-white/50 dark:text-slate-400 mb-8">Phase
                        Control</h3>
                    <form action="{{ route('pitch.leads.updateStatus', $lead->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Target
                                Phase</label>
                            <select name="status"
                                class="w-full bg-white/10 dark:bg-slate-100 border-none rounded-2xl px-5 py-4 text-xs font-black text-white dark:text-slate-900">
                                <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>NEW</option>
                                <option value="In Progress" {{ $lead->status === 'In Progress' ? 'selected' : '' }}>IN
                                    PROGRESS</option>
                                <option value="Won" {{ $lead->status === 'Won' ? 'selected' : '' }}>WON</option>
                                <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>LOST</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Brief
                                Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full bg-white/10 dark:bg-slate-100 border-none rounded-2xl px-5 py-4 text-xs font-bold text-white dark:text-slate-900 resize-none"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-brand-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Update
                            Phase</button>
                    </form>
                </div>
                @else
                <div class="bg-emerald-50 p-8 rounded-[2.5rem] border border-emerald-100">
                    <h3 class="text-xs font-black uppercase tracking-[3px] text-emerald-600 mb-4">Handoff Locked</h3>
                    <p class="text-xs text-emerald-700 font-medium leading-relaxed">This lead has been successfully
                        converted into a project. Sales team actions are now restricted to read-only.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Design & Concept Tab -->
    <div x-show="activeTab === 'design'" class="animate-in fade-in duration-500" x-data="{ assetConceptId: null }">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Design
                    Revisioning</h2>
                <p class="text-sm text-slate-400 font-medium mt-1">Track moodboards, 2D/3D renders and capture client
                    approval.</p>
            </div>
            @if(!$lead->is_converted || !auth()->user()->isSales())
            <button x-data x-on:click="$dispatch('open-modal', 'add-concept-modal')"
                class="bg-brand-600 text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-700 transition-all flex items-center gap-2">
                Init New Revision
            </button>
            @endif
        </div>

        <div class="space-y-12">
            @forelse($lead->concepts->sortByDesc('version') as $concept)
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[3rem] border border-ui-border dark:border-dark-border shadow-premium relative">
                <div
                    class="flex items-center justify-between mb-8 border-b border-slate-50 dark:border-dark-border pb-6">
                    <div class="flex items-center gap-6">
                        <span class="text-2xl font-black text-slate-300">v{{ $concept->version }}</span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                Revision Intelligence</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Initialized
                                on {{ $concept->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @php
                        $conceptStatusClasses = [
                        'Pending' => 'bg-amber-50 text-amber-600',
                        'Approved' => 'bg-emerald-50 text-emerald-600',
                        'Changes Required' => 'bg-rose-50 text-rose-600',
                        ];
                        $cStatusClass = $conceptStatusClasses[$concept->status] ?? 'bg-slate-50';
                        @endphp
                        <span
                            class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $cStatusClass }}">
                            {{ $concept->status }}
                        </span>

                        @if($concept->status === 'Pending')
                        <div class="flex items-center gap-2">
                            <form action="{{ route('pitch.concepts.updateStatus', $concept->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Approved">
                                <button
                                    class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg></button>
                            </form>
                            <form action="{{ route('pitch.concepts.updateStatus', $concept->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Changes Required">
                                <button
                                    class="p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg></button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($concept->assets as $asset)
                    <div
                        class="group bg-slate-50 dark:bg-dark-bg rounded-[2rem] overflow-hidden border border-ui-border transition-all hover:shadow-2xl hover:-translate-y-2">
                        <div class="aspect-video relative overflow-hidden bg-slate-200">
                            <img src="{{ Storage::url($asset->file_path) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                <span
                                    class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[8px] font-black text-white uppercase tracking-widest border border-white/30">
                                    {{ $asset->type }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h4
                                class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">
                                {{ $asset->title }}</h4>
                            <p class="text-[10px] text-slate-400 font-medium line-clamp-2 leading-relaxed mb-4">{{
                                $asset->description }}</p>

                            <!-- Comment Listing -->
                            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-dark-border">
                                @foreach($asset->feedback as $fb)
                                <div class="bg-white/50 dark:bg-white/5 p-3 rounded-xl border border-white">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[8px] font-black text-brand-600 uppercase">{{ $fb->user->name
                                            }}</span>
                                        <span class="text-[7px] text-slate-300 font-bold uppercase">{{
                                            $fb->created_at->format('d/m') }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 italic">"{{ $fb->comment }}"</p>
                                </div>
                                @endforeach

                                <form action="{{ route('pitch.assets.feedback.store', $asset->id) }}" method="POST"
                                    class="mt-4">
                                    @csrf
                                    <div class="relative">
                                        <input type="text" name="comment" placeholder="Add clarity..."
                                            class="w-full bg-slate-200/50 dark:bg-dark-surface border-none rounded-xl px-4 py-2.5 text-[10px] font-bold focus:ring-2 focus:ring-brand-500/20">
                                        <button class="absolute right-2 top-2 text-slate-400 hover:text-brand-600"><svg
                                                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="3"></path>
                                            </svg></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if(!$lead->is_converted || !auth()->user()->isSales())
                    <button x-on:click="assetConceptId = {{ $concept->id }}; $dispatch('open-modal', 'add-asset-modal')"
                        class="aspect-video bg-slate-50 dark:bg-dark-bg border-2 border-dashed border-slate-200 dark:border-dark-border rounded-[2rem] flex flex-col items-center justify-center gap-4 group hover:border-brand-600 transition-all">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white dark:bg-dark-surface flex items-center justify-center text-slate-400 group-hover:text-brand-600 group-hover:rotate-90 transition-all duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-brand-600">Inject
                            Component</span>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div
                class="bg-white dark:bg-dark-surface p-20 text-center rounded-[3rem] border-2 border-dashed border-slate-100">
                <div
                    class="w-20 h-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase">Visual Void</h3>
                <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto">No design concepts or visualizations have
                    been attached to this pitch dossier yet.</p>
                <button x-on:click="$dispatch('open-modal', 'add-concept-modal')"
                    class="bg-slate-900 text-white px-10 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest">Begin
                    Concepting</button>
            </div>
            @endforelse
        </div>

        <!-- Design Modals -->
        <x-modal name="add-concept-modal" focusable>
            <div class="p-10">
                <h2
                    class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 border-b pb-4">
                    Initialize Revision</h2>
                <form action="{{ route('pitch.leads.concepts.store', $lead->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Revision
                            Goal / Notes</label>
                        <textarea name="notes" rows="4"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold resize-none"
                            placeholder="e.g. Incorporating modern rustic elements based on feedback..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Cancel</button>
                        <button type="submit"
                            class="bg-brand-600 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-brand-500/20">Initialize</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <x-modal name="add-asset-modal" focusable>
            <div class="p-10">
                <h2
                    class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 border-b pb-4">
                    Inject Design Component</h2>
                <form :action="'{{ url('/pitch/concepts') }}/' + assetConceptId + '/assets'" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Visual
                                Category</label>
                            <select name="type" required
                                class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-black uppercase">
                                <option value="Moodboard">Moodboard</option>
                                <option value="2D Drawing">2D Drawing</option>
                                <option value="3D Render">3D Render</option>
                                <option value="Material Selection">Material Selection</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Title</label>
                            <input type="text" name="title" required
                                class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">High-Res
                            Render/File</label>
                        <input type="file" name="file" required
                            class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-6 text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Brief
                            Context</label>
                        <textarea name="description" rows="2"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold resize-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Cancel</button>
                        <button type="submit"
                            class="bg-slate-900 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">Inject
                            Asset</button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    <!-- On-Site Logistics Tab -->
    <div x-show="activeTab === 'logistics'" class="animate-in fade-in duration-500">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8 space-y-10">
                <div
                    class="bg-white dark:bg-dark-surface p-10 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-[3px] text-brand-600">On-Site Engagement
                            </h3>
                            <p class="text-xs text-ui-muted dark:text-dark-muted font-medium mt-2">Log of site visits,
                                meetings, and physical inspections.</p>
                        </div>
                        @if(!$lead->is_converted || !auth()->user()->isSales())
                        <button x-data x-on:click="$dispatch('open-modal', 'add-visit-modal')"
                            class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-600 transition-colors">
                            Add Visit
                        </button>
                        @endif
                    </div>

                    <div class="space-y-6">
                        @forelse($lead->visits as $visit)
                        <div class="p-6 bg-slate-50 dark:bg-dark-bg rounded-3xl border border-ui-border">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="text-[9px] font-black text-brand-600 uppercase tracking-widest mb-1">{{
                                        \Carbon\Carbon::parse($visit->visit_date)->format('d M Y') }}</p>
                                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $visit->purpose }}
                                    </h4>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Rep: {{
                                    $visit->user->name }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-dark-muted font-medium leading-relaxed">{{
                                $visit->notes }}</p>
                        </div>
                        @empty
                        <div class="text-center py-10 opacity-40">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">No on-site
                                engagements recorded.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-10">
                <div
                    class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xs font-black uppercase tracking-[3px] text-brand-600">Operational Sites</h3>
                        @if(!$lead->is_converted || !auth()->user()->isSales())
                        <button x-data x-on:click="$dispatch('open-modal', 'add-site-modal')"
                            class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-dark-bg flex items-center justify-center text-slate-400 hover:text-brand-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($lead->sites as $site)
                        <div class="p-4 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border">
                            <p class="text-xs font-bold text-slate-900 dark:text-white mb-1">{{ $site->address }}</p>
                            <p class="text-[9px] font-black text-brand-600 uppercase tracking-widest">{{
                                $site->plot_size ?? 'Plot Size: TBD' }}</p>
                        </div>
                        @empty
                        <div
                            class="text-center py-10 opacity-40 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            No sites identified.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logistics Modals (Existing) -->
<x-modal name="add-site-modal" focusable>
    <div class="p-10">
        <h2 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 border-b pb-4">New
            Operational Site</h2>
        <form action="{{ route('pitch.leads.sites.store', $lead->id) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Full
                    Address</label>
                <input type="text" name="address" required
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Plot size /
                    Dimension</label>
                <input type="text" name="plot_size"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold"
                    placeholder="e.g. 30x40, 1200 sqft">
            </div>
            <div class="flex justify-end gap-3 pt-6">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Cancel</button>
                <button type="submit"
                    class="bg-brand-600 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">Save
                    Site</button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal name="add-visit-modal" focusable>
    <div class="p-10">
        <h2 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 border-b pb-4">
            Schedule Site Visit</h2>
        <form action="{{ route('pitch.leads.visits.store', $lead->id) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Visit
                    Date</label>
                <input type="date" name="visit_date" required
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Purpose of
                    Visit</label>
                <input type="text" name="purpose" required
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold"
                    placeholder="e.g. Initial Measurement">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Brief
                    Notes</label>
                <textarea name="notes" rows="3"
                    class="w-full bg-slate-50 dark:bg-dark-bg border-slate-200 rounded-xl text-sm font-bold resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-6">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Cancel</button>
                <button type="submit"
                    class="bg-brand-600 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">Schedule
                    Visit</button>
            </div>
        </form>
    </div>
</x-modal>
@endsection