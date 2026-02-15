@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Permission Matrix</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Configure structural access levels
                and functional keys.</p>
        </div>

        <a href="{{ route('users.index') }}"
            class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Personnel
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Add Role Form -->
        <div class="lg:col-span-4">
            <div
                class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden sticky top-8">
                <div
                    class="px-8 py-5 border-b border-ui-border dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/50">
                    <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                        </span>
                        New Definition
                    </h3>
                </div>

                <form action="{{ route('roles.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted ml-1">Structural
                            Key</label>
                        <input type="text" name="name" required placeholder="e.g. project_lead"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-black tracking-widest text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mt-2">Lowercase.
                            Underscores only.</p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted ml-1">Functional
                            Label</label>
                        <input type="text" name="description" required placeholder="e.g. Project Director"
                            class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted ml-1">Privilege
                            Depth</label>
                        <select name="type" required
                            class="w-full bg-brand-50/50 dark:bg-brand-500/5 border-transparent rounded-2xl px-5 py-4 text-sm font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="viewer">Viewer (Read Only)</option>
                            <option value="editor">Editor (Write Access)</option>
                            <option value="admin">Admin (System Global)</option>
                            <option value="sales">Sales (Leads & Pitching)</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-slate-900 text-white px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition-all shadow-2xl active:scale-95 group flex items-center justify-center gap-3">
                        Deploy Definition
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Role List -->
        <div class="lg:col-span-8">
            <div
                class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                            <th class="py-5 px-8">Functional Class</th>
                            <th class="py-5 px-8">Logic Strength</th>
                            <th class="py-5 px-8 text-right">Operational Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                        @foreach($roles as $role)
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                            <td class="py-6 px-8">
                                <div class="text-sm font-black text-slate-900 dark:text-white">{{ $role->description }}
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Key:
                                    {{ $role->name }}</div>
                            </td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                @if($role->type === 'admin')
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 dark:bg-rose-500/10 flex items-center gap-2 w-fit">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50">
                                    </div>
                                    System Administrator
                                </span>
                                @elseif($role->type === 'editor')
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 dark:bg-blue-500/10 flex items-center gap-2 w-fit">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                    Operational Editor
                                </span>
                                @elseif($role->type === 'sales')
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-green-50 text-green-600 dark:bg-green-500/10 flex items-center gap-2 w-fit">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                    Sales Team Scope
                                </span>
                                @else
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-500 dark:bg-slate-500/10 flex items-center gap-2 w-fit">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                    Standard Observer
                                </span>
                                @endif
                            </td>
                            <td class="py-6 px-8 text-right">
                                @if(!in_array($role->name, ['admin', 'editor', 'viewer']))
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Purge this role definition? Users under this key will be revoked.');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-500 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span
                                    class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] italic">Immutable
                                    System Core</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection