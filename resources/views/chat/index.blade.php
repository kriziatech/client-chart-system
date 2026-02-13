@extends('layouts.app')

@section('content')
<div x-data="teamChat()" x-init="initChat()"
    class="h-[calc(100vh-6rem)] w-full max-w-[1600px] mx-auto bg-white dark:bg-dark-surface rounded-2xl shadow-2xl overflow-hidden border border-ui-border dark:border-dark-border flex animate-in fade-in duration-500">

    <!-- 1. LEFT SIDEBAR: Navigation -->
    <div
        class="w-80 flex-shrink-0 bg-slate-50 dark:bg-dark-bg border-r border-ui-border dark:border-dark-border flex flex-col">
        <!-- Sidebar Header -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-ui-border dark:border-dark-border">
            <h2 class="text-sm font-bold uppercase tracking-wider text-ui-muted">Communications</h2>
            <button class="p-2 text-ui-muted hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
        </div>

        <!-- Search -->
        <div class="p-4">
            <div class="relative">
                <input type="text" placeholder="Search chats..."
                    class="w-full bg-white dark:bg-dark-surface border-none rounded-xl py-2 pl-9 pr-4 text-xs font-medium focus:ring-2 focus:ring-brand-500 shadow-sm transition-all">
                <svg class="w-4 h-4 absolute left-3 top-2 text-ui-muted" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Channel List -->
        <div class="flex-1 overflow-y-auto px-3 space-y-6 custom-scrollbar">
            <!-- Project Channels -->
            <div>
                <div class="px-3 mb-2 flex items-center justify-between group">
                    <h3
                        class="text-[10px] font-bold uppercase tracking-widest text-ui-muted group-hover:text-brand-600 transition-colors cursor-pointer">
                        Project Groups</h3>
                    <span class="text-[10px] bg-brand-100 text-brand-700 font-bold px-1.5 rounded-md">{{
                        $projects->count() }}</span>
                </div>
                <div class="space-y-0.5">
                    <button @click="switchChannel(null, 'General Chat')"
                        :class="activeChannel === null ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600 dark:text-brand-400' : 'text-ui-muted hover:bg-slate-100 dark:hover:bg-dark-surface/50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-200 dark:bg-slate-800 text-slate-500 group-hover:bg-brand-100 dark:group-hover:bg-brand-900 group-hover:text-brand-600 transition-colors">
                            <span class="font-bold text-xs">#</span>
                        </div>
                        <div class="text-left overflow-hidden">
                            <div class="text-sm font-bold truncate">General Chat</div>
                            <div class="text-[10px] truncate opacity-60">Company-wide announcements</div>
                        </div>
                    </button>

                    @foreach($projects as $project)
                    <button
                        @click="switchChannel({{ $project->id }}, '{{ $project->first_name }} {{ $project->last_name }}')"
                        :class="activeChannel === {{ $project->id }} ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600 dark:text-brand-400' : 'text-ui-muted hover:bg-slate-100 dark:hover:bg-dark-surface/50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 font-bold text-xs group-hover:bg-brand-600 group-hover:text-white transition-colors">
                            {{ substr($project->first_name, 0, 1) }}
                        </div>
                        <div class="text-left overflow-hidden">
                            <div class="text-xs font-bold truncate">{{ $project->first_name }} {{ $project->last_name }}
                            </div>
                            <div class="text-[9px] font-mono truncate opacity-60">{{ $project->file_number }}</div>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Private Messages -->
            <div>
                <div class="px-3 mb-2 flex items-center justify-between">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-ui-muted">Direct Messages</h3>
                </div>
                <div class="space-y-0.5">
                    @foreach($users as $user)
                    @if($user->id !== Auth::id())
                    <button
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-ui-muted hover:bg-slate-100 dark:hover:bg-dark-surface/50 transition-all opacity-60 hover:opacity-100">
                        <div class="relative">
                            <div
                                class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-900/30 text-brand-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span
                                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-brand-400 border-2 border-slate-50 dark:border-dark-bg rounded-full"></span>
                        </div>
                        <div class="text-left">
                            <div class="text-xs font-bold text-ui-primary dark:text-white">{{ $user->name }}</div>
                            <div class="text-[9px] status-text">Online</div>
                        </div>
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 2. CENTER PANEL: Chat Window -->
    <div class="flex-1 flex flex-col min-w-0 bg-white dark:bg-dark-surface relative">
        <!-- Chat Header -->
        <div
            class="h-16 px-6 border-b border-ui-border dark:border-dark-border flex items-center justify-between bg-white/80 dark:bg-dark-surface/80 backdrop-blur-md z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-lg shadow-brand-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-ui-primary dark:text-white leading-tight" x-text="channelName">
                    </h2>
                    <p class="text-[11px] text-ui-muted font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        <span x-text="users.length + ' Members Active'"></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex -space-x-2">
                    <template x-for="u in users.slice(0, 4)">
                        <div class="w-8 h-8 rounded-full border-2 border-white dark:border-dark-surface bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-ui-muted"
                            :title="u.name">
                            <span x-text="u.name.charAt(0)"></span>
                        </div>
                    </template>
                    <div x-show="users.length > 4"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-dark-surface bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-ui-muted">
                        <span x-text="'+' + (users.length - 4)"></span>
                    </div>
                </div>
                <div class="h-8 w-px bg-ui-border dark:bg-dark-border mx-2"></div>
                <button @click="rightPanelOpen = !rightPanelOpen"
                    class="p-2 text-ui-muted hover:text-brand-600 transition-colors rounded-lg hover:bg-slate-50 dark:hover:bg-dark-bg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="messages-container"
            class="flex-1 overflow-y-auto px-6 py-4 space-y-6 custom-scrollbar bg-slate-50/50 dark:bg-dark-bg/50">
            <!-- Loading -->
            <div x-show="isLoading && messages.length === 0" class="flex justify-center py-10">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-xs font-bold text-ui-muted uppercase tracking-widest">Loading
                        Conversation...</span>
                </div>
            </div>

            <!-- Empty State -->
            <div x-show="!isLoading && messages.length === 0"
                class="flex flex-col items-center justify-center h-full opacity-60">
                <div
                    class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-ui-muted">No messages yet. Start the conversation!</p>
            </div>

            <template x-for="(msg, index) in messages" :key="msg.id">
                <div class="group flex gap-4 transition-all duration-300 animate-in slide-in-from-bottom-2"
                    :class="msg.user_id == {{ Auth::id() }} ? 'flex-row-reverse' : ''">

                    <!-- Avatar -->
                    <div class="flex-shrink-0 pt-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold ring-2 ring-white dark:ring-dark-surface"
                            :class="msg.user_id == {{ Auth::id() }} ? 'bg-brand-100 text-brand-600' : 'bg-slate-200 text-slate-600'">
                            <span x-text="msg.user.name.charAt(0)"></span>
                        </div>
                    </div>

                    <!-- Bubble -->
                    <div class="max-w-[70%]">
                        <div class="flex items-end gap-2 mb-1"
                            :class="msg.user_id == {{ Auth::id() }} ? 'flex-row-reverse' : ''">
                            <span class="text-[11px] font-bold text-ui-primary dark:text-white"
                                x-text="msg.user.name"></span>
                            <span class="text-[9px] text-ui-muted" x-text="formatTime(msg.created_at)"></span>
                        </div>

                        <div class="relative group/bubble">
                            <div class="px-5 py-3 rounded-2xl shadow-sm text-sm leading-relaxed"
                                :class="msg.user_id == {{ Auth::id() }} ? 
                                    'bg-gradient-to-br from-brand-600 to-brand-700 text-white rounded-tr-none' : 
                                    'bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border text-ui-primary dark:text-slate-200 rounded-tl-none'">
                                <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                            </div>

                            <!-- Actions on Hover -->
                            <div class="absolute top-1/2 -translate-y-1/2 opacity-0 group-hover/bubble:opacity-100 transition-opacity flex items-center gap-1 bg-white dark:bg-dark-surface shadow-lg rounded-full px-2 py-1 border border-ui-border dark:border-dark-border"
                                :class="msg.user_id == {{ Auth::id() }} ? 'right-full mr-2' : 'left-full ml-2'">
                                <button class="p-1 text-slate-400 hover:text-amber-500 transition-colors"
                                    title="Create Task">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                </button>
                                <button class="p-1 text-slate-400 hover:text-brand-500 transition-colors" title="Reply">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="p-5 bg-white dark:bg-dark-surface border-t border-ui-border dark:border-dark-border">
            <form @submit.prevent="sendMessage" class="relative">
                <input type="text" x-model="newMessage" placeholder="Type a message to the team..."
                    class="w-full pl-5 pr-32 py-4 bg-slate-50 dark:bg-dark-bg border border-transparent focus:border-brand-500/30 focus:bg-white dark:focus:bg-dark-surface rounded-2xl focus:ring-4 focus:ring-brand-500/10 dark:text-white transition-all shadow-inner font-medium placeholder:text-slate-400"
                    :disabled="isSending">

                <div class="absolute right-2 top-2 bottom-2 flex items-center gap-1">
                    <button type="button"
                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-dark-bg rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>