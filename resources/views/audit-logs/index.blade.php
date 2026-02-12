@extends('layouts.app')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
    {{-- Header --}}
    <div
        class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Audit Logs
            </h2>
            <p class="text-sm text-gray-500 mt-1">Track all changes in the system</p>
        </div>
        <div class="flex items-center gap-3">
            <div id="live-indicator"
                class="flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-xs font-medium border border-green-200">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Live
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-wrap gap-3 items-center">
            <select name="action"
                class="text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">All Actions</option>
                <option value="created" {{ request('action')==='created' ? 'selected' : '' }}>🟢 Created</option>
                <option value="updated" {{ request('action')==='updated' ? 'selected' : '' }}>🟡 Updated</option>
                <option value="deleted" {{ request('action')==='deleted' ? 'selected' : '' }}>🔴 Deleted</option>
            </select>
            <select name="user_id"
                class="text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">All Users</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name }}
                </option>
                @endforeach
            </select>
            <select name="model"
                class="text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">All Models</option>
                <option value="Client" {{ request('model')==='Client' ? 'selected' : '' }}>Client</option>
                <option value="Task" {{ request('model')==='Task' ? 'selected' : '' }}>Task</option>
                <option value="Comment" {{ request('model')==='Comment' ? 'selected' : '' }}>Comment</option>
                <option value="Payment" {{ request('model')==='Payment' ? 'selected' : '' }}>Payment</option>
                <option value="User" {{ request('model')==='User' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit"
                class="bg-teal-600 hover:bg-teal-700 text-white text-xs px-4 py-2 rounded-lg font-medium transition">
                Filter
            </button>
            <a href="{{ route('audit-logs.index') }}"
                class="text-xs text-gray-500 hover:text-gray-700 transition">Clear</a>
        </form>
    </div>

    {{-- Live Log Entries Container --}}
    <div id="live-logs-container"></div>

    {{-- Log Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                    <th class="py-3 px-4">When</th>
                    <th class="py-3 px-4">Who</th>
                    <th class="py-3 px-4">Action</th>
                    <th class="py-3 px-4">Description</th>
                    <th class="py-3 px-4">Details</th>
                </tr>
            </thead>
            <tbody id="log-table-body" class="text-gray-600 text-sm">
                @forelse($logs as $log)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition" data-log-id="{{ $log->id }}">
                    <td class="py-3 px-4 whitespace-nowrap">
                        <div class="text-xs font-medium">{{ $log->created_at->format('d M, Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->format('h:i:s A') }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                        @if($log->ip_address)
                        <div class="text-xs text-gray-400">{{ $log->ip_address }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @php
                        $actionStyles = [
                        'created' => 'bg-green-100 text-green-800',
                        'updated' => 'bg-yellow-100 text-yellow-800',
                        'deleted' => 'bg-red-100 text-red-800',
                        ];
                        $actionIcons = ['created' => '🟢', 'updated' => '🟡', 'deleted' => '🔴'];
                        @endphp
                        <span
                            class="{{ $actionStyles[$log->action] ?? 'bg-gray-100' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ $actionIcons[$log->action] ?? '' }} {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-gray-700">{{ $log->description }}</span>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $log->model_name }} #{{ $log->model_id }}</div>
                    </td>
                    <td class="py-3 px-4">
                        @if($log->old_values || $log->new_values)
                        <button onclick="toggleDetails({{ $log->id }})"
                            class="text-teal-600 hover:text-teal-800 text-xs font-medium underline transition">
                            View Changes
                        </button>
                        <div id="details-{{ $log->id }}" class="hidden mt-2 bg-gray-50 rounded-lg p-3 text-xs max-w-md">
                            @if($log->action === 'updated' && $log->old_values && $log->new_values)
                            @foreach($log->new_values as $key => $newVal)
                            <div class="flex gap-2 mb-1">
                                <span class="font-semibold text-gray-500 min-w-[80px]">{{ $key }}:</span>
                                <span class="text-red-500 line-through">{{ $log->old_values[$key] ?? '—' }}</span>
                                <span class="text-gray-400">→</span>
                                <span class="text-green-600">{{ $newVal }}</span>
                            </div>
                            @endforeach
                            @elseif($log->new_values)
                            @foreach($log->new_values as $key => $val)
                            @if(!in_array($key, ['id', 'created_at', 'updated_at', 'password']))
                            <div class="flex gap-2 mb-1">
                                <span class="font-semibold text-gray-500 min-w-[80px]">{{ $key }}:</span>
                                <span class="text-green-600">{{ is_array($val) ? json_encode($val) : $val }}</span>
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                        @else
                        <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        No audit logs yet. Changes will appear here automatically.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function toggleDetails(id) {
        const el = document.getElementById('details-' + id);
        el.classList.toggle('hidden');
    }

    // Live polling — check for new logs every 5 seconds
    let latestLogId = {{ $logs-> first() ? -> id ?? 0 }};

    function pollForNewLogs() {
        fetch(`{{ route('audit-logs.latest') }}?after_id=${latestLogId}`)
            .then(res => res.json())
            .then(logs => {
                if (logs.length > 0) {
                    latestLogId = Math.max(...logs.map(l => l.id));
                    const container = document.getElementById('live-logs-container');

                    logs.forEach(log => {
                        // Check if this log ID already exists in the table
                        if (document.querySelector(`[data-log-id="${log.id}"]`)) return;

                        const actionStyles = {
                            'created': 'bg-green-100 text-green-700 border-green-200',
                            'updated': 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'deleted': 'bg-red-100 text-red-700 border-red-200',
                        };
                        const actionIcons = { 'created': '🟢', 'updated': '🟡', 'deleted': '🔴' };

                        const el = document.createElement('div');
                        el.className = `px-6 py-3 border-b border-l-4 ${actionStyles[log.action] || 'bg-gray-100'} animate-pulse`;
                        el.dataset.logId = log.id;
                        el.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg">${actionIcons[log.action] || '⚪'}</span>
                                    <div>
                                        <span class="font-medium text-sm text-gray-800">${log.description}</span>
                                        <span class="text-xs text-gray-500 ml-2">by ${log.user_name}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">${log.time_ago}</span>
                            </div>
                        `;
                        container.prepend(el);

                        // Remove animation after 3s
                        setTimeout(() => el.classList.remove('animate-pulse'), 3000);
                    });
                }
            })
            .catch(err => console.warn('Audit poll error:', err));
    }

    // Poll every 5 seconds
    setInterval(pollForNewLogs, 5000);
</script>
@endsection