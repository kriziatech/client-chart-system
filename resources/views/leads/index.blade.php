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