@extends('layouts.app')

@section('content')
<div x-data="leadApp()" x-cloak class="min-h-screen">
    <x-journey-header stage="Lead Generation & Selection" nextStep="Qualify leads and move them to Pipeline"
        progress="10" statusColor="blue" />

    {{-- Success Toast --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
        class="fixed top-6 right-6 z-50 bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-xl text-sm font-bold flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <header
        class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 px-6 py-5 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span x-text="viewMode === 'board' ? 'Sales Pipeline' : 'Lead Selection'"></span>
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-[52px]"
                    x-text="viewMode === 'board' ? 'Visualize and move deals through stages' : 'Manage and qualify incoming leads'">
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- View Toggle --}}
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                    <button @click="viewMode = 'list'"
                        :class="viewMode === 'list' ? 'bg-white dark:bg-slate-700 shadow text-brand-600' : 'text-slate-500'"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">List</button>
                    <button @click="viewMode = 'board'"
                        :class="viewMode === 'board' ? 'bg-white dark:bg-slate-700 shadow text-brand-600' : 'text-slate-500'"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Board</button>
                </div>
                <button @click="showCreateModal = true"
                    class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Lead
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8 space-y-6">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Leads</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-widest">New</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['new'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-blue-500 uppercase tracking-widest">In Progress</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['contacted'] +
                    $stats['visited'] + $stats['quote_sent'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-green-500 uppercase tracking-widest">Won</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['won'] }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 {{ $stats['needs_attention'] > 0 ? 'ring-2 ring-amber-400' : '' }}">
                <p class="text-[11px] font-bold text-amber-500 uppercase tracking-widest">⚠️ Needs Attention</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['needs_attention'] }}</p>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="searchQuery" placeholder="Search by name, phone, location..."
                    class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
            </div>
            <select x-model="filterStatus"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                <option value="all">All Status</option>
                <template x-for="s in statuses" :key="s">
                    <option :value="s" x-text="s"></option>
                </template>
            </select>
        </div>

        {{-- Main Content Area --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- LEFT: List or Board --}}
            <div class="xl:col-span-8">

                {{-- LIST VIEW --}}
                <div x-show="viewMode === 'list'"
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                    <template x-if="filteredLeads.length === 0">
                        <div class="p-12 text-center">
                            <div
                                class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">No Leads Found</h3>
                            <p class="text-sm text-slate-500 mt-2">Create your first lead to get started</p>
                        </div>
                    </template>

                    {{-- Table Header --}}
                    <template x-if="filteredLeads.length > 0">
                        <div>
                            <div
                                class="grid grid-cols-12 gap-4 px-6 py-3 bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                                <div class="col-span-3">Name</div>
                                <div class="col-span-2">Phone</div>
                                <div class="col-span-2">Location</div>
                                <div class="col-span-2">Budget</div>
                                <div class="col-span-2">Status</div>
                                <div class="col-span-1">Actions</div>
                            </div>
                            <template x-for="lead in filteredLeads" :key="lead.id">
                                <div @click="selectLead(lead)"
                                    class="grid grid-cols-12 gap-4 px-6 py-4 items-center cursor-pointer border-b border-slate-50 dark:border-slate-800/50 hover:bg-brand-50/50 dark:hover:bg-brand-900/10 transition-colors"
                                    :class="selectedLead?.id === lead.id ? 'bg-brand-50 dark:bg-brand-900/20 border-l-4 border-l-brand-500' : 'border-l-4 border-l-transparent'">
                                    <div class="col-span-3 flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm"
                                            :class="getStatusDotColor(lead.status)"
                                            x-text="lead.name.charAt(0).toUpperCase()"></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-900 dark:text-white">
                                                <span class="text-xs text-slate-400 font-normal mr-1"
                                                    x-text="lead.lead_number"></span>
                                                <span x-text="lead.name"></span>
                                            </p>
                                            <p class="text-[11px] text-slate-400" x-text="lead.email || ''"></p>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-sm text-slate-600 dark:text-slate-400"
                                        x-text="lead.phone || '—'"></div>
                                    <div class="col-span-2 text-sm text-slate-600 dark:text-slate-400"
                                        x-text="lead.location || '—'"></div>
                                    <div class="col-span-2 text-sm font-bold text-slate-900 dark:text-white"
                                        x-text="formatBudget(lead.budget)"></div>
                                    <div class="col-span-2">
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                            :class="getStatusBadge(lead.status)" x-text="lead.status"></span>
                                    </div>
                                    <div class="col-span-1 flex items-center gap-1" @click.stop>
                                        <a :href="'tel:' + lead.phone" x-show="lead.phone"
                                            class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center hover:bg-blue-100 transition"
                                            title="Call">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </a>
                                        <a :href="'https://wa.me/91' + (lead.whatsapp || lead.phone)"
                                            x-show="lead.phone || lead.whatsapp" target="_blank"
                                            class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-900/30 flex items-center justify-center hover:bg-green-100 transition"
                                            title="WhatsApp">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- BOARD VIEW --}}
                <div x-show="viewMode === 'board'" class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                    <template x-for="stage in boardStages" :key="stage">
                        <div class="flex-shrink-0 w-72 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 min-h-[500px]"
                            @dragover.prevent @drop="dropLead(stage, $event)">
                            <div
                                class="px-4 py-3 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full" :class="getStatusDotColor(stage)"></div>
                                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider"
                                        x-text="stage"></h3>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-slate-400 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full"
                                    x-text="getStageLeads(stage).length"></span>
                            </div>
                            <div class="p-3 space-y-3">
                                <template x-for="lead in getStageLeads(stage)" :key="lead.id">
                                    <div draggable="true" @dragstart="$event.dataTransfer.setData('lead_id', lead.id)"
                                        @click="selectLead(lead)"
                                        class="p-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-md border cursor-pointer transition-all"
                                        :class="{
                                            'border-amber-300 dark:border-amber-700': lead.days_inactive >= 5 && lead.days_inactive < 10 && lead.status !== 'Won' && lead.status !== 'Lost',
                                            'border-red-400 dark:border-red-700 animate-pulse': lead.days_inactive >= 10 && lead.status !== 'Won' && lead.status !== 'Lost',
                                            'border-slate-100 dark:border-slate-700': lead.days_inactive < 5 || lead.status === 'Won' || lead.status === 'Lost',
                                            'ring-2 ring-brand-500': selectedLead?.id === lead.id
                                        }">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white" x-text="lead.name">
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-1"
                                            x-text="lead.location || lead.phone || ''"></p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-xs font-bold text-brand-600"
                                                x-text="formatBudget(lead.budget)"></span>
                                            <span
                                                x-show="lead.days_inactive >= 5 && lead.status !== 'Won' && lead.status !== 'Lost'"
                                                class="text-[10px] font-bold text-amber-600"
                                                x-text="'⚠️ ' + lead.days_inactive + 'd'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- RIGHT: Detail Panel --}}
            <div class="xl:col-span-4">
                <template x-if="selectedLead">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden sticky top-28">
                        {{-- Lead Header --}}
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-bold text-brand-600 uppercase tracking-widest">Lead
                                    Profile</span>
                                <button @click="selectedLead = null"
                                    class="text-slate-400 hover:text-slate-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white" x-text="selectedLead.name">
                            </h2>
                            <div class="flex items-center gap-4 mt-2 text-sm text-slate-500">
                                <span x-show="selectedLead.phone" class="flex items-center gap-1">📞 <span
                                        x-text="selectedLead.phone"></span></span>
                                <span x-show="selectedLead.location" class="flex items-center gap-1">📍 <span
                                        x-text="selectedLead.location"></span></span>
                            </div>
                            <div class="flex items-center gap-2 mt-3" x-show="selectedLead.budget">
                                <span class="text-lg font-bold text-brand-600"
                                    x-text="formatBudget(selectedLead.budget)"></span>
                                <span class="text-[10px] text-slate-400 uppercase">budget</span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="p-4 grid grid-cols-2 gap-2 border-b border-slate-100 dark:border-slate-800">
                            <a :href="'tel:' + selectedLead.phone"
                                class="flex items-center justify-center gap-2 py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Call
                            </a>
                            <a :href="'https://wa.me/91' + (selectedLead.whatsapp || selectedLead.phone)"
                                target="_blank"
                                class="flex items-center justify-center gap-2 py-2.5 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-xl text-xs font-bold hover:bg-green-100 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                </svg>
                                WhatsApp
                            </a>
                        </div>

                        {{-- Status Move --}}
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                            <label
                                class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Move
                                Status</label>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="s in statuses" :key="s">
                                    <button @click="updateLeadStatus(selectedLead.id, s)"
                                        class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all"
                                        :class="selectedLead.status === s ? getStatusBadge(s) + ' ring-2 ring-offset-1' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200'"
                                        x-text="s"></button>
                                </template>
                            </div>
                        </div>

                        {{-- Add Note --}}
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Add
                                Note</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="newNote" placeholder="Type a note..."
                                    class="flex-1 bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500 transition"
                                    @keydown.enter="addNote(selectedLead.id)">
                                <button @click="addNote(selectedLead.id)"
                                    class="bg-brand-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-brand-700 transition">Add</button>
                            </div>
                            <div class="mt-3 space-y-2 max-h-32 overflow-y-auto"
                                x-show="selectedLead.metadata?.note_history?.length > 0">
                                <template
                                    x-for="(note, i) in (selectedLead.metadata?.note_history || []).slice().reverse()"
                                    :key="i">
                                    <div class="p-2.5 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <p class="text-xs text-slate-700 dark:text-slate-300" x-text="note.text"></p>
                                        <p class="text-[10px] text-slate-400 mt-1" x-text="note.by + ' • ' + note.at">
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Follow-up --}}
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">📅
                                Follow-up</label>
                            <div x-show="selectedLead.next_follow_up_at"
                                class="mb-2 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-xs font-bold text-amber-700 dark:text-amber-400">
                                Next: <span
                                    x-text="selectedLead.next_follow_up_at ? new Date(selectedLead.next_follow_up_at).toLocaleString('en-IN', {dateStyle: 'medium', timeStyle: 'short'}) : ''"></span>
                            </div>
                            <div class="flex gap-2">
                                <input type="datetime-local" x-model="followUpDate"
                                    class="flex-1 bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-2.5 text-sm focus:ring-brand-500">
                                <button @click="setFollowUp(selectedLead.id)"
                                    class="bg-amber-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-amber-600 transition">Set</button>
                            </div>
                        </div>

                        {{-- Requirements Snapshot --}}
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30"
                            x-show="selectedLead.metadata?.requirements">
                            <div class="flex items-center justify-between mb-3">
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Requirements
                                    Snapshot</label>
                                <span
                                    class="px-2 py-0.5 bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase rounded-md border border-emerald-500/20">Dossier
                                    Active</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div
                                    class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Property
                                    </p>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mt-1"
                                        x-text="selectedLead.metadata?.requirements?.property_type + ' (' + (selectedLead.metadata?.requirements?.area || '—') + ' sqft)'">
                                    </p>
                                </div>
                                <div
                                    class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Budget
                                        Range</p>
                                    <p class="text-xs font-bold text-brand-600 mt-1"
                                        x-text="selectedLead.metadata?.requirements?.budget_range || 'Not specified'">
                                    </p>
                                </div>
                                <div
                                    class="col-span-2 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Key
                                        Priorities</p>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        <template
                                            x-for="prio in (selectedLead.metadata?.requirements?.priorities || [])">
                                            <span
                                                class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-900 text-slate-500 text-[9px] font-bold rounded"
                                                x-text="prio"></span>
                                        </template>
                                        <span class="text-[9px] text-slate-400 italic"
                                            x-show="!selectedLead.metadata?.requirements?.priorities?.length">None
                                            listed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Next Step CTA --}}
                        <div class="p-4">
                            <div x-show="selectedLead.status !== 'Won' && selectedLead.status !== 'Lost'"
                                class="p-4 bg-brand-50 dark:bg-brand-900/20 rounded-xl border border-brand-200 dark:border-brand-800">
                                <p class="text-[11px] font-bold text-brand-500 uppercase tracking-widest mb-1">▸ Next
                                    Step</p>
                                <p class="text-sm font-bold text-brand-700 dark:text-brand-400"
                                    x-text="getNextStep(selectedLead.status)"></p>
                                <button @click="moveToNextStatus(selectedLead)"
                                    class="mt-3 w-full bg-brand-600 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-brand-700 transition"
                                    x-text="'Move to ' + getNextStatus(selectedLead.status) + ' →'"></button>
                            </div>

                            {{-- Requirement Form Trigger --}}
                            <div class="mt-3">
                                <button @click="openRequirementsModal(selectedLead)"
                                    class="w-full flex items-center justify-center gap-2 py-3 bg-slate-900 dark:bg-brand-500/20 text-white dark:text-brand-400 rounded-xl text-sm font-bold border border-transparent hover:bg-black dark:hover:bg-brand-500/30 transition shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Requirement Form
                                </button>
                                <p
                                    class="text-[10px] text-slate-400 text-center mt-2 font-medium uppercase tracking-widest">
                                    Standard visitor dossier</p>
                            </div>

                            <div x-show="selectedLead.status === 'Won'"
                                class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl text-center">
                                <p class="text-sm font-bold text-green-700 dark:text-green-400">✅ Lead Won!</p>
                            </div>
                        </div>

                        {{-- Edit/Delete --}}
                        <div class="px-4 pb-4 flex gap-2">
                            <button @click="openEditModal(selectedLead)"
                                class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Edit
                                Details</button>
                            <button @click="deleteLead(selectedLead.id)"
                                class="py-2.5 px-4 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100 transition">Delete</button>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <template x-if="!selectedLead">
                    <div
                        class="h-64 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center text-center p-8 sticky top-28">
                        <div
                            class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Select a lead</p>
                        <p class="text-xs text-slate-400 mt-1">Click on any lead to view details & actions</p>
                    </div>
                </template>
            </div>
        </div>
    </main>

    {{-- Create / Edit Modal --}}
    <div x-show="showCreateModal || showEditModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
            @click="showCreateModal = false; showEditModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden"
            @click.stop>
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white"
                    x-text="showEditModal ? 'Edit Lead' : 'New Lead'"></h3>
            </div>
            <form :action="showEditModal ? '/leads/' + editForm.id : '{{ route('leads.store') }}'" method="POST"
                class="p-6 space-y-4">
                @csrf
                <template x-if="showEditModal"><input type="hidden" name="_method" value="PUT"></template>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Name
                            *</label>
                        <input type="text" name="name" x-model="editForm.name" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Phone</label>
                        <input type="text" name="phone" x-model="editForm.phone"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">WhatsApp</label>
                        <input type="text" name="whatsapp" x-model="editForm.whatsapp"
                            placeholder="If different from phone"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Email</label>
                        <input type="email" name="email" x-model="editForm.email"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Location</label>
                        <input type="text" name="location" x-model="editForm.location" placeholder="e.g. Noida Sec 62"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Budget
                            (₹)</label>
                        <input type="number" name="budget" x-model="editForm.budget" placeholder="e.g. 1800000"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                    </div>
                    <div>
                        <label
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Source</label>
                        <select name="source" x-model="editForm.source"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500">
                            <option value="">Select...</option>
                            <option value="Referral">Referral</option>
                            <option value="Website">Website</option>
                            <option value="Instagram">Instagram</option>
                            <option value="JustDial">JustDial</option>
                            <option value="Walk-in">Walk-in</option>
                            <option value="Cold Call">Cold Call</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Work
                            Description</label>
                        <textarea name="work_description" x-model="editForm.work_description" rows="2"
                            placeholder="What does the client need?"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-brand-500"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showCreateModal = false; showEditModal = false"
                        class="flex-1 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 transition">Cancel</button>
                    <button type="submit"
                        class="flex-[2] bg-brand-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-brand-700 shadow-lg transition"
                        x-text="showEditModal ? 'Update Lead' : 'Create Lead'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Requirements Modal --}}
    <div x-show="showRequirementsModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md" @click="showRequirementsModal = false"></div>
        <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
            @click.stop>
            {{-- Modal Header --}}
            <div
                class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                <div>
                    <h3
                        class="text-2xl font-black text-slate-900 dark:text-white font-display uppercase tracking-tight">
                        Requirement Dossier</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1"
                        x-text="selectedLead?.name + ' • ' + selectedLead?.lead_number"></p>
                </div>
                <button @click="showRequirementsModal = false"
                    class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-slate-600 transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-10">
                <form id="requirementsForm" class="space-y-12">
                    <!-- Section: Property Info -->
                    <div class="space-y-6">
                        <h4
                            class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-8 h-px bg-brand-500"></span>
                            Property Identity
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Project
                                    Type</label>
                                <select x-model="reqForm.project_type"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-brand-500">
                                    <option value="Interior Design">Interior Design</option>
                                    <option value="Renovation">Renovation</option>
                                    <option value="Consultation">Consultation Only</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Property
                                    Type</label>
                                <select x-model="reqForm.property_type"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-brand-500">
                                    <option value="Flat">Individual Flat (Apartment)</option>
                                    <option value="Villa">Villa / Penthouse</option>
                                    <option value="Office">Commercial Office</option>
                                    <option value="Shop">Retail Shop</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Carpet
                                    Area (Sq.Ft.)</label>
                                <input type="text" x-model="reqForm.area" placeholder="e.g. 1250"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-brand-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Budget & Expectations -->
                    <div class="space-y-6">
                        <h4
                            class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-8 h-px bg-brand-500"></span>
                            Budget & Expectations
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Approximate
                                    Budget Range</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <template
                                        x-for="range in ['below 10 Lakhs', '10-20 Lakhs', '20-40 Lakhs', '40-60 Lakhs', '60 Lakhs+']">
                                        <button type="button" @click="reqForm.budget_range = range"
                                            :class="reqForm.budget_range === range ? 'bg-brand-500 text-white' : 'bg-slate-50 dark:bg-slate-800 text-slate-500'"
                                            class="px-3 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                                            x-text="range"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Budget
                                    Flexibility</label>
                                <div class="flex gap-2">
                                    <template x-for="flex in ['Fixed', 'Slightly Flexible', 'Fully Flexible']">
                                        <button type="button" @click="reqForm.budget_flexibility = flex"
                                            :class="reqForm.budget_flexibility === flex ? 'bg-indigo-500 text-white' : 'bg-slate-50 dark:bg-slate-800 text-slate-500'"
                                            class="flex-1 px-3 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                                            x-text="flex"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Top
                                    Priorities (Multiple)</label>
                                <div class="flex flex-wrap gap-2">
                                    <template
                                        x-for="prio in ['Design & Aesthetics', 'Durability / Quality', 'Cost Optimization', 'Fast Completion']">
                                        <button type="button" @click="toggleReqArray('priorities', prio)"
                                            :class="reqForm.priorities.includes(prio) ? 'bg-emerald-500 text-white' : 'bg-slate-50 dark:bg-slate-800 text-slate-500'"
                                            class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                            x-text="prio"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Final
                                    Decision Maker</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="dm in ['Self', 'Spouse', 'Family', 'Company Management']">
                                        <button type="button" @click="reqForm.decision_maker = dm"
                                            :class="reqForm.decision_maker === dm ? 'bg-slate-900 dark:bg-white dark:text-slate-900 text-white' : 'bg-slate-50 dark:bg-slate-800 text-slate-500'"
                                            class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                            x-text="dm"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Room Requirements -->
                    <div class="space-y-6">
                        <h4
                            class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-8 h-px bg-brand-500"></span>
                            Room Intel
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div
                                class="p-6 bg-slate-50 dark:bg-slate-800/30 rounded-3xl border border-slate-100 dark:border-slate-800">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-4">Living
                                    Room Needs</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <template
                                        x-for="item in ['TV Unit', 'False Ceiling', 'Wall Panel / Texture', 'Decorative Lighting', 'Sofa Set', 'Dining Unit']">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" :value="item" x-model="reqForm.living_room_items"
                                                class="w-5 h-5 rounded-lg border-slate-200 dark:border-slate-700 text-brand-600 focus:ring-brand-500">
                                            <span
                                                class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-brand-500 transition-colors"
                                                x-text="item"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                            <div
                                class="p-6 bg-slate-50 dark:bg-slate-800/30 rounded-3xl border border-slate-100 dark:border-slate-800">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-4">Kitchen
                                    Details</label>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-2">
                                            Structure</p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="reqForm.kitchen_type = 'Modular'"
                                                :class="reqForm.kitchen_type === 'Modular' ? 'bg-brand-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-500 shadow-sm'"
                                                class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Modular</button>
                                            <button type="button" @click="reqForm.kitchen_type = 'Semi-Modular'"
                                                :class="reqForm.kitchen_type === 'Semi-Modular' ? 'bg-brand-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-500 shadow-sm'"
                                                class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Semi-Modular</button>
                                        </div>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-2 text-right">
                                            Finish Preference</p>
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <template x-for="f in ['Laminate', 'Acrylic', 'PU Finish', 'Veneer']">
                                                <button type="button" @click="toggleReqArray('kitchen_finish', f)"
                                                    :class="reqForm.kitchen_finish.includes(f) ? 'bg-indigo-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-500 shadow-sm'"
                                                    class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest"
                                                    x-text="f"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Bed & Bathroom -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h4
                                class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-brand-500"></span>
                                Sleeping Quarters
                            </h4>
                            <div class="space-y-4">
                                <div
                                    class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Number
                                        of Bedrooms</span>
                                    <input type="number" x-model="reqForm.bedrooms"
                                        class="w-20 bg-slate-100 dark:bg-slate-900 border-transparent rounded-xl px-3 py-1.5 text-center font-black">
                                </div>
                                <div
                                    class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                    <span
                                        class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Wardrobe
                                        Type</span>
                                    <select x-model="reqForm.wardrobe_type"
                                        class="bg-slate-100 dark:bg-slate-900 border-transparent rounded-xl px-3 py-1.5 text-[10px] font-black uppercase tracking-widest">
                                        <option value="Sliding">Sliding Door</option>
                                        <option value="Openable">Openable Door</option>
                                        <option value="Walk-in">Walk-in Closet</option>
                                    </select>
                                </div>
                                <div
                                    class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Bed
                                        with Storage?</span>
                                    <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                                        <button type="button" @click="reqForm.bed_storage = 'Yes'"
                                            :class="reqForm.bed_storage === 'Yes' ? 'bg-white dark:bg-slate-700 shadow text-brand-600' : 'text-slate-400 text-xs'"
                                            class="px-4 py-1.5 rounded-lg font-black uppercase text-[10px]">Yes</button>
                                        <button type="button" @click="reqForm.bed_storage = 'No'"
                                            :class="reqForm.bed_storage === 'No' ? 'bg-white dark:bg-slate-700 shadow text-brand-600' : 'text-slate-400 text-xs'"
                                            class="px-4 py-1.5 rounded-lg font-black uppercase text-[10px]">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4
                                class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-brand-500"></span>
                                Sanitation Space
                            </h4>
                            <div class="grid grid-cols-1 gap-3">
                                <template
                                    x-for="item in ['Vanity Unit', 'Mirror Cabinet', 'Shower Partition', 'Full Tiles Replacement', 'CP Fittings Replacement']">
                                    <div @click="toggleReqArray('bathroom_needs', item)"
                                        class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 dark:border-slate-800 cursor-pointer transition-all"
                                        :class="reqForm.bathroom_needs.includes(item) ? 'bg-brand-500 text-white border-brand-500 translate-x-1' : 'bg-white dark:bg-slate-800 text-slate-500 hover:bg-slate-50'">
                                        <span class="text-[11px] font-black uppercase tracking-widest"
                                            x-text="item"></span>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                            :class="reqForm.bathroom_needs.includes(item) ? 'border-white' : 'border-slate-200'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-white"
                                                x-show="reqForm.bathroom_needs.includes(item)"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Products & Timeline -->
                    <div class="space-y-8 pb-10">
                        <h4
                            class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-8 h-px bg-brand-500"></span>
                            Final Logistics
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <label
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Products
                                    to Purchase</label>
                                <div class="flex flex-wrap gap-2">
                                    <template
                                        x-for="prod in ['Lights', 'Furniture', 'Modular Kitchen Hardware', 'Appliances', 'Curtains', 'Wallpaper']">
                                        <button type="button" @click="toggleReqArray('products', prod)"
                                            :class="reqForm.products.includes(prod) ? 'bg-slate-900 dark:bg-white dark:text-slate-900 text-white' : 'bg-slate-50 dark:bg-slate-800 text-slate-500'"
                                            class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                            x-text="prod"></button>
                                    </template>
                                </div>
                                <div class="pt-4">
                                    <label
                                        class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Preferred
                                        Hardware Brand</label>
                                    <div class="flex gap-2">
                                        <template x-for="brand in ['Hettich', 'Hafele', 'Ebco', 'No Preference']">
                                            <button type="button" @click="reqForm.preferred_brand = brand"
                                                :class="reqForm.preferred_brand === brand ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                                                class="px-3 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest transition-all"
                                                x-text="brand"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Expected
                                            Start</label>
                                        <input type="date" x-model="reqForm.start_date"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-brand-500">
                                    </div>
                                    <div>
                                        <label
                                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Completion
                                            Timeline</label>
                                        <select x-model="reqForm.timeline"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-brand-500">
                                            <option value="1-2 Months">1-2 Months</option>
                                            <option value="3-4 Months">3-4 Months</option>
                                            <option value="6+ Months">6+ Months</option>
                                        </select>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-between p-4 bg-rose-50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-900/30">
                                    <span class="text-[11px] font-black text-rose-600 uppercase tracking-widest">Urgency
                                        Level</span>
                                    <div class="flex bg-white/50 dark:bg-black/20 p-1 rounded-xl">
                                        <button type="button" @click="reqForm.urgency = 'Normal'"
                                            :class="reqForm.urgency === 'Normal' ? 'bg-white dark:bg-slate-700 shadow text-slate-600' : 'text-slate-400'"
                                            class="px-4 py-1.5 rounded-lg font-black uppercase text-[10px]">Normal</button>
                                        <button type="button" @click="reqForm.urgency = 'Fast Track'"
                                            :class="reqForm.urgency === 'Fast Track' ? 'bg-rose-500 text-white shadow-lg' : 'text-rose-400'"
                                            class="px-4 py-1.5 rounded-lg font-black uppercase text-[10px]">Fast
                                            Track</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6">
                            <label
                                class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Internal
                                Disposition Notes</label>
                            <textarea x-model="reqForm.notes" rows="3"
                                placeholder="Any specific requirements or technical observations from the site visit..."
                                class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 rounded-[2rem] px-6 py-5 text-sm font-bold focus:ring-brand-500 shadow-inner"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div
                class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-end gap-3">
                <button @click="showRequirementsModal = false"
                    class="px-8 py-4 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition">Discard</button>
                <button @click="saveRequirements()"
                    class="px-12 py-4 bg-brand-600 text-white rounded-2xl text-[12px] font-black uppercase tracking-[0.2em] hover:bg-brand-700 shadow-2xl shadow-brand-500/30 transition-all transform active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Commit Dossier
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation --}}
    <div x-show="showDeleteConfirm" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteConfirm = false"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl p-8 max-w-sm shadow-2xl text-center" @click.stop>
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Delete Lead?</h3>
            <p class="text-sm text-slate-500 mt-2">This action cannot be undone.</p>
            <form :action="'/leads/' + deleteLeadId" method="POST" class="flex gap-3 mt-6">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteConfirm = false"
                    class="flex-1 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 transition">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    window.allLeads = @json($leads);

    function leadApp() {
        return {
            leads: window.allLeads || [],
            searchQuery: '',
            filterStatus: 'all',
            viewMode: new URLSearchParams(window.location.search).get('view') === 'board' ? 'board' : 'list',
            selectedLead: null,
            showCreateModal: false,
            showEditModal: false,
            showDeleteConfirm: false,
            deleteLeadId: null,
            newNote: '',
            followUpDate: '',
            editForm: { id: '', name: '', phone: '', whatsapp: '', email: '', location: '', budget: '', source: '', work_description: '' },
            statuses: @json(\App\Models\Lead:: STATUSES),
            boardStages: ['New', 'Contacted', 'Visited', 'Quote Sent'],

            get filteredLeads() {
                return this.leads.filter(l => {
                    const matchSearch = !this.searchQuery || l.name?.toLowerCase().includes(this.searchQuery.toLowerCase()) || l.phone?.includes(this.searchQuery) || l.location?.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchStatus = this.filterStatus === 'all' || l.status === this.filterStatus;
                    return matchSearch && matchStatus;
                });
            },

            selectLead(lead) { this.selectedLead = lead; },

            getStageLeads(stage) {
                return this.leads.filter(l => l.status === stage);
            },

            formatBudget(b) {
                if (!b) return '—';
                b = parseFloat(b);
                if (b >= 10000000) return '₹' + (b / 10000000).toFixed(1) + ' Cr';
                if (b >= 100000) return '₹' + (b / 100000).toFixed(1) + 'L';
                if (b >= 1000) return '₹' + (b / 1000).toFixed(1) + 'K';
                return '₹' + b.toLocaleString('en-IN');
            },

            getStatusBadge(s) {
                const m = { 'New': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Contacted': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Visited': 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Quote Sent': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400', 'Won': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', 'Lost': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' };
                return m[s] || 'bg-slate-100 text-slate-600';
            },

            getStatusDotColor(s) {
                const m = { 'New': 'bg-emerald-500', 'Contacted': 'bg-blue-500', 'Visited': 'bg-amber-500', 'Quote Sent': 'bg-orange-500', 'Won': 'bg-green-500', 'Lost': 'bg-red-500' };
                return m[s] || 'bg-slate-400';
            },

            getNextStep(s) {
                const m = { 'New': 'Contact the lead via Call or WhatsApp', 'Contacted': 'Schedule a site visit', 'Visited': 'Create and send a quotation', 'Quote Sent': 'Follow up for approval' };
                return m[s] || '';
            },

            getNextStatus(s) {
                const m = { 'New': 'Contacted', 'Contacted': 'Visited', 'Visited': 'Quote Sent', 'Quote Sent': 'Won' };
                return m[s] || s;
            },

            async updateLeadStatus(id, status) {
                try {
                    const res = await fetch(`/leads/${id}/status`, {
                        method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ status })
                    });
                    const data = await res.json();
                    if (data.success) {
                        const idx = this.leads.findIndex(l => l.id === id);
                        if (idx > -1) { this.leads[idx] = data.lead; this.selectedLead = data.lead; }
                    }
                } catch (e) { console.error(e); }
            },

            moveToNextStatus(lead) { this.updateLeadStatus(lead.id, this.getNextStatus(lead.status)); },

            async addNote(id) {
                if (!this.newNote.trim()) return;
                try {
                    const res = await fetch(`/leads/${id}/note`, {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ note: this.newNote })
                    });
                    const data = await res.json();
                    if (data.success) {
                        if (!this.selectedLead.metadata) this.selectedLead.metadata = {};
                        this.selectedLead.metadata.note_history = data.note_history;
                        this.newNote = '';
                    }
                } catch (e) { console.error(e); }
            },

            async setFollowUp(id) {
                if (!this.followUpDate) return;
                try {
                    const res = await fetch(`/leads/${id}/follow-up`, {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ next_follow_up_at: this.followUpDate })
                    });
                    const data = await res.json();
                    if (data.success) { this.selectedLead.next_follow_up_at = this.followUpDate; this.followUpDate = ''; }
                } catch (e) { console.error(e); }
            },

            openEditModal(lead) {
                this.editForm = { id: lead.id, name: lead.name, phone: lead.phone || '', whatsapp: lead.whatsapp || '', email: lead.email || '', location: lead.location || '', budget: lead.budget || '', source: lead.source || '', work_description: lead.work_description || '' };
                this.showEditModal = true;
            },

            deleteLead(id) { this.deleteLeadId = id; this.showDeleteConfirm = true; },

            dropLead(targetStage, e) {
                const id = parseInt(e.dataTransfer.getData('lead_id'));
                const lead = this.leads.find(l => l.id === id);
                if (lead && lead.status !== targetStage) { this.updateLeadStatus(id, targetStage); }
            },

            // Requirement Form Logic
            showRequirementsModal: false,
            reqForm: {
                project_type: 'Interior Design',
                property_type: 'Flat',
                area: '',
                budget_range: '',
                budget_flexibility: 'Slightly Flexible',
                priorities: [],
                decision_maker: 'Self',
                living_room_items: [],
                kitchen_type: 'Modular',
                kitchen_finish: [],
                bedrooms: 3,
                wardrobe_type: 'Sliding',
                bed_storage: 'Yes',
                bathroom_needs: [],
                products: [],
                preferred_brand: 'No Preference',
                start_date: '',
                timeline: '3-4 Months',
                urgency: 'Normal',
                notes: ''
            },

            openRequirementsModal(lead) {
                this.selectedLead = lead;
                // Pre-fill if exists
                if (lead.metadata && lead.metadata.requirements) {
                    this.reqForm = { ...this.reqForm, ...lead.metadata.requirements };
                } else {
                    // Reset to defaults
                    this.reqForm = {
                        project_type: 'Interior Design',
                        property_type: 'Flat',
                        area: '',
                        budget_range: '',
                        budget_flexibility: 'Slightly Flexible',
                        priorities: [],
                        decision_maker: 'Self',
                        living_room_items: [],
                        kitchen_type: 'Modular',
                        kitchen_finish: [],
                        bedrooms: 3,
                        wardrobe_type: 'Sliding',
                        bed_storage: 'Yes',
                        bathroom_needs: [],
                        products: [],
                        preferred_brand: 'No Preference',
                        start_date: '',
                        timeline: '3-4 Months',
                        urgency: 'Normal',
                        notes: ''
                    };
                }
                this.showRequirementsModal = true;
            },

            toggleReqArray(field, value) {
                if (this.reqForm[field].includes(value)) {
                    this.reqForm[field] = this.reqForm[field].filter(v => v !== value);
                } else {
                    this.reqForm[field].push(value);
                }
            },

            async saveRequirements() {
                try {
                    const res = await fetch(`/leads/${this.selectedLead.id}/requirements`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ requirements: this.reqForm })
                    });
                    const data = await res.json();
                    if (data.success) {
                        const idx = this.leads.findIndex(l => l.id === this.selectedLead.id);
                        if (idx > -1) {
                            this.leads[idx] = data.lead;
                            this.selectedLead = data.lead;
                        }
                        this.showRequirementsModal = false;
                        // Flash success via toast
                        window.dispatchEvent(new CustomEvent('notify', { detail: 'Requirements Dossier Saved' }));
                    }
                } catch (e) {
                    console.error(e);
                    alert('Failed to save requirements. Please check your connection.');
                }
            }
        };
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>
@endsection