@extends('layouts.app')

@section('content')
<div
    class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:bg-dark-surface dark:border-dark-border transition-colors duration-200 mt-8 mb-16">
    <div
        class="bg-gray-50 border-b border-gray-200 dark:bg-slate-800 dark:border-dark-border px-8 py-6 flex justify-between items-center transition-colors duration-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Project Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Real-time progress tracking</p>
        </div>
        <div>
            <a href="javascript:window.print()"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-medium shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Report
            </a>
        </div>
    </div>

    <div x-data="{ activeTab: 'overview', showWelcome: true }" class="p-8 space-y-8">

        <!-- Welcome Modal -->
        <div x-show="showWelcome"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-cloak>
            <div
                class="bg-white dark:bg-dark-surface p-10 rounded-[3rem] w-full max-w-2xl shadow-2xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-600/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-brand-600 rounded-3xl flex items-center justify-center text-white mb-6 shadow-xl shadow-brand-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z">
                            </path>
                        </svg>
                    </div>

                    <h2 class="text-3xl font-black text-ui-primary dark:text-white leading-tight">Welcome to <br><span
                            class="text-brand-600">The InteriorTouch Experience</span></h2>
                    <p class="text-ui-muted mt-4 text-base font-medium">We're thrilled to begin your project. Here's a
                        quick guide to how we work together through this portal.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">01.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Planning</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">View your roadmap and approve designs
                                via the 'Roadmap' tab.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">02.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Execution</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">Track daily site updates and material
                                procurement in real-time.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-brand-600 font-black text-xl">03.</div>
                            <div class="text-xs font-black uppercase tracking-widest text-ui-primary dark:text-white">
                                Delivery</div>
                            <p class="text-[11px] text-ui-muted leading-relaxed">Formal handover and quality checks are
                                logged here for your records.</p>
                        </div>
                    </div>

                    <button @click="showWelcome = false"
                        class="mt-12 w-full py-4 bg-brand-600 text-white rounded-2xl font-black text-sm uppercase tracking-[3px] shadow-xl shadow-brand-500/30 hover:bg-brand-700 transition-all active:scale-95">
                        Get Started
                    </button>
                </div>
            </div>
        </div>

        <!-- Portal Tabs -->
        <div class="flex flex-wrap gap-1 bg-slate-100 dark:bg-dark-bg p-1 rounded-2xl w-fit">
            <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Overview</button>
            <button @click="activeTab = 'scope'"
                :class="activeTab === 'scope' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Scope</button>
            <button @click="activeTab = 'roadmap'"
                :class="activeTab === 'roadmap' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Roadmap</button>
            <button @click="activeTab = 'quotations'"
                :class="activeTab === 'quotations' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Quotations</button>
            <button @click="activeTab = 'execution'"
                :class="activeTab === 'execution' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Execution</button>
            <button @click="activeTab = 'inventory'"
                :class="activeTab === 'inventory' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Materials</button>
            <button @click="activeTab = 'payments'"
                :class="activeTab === 'payments' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Payments</button>
            <button @click="activeTab = 'portfolio'"
                :class="activeTab === 'portfolio' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Portfolio</button>
            <button @click="activeTab = 'handover'"
                :class="activeTab === 'handover' ? 'bg-white dark:bg-dark-surface shadow-sm text-brand-600' : 'text-ui-muted hover:text-ui-primary'"
                class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest leading-none">Handover</button>
        </div>

        <!-- Overview Content -->
        <div x-show="activeTab === 'overview'" class="space-y-8 animate-in slide-in-from-bottom duration-500">
            <x-project-lifecycle :client="$client" />

            <div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[11px] font-black text-ui-muted uppercase tracking-[1.5px]">Project Health</span>
                    <span class="text-3xl font-black text-brand-600">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-brand-600 h-full rounded-full transition-all duration-1000 ease-out shadow-lg shadow-brand-500/20"
                        style="width: {{ $progress }}%"></div>
                </div>
                @if($client->delivery_date)
                <div class="mt-4 flex justify-between text-[11px] font-bold uppercase tracking-wider text-ui-muted">
                    <div>Started: <span class="text-ui-primary dark:text-white ml-2">{{ $client->start_date ?
                            $client->start_date->format('d M, Y') : '-' }}</span></div>
                    <div>Target: <span class="text-ui-primary dark:text-white ml-2">{{ $client->delivery_date->format('d
                            M, Y') }}</span></div>
                </div>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div
                    class="bg-slate-50 dark:bg-dark-bg p-7 rounded-3xl border border-ui-border dark:border-dark-border">
                    <h3 class="text-[10px] font-black uppercase text-brand-600 mb-6 tracking-[2px]">Core Details</h3>
                    <div class="space-y-5">
                        <div class="flex flex-col border-b border-ui-border dark:border-dark-border pb-4">
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-1">Project Name</span>
                            <span class="font-black text-ui-primary dark:text-white text-base">{{ $client->first_name }}
                                {{ $client->last_name }}</span>
                        </div>
                        <div class="flex flex-col border-b border-ui-border dark:border-dark-border pb-4">
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-1">File Reference</span>
                            <span class="font-bold text-ui-primary dark:text-white text-sm">#{{ $client->file_number
                                }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-ui-muted uppercase mb-2 block">Scope Analysis</span>
                            <p class="text-ui-primary dark:text-white font-medium text-sm leading-relaxed">{{
                                $client->work_description }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-dark-bg p-7 rounded-3xl border border-ui-border dark:border-dark-border">
                    <h3 class="text-[10px] font-black uppercase text-brand-600 mb-6 tracking-[2px]">Phase Approvals</h3>
                    <div class="space-y-3 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($client->checklistItems as $item)
                        <div
                            class="flex items-center p-3 rounded-2xl bg-white dark:bg-dark-surface border border-ui-border">
                            <span
                                class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded-xl text-[10px] mr-4 {{ $item->is_checked ? 'bg-ui-success text-white shadow-lg shadow-green-500/20' : 'border-2 border-slate-200 dark:border-slate-700 text-transparent' }}">✓</span>
                            <span
                                class="text-sm {{ $item->is_checked ? 'text-ui-primary dark:text-white font-bold' : 'text-ui-muted font-medium' }}">{{
                                $item->name }}</span>
                        </div>
                        @empty
                        <p class="text-ui-muted text-xs italic text-center py-10 uppercase tracking-widest font-bold">No
                            milestones logged.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Scope Content -->
        <div x-show="activeTab === 'scope'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight mb-6">Detailed Scope of
                    Work</h3>
                @if($client->scopeOfWork && $client->scopeOfWork->items->count() > 0)
                <div class="space-y-4">
                    @foreach($client->scopeOfWork->items as $item)
                    <div class="p-5 bg-slate-50 dark:bg-dark-bg rounded-2xl border border-ui-border">
                        <h4 class="text-sm font-black text-ui-primary dark:text-white uppercase tracking-wider mb-2">{{
                            $item->area }}</h4>
                        <p class="text-xs text-ui-muted font-medium leading-relaxed">{{ $item->description }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div
                    class="py-20 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-ui-border">
                    <p class="text-xs font-black text-ui-muted uppercase tracking-widest">Scope details are being
                        finalized.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Roadmap Content -->
        <div x-show="activeTab === 'roadmap'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight mb-10">Project Visual
                    Roadmap</h3>
                <div
                    class="space-y-6 relative before:absolute before:inset-y-0 before:left-3 before:w-1 before:bg-brand-600/10 before:rounded-full">
                    @forelse($client->tasks->sortBy('deadline') as $task)
                    <div class="relative pl-12 group">
                        <div
                            class="absolute left-0 top-1.5 w-7 h-7 bg-white dark:bg-dark-surface rounded-full border-4 border-brand-600 shadow-premium z-10 flex items-center justify-center">
                            @if($task->status === 'Completed')
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <div class="w-2 h-2 bg-brand-600 rounded-full animate-pulse"></div>
                            @endif
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-dark-bg p-5 rounded-2xl border border-ui-border transition-all hover:border-brand-300">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                <div>
                                    <span
                                        class="text-xs font-black text-brand-600 uppercase tracking-widest mb-1 block">{{
                                        $task->status }}</span>
                                    <h4 class="text-sm font-black text-ui-primary dark:text-white leading-snug">{{
                                        $task->description }}</h4>
                                </div>
                                <div
                                    class="px-3 py-1 bg-white dark:bg-dark-surface rounded-lg text-[10px] font-black text-ui-muted uppercase tracking-widest border border-ui-border">
                                    Target: {{ $task->deadline ? $task->deadline->format('M d, Y') : 'TBD' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-ui-muted font-bold text-xs uppercase tracking-[3px] text-center py-20">Timeline not
                        yet established.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quotations Content -->
        <div x-show="activeTab === 'quotations'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div
                class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 dark:bg-dark-bg border-b border-ui-border">
                    <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Project Quotations
                    </h3>
                    <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Review and approve your
                        project estimates</p>
                </div>
                <div class="divide-y divide-ui-border">
                    @forelse($client->quotations as $quotation)
                    <div class="p-8 hover:bg-slate-50 dark:hover:bg-dark-bg transition-colors">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="space-y-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-black text-ui-primary dark:text-white">#{{
                                        $quotation->quotation_number }}</span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest {{ $quotation->status === 'Signed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $quotation->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-ui-muted font-bold uppercase tracking-widest">{{
                                    $quotation->date->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-brand-600 block">₹{{
                                    number_format($quotation->total_amount) }}</span>
                                <p class="text-[10px] text-ui-muted font-black uppercase tracking-widest mt-1">Total
                                    Estimate</p>
                            </div>
                            @if($quotation->status !== 'Signed')
                            <div>
                                <form
                                    action="{{ route('portal.quotation.approve', ['client' => $client->uuid, 'quotation' => $quotation->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-brand-500/20 transition-all transform active:scale-95">
                                        Approve & Sign
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center">
                        <p class="text-xs font-black text-ui-muted uppercase tracking-widest">No quotations issued yet.
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Execution Content -->
        <div x-show="activeTab === 'execution'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div
                class="space-y-12 relative before:absolute before:inset-y-0 before:left-3 before:w-1 before:bg-brand-600/10 before:rounded-full">
                @forelse($client->dailyReports->sortByDesc('report_date') as $report)
                <div class="relative pl-12 group transition-all">
                    {{-- Timeline Node --}}
                    <div
                        class="absolute left-0 top-1.5 w-7 h-7 bg-white dark:bg-dark-surface rounded-full border-4 border-brand-600 shadow-premium z-10 flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-brand-600 rounded-full"></div>
                    </div>

                    <div
                        class="bg-white dark:bg-dark-surface p-8 rounded-[32px] border border-ui-border shadow-premium hover:shadow-2xl transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="text-xs font-black text-brand-600 dark:text-brand-400 uppercase tracking-widest">{{
                                $report->report_date ? $report->report_date->format('l, d M Y') : '-' }}</span>
                        </div>

                        <p
                            class="text-ui-primary dark:text-white font-medium leading-relaxed whitespace-pre-line text-sm mb-6">
                            {{ $report->content }}</p>

                        @if($report->tasks->count() > 0)
                        <div class="mb-6 flex flex-wrap gap-2">
                            @foreach($report->tasks as $task)
                            <div
                                class="flex items-center gap-2 px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 rounded-full border border-emerald-100 dark:border-emerald-500/20">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span
                                    class="text-[10px] font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest">{{
                                    $task->title }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($report->images->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($report->images as $img)
                            <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank"
                                class="aspect-square rounded-2xl overflow-hidden border border-ui-border shadow-sm hover:scale-[1.02] transition-all">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover"
                                    alt="Site Update">
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div
                    class="py-20 text-center bg-slate-50 dark:bg-dark-bg rounded-[2.5rem] border-2 border-dashed border-ui-border">
                    <p class="text-xs font-black text-ui-muted uppercase tracking-widest">No daily site updates logged
                        yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Inventory Content -->
        <div x-show="activeTab === 'inventory'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div
                class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 dark:bg-dark-bg border-b border-ui-border">
                    <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Material Log</h3>
                    <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Procurement & delivery
                        status</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] border-b border-ui-border">
                                <th class="px-8 py-5">Material Description</th>
                                <th class="px-8 py-5">Quantity</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5">Arrival Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ui-border">
                            @forelse($client->projectMaterials as $material)
                            <tr class="hover:bg-slate-50 dark:hover:bg-dark-bg transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-ui-primary dark:text-white">{{
                                            $material->inventoryItem->name }}</span>
                                        <span
                                            class="text-[10px] text-ui-muted font-bold uppercase tracking-wider mt-1">{{
                                            $material->inventoryItem->category }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm font-black text-ui-primary dark:text-white">{{
                                    $material->quantity_dispatched }} {{ $material->inventoryItem->unit }}</td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $material->status === 'Delivered' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $material->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-sm font-bold text-ui-muted">{{ $material->delivery_date ?
                                    $material->delivery_date->format('M d, Y') : 'Pending' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4"
                                    class="px-8 py-20 text-center font-bold text-xs text-ui-muted uppercase tracking-[3px]">
                                    No materials logged yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments Content -->
        <div x-show="activeTab === 'payments'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div
                class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-ui-border shadow-premium overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 dark:bg-dark-bg border-b border-ui-border">
                    <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Payment Ledger</h3>
                    <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Tracking advance
                        payments & transactions</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-black text-ui-muted uppercase tracking-[2px] border-b border-ui-border">
                                <th class="px-8 py-5">Date</th>
                                <th class="px-8 py-5">Description</th>
                                <th class="px-8 py-5">Mode</th>
                                <th class="px-8 py-5 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ui-border">
                            @forelse($client->payments as $payment)
                            <tr class="hover:bg-slate-50 dark:hover:bg-dark-bg transition-colors">
                                <td class="px-8 py-6 text-sm font-bold text-ui-primary dark:text-white">{{
                                    $payment->date->format('d M, Y') }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-ui-primary dark:text-white">{{
                                            $payment->purpose ?? 'Project Payment' }}</span>
                                        <span
                                            class="text-[10px] text-ui-muted font-black uppercase tracking-widest mt-1">Ref:
                                            #{{ $payment->reference_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[10px] font-black text-ui-muted uppercase tracking-widest border border-ui-border">
                                        {{ $payment->payment_mode ?? 'Online' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-base font-black text-brand-600">₹{{
                                        number_format($payment->amount) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4"
                                    class="px-8 py-20 text-center font-bold text-xs text-ui-muted uppercase tracking-[3px]">
                                    No transactions recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Portfolio Content -->
        <div x-show="activeTab === 'portfolio'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-black text-ui-primary dark:text-white tracking-tight">Project Portfolio
                        </h3>
                        <p class="text-xs text-ui-muted font-medium mt-1 uppercase tracking-widest">Visual gallery of
                            your site progress</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse($client->galleries as $image)
                    <div
                        class="group relative aspect-[4/3] rounded-3xl overflow-hidden border border-ui-border shadow-sm hover:shadow-xl transition-all">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="{{ $image->caption }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-end">
                            <span class="text-[10px] font-black text-brand-400 uppercase tracking-[2px] mb-1">{{
                                $image->type }}</span>
                            <p class="text-white text-xs font-bold leading-snug">{{ $image->caption }}</p>
                        </div>
                    </div>
                    @empty
                    <div
                        class="col-span-full py-20 text-center bg-slate-50 dark:bg-dark-bg rounded-3xl border-2 border-dashed border-ui-border">
                        <p class="text-xs font-black text-ui-muted uppercase tracking-widest">Portfolio is being
                            curated.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Handover Content -->
        <div x-show="activeTab === 'handover'" class="space-y-8 animate-in slide-in-from-bottom duration-500" x-cloak>
            @if($client->handover && $client->handover->status === 'completed')
            <div
                class="relative overflow-hidden bg-slate-900 text-white p-8 rounded-[2.5rem] border border-slate-700 shadow-2xl">
                <div class="absolute top-0 right-0 p-10 opacity-10">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-xs font-black text-green-400 uppercase tracking-[3px]">Warranty
                                Active</span>
                        </div>
                        <h2 class="text-3xl font-black tracking-tight mb-2">Service Warranty Certificate</h2>
                        <p class="text-slate-400 font-medium max-w-md">This project is covered under our premium service
                            warranty against manufacturing defects.</p>
                        <div class="mt-8 grid grid-cols-2 gap-8">
                            <div>
                                <span
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1">Valid
                                    Until</span>
                                <span class="text-xl font-mono font-bold">{{
                                    $client->handover->warranty_expiry->format('d M, Y') }}</span>
                            </div>
                            <div>
                                <span
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1">Coverage</span>
                                <span class="text-xl font-bold">{{ $client->handover->warranty_years }} Years</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 text-center">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-300 block mb-2">Reference
                            ID</span>
                        <div class="font-mono text-2xl font-bold tracking-wider">{{ $client->file_number }}</div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-amber-800">Handover in Progress</h3>
                    <p class="text-sm text-amber-700">The warranty certificate will be generated once the official
                        handover is complete.</p>
                </div>
            </div>
            @endif

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                    <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">
                        Handover Checklist</h3>
                    @if($client->handover)
                    <div class="space-y-3">
                        @forelse($client->handover->checklistItems as $item)
                        <div
                            class="flex items-center p-4 rounded-2xl bg-slate-50 dark:bg-dark-bg border border-ui-border">
                            <span
                                class="w-5 h-5 flex-shrink-0 flex items-center justify-center rounded-lg {{ $item->is_completed ? 'bg-ui-success text-white' : 'border-2 border-slate-200' }}">
                                @if($item->is_completed) ✓ @endif
                            </span>
                            <span
                                class="ml-3 text-sm font-bold text-ui-primary dark:text-white {{ $item->is_completed ? 'line-through opacity-50' : '' }}">{{
                                $item->item_name }}</span>
                        </div>
                        @empty
                        <p class="text-xs text-ui-muted font-bold uppercase tracking-widest text-center py-8">Checklist
                            being prepared.</p>
                        @endforelse
                    </div>
                    @else
                    <p class="text-xs text-ui-muted font-bold uppercase tracking-widest text-center py-8">Handover
                        process not started.</p>
                    @endif
                </div>

                <div class="bg-white dark:bg-dark-surface p-8 rounded-[2.5rem] border border-ui-border shadow-premium">
                    <h3 class="text-lg font-black text-ui-primary dark:text-white uppercase tracking-tight mb-6">Your
                        Feedback</h3>
                    @if($client->feedback)
                    <div class="text-center py-8">
                        <div class="inline-flex gap-1 text-amber-400 mb-4">
                            @for($i=1; $i<=5; $i++) <svg
                                class="w-8 h-8 {{ $i <= $client->feedback->rating ? 'fill-current' : 'text-slate-200' }}"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>@endfor
                        </div>
                        <p class="text-ui-primary dark:text-white font-medium italic">"{{ $client->feedback->comment }}"
                        </p>
                        <p class="mt-4 text-xs font-black text-brand-600 uppercase tracking-widest">Submitted on {{
                            $client->feedback->created_at->format('d M, Y') }}</p>
                    </div>
                    @else
                    <form action="{{ route('feedback.store', $client) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-black uppercase text-ui-muted mb-2 tracking-widest">Rate
                                your experience</label>
                            <div class="flex flex-row-reverse justify-end gap-2 group">
                                @for($i=5; $i>=1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}"
                                    class="peer/{{ $i }} hidden" {{ $i==5 ? 'checked' : '' }} />
                                <label for="star{{ $i }}"
                                    class="cursor-pointer text-slate-200 peer-checked/{{ $i }}:text-amber-400 hover:text-amber-400 peer-hover/{{ $i }}:text-amber-400 transition-colors">
                                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                </label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase text-ui-muted mb-2 tracking-widest">Comments
                                (Optional)</label>
                            <textarea name="comment" rows="3"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-500"
                                placeholder="How was your journey with us?"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-brand-500/20 transition-all transform active:scale-95">Submit
                            Review</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-slate-800 px-8 py-4 border-t border-gray-200 dark:border-slate-700 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            For support or queries, please contact your project manager. <br>
            Reference ID: #{{ $client->uuid }}
        </p>
    </div>
</div>
@endsection