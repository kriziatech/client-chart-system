@extends('layouts.app')

@section('content')
<div x-data="pipelineApp()" x-cloak class="min-h-screen bg-gray-50 dark:bg-gray-900">

    {{-- Header --}}
    <header
        class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 px-6 py-5 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    Sales Pipeline
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-[52px]">Track deal progress from Lead to
                    Won</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pipeline Value</p>
                    <p class="text-lg font-black text-indigo-600 dark:text-indigo-400">₹{{
                        number_format($stats['pipeline_value']) }}</p>
                </div>
                <div class="h-10 w-px bg-slate-200 dark:bg-slate-700 hidden md:block"></div>
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                    <a href="{{ route('leads.index') }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-indigo-600 transition-all">List
                        View</a>
                    <button
                        class="bg-white dark:bg-slate-700 shadow text-indigo-600 px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Board
                        View</button>
                </div>
            </div>
        </div>
    </header>

    <main class="p-6 overflow-x-auto h-[calc(100vh-100px)] custom-scrollbar">
        <div class="flex gap-6 min-w-max h-full pb-4">
            @foreach($pipeline as $stage => $data)
            <div class="flex flex-col w-80 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-800"
                @dragover.prevent @drop="dropLead('{{ $stage }}', $event)">

                {{-- Stage Header --}}
                <div
                    class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between sticky top-0 bg-inherit rounded-t-2xl z-10">
                    <div>
                        <div class="flex items-center gap-2">
                            <div @class([ 'w-2.5 h-2.5 rounded-full' , 'bg-emerald-500'=> $stage === 'New',
                                'bg-blue-500' => $stage === 'Contacted',
                                'bg-amber-500' => $stage === 'Visited',
                                'bg-orange-500' => $stage === 'Quote Sent',
                                'bg-green-500' => $stage === 'Won',
                                'bg-red-500' => $stage === 'Lost',
                                ])></div>
                            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-widest">
                                {{ $stage }}</h3>
                        </div>
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">₹{{
                            number_format($data['value']) }}</p>
                    </div>
                    <span
                        class="bg-white dark:bg-slate-800 text-slate-500 px-2.5 py-1 rounded-full text-[10px] font-black shadow-sm border border-slate-100 dark:border-slate-700">
                        {{ $data['count'] }}
                    </span>
                </div>

                {{-- Stage Body (Cards) --}}
                <div class="p-3 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                    @foreach($data['leads'] as $lead)
                    <div draggable="true" @dragstart="dragStart({{ $lead->id }}, $event)"
                        @click="openLeadDetails({{ $lead->toJson() }})"
                        @class([ 'p-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border-2 cursor-pointer transition-all hover:shadow-md hover:-translate-y-0.5 group'
                        , 'border-slate-100 dark:border-slate-700'=> !$lead->needs_attention,
                        'border-amber-300 dark:border-amber-700' => $lead->needs_attention && $lead->days_inactive <
                            10, 'border-red-400 dark:border-red-700 animate-pulse'=> $lead->needs_attention &&
                            $lead->days_inactive >= 10,
                            ])>

                            <div class="flex justify-between items-start mb-2">
                                <h4
                                    class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">
                                    {{ $lead->name }}</h4>
                                @if($lead->needs_attention)
                                <span
                                    class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-[9px] font-black uppercase">⚠️
                                    {{ $lead->days_inactive }}D</span>
                                @endif
                            </div>

                            <div class="space-y-1.5">
                                <p class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $lead->location ?: 'No location' }}
                                </p>
                                <p class="text-sm font-black text-slate-900 dark:text-white">
                                    {{ $lead->formatted_budget }}
                                </p>
                            </div>

                            <div
                                class="mt-4 pt-3 border-t border-slate-50 dark:border-slate-700/50 flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    @if($lead->assignedTo)
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-[10px] font-bold text-indigo-600 border-2 border-white dark:border-slate-800"
                                        title="{{ $lead->assignedTo->name }}">
                                        {{ substr($lead->assignedTo->name, 0, 1) }}
                                    </div>
                                    @else
                                    <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-400 border-2 border-white dark:border-slate-800"
                                        title="Unassigned">
                                        ?
                                    </div>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium italic">{{
                                    $lead->updated_at->diffForHumans() }}</span>
                            </div>
                    </div>
                    @endforeach

                    @if($data['count'] === 0)
                    <div
                        class="h-24 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center text-center p-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Empty Stage</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </main>

    {{-- Lead Detail Slide-over (or Modal) --}}
    <div x-show="showDetails" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full max-w-md bg-white dark:bg-slate-900 shadow-2xl z-50 border-l border-slate-100 dark:border-slate-800 p-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Lead Intelligence
            </h2>
            <button @click="showDetails = false" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <template x-if="selectedLead">
            <div class="space-y-8">
                <div>
                    <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400" x-text="selectedLead.name">
                    </h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span
                            class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-black uppercase text-slate-500"
                            x-text="selectedLead.status"></span>
                        <span
                            class="px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 rounded text-[10px] font-black uppercase text-indigo-600"
                            x-text="selectedLead.formatted_budget"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a :href="'tel:' + selectedLead.phone"
                        class="flex items-center justify-center gap-2 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-xl text-xs font-black hover:bg-blue-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        CALL
                    </a>
                    <a :href="'https://wa.me/91' + (selectedLead.whatsapp || selectedLead.phone)" target="_blank"
                        class="flex items-center justify-center gap-2 py-3 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-xl text-xs font-black hover:bg-green-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                        </svg>
                        WHATSAPP
                    </a>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Location</label>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-text="selectedLead.location || 'Not Specified'"></span>
                        </p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Work
                            Description</label>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic"
                            x-text="selectedLead.work_description || 'No description provided.'"></p>
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Internal
                            Notes</label>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"
                            x-text="selectedLead.notes || 'No notes.'"></p>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Quick Actions</p>
                    <div class="flex flex-col gap-3">
                        <button @click="updateLeadStatus(selectedLead.id, getNextStatus(selectedLead.status))"
                            class="w-full py-4 bg-indigo-600 text-white rounded-xl text-xs font-black hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                            MOVE TO <span x-text="getNextStatus(selectedLead.status).toUpperCase()"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                        <a :href="'/leads'"
                            class="w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-black text-center hover:bg-slate-200 transition-all">EDIT
                            FULL DETAILS</a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Overlay --}}
    <div x-show="showDetails" @click="showDetails = false"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 transition-opacity"></div>
</div>

<script>
    function pipelineApp() {
        return {
            showDetails: false,
            selectedLead: null,

            dragStart(leadId, e) {
                e.dataTransfer.setData('lead_id', leadId);
                e.dataTransfer.effectAllowed = 'move';
            },

            async dropLead(targetStage, e) {
                const leadId = e.dataTransfer.getData('lead_id');
                if (leadId) {
                    await this.updateLeadStatus(leadId, targetStage);
                }
            },

            openLeadDetails(lead) {
                this.selectedLead = lead;
                this.showDetails = true;
            },

            async updateLeadStatus(id, status) {
                try {
                    const res = await fetch(`/leads/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status })
                    });
                    const data = await res.json();
                    if (data.success) {
                        // Force reload to update UI easily, or manually move if needed
                        window.location.reload();
                    }
                } catch (e) {
                    console.error('Failed to update status:', e);
                }
            },

            getNextStatus(currentStatus) {
                const stages = ['New', 'Contacted', 'Visited', 'Quote Sent', 'Won'];
                const idx = stages.indexOf(currentStatus);
                if (idx === -1 || idx === stages.length - 1) return currentStatus;
                return stages[idx + 1];
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
    }
</style>
@endsection