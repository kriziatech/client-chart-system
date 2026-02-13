@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-theme(spacing.28))] flex gap-6" x-data="teamChat()" x-init="initChat()">

    <!-- Sidebar: Channels & Users -->
    <div class="w-72 flex flex-col flex-shrink-0 gap-6">

        <!-- Channels List -->
        <div
            class="bg-white dark:bg-dark-surface rounded-2xl shadow-premium border border-slate-200 dark:border-dark-border overflow-hidden flex flex-col flex-grow min-h-0">
            <div
                class="p-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50 flex justify-between items-center">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Channels
                </h3>
            </div>

            <div class="p-2 space-y-1 overflow-y-auto custom-scrollbar flex-grow">
                <!-- General Channel -->
                <button @click="switchChannel(null, 'General Chat')"
                    :class="activeChannel === null ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400'"
                    class="w-full text-left px-4 py-3 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-black"
                            :class="activeChannel === null ? 'bg-white text-brand-500 shadow-sm' : 'bg-slate-100 text-slate-400 group-hover:bg-white group-hover:shadow-sm transition-all'">
                            #</div>
                        <div>
                            <div class="font-bold text-sm">General</div>
                            <div class="text-[10px] text-slate-400 font-medium">Team Announcements</div>
                        </div>
                    </div>
                </button>

                <!-- Project Channels -->
                @foreach($projects as $project)
                <button
                    @click="switchChannel({{ $project->id }}, '{{ $project->first_name }} {{ $project->last_name }}')"
                    :class="activeChannel === {{ $project->id }} ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400'"
                    class="w-full text-left px-4 py-3 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-black"
                            :class="activeChannel === {{ $project->id }} ? 'bg-white text-brand-500 shadow-sm' : 'bg-slate-100 text-slate-400 group-hover:bg-white group-hover:shadow-sm transition-all'">
                            {{ substr($project->first_name, 0, 1) }}</div>
                        <div class="min-w-0">
                            <div class="font-bold text-sm truncate">{{ $project->first_name }} {{ $project->last_name }}
                            </div>
                            <div class="text-[10px] text-slate-400 font-medium truncate">Project Discussion</div>
                        </div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div
        class="flex-grow flex flex-col bg-white dark:bg-dark-surface rounded-2xl shadow-premium border border-slate-200 dark:border-dark-border overflow-hidden relative">

        <!-- Header -->
        <div
            class="h-16 border-b border-slate-100 dark:border-dark-border flex items-center justify-between px-6 bg-white/80 dark:bg-dark-surface/90 backdrop-blur z-10">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <span class="font-black text-lg">#</span>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white text-lg" x-text="channelName"></h2>
                    <p class="text-xs text-slate-400 font-medium">Real-time team collaboration</p>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2">
                <button @click="rightPanelOpen = !rightPanelOpen"
                    class="p-2 text-slate-400 hover:text-brand-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-grow overflow-y-auto p-6 space-y-6 bg-slate-50/50 dark:bg-dark-bg/30 relative"
            id="chatContainer">

            <!-- Loading -->
            <div x-show="isLoading"
                class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-dark-surface/50 backdrop-blur-sm z-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
            </div>

            <!-- Messages Loop -->
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex gap-4 group animate-in fade-in slide-in-from-bottom-2 duration-300"
                    :class="msg.user_id === currentUserId ? 'flex-row-reverse' : ''">

                    <!-- Avatar -->
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md transition-transform hover:scale-110"
                            :class="msg.user_id === currentUserId ? 'bg-brand-500' : 'bg-slate-400'">
                            <span x-text="msg.user.name.charAt(0)"></span>
                        </div>
                    </div>

                    <!-- Bubble -->
                    <div class="max-w-[70%]">
                        <div class="flex items-end gap-2 mb-1"
                            :class="msg.user_id === currentUserId ? 'justify-end' : ''">
                            <span class="text-xs font-bold text-slate-900 dark:text-white"
                                x-text="msg.user.name"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase"
                                x-text="formatTime(msg.created_at)"></span>
                        </div>
                        <div class="p-4 rounded-2xl shadow-sm text-sm leading-relaxed relative"
                            :class="msg.user_id === currentUserId ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-tl-none border border-slate-100 dark:border-slate-700'">
                            <p x-text="msg.message"></p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="!isLoading && messages.length === 0"
                class="h-full flex flex-col items-center justify-center text-slate-400">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <p class="font-medium text-sm">No messages yet in this channel.</p>
                <p class="text-xs opacity-70">Be the first to say hello!</p>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white dark:bg-dark-surface border-t border-slate-100 dark:border-dark-border z-20">
            <form @submit.prevent="sendMessage" class="relative">
                <input type="text" x-model="newMessage" placeholder="Type a message to the team..."
                    class="w-full pl-6 pr-32 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-brand-500 transition-all font-medium placeholder:text-slate-400 shadow-inner">
                <div class="absolute right-2 top-2 bottom-2 flex items-center gap-1">
                    <button type="button"
                        class="p-2 text-slate-400 hover:text-brand-500 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                    </button>
                    <button type="submit" :disabled="!newMessage.trim() || isSending"
                        class="h-full px-4 bg-brand-500 text-white rounded-lg flex items-center gap-2 hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-brand-500/20 font-bold text-xs uppercase tracking-wider">
                        <span x-show="!isSending">Send</span>
                        <svg x-show="!isSending" class="w-3 h-3 transform rotate-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <svg x-show="isSending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Panel: Online Users -->
    <div class="w-72 bg-white dark:bg-dark-surface rounded-2xl shadow-premium border border-slate-200 dark:border-dark-border overflow-hidden flex flex-col flex-shrink-0 transition-all duration-300"
        :class="rightPanelOpen ? 'w-72 opacity-100' : 'w-0 opacity-0 overflow-hidden ml-[-1.5rem]'">
        <div class="p-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Team Directory
            </h3>
        </div>
        <div class="p-2 space-y-1 overflow-y-auto flex-grow custom-scrollbar">
            @foreach($users as $user)
            <div
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group cursor-pointer">
                <div class="relative">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-brand-500/20 group-hover:scale-110 transition-transform">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white dark:border-dark-surface rounded-full">
                    </div>
                </div>
                <div class="flex-grow min-w-0">
                    <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                    <div class="text-[10px] uppercase font-bold text-slate-400 truncate">{{ $user->role ?
                        $user->role->name : 'Team Member' }}</div>
                </div>
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<script>
    function teamChat() {
        return {
            messages: [],
            newMessage: '',
            isLoading: true,
            isSending: false,
            currentUserId: {{ Auth::id() }},
    lastId: 0,
            activeChannel: null, // null = General, ID = Project
            channelName: 'General Chat',
            rightPanelOpen: true,
            pollInterval: null,

            
     in                       chMessages(true);
                // Poll every 3 seconds
                this.pollInterval =                 () => {
                                  etchMessages(false);                             }, 3000);
                    switchChannel(channelId, name) {
                if (this.activeChannel === channelId) return;
                
                this.activeChannel = channelId;
                this.channelName = name;
                this.messages = [];
                this.lastId = 0;
                this.fetchMessages(true);
            },

            fetchMessages(showLoading = false) {
                if (showLoading) this.isLoading = true;

                let url = '/chat/messages';
                if (this.activeChannel) {
                    url += `?project_id=${this.activeChannel}`;
                }

                // Append last_id to fetch only new messages (if optimizing)
                // url += `&last_id=${this.lastId}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        this.messages = data;
                        this.isLoading = false;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                        
                        if (data.length > 0) {
                            this.lastId = data[data.length - 1].id;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching messages:', error);
                        this.isLoading = false;
                    });
            },

            sendMessage() {
                if (this.newMessage.trim() === '') return;

                this.isSending = true;

                fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: this.newMessage,
                        project_id: this.activeChannel
                    })
                })
                .then(response => {
                    if (response.ok) {
                        this.newMessage = '';
                        this.fetchMessages(false);
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    this.isSending = false;
                });
            },

            formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            },

            scrollToBottom() {
                const container = document.getElementById('chatContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }
    }
</script>
@endsection