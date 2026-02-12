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
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Project Audit Logs
            </h2>
            <p class="text-sm text-gray-500 mt-1">Comprehensive tracking of all system activities</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400">Auto-refreshing</span>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-wrap gap-2 items-center">
            <select name="action" class="text-xs border-gray-300 rounded px-2 py-1.5 w-32">
                <option value="">All Actions</option>
                <option value="Created" {{ request('action')=='Created' ? 'selected' : '' }}>Created</option>
                <option value="Updated" {{ request('action')=='Updated' ? 'selected' : '' }}>Updated</option>
                <option value="Deleted" {{ request('action')=='Deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="Login" {{ request('action')=='Login' ? 'selected' : '' }}>Login</option>
                <option value="Logout" {{ request('action')=='Logout' ? 'selected' : '' }}>Logout</option>
            </select>
            <select name="user_id" class="text-xs border-gray-300 rounded px-2 py-1.5 w-32">
                <option value="">All Users</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name }}
                </option>
                @endforeach
            </select>
            <select name="status" class="text-xs border-gray-300 rounded px-2 py-1.5 w-24">
                <option value="">Status</option>
                <option value="success" {{ request('status')=='success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit"
                class="bg-gray-800 text-white text-xs px-3 py-1.5 rounded hover:bg-gray-700">Filter</button>
            <a href="{{ route('audit-logs.index') }}" class="text-xs text-gray-500 underline ml-2">Reset</a>
        </form>
    </div>

    {{-- Log Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-[10px] tracking-wider border-b border-gray-200">
                    <th class="py-3 px-4 w-40">Date & Time</th>
                    <th class="py-3 px-4 w-48">User / Role</th>
                    <th class="py-3 px-4 w-32">Action / Module</th>
                    <th class="py-3 px-4">Description / Changes</th>
                    <th class="py-3 px-4 w-24">Status</th>
                    <th class="py-3 px-4 w-48 text-right">Context (IP/Device)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition group">
                    <td class="py-3 px-4 align-top">
                        <div class="font-medium text-gray-800">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i:s A') }}</div>
                    </td>
                    <td class="py-3 px-4 align-top">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($log->user_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 leading-tight">{{ $log->user_name }}</div>
                                <div
                                    class="text-[10px] uppercase tracking-wide text-gray-500 bg-gray-100 px-1 rounded inline-block mt-0.5">
                                    {{ $log->user_role ?: 'Guest' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 align-top">
                        @php
                        $colors = [
                        'Created' => 'bg-green-50 text-green-700 border-green-200',
                        'Updated' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Deleted' => 'bg-red-50 text-red-700 border-red-200',
                        'Login' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'Logout' => 'bg-gray-50 text-gray-700 border-gray-200',
                        'Login Failed' => 'bg-red-100 text-red-800 border-red-300 font-bold',
                        ];
                        $colorClass = $colors[$log->action] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                        @endphp
                        <span
                            class="border {{ $colorClass }} px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide block w-fit mb-1">
                            {{ $log->action }}
                        </span>
                        <div class="text-xs text-gray-500 font-medium">{{ $log->module }}</div>
                        @if($log->model_id)
                        <div class="text-[10px] text-gray-400">ID: #{{ $log->model_id }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4 align-top">
                        <div class="text-gray-800 mb-1">{{ $log->description }}</div>

                        @if($log->failure_reason)
                        <div class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded border border-red-100 mt-1">
                            <strong>Error:</strong> {{ $log->failure_reason }}
                        </div>
                        @endif

                        @if($log->old_values || $log->new_values)
                        <button onclick="document.getElementById('details-{{ $log->id }}').classList.toggle('hidden')"
                            class="text-[10px] text-teal-600 font-semibold hover:underline flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            View Details
                        </button>
                        <div id="details-{{ $log->id }}"
                            class="hidden mt-2 bg-slate-50 border border-slate-200 rounded p-2 text-xs">
                            <table class="w-full text-left">
                                <tr class="text-gray-400 border-b border-gray-200">
                                    <th class="pb-1 font-normal w-1/3">Field</th>
                                    <th class="pb-1 font-normal w-1/3">Old</th>
                                    <th class="pb-1 font-normal w-1/3">New</th>
                                </tr>
                                @if($log->action === 'Updated' && is_array($log->new_values))
                                @foreach($log->new_values as $key => $val)
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-1 font-medium text-gray-600">{{ $key }}</td>
                                    <td class="py-1 text-red-500 break-all">{{ $log->old_values[$key] ?? '-' }}</td>
                                    <td class="py-1 text-green-600 break-all">{{ is_array($val) ? json_encode($val) :
                                        $val }}</td>
                                </tr>
                                @endforeach
                                @elseif(is_array($log->new_values))
                                @foreach($log->new_values as $key => $val)
                                <tr class="border-b border-gray-100">
                                    <td class="py-1 font-medium text-gray-600">{{ $key }}</td>
                                    <td class="py-1 text-gray-400">-</td>
                                    <td class="py-1 text-green-600 break-all">{{ is_array($val) ? json_encode($val) :
                                        $val }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 align-top">
                        @if($log->status === 'success')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Success
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Failed
                        </span>
                        @endif
                    </td>
                    <td class="py-3 px-4 align-top text-right">
                        <div class="text-xs text-gray-900 font-mono">{{ $log->ip_address }}</div>
                        <div class="text-[10px] text-gray-500 mt-0.5 flex flex-col items-end gap-0.5">
                            @if($log->device_type)<span class="bg-gray-100 px-1 rounded">{{ $log->device_type
                                }}</span>@endif
                            @if($log->browser)<span>{{ $log->browser }} on {{ $log->os }}</span>@endif
                            @if($log->source === 'api')<span class="text-purple-600 font-semibold">API</span>@endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400">No logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 border-t bg-gray-50">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>
@endsection
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
        <a href="{{ route('audit-logs.index') }}" class="text-xs text-gray-500 hover:text-gray-700 transition">Clear</a>
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
         const  e l  = document.getElementById('details-' + id);
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