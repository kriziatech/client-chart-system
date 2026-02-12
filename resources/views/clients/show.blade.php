@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
    <div class="bg-gray-50 border-b border-gray-200 px-8 py-4 flex justify-between items-center no-print">
        <h1 class="text-2xl font-bold text-gray-800">Project Details</h1>
        <div class="space-x-2">
            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Back</a>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.edit', $client) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Edit</a>
            @endif
            <a href="{{ route('clients.print', $client) }}" target="_blank"
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded">Print View</a>
        </div>
    </div>

    <!-- This section mirrors the print view structure but with Tailwind utilities for screen -->
    <div class="p-8 space-y-8">
        <!-- Section 1: Header -->
        <div class="border-b-2 border-teal-700 pb-4">
            <h1 class="text-3xl font-bold text-center text-teal-800 uppercase tracking-wider">Client Chart</h1>
            <p class="text-center text-gray-500 text-sm">Project Work Sheet</p>
        </div>

        <!-- Section 1: Client Info -->
        <div class="grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded border border-gray-200">
            <div>
                <p><span class="font-bold text-gray-700">Client Name:</span> {{ $client->first_name }} {{
                    $client->last_name }}</p>
                <p><span class="font-bold text-gray-700">File No:</span> {{ $client->file_number }}</p>
                <p><span class="font-bold text-gray-700">Mobile:</span> {{ $client->mobile }}</p>
            </div>
            <div>
                <p><span class="font-bold text-gray-700">Start Date:</span> {{ $client->start_date }}</p>
                <p><span class="font-bold text-gray-700">Delivery Date:</span> {{ $client->delivery_date }}</p>
                <p><span class="font-bold text-gray-700">Address:</span> {{ $client->address }}</p>
            </div>
            <div class="col-span-2">
                <p><span class="font-bold text-gray-700">Description:</span> {{ $client->work_description }}</p>
            </div>
        </div>

        <!-- Section 2: Checklist -->
        <div>
            <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">Work Checklist</h3>
            <div class="grid grid-cols-3 md:grid-cols-5 gap-2 text-sm">
                @forelse($client->checklistItems as $item)
                <div class="flex items-center">
                    <span
                        class="w-5 h-5 border border-gray-400 mr-2 flex items-center justify-center rounded text-xs {{ $item->is_checked ? 'bg-teal-100 text-teal-700 border-teal-500' : 'text-gray-300' }}">
                        {{ $item->is_checked ? '✓' : '' }}
                    </span>
                    <span class="{{ $item->is_checked ? 'text-gray-800' : 'text-gray-500' }}">{{ $item->name }}</span>
                </div>
                @empty
                <p class="text-gray-500 col-span-5">No checklist items.</p>
                @endforelse
            </div>
        </div>

        <!-- Section 3: Site Info & Permissions -->
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">Site Information</h3>
                <p class="text-sm mb-1"><span class="font-bold">Signed By:</span> {{ $client->siteInfo->signed_by }}</p>
                <p class="text-sm mb-1"><span class="font-bold">Facts:</span> {{ $client->siteInfo->site_facts }}</p>
                <p class="text-sm mb-1"><span class="font-bold">Instructions:</span> {{
                    $client->siteInfo->working_instructions }}</p>
            </div>
            <div>
                <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">Permissions</h3>
                <div class="space-y-2 text-sm">
                    <p>Work Permit: <span class="font-bold">{{ $client->permission->work_permit ? 'Yes' : 'No' }}</span>
                    </p>
                    <p>Gate Pass: <span class="font-bold">{{ $client->permission->gate_pass ? 'Yes' : 'No' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Section 5: Comments -->
        <div>
            <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">Comments</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Date</th>
                        <th class="border border-gray-300 p-2 text-left">Work</th>
                        <th class="border border-gray-300 p-2 text-left">Initials</th>
                        <th class="border border-gray-300 p-2 text-left">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($client->comments as $comment)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $comment->date }}</td>
                        <td class="border border-gray-300 p-2">{{ $comment->work }}</td>
                        <td class="border border-gray-300 p-2">{{ $comment->initials }}</td>
                        <td class="border border-gray-300 p-2">{{ $comment->comment }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-2 text-center text-gray-500">No comments.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section 6: Payments -->
        <div>
            <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">Payments</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Name</th>
                        <th class="border border-gray-300 p-2 text-left">Role</th>
                        <th class="border border-gray-300 p-2 text-left">Amount</th>
                        <th class="border border-gray-300 p-2 text-left">Date</th>
                        <th class="border border-gray-300 p-2 text-left">Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($client->payments as $payment)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $payment->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $payment->role }}</td>
                        <td class="border border-gray-300 p-2">₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="border border-gray-300 p-2">{{ $payment->date }}</td>
                        <td class="border border-gray-300 p-2">{{ $payment->purpose }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-2 text-center text-gray-500">No payments recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section 7: Tasks & Timeline -->
        <div class="mt-8">
            <h3 class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2">7. Tasks & Timeline</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left w-1/3">Task / Description</th>
                        <th class="border border-gray-300 p-2 text-left">Assigned To</th>
                        <th class="border border-gray-300 p-2 text-left">Deadline</th>
                        <th class="border border-gray-300 p-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($client->tasks as $task)
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">{{ $task->description }}</td>
                        <td class="border border-gray-300 p-2">{{ $task->assigned_to ?? '-' }}</td>
                        <td class="border border-gray-300 p-2 text-gray-600">
                            {{ $task->deadline ? $task->deadline->format('d M, Y') : '-' }}
                            @if($task->deadline && $task->deadline->isPast() && $task->status !== 'Completed')
                            <span class="text-red-500 text-xs ml-1 font-bold">(Overdue)</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2">
                            @php
                            $statusColors = [
                            'Pending' => 'bg-yellow-100 text-yellow-800',
                            'In Progress' => 'bg-blue-100 text-blue-800',
                            'Completed' => 'bg-green-100 text-green-800',
                            ];
                            $color = $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="{{ $color }} text-xs font-medium px-2.5 py-0.5 rounded">{{ $task->status
                                }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-2 text-center text-gray-500">No tasks assigned.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection