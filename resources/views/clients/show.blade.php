@extends('layouts.app')

@section('content')
<style>
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }

        body {
            background: white;
        }

        /* Ensure tables break cleanly */
        tr {
            page-break-inside: avoid;
        }
    }
</style>
<div
    class="w-full mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:bg-slate-800 dark:border-slate-700 transition-colors duration-200">
    <div
        class="bg-gray-50 border-b border-gray-200 dark:bg-slate-800 dark:border-slate-700 px-8 py-4 flex justify-between items-center no-print transition-colors duration-200">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Project Details</h1>
        <div class="space-x-2">
            <a href="{{ route('clients.index') }}"
                class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white px-3 py-2 transition">Back</a>
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <a href="{{ route('clients.edit', $client) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">Edit</a>
            @endif
            <a href="{{ route('finance.analytics', $client) }}"
                class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded shadow transition flex items-center gap-2 inline-flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                    </path>
                </svg>
                Financial Analysis
            </a>
            <a href="{{ route('portal.show', $client->uuid) }}" target="_blank"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded shadow transition">Client Portal</a>
            <a href="{{ route('clients.print', $client) }}" target="_blank"
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded shadow transition">Print View</a>
        </div>
    </div>

    <div class="p-8 space-y-8">
        <!-- Section 1: Header -->
        <div class="border-b-2 border-teal-700 pb-4 dark:border-teal-500">
            <h1 class="text-3xl font-bold text-center text-teal-800 uppercase tracking-wider dark:text-teal-400">Client
                Chart</h1>
            <p class="text-center text-gray-500 text-sm dark:text-gray-400">Project Work Sheet</p>
        </div>

        {{-- AI Insights Widget --}}
        @php
        $risk = $client->risk_analysis;
        $riskLevel = $risk['level'];

        // Explicit classes for Tailwind JIT
        $textColors = match($riskLevel) {
        'High' => 'text-red-600 dark:text-red-400',
        'Medium' => 'text-yellow-600 dark:text-yellow-400',
        'Low' => 'text-green-600 dark:text-green-400',
        default => 'text-gray-600 dark:text-gray-400'
        };
        $iconColors = match($riskLevel) {
        'High' => 'text-red-500 dark:text-red-400',
        'Medium' => 'text-yellow-500 dark:text-yellow-400',
        'Low' => 'text-green-500 dark:text-green-400',
        default => 'text-gray-500 dark:text-gray-400'
        };
        $borderClass = match($riskLevel) {
        'High' => 'border-red-500',
        'Medium' => 'border-yellow-500',
        'Low' => 'border-green-500',
        default => 'border-gray-500'
        };
        @endphp
        <div
            class="bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-900 border-l-4 {{ $borderClass }} rounded-lg p-6 shadow-sm dark:shadow-none transition-colors duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 {{ $iconColors }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        AI Project Insights
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Predictive analysis based on current
                        progress</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-black {{ $textColors }}">{{ $risk['score'] }}/100</div>
                    <div class="text-xs font-bold uppercase tracking-wide {{ $iconColors }}">{{ $risk['level'] }} Risk
                    </div>
                </div>
            </div>

            <div class="mt-4 grid md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-slate-800/50 p-3 rounded border border-gray-100 dark:border-slate-700">
                    <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Key Factors</h4>
                    <ul class="space-y-1">
                        @forelse($risk['reasons'] as $reason)
                        <li class="text-sm text-gray-700 dark:text-white flex items-start gap-2">
                            <span class="{{ $iconColors }} mt-1">•</span> {{ $reason }}
                        </li>
                        @empty
                        <li class="text-sm text-gray-500 italic">No significant risks detected.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="bg-white dark:bg-slate-800/50 p-3 rounded border border-gray-100 dark:border-slate-700">
                    <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Projections</h4>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Est. Completion</div>
                            <div class="font-medium text-gray-800 dark:text-white">
                                {{ now()->addDays($risk['projected_delay'] + ($client->delivery_date ?
                                now()->diffInDays($client->delivery_date) : 0))->format('d M, Y') }}
                            </div>
                        </div>
                        <div class="flex-1 border-l border-gray-200 dark:border-slate-700 pl-3">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Delay Risk</div>
                            @if($risk['projected_delay'] > 0)
                            <div class="font-bold text-red-500 dark:text-red-400">+{{ round($risk['projected_delay']) }}
                                Days</div>
                            @else
                            <div class="font-bold text-green-500 dark:text-green-400">On Track</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Financial & Quotation Summary --}}
            @php
            $approvedTotal = $client->quotations->where('status', 'approved')->sum('total_amount');
            $pendingTotal = $client->quotations->where('status', 'sent')->sum('total_amount');
            $paidTotal = $client->payments->sum('amount');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print mb-8">
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl border-l-4 border-teal-500 shadow-sm relative group">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Authorized Budget
                    </h4>
                    <div class="text-2xl font-black text-teal-600">₹{{ number_format($approvedTotal) }}</div>
                    <p class="text-[9px] text-slate-500 mt-2">Work officially approved by client.</p>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl border-l-4 border-blue-500 shadow-sm relative group">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Payments Collected
                    </h4>
                    <div class="text-2xl font-black text-blue-600">₹{{ number_format($paidTotal) }}</div>
                    <p class="text-[9px] text-slate-500 mt-2">Total payments received till date.</p>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl border-l-4 {{ $pendingTotal > 0 ? 'border-orange-500 bg-orange-50/10' : 'border-slate-300' }} shadow-sm relative group">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending Approval
                    </h4>
                    <div class="text-2xl font-black {{ $pendingTotal > 0 ? 'text-orange-600' : 'text-slate-300' }}">₹{{
                        number_format($pendingTotal) }}</div>
                    @if($pendingTotal > 0)
                    <p class="text-[9px] text-orange-600 mt-2 font-bold animate-pulse">ACTION REQUIRED: CLIENT APPROVAL
                        PENDING</p>
                    @else
                    <p class="text-[9px] text-slate-400 mt-2 italic">Nothing pending for approval.</p>
                    @endif
                </div>
            </div>

            <!-- Section 1: Client Info -->
            <div
                class="grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded border border-gray-200 dark:bg-slate-900 dark:border-slate-700 transition-colors duration-200">
                <div>
                    <p class="text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Client
                            Name:</span> {{ $client->first_name }} {{ $client->last_name }}</p>
                    <p class="text-gray-800 dark:text-white"><span class="font-bold text-gray-700 dark:text-white">File
                            No:</span> {{ $client->file_number }}</p>
                    <p class="text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Mobile:</span> {{ $client->mobile }}</p>
                </div>
                <div>
                    <p class="text-gray-800 dark:text-white"><span class="font-bold text-gray-700 dark:text-white">Start
                            Date:</span> {{ $client->start_date ? $client->start_date->format('d M Y') : '-' }}</p>
                    <p class="text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Delivery
                            Date:</span> {{ $client->delivery_date
                        ? $client->delivery_date->format('d M Y') : '-' }}</p>
                    <p class="text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Address:</span> {{ $client->address }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Description:</span> {{
                        $client->work_description
                        }}</p>
                </div>
            </div>

            <!-- Section 2: Checklist -->
            <div>
                <h3
                    class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                    Work Checklist</h3>
                <div class="grid grid-cols-3 md:grid-cols-5 gap-2 text-sm">
                    @forelse($client->checklistItems as $item)
                    <div class="flex items-center">
                        <span
                            class="w-5 h-5 border border-gray-400 mr-2 flex items-center justify-center rounded text-xs {{ $item->is_checked ? 'bg-teal-100 text-teal-700 border-teal-500 dark:bg-teal-900 dark:text-teal-300 dark:border-teal-700' : 'text-gray-300 dark:border-slate-600' }}">
                            {{ $item->is_checked ? '✓' : '' }}
                        </span>
                        <span
                            class="{{ $item->is_checked ? 'text-gray-800 dark:text-gray-200' : 'text-gray-500 dark:text-gray-500' }}">{{
                            $item->name }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 col-span-5">No checklist items.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section 3: Site Info & Permissions -->
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3
                        class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                        Site Information</h3>
                    <p class="text-sm mb-1 text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Signed By:</span> {{
                        $client->siteInfo->signed_by }}</p>
                    <p class="text-sm mb-1 text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Facts:</span> {{
                        $client->siteInfo->site_facts
                        }}</p>
                    <p class="text-sm mb-1 text-gray-800 dark:text-white"><span
                            class="font-bold text-gray-700 dark:text-white">Instructions:</span> {{
                        $client->siteInfo->working_instructions }}</p>
                </div>
                <div>
                    <h3
                        class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                        Permissions</h3>
                    <div class="space-y-2 text-sm text-gray-800 dark:text-white">
                        <p>Work Permit: <span class="font-bold text-gray-700 dark:text-white">{{
                                $client->permission->work_permit ? 'Yes' : 'No' }}</span></p>
                        <p>Gate Pass: <span class="font-bold text-gray-700 dark:text-white">{{
                                $client->permission->gate_pass ? 'Yes' : 'No' }}</span></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section 4: Project Gallery -->
        <div class="mt-8">
            <h3
                class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-4 dark:text-teal-400 dark:border-slate-600">
                Project Gallery</h3>

            <!-- Feedback Messages -->
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Gallery Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @forelse($client->galleries as $image)
                <div
                    class="group relative bg-gray-50 dark:bg-slate-900 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank"
                        class="block aspect-square overflow-hidden bg-gray-200 dark:bg-slate-800">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $image->caption ?? 'Project Image' }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </a>

                    @if($image->caption)
                    <div
                        class="p-2 text-xs text-center border-t border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-900 truncate">
                        {{ $image->caption }}
                    </div>
                    @endif

                    <!-- Delete Button (Admin Only) -->
                    @if(Auth::user()->isAdmin())
                    <form action="{{ route('gallery.destroy', $image) }}" method="POST"
                        onsubmit="return confirm('Delete this image?');"
                        class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600 focus:outline-none"
                            title="Delete Image">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
                @empty
                <div
                    class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-slate-900/50 rounded border border-dashed border-gray-300 dark:border-slate-700">
                    No images uploaded yet.
                </div>
                @endforelse
            </div>

            <!-- Upload Form (Admin/Editor Only) -->
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
            <form action="{{ route('gallery.store', $client) }}" method="POST" enctype="multipart/form-data"
                class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded border border-gray-200 dark:border-slate-700 no-print">
                @csrf
                <div class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Upload
                            Image</label>
                        <input type="file" name="image" required
                            class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-900/30 dark:file:text-teal-300">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Caption
                            (Optional)</label>
                        <input type="text" name="caption" placeholder="E.g. Kitchen Before"
                            class="w-full rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-bold shadow transition">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>

        <!-- Section 5: Comments -->
        <div>
            <h3
                class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                Comments</h3>
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-gray-100 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Date</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Work</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Initials</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Comment</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->comments as $comment)
                    <tr>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $comment->date }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $comment->work }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $comment->initials }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $comment->comment }}</td>
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
            <h3
                class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                Payments</h3>
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-gray-100 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Name</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Role</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Amount</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Date</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Purpose</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->payments as $payment)
                    <tr>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $payment->name }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $payment->role }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">
                            ₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $payment->date }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $payment->purpose }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-2 text-center text-gray-500">No payments recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section 6.2: Advance Payment Requests -->
        <div class="mt-8">
            <div class="flex justify-between items-center border-b border-gray-300 mb-2 dark:border-slate-600">
                <h3 class="text-lg font-bold text-blue-700 dark:text-blue-400">Advance Payment Requests</h3>
                @if(!auth()->user()->isViewer())
                <a href="{{ route('payment-requests.create', ['client_id' => $client->id]) }}"
                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded no-print hover:bg-blue-700 transition">+
                    Request Advance</a>
                @endif
            </div>
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-blue-50 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Title</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Amount</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Due Date</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-center text-gray-700 dark:text-white">
                            Status</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-right text-gray-700 dark:text-white">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->paymentRequests as $preq)
                    <tr>
                        <td class="border border-gray-300 dark:border-slate-700 p-2">
                            <div class="font-bold text-slate-800 dark:text-white">{{ $preq->title }}</div>
                            @if($preq->quotation) <div class="text-[10px] text-slate-400">Linked to: {{
                                $preq->quotation->quotation_number }}</div> @endif
                        </td>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 text-blue-600 font-bold italic text-lg">
                            ₹{{ number_format($preq->amount, 2) }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $preq->due_date ? $preq->due_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $preq->status === 'paid' ? 'bg-green-100 text-green-700' : ($preq->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $preq->status }}
                            </span>
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-right space-x-1">
                            @if(!auth()->user()->isViewer() && $preq->status === 'pending')
                            <form action="{{ route('payment-requests.updateStatus', $preq) }}" method="POST"
                                class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit"
                                    class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Mark
                                    Paid</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-400 italic">No advance payment requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section 6.5: Quotations & Work Approval -->
        <div class="mt-8" id="quotations-section">
            <div class="flex justify-between items-center border-b border-gray-300 mb-2 dark:border-slate-600">
                <h3 class="text-lg font-bold text-teal-700 dark:text-teal-400">Quotations & Work Approval</h3>
                @if(!auth()->user()->isViewer())
                <a href="{{ route('quotations.create', ['client_id' => $client->id]) }}"
                    class="text-xs bg-teal-600 text-white px-3 py-1 rounded no-print hover:bg-teal-700 transition">+ Add
                    Additional Work</a>
                @endif
            </div>
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-gray-100 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            ID #</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Date</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Total Amount</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-center text-gray-700 dark:text-white">
                            Status</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-right text-gray-700 dark:text-white">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->quotations as $quotation)
                    <tr>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400 font-mono">
                            {{ $quotation->quotation_number }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $quotation->date->format('d M, Y') }}</td>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 text-gray-800 dark:text-white font-bold">
                            ₹{{ number_format($quotation->total_amount, 2) }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $quotation->status === 'approved' ? 'bg-green-100 text-green-700' : ($quotation->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $quotation->status }}
                            </span>
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-right">
                            <a href="{{ route('quotations.show', $quotation) }}"
                                class="text-teal-600 font-bold hover:underline">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 italic">No quotations issued for this
                            project.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section: Material Tracking -->
        <div class="mt-8">
            <div class="flex justify-between items-center border-b border-gray-300 mb-2 dark:border-slate-600">
                <h3 class="text-lg font-bold text-teal-700 dark:text-teal-400">Inventory & Material Tracking</h3>
                @if(!auth()->user()->isViewer())
                <button onclick="document.getElementById('dispatch-modal').classList.toggle('hidden')"
                    class="text-xs bg-teal-600 text-white px-3 py-1 rounded no-print hover:bg-teal-700 transition">+
                    Dispatch Material</button>
                @endif
            </div>

            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-gray-100 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Material</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Qty Dispatched</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Date</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-center text-gray-700 dark:text-white">
                            Status</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-right text-gray-700 dark:text-white no-print">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->materials as $mat)
                    <tr>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 text-gray-800 dark:text-white font-bold">
                            {{ $mat->inventoryItem->name }}
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">
                            {{ $mat->quantity_dispatched }} {{ $mat->inventoryItem->unit }}
                        </td>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400 font-mono text-xs">
                            {{ $mat->delivery_date ? $mat->delivery_date->format('d M, Y') : '-' }}
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-center">
                            @if(!auth()->user()->isViewer())
                            <form action="{{ route('project-materials.updateStatus', $mat) }}" method="POST"
                                class="inline no-print">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-[10px] px-2 py-0.5 rounded border-none bg-slate-100 dark:bg-slate-800 dark:text-gray-300 cursor-pointer">
                                    <option value="Stocked" {{ $mat->status === 'Stocked' ? 'selected' : '' }}>Stocked
                                    </option>
                                    <option value="In Use" {{ $mat->status === 'In Use' ? 'selected' : '' }}>In Use
                                    </option>
                                    <option value="Consumed" {{ $mat->status === 'Consumed' ? 'selected' : ''
                                        }}>Consumed</option>
                                </select>
                            </form>
                            <span class="print:block hidden text-xs">{{ $mat->status }}</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-700">{{
                                $mat->status }}</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-right no-print">
                            @if(!auth()->user()->isViewer())
                            <form action="{{ route('project-materials.destroy', $mat) }}" method="POST" class="inline"
                                onsubmit="return confirm('Remove this dispatch record?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 text-xs font-bold uppercase">Remove</button>
                            </form>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 italic">No materials dispatched yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Section 7: Tasks & Timeline -->
        <div class="mt-8">
            <h3
                class="text-lg font-bold text-teal-700 border-b border-gray-300 mb-2 dark:text-teal-400 dark:border-slate-600">
                Tasks & Timeline</h3>
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-slate-700">
                <thead class="bg-gray-100 dark:bg-slate-800">
                    <tr>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left w-1/3 text-gray-700 dark:text-white">
                            Task / Description</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Assigned To</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Deadline</th>
                        <th
                            class="border border-gray-300 dark:border-slate-700 p-2 text-left text-gray-700 dark:text-white">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-slate-900/50">
                    @forelse($client->tasks as $task)
                    <tr>
                        <td
                            class="border border-gray-300 dark:border-slate-700 p-2 font-medium text-gray-800 dark:text-white">
                            {{ $task->description }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">{{
                            $task->assigned_to ?? '-' }}</td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2 text-gray-600 dark:text-gray-400">
                            {{ $task->deadline ? $task->deadline->format('d M, Y') : '-' }}
                            @if($task->deadline && $task->deadline->isPast() && $task->status !== 'Completed')
                            <span class="text-red-500 text-xs ml-1 font-bold">(Overdue)</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 dark:border-slate-700 p-2">
                            @php
                            $statusColors = [
                            'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'In Progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'Completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            ];
                            $color = $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800
                            dark:text-white';
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
@if(!auth()->user()->isViewer())
<div id="dispatch-modal"
    class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-2xl max-w-md w-full border border-slate-200 dark:border-slate-700">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Dispatch Material to Site</h3>
            <button onclick="document.getElementById('dispatch-modal').classList.toggle('hidden')"
                class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="{{ route('project-materials.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <div>
                <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Select Material
                    from Catalog</label>
                <select name="inventory_item_id" required
                    class="w-full border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white rounded shadow-sm focus:ring-teal-500">
                    <option value="">-- Select Material --</option>
                    @foreach(\App\Models\InventoryItem::orderBy('name')->get() as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Quantity</label>
                    <input type="number" step="0.01" name="quantity_dispatched" required
                        class="w-full border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white rounded shadow-sm focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Date</label>
                    <input type="date" name="delivery_date" value="{{ date('Y-m-d') }}"
                        class="w-full border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white rounded shadow-sm focus:ring-teal-500">
                </div>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('dispatch-modal').classList.toggle('hidden')"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">Cancel</button>
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded font-bold transition">Confirm
                    Dispatch</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection