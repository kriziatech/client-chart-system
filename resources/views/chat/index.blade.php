@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-theme(spacing.20))] flex gap-0 -m-8 overflow-hidden bg-slate-50 dark:bg-dark-bg"
    x-data="teamChat">

    {{-- ═══════ LEFT SIDEBAR: SPACES ═══════ --}}
    <div
        class="w-80 flex flex-col border-r border-slate-200 dark:border-dark-border bg-white dark:bg-dark-surface z-20">
        <div class="p-6 border-b border-slate-100 dark:border-dark-border">
            <h1
                class="text-xl font-bold text-slate-900 dark:text-white font-display tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                    </path>
                </svg>
                Collaboration Hub
            </h1>
        </div>

        <div class="flex-grow overflow-y-auto custom-scrollbar p-3 space-y-1">
            {{-- General Space --}}
            <button @click="switchChannel(null, 'General Announcement')"
                :class="activeChannel === null ? 'bg-brand-50 dark:bg-brand-500/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                class="w-full text-left p-4 rounded-2xl transition-all group relative">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-black bg-brand-500 text-white shadow-lg shadow-brand-500/20">
                        #</div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span class="font-bold text-sm text-slate-900 dark:text-white"
                                :class="activeChannel === null ? 'text-brand-600 dark:text-brand-400' : ''">General</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Active</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-dark-muted truncate font-medium">Global team
                            announcements and updates</p>
                    </div>
                </div>
            </button>

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[1.5px]">Project Spaces</p>
            </div>

            {{-- Project Spaces --}}
            @foreach($projects as $project)
            <button
                @click="switchChannel({{ $project->id }}, '{{ addslashes($project->first_name . ' ' . $project->last_name) }}')"
                :class="activeChannel === {{ $project->id }} ? 'bg-brand-50 dark:bg-brand-500/10 border-brand-100 dark:border-brand-500/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50 border-transparent'"
                class="w-full text-left p-4 rounded-2xl transition-all border group relative">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-black"
                        :class="activeChannel === {{ $project->id }} ? 'bg-white dark:bg-slate-800 text-brand-500 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 transition-all'">
                        {{ substr($project->first_name, 0, 1) }}
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span
                                class="font-bold text-sm text-slate-900 dark:text-white truncate pr-2 group-hover:text-brand-600 transition-colors">{{
                                $project->first_name }} {{ $project->last_name }}</span>
                            <span
                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">{{
                                $project->file_number }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-dark-muted truncate font-medium">Direct
                            collaboration for this journey</p>
                    </div>
                </div>
                {{-- Mock Unread Dot --}}
                @if($loop->first)
                <div class="absolute top-4 right-4 w-2 h-2 bg-brand-500 rounded-full"></div>
                @endif
            </button>
            @endforeach
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-dark-border">
            <div class="bg-slate-50 dark:bg-dark-bg p-4 rounded-2xl flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-slate-900 dark:bg-brand-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="flex-grow min-w-0">
                    <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}
                    </div>
                    <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ Auth::user()->role ?
                        Auth::user()->role->name : 'Team' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════ MAIN CHAT AREA ═══════ --}}
    <div class="flex-grow flex flex-col relative bg-slate-50 dark:bg-dark-bg min-w-0">

        {{-- Chat Header --}}
        <header
            class="h-16 bg-white/80 dark:bg-dark-surface/80 backdrop-blur-md border-b border-slate-200 dark:border-dark-border flex items-center justify-between px-8 z-10">
            <div class="flex items-center gap-4">
                <div
                    class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white tracking-tight" x-text="channelName"></h2>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active
                            Collaboration</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="p-2.5 text-slate-400 hover:text-brand-500 bg-slate-50 dark:bg-slate-800 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <button @click="rightPanelOpen = !rightPanelOpen"
                    class="p-2.5 text-slate-400 hover:text-brand-500 bg-slate-50 dark:bg-slate-800 rounded-xl transition-all"
                    :class="rightPanelOpen ? 'text-brand-500 ring-2 ring-brand-100 dark:ring-brand-900/40' : ''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Messages Scroll Area --}}
        <div class="flex-grow overflow-y-auto p-8 space-y-2 relative custom-scrollbar" id="chatContainer"
            @scroll="checkScroll">

            <template x-for="(group, groupIndex) in groupedMessages" :key="groupIndex">
                <div class="space-y-1 py-1">
                    {{-- Group Header (Avatar/Name) --}}
                    <div class="flex items-center gap-4 mb-2">
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 dark:from-brand-500 dark:to-brand-700 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-slate-200 dark:shadow-none translate-y-2">
                            <span x-text="group.user.name.charAt(0)"></span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="font-bold text-sm text-slate-900 dark:text-white"
                                x-text="group.user.name"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                                x-text="formatDate(group.timestamp)"></span>
                        </div>
                    </div>

                    {{-- Grouped Message Cards --}}
                    <div class="ml-14 space-y-2">
                        <template x-for="msg in group.messages" :key="msg.id">
                            <div class="group relative max-w-[85%]">
                                <div
                                    class="bg-white dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm hover:shadow-md transition-all relative">

                                    {{-- Status Badges --}}
                                    <div class="absolute top-3 right-4 flex gap-2">
                                        <template x-if="msg.is_pinned">
                                            <span
                                                class="p-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 rounded-lg">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                                                </svg>
                                            </span>
                                        </template>
                                        <template x-if="msg.is_decision">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-100 dark:border-emerald-800">Decision</span>
                                        </template>
                                    </div>

                                    <div class="text-[13.5px] text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-wrap"
                                        x-text="msg.message"></div>

                                    {{-- Task Link --}}
                                    <template x-if="msg.linked_task">
                                        <div
                                            class="mt-4 p-3 bg-slate-50 dark:bg-dark-bg rounded-xl border border-slate-200 dark:border-dark-border flex items-center justify-between group/task cursor-pointer hover:border-brand-300 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 flex items-center justify-center text-brand-500 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div
                                                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                                        Linked Work Unit</div>
                                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-200"
                                                        x-text="msg.linked_task.description"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest"
                                                :class="{
                                                    'bg-amber-100 text-amber-700': msg.linked_task.status === 'Pending',
                                                    'bg-brand-100 text-brand-700': msg.linked_task.status === 'In Progress',
                                                    'bg-emerald-100 text-emerald-700': msg.linked_task.status === 'Completed'
                                                }" x-text="msg.linked_task.status"></span>
                                        </div>
                                    </template>

                                    {{-- Attachment --}}
                                    <template x-if="msg.attachment">
                                        <div class="mt-4 flex gap-4 overflow-x-auto pb-2 custom-scrollbar">
                                            <div
                                                class="p-3 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-slate-200 dark:border-dark-border flex items-center gap-4 min-w-[280px] group/file shadow-sm cursor-pointer hover:bg-white transition-all">
                                                <div
                                                    class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center text-brand-500 shadow-sm group-hover/file:scale-105 transition-transform">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 pr-4">
                                                    <div class="text-[13px] font-bold text-slate-900 dark:text-white truncate"
                                                        x-text="msg.metadata?.filename || msg.attachment.split('/').pop()">
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"
                                                        x-text="formatBytes(msg.metadata?.size || 0)"></div>
                                                </div>
                                                <a :href="'/storage/' + msg.attachment" target="_blank"
                                                    class="ml-auto p-2 text-slate-400 hover:text-brand-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Thread Button --}}
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <button @click="openThread(msg)"
                                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group/thread">
                                                <div class="flex -space-x-2">
                                                    <div
                                                        class="w-5 h-5 rounded-full bg-slate-200 border-2 border-white dark:border-dark-surface">
                                                    </div>
                                                    <div
                                                        class="w-5 h-5 rounded-full bg-slate-300 border-2 border-white dark:border-dark-surface">
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-[11px] font-black text-slate-400 group-hover/thread:text-brand-600 uppercase tracking-widest"
                                                    x-text="msg.replies_count > 0 ? msg.replies_count + ' Replies' : 'Reply'"></span>
                                            </button>

                                            {{-- Reactions --}}
                                            <div class="flex items-center gap-1.5">
                                                <template x-for="(users, emoji) in msg.reactions" :key="emoji">
                                                    <button @click="react(msg, emoji)"
                                                        class="px-2 py-1 bg-slate-50 dark:bg-brand-500/10 rounded-lg border border-slate-100 dark:border-brand-500/20 flex items-center gap-1.5 hover:ring-2 hover:ring-brand-100 transition-all">
                                                        <span class="text-xs" x-text="emoji"></span>
                                                        <span
                                                            class="text-[10px] font-bold text-slate-500 dark:text-brand-400"
                                                            x-text="users.length"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- Actions bar (on hover) --}}
                                        <div
                                            class="opacity-0 group-hover:opacity-100 transition-all flex items-center gap-1 bg-white dark:bg-slate-800 p-1 rounded-xl shadow-premium border border-slate-100 dark:border-dark-border">
                                            <button @click="react(msg, '✅')" title="Done"
                                                class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg></button>
                                            <button @click="togglePin(msg)" :title="msg.is_pinned ? 'Unpin' : 'Pin'"
                                                :class="msg.is_pinned ? 'text-amber-500 bg-amber-50 dark:bg-amber-900/30' : 'text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg'"
                                                class="p-2 transition-all"><svg class="w-4 h-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M5 5a2 2 0 012-2h6a2 2 0 012 2v11l-5-2.5L5 16V5z"></path>
                                                </svg></button>
                                            <button @click="toggleDecision(msg)" title="Decision"
                                                :class="msg.is_decision ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-900/30' : 'text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-lg'"
                                                class="p-2 transition-all"><svg class="w-4 h-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg></button>
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open"
                                                    class="p-2 text-slate-400 hover:text-brand-500 hover:bg-brand-50 rounded-lg transition-all"><svg
                                                        class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                        </path>
                                                    </svg></button>
                                                <div x-show="open" @click.outside="open = false"
                                                    class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-dark-surface rounded-2xl shadow-2xl border border-slate-100 dark:border-dark-border p-2 z-50 overflow-hidden ring-1 ring-black/5 animate-in slide-in-from-bottom-2">
                                                    <button @click="showTaskLinker(msg); open = false"
                                                        class="w-full text-left px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 transition-all flex items-center gap-3"><svg
                                                            class="w-4 h-4 text-brand-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                            </path>
                                                        </svg>Link to Work Unit</button>
                                                    <button
                                                        class="w-full text-left px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 transition-all flex items-center gap-3"><svg
                                                            class="w-4 h-4 text-amber-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>Set Reminder</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="messages.length === 0"
                class="h-full flex flex-col items-center justify-center py-20 opacity-50">
                <div
                    class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-[32px] flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-1">Begin the Discussion</h3>
                <p class="text-sm font-medium text-slate-500">Choose a Space to start or continue your work journey.</p>
            </div>
        </div>

        {{-- Message Input Area --}}
        <div class="p-8 pb-10 bg-slate-50/80 dark:bg-dark-bg/80 backdrop-blur-sm z-20">
            <div class="max-w-4xl mx-auto relative">

                {{-- Preview Area --}}
                <div x-show="attachmentPreview"
                    class="mb-4 p-4 bg-white dark:bg-dark-surface rounded-2xl border border-slate-200 dark:border-dark-border shadow-soft flex items-center justify-between animate-in slide-in-from-bottom-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[300px]"
                                x-text="attachmentName"></div>
                            <div
                                class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none mt-1">
                                Ready for transition</div>
                        </div>
                    </div>
                    <button @click="clearAttachment"
                        class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-5 h-5"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>

                <form @submit.prevent="sendMessage" class="group/inp relative">
                    <input type="file" id="chatAttachment" class="hidden" @change="handleFileSelect">
                    <textarea x-model="newMessage" @keydown.enter.prevent="if(!event.shiftKey) sendMessage()" rows="1"
                        placeholder="Craft a professional update..."
                        class="w-full bg-white dark:bg-dark-surface border-slate-200 dark:border-dark-border rounded-3xl pl-8 pr-40 py-5 text-[15px] font-medium placeholder:text-slate-400 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all resize-none shadow-premium"></textarea>

                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                        <button type="button" @click="$refs.fileInput.click()"
                            class="p-3 text-slate-400 hover:text-brand-500 bg-slate-50 dark:bg-slate-800 rounded-2xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                        </button>
                        <button type="submit" :disabled="(!newMessage.trim() && !attachmentFile) || isSending"
                            class="px-8 py-3 bg-slate-900 dark:bg-brand-500 text-white rounded-2xl flex items-center gap-3 hover:bg-black dark:hover:bg-brand-600 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-xl shadow-slate-200 dark:shadow-brand-500/20 group/send">
                            <span class="text-xs font-black uppercase tracking-widest"
                                x-text="isSending ? 'Syncing...' : 'Commit'"></span>
                            <svg x-show="!isSending"
                                class="w-4 h-4 transform group-hover/send:translate-x-1 duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                            </svg>
                            <svg x-show="isSending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════ RIGHT PANEL: THREADS / DETAILS ═══════ --}}
    <div class="bg-white dark:bg-dark-surface border-l border-slate-200 dark:border-dark-border overflow-hidden flex flex-col transition-all duration-500"
        :class="rightPanelOpen ? 'w-[450px] opacity-100' : 'w-0 opacity-0 ml-0 shadow-none'">

        <template x-if="activeThread">
            <div class="h-full flex flex-col">
                <div
                    class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-dark-border">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Thread
                        Discussion</h3>
                    <button @click="closeThread"
                        class="p-2 text-slate-400 hover:text-rose-500 rounded-xl bg-slate-50 dark:bg-slate-800 transition-all"><svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>

                <div class="flex-grow overflow-y-auto p-6 space-y-6 custom-scrollbar bg-slate-50/30">
                    {{-- Original Message in Thread --}}
                    <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border-l-4 border-brand-500 shadow-sm mb-8">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-slate-900 dark:text-white"
                                x-text="activeThread.user.name"></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase"
                                x-text="formatDate(activeThread.created_at)"></span>
                        </div>
                        <div class="text-[13px] text-slate-700 dark:text-slate-200 leading-relaxed"
                            x-text="activeThread.message"></div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="reply in threadMessages" :key="reply.id">
                            <div class="flex gap-4 group">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 text-xs font-bold flex-shrink-0"
                                    x-text="reply.user.name.charAt(0)"></div>
                                <div class="flex-grow">
                                    <div class="flex items-baseline gap-2 mb-1">
                                        <span class="text-xs font-bold text-slate-900 dark:text-white"
                                            x-text="reply.user.name"></span>
                                        <span class="text-[9px] font-bold text-slate-400"
                                            x-text="formatDate(reply.created_at)"></span>
                                    </div>
                                    <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-100 dark:border-dark-border text-xs text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm"
                                        x-text="reply.message"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-dark-surface">
                    <form @submit.prevent="sendReply" class="relative">
                        <input type="text" x-model="newReply" placeholder="Reply to thread..."
                            class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl pl-5 pr-14 py-4 text-sm font-medium focus:ring-2 focus:ring-brand-500 shadow-inner">
                        <button type="submit"
                            class="absolute right-2 top-2 bottom-2 px-3 bg-brand-500 text-white rounded-xl shadow-lg shadow-brand-500/20 active:scale-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 13h3a3 3 0 000-6h-3M2 2l.324 2.162a4 4 0 003.352 3.352L13 8l-7.324.486a4 4 0 00-3.352 3.352L2 14">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </template>

        <template x-if="!activeThread">
            <div class="h-full flex flex-col p-8">
                <div class="flex-grow flex flex-col items-center justify-center text-center py-10 opacity-50">
                    <div
                        class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Workspace Digest</h3>
                    <p class="text-sm font-medium text-slate-500 italic px-8">Select a message thread or collaboration
                        unit to view focused discussion data.</p>
                </div>
            </div>
        </template>
    </div>

    {{-- Task Linking Modal --}}
    <div x-show="taskLinkerOpen" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-300">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md rounded-[32px] shadow-2xl overflow-hidden border border-slate-100 dark:border-dark-border"
            @click.outside="taskLinkerOpen = false">
            <div class="p-8 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Link to Work Unit</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Connect message to
                        active task</p>
                </div>
                <button @click="taskLinkerOpen = false"
                    class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
            <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar space-y-3">
                @foreach($tasks as $task)
                <button @click="linkToTask({{ $task->id }})"
                    class="w-full text-left p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-brand-500 hover:text-white transition-all group flex items-start gap-4 ring-1 ring-slate-200 dark:ring-slate-700 hover:ring-brand-400">
                    <div
                        class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center text-brand-500 shadow-sm group-hover:bg-brand-400 group-hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div
                            class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">
                            {{ $task->client?->first_name }} • {{ $task->trade }}</div>
                        <div class="text-sm font-bold truncate pr-4">{{ $task->description }}</div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('teamChat', () => ({
            messages: [],
            newMessage: '',
            isSending: false,
            activeChannel: null,
            channelName: 'General Announcement',
            rightPanelOpen: true,

            // Thread State
            activeThread: null,
            threadMessages: [],
            newReply: '',

            // Task State
            taskLinkerOpen: false,
            activeMessage: null,

            // File State
            attachmentFile: null,
            attachmentName: '',
            attachmentPreview: false,

            init() {
                this.fetchMessages();
                setInterval(() => this.fetchMessages(), 5000);
            },

            get groupedMessages() {
                const groups = [];
                let currentGroup = null;

                this.messages.forEach(msg => {
                    const msgTime = new Date(msg.created_at).getTime();

                    if (currentGroup &&
                        currentGroup.user.id === msg.user_id &&
                        (msgTime - currentGroup.timestamp) < (5 * 60000)) { // 5 min grouping
                        currentGroup.messages.push(msg);
                    } else {
                        currentGroup = {
                            user: msg.user,
                            timestamp: msgTime,
                            messages: [msg]
                        };
                        groups.push(currentGroup);
                    }
                });
                return groups;
            },

            fetchMessages() {
                let url = '/chat/fetch';
                if (this.activeChannel) url += '?project_id=' + this.activeChannel;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        const oldLen = this.messages.length;
                        this.messages = data;
                        if (data.length > oldLen) {
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });

                // Update active thread if open
                if (this.activeThread) {
                    fetch('/chat/fetch?parent_id=' + this.activeThread.id)
                        .then(res => res.json())
                        .then(data => this.threadMessages = data);
                }
            },

            switchChannel(id, name) {
                this.activeChannel = id;
                this.channelName = name;
                this.messages = [];
                this.fetchMessages();
                this.activeThread = null;
            },

            sendMessage() {
                if (!this.newMessage.trim() && !this.attachmentFile) return;
                this.isSending = true;

                const formData = new FormData();
                formData.append('message', this.newMessage);
                if (this.activeChannel) formData.append('project_id', this.activeChannel);
                if (this.attachmentFile) formData.append('attachment', this.attachmentFile);

                fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                })
                    .then(res => {
                        this.newMessage = '';
                        this.clearAttachment();
                        this.fetchMessages();
                    })
                    .finally(() => this.isSending = false);
            },

            openThread(msg) {
                this.activeThread = msg;
                this.rightPanelOpen = true;
                this.fetchMessages();
            },

            closeThread() {
                this.activeThread = null;
                this.threadMessages = [];
            },

            sendReply() {
                if (!this.newReply.trim()) return;
                const formData = new FormData();
                formData.append('message', this.newReply);
                formData.append('parent_id', this.activeThread.id);
                if (this.activeChannel) formData.append('project_id', this.activeChannel);

                fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                })
                    .then(res => {
                        this.newReply = '';
                        this.fetchMessages();
                    });
            },

            react(msg, emoji) {
                fetch(`/chat/${msg.id}/react`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ emoji: emoji })
                }).then(() => this.fetchMessages());
            },

            togglePin(msg) {
                fetch(`/chat/${msg.id}/pin`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).then(() => this.fetchMessages());
            },

            toggleDecision(msg) {
                fetch(`/chat/${msg.id}/decision`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).then(() => this.fetchMessages());
            },

            showTaskLinker(msg) {
                this.activeMessage = msg;
                this.taskLinkerOpen = true;
            },

            linkToTask(taskId) {
                fetch(`/chat/${this.activeMessage.id}/link-task`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ task_id: taskId })
                }).then(() => {
                    this.taskLinkerOpen = false;
                    this.fetchMessages();
                });
            },

            handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.attachmentFile = file;
                this.attachmentName = file.name;
                this.attachmentPreview = true;
            },

            clearAttachment() {
                this.attachmentFile = null;
                this.attachmentName = '';
                this.attachmentPreview = false;
                document.getElementById('chatAttachment').value = '';
            },

            formatDate(d) {
                if (!d) return '';
                const date = new Date(d);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            },

            formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            },

            scrollToBottom() {
                const c = document.getElementById('chatContainer');
                if (c) c.scrollTop = c.scrollHeight;
            }
        }));
    });
</script>

<style>
    .shadow-soft {
        box-shadow: 0 4px 20px -10px rgba(0, 0, 0, 0.05);
    }

    textarea:focus {
        outline: none;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e293b;
    }
</style>
@endsection