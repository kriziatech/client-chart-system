@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Personnel Management</h1>
            <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-1">Control access credentials and
                operational roles for all system users.</p>
        </div>

        <button onclick="window.location.href='{{ route('register') }}'"
            class="bg-slate-900 dark:bg-brand-600 text-white text-[11px] px-6 py-3 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            Onboard New Personnel
        </button>
    </div>

    <div
        class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-[0.2em] text-ui-muted dark:text-dark-muted border-b border-ui-border dark:border-dark-border">
                        <th class="py-5 px-8">ID</th>
                        <th class="py-5 px-8">Full Name / Profile</th>
                        <th class="py-5 px-6">Access Credentials</th>
                        <th class="py-5 px-6">Operational Role</th>
                        <th class="py-5 px-6">Onboard Date</th>
                        <th class="py-5 px-8 text-center">Lifecycle Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border dark:divide-dark-border">
                    @foreach($users as $index => $usr)
                    @php /** @var \App\Models\User $usr */ @endphp
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-dark-bg/30 transition-all duration-300">
                        <td class="py-6 px-8 whitespace-nowrap">
                            <span class="text-[10px] font-black text-slate-300 font-mono">{{ sprintf('%02d', $index + 1)
                                }}</span>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-100 dark:bg-dark-bg border-4 border-white dark:border-dark-surface shadow-sm flex items-center justify-center text-slate-400 font-black text-sm uppercase">
                                    {{ substr($usr->name, 0, 1) }}{{ substr(strrchr($usr->name, " "), 1, 1) ?:
                                    substr($usr->name, 1, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                                        {{ $usr->name }}
                                        @if($usr->id === auth()->id())
                                        <span
                                            class="text-[9px] bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded-md font-black uppercase tracking-widest italic">Auth
                                            User</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-6">
                            <span class="text-xs font-bold text-slate-500 dark:text-dark-muted">{{ $usr->email }}</span>
                        </td>
                        <td class="py-6 px-6">
                            @php
                            $roleColors = [
                            'admin' => 'bg-brand-800 text-brand-50 dark:bg-brand-500/20 dark:text-brand-200',
                            'editor' => 'bg-brand-500 text-white dark:bg-brand-400/20 dark:text-brand-300',
                            'viewer' => 'bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-100',
                            ];
                            $colorClass = $roleColors[$usr->role->name] ?? 'bg-brand-50 text-brand-600
                            dark:bg-brand-500/10';
                            @endphp
                            <span
                                class="{{ $colorClass }} text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest">
                                {{ $usr->role->description ?? $usr->role->name }}
                            </span>
                        </td>
                        <td class="py-6 px-6">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">{{ $usr->created_at->format('d
                                M, Y') }}</span>
                        </td>
                        <td class="py-6 px-8">
                            @if($usr->id !== auth()->id())
                            <div
                                class="flex items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <form method="POST" action="{{ route('users.updateRole', $usr) }}"
                                    class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <select name="role_id"
                                        class="bg-slate-100 dark:bg-dark-bg border-none rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-brand-500/10 transition-all cursor-pointer">
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $usr->role_id === $role->id ? 'selected' : ''
                                            }}>
                                            {{ $role->description }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                        class="bg-slate-900 text-white text-[9px] px-4 py-2 rounded-xl font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm">Sync</button>
                                </form>

                                <form method="POST" action="{{ route('users.destroy', $usr) }}"
                                    onsubmit="return confirm('Immediately revoke all access privileges for this personnel?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-500 hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm"
                                        title="Revoke Access">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @else
                            <div
                                class="text-center italic text-[10px] text-slate-300 uppercase tracking-widest font-bold">
                                Immutable Self</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection