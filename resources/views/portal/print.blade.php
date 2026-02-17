<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Dispatch: {{ $client->first_name }} {{ $client->last_name }} | Interior Touch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#CAF0F8',
                            100: '#ADE8F4',
                            200: '#90E0EF',
                            300: '#48CAE4',
                            400: '#00B4D8',
                            500: '#0096C7',
                            600: '#0077B6',
                            700: '#023E8A',
                            800: '#03045E',
                            900: '#020344',
                            950: '#010222',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 12mm 15mm;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: white !important;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased leading-relaxed p-0">

    <!-- Official Header -->
    <div class="border-b-[6px] border-slate-900 pb-12 mb-14 flex justify-between items-end">
        <div class="space-y-3">
            <h1 class="text-5xl font-[900] uppercase tracking-tighter text-slate-900">Interior Touch</h1>
            <div class="flex items-center gap-3">
                <div class="h-4 w-1 bg-brand-600"></div>
                <p class="text-[11px] font-[800] text-brand-600 uppercase tracking-[0.5em]">All-in-One Business
                    Operating System</p>
            </div>
        </div>
        <div class="text-right space-y-1">
            <div class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Project Dossier</div>
            <div
                class="text-xl font-black text-slate-900 font-mono tracking-tight bg-slate-100 px-4 py-1 rounded-lg inline-block">
                #{{ $client->file_number ?: 'PROJ-'.strtoupper(substr($client->uuid, 0, 8)) }}
            </div>
            <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest pt-3">Issued: {{ now()->format('d
                F, Y | H:i') }}</div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="grid grid-cols-12 gap-12 mb-20">
        <div class="col-span-7 space-y-10">
            <div>
                <span
                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] block mb-3 border-l-2 border-brand-500 pl-3">Principal
                    Stakeholder</span>
                <h2 class="text-4xl font-[900] text-slate-900 tracking-tight">{{ $client->first_name }} {{
                    $client->last_name }}</h2>
                <p class="text-[13px] font-bold text-slate-500 mt-2 uppercase tracking-widest">{{ $client->address ?:
                    'Location Confidential' }}</p>
            </div>

            <div class="pt-8 border-t border-slate-100">
                <span
                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] block mb-4 border-l-2 border-brand-500 pl-3">Operational
                    Manifesto</span>
                <p
                    class="text-[15px] font-medium text-slate-700 leading-relaxed italic border-l-4 border-slate-900 pl-8 bg-slate-50/50 py-4 pr-4">
                    "{{ $client->work_description ?: 'Initial operational parameters under review. System awaiting full
                    architectural description.' }}"
                </p>
            </div>
        </div>

        <div class="col-span-5 flex flex-col justify-between">
            <div class="bg-slate-900 p-8 rounded-[32px] text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] block mb-6">Completion
                    Quotient</span>
                @php
                $total = $client->tasks->count();
                $completed = $client->tasks->where('status', 'Completed')->count();
                $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
                @endphp
                <div class="text-7xl font-[900] text-white tracking-tighter mb-4">{{ $percent }}<span
                        class="text-brand-500 text-4xl">%</span></div>
                <div class="w-full bg-slate-800 rounded-full h-2.5 mb-4 p-[1px]">
                    <div class="bg-brand-500 h-full rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">{{ $completed }} /
                    {{ $total }} Phases Verified</span>
            </div>

            <div class="grid grid-cols-2 gap-6 mt-8">
                <div class="space-y-2 border-l border-slate-100 pl-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Initialization</span>
                    <p class="text-sm font-black text-slate-900">{{ $client->start_date ?
                        \Carbon\Carbon::parse($client->start_date)->format('d M, Y') : 'TBD' }}</p>
                </div>
                <div class="space-y-2 border-l border-brand-500 pl-4 text-right">
                    <span class="text-[10px] font-black text-brand-600 uppercase tracking-widest">Target horizon</span>
                    <p class="text-sm font-black text-slate-900">{{ $client->delivery_date ?
                        \Carbon\Carbon::parse($client->delivery_date)->format('d M, Y') : 'TBD' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Phase Progression Table -->
    <div class="mb-20">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.4em] mb-8 flex items-center gap-6">
            Structural Roadmap
            <div class="flex-grow h-[2px] bg-slate-900"></div>
        </h3>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] border-b-2 border-slate-900">
                    <th class="py-5 px-4 bg-slate-50">Operation Axis</th>
                    <th class="py-5 px-6 w-56 text-center bg-slate-50">Deadline Horizon</th>
                    <th class="py-5 px-6 text-right bg-slate-50">Status Verified</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-slate-100">
                @forelse($client->tasks->sortBy('deadline') as $task)
                <tr>
                    <td class="py-7 px-4">
                        <div class="text-[16px] font-[800] text-slate-900 tracking-tight leading-tight mb-1">{{
                            $task->description }}</div>
                        <div class="text-[9px] font-bold text-brand-600 uppercase tracking-widest">LOGIC VECTOR REF: {{
                            hash('crc32', $task->id) }}</div>
                    </td>
                    <td class="py-7 px-6 text-center whitespace-nowrap">
                        <span class="text-[13px] font-black text-slate-700 uppercase tracking-tight">{{ $task->deadline
                            ? \Carbon\Carbon::parse($task->deadline)->format('d F, Y') : 'PENDING HORIZON' }}</span>
                    </td>
                    <td class="py-7 px-6 text-right">
                        @php
                        $taskColors = [
                        'Completed' => 'bg-slate-900 text-white',
                        'In Progress' => 'bg-brand-50 text-brand-700 border border-brand-200',
                        'Pending' => 'bg-slate-50 text-slate-400 border border-slate-200',
                        ];
                        $taskColor = $taskColors[$task->status] ?? 'bg-slate-50 text-slate-300';
                        @endphp
                        <span
                            class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-[0.15em] {{ $taskColor }}">
                            {{ $task->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3"
                        class="py-24 text-center text-xs font-black text-slate-300 uppercase tracking-[0.5em] italic">
                        Project Matrix Initialization Pending...
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Milestone Matrix -->
    <div class="page-break"></div>
    <div class="mt-12 pt-16 border-t-4 border-slate-900">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.4em] mb-12 flex items-center gap-6">
            Critical High-Level Milestones
            <div class="flex-grow h-[2px] bg-slate-900"></div>
        </h3>
        <div class="grid grid-cols-2 gap-x-12 gap-y-8">
            @forelse($client->checklistItems as $item)
            <div
                class="flex items-start p-6 border-2 rounded-2xl transition-all {{ $item->is_checked ? 'bg-slate-50 border-slate-900' : 'border-slate-100 opacity-30 grayscale' }}">
                <div
                    class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl border-2 mr-6 {{ $item->is_checked ? 'bg-slate-900 border-slate-900 text-brand-500' : 'border-slate-200 text-transparent' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex flex-col pt-1">
                    <span
                        class="text-[13px] font-[900] uppercase tracking-wider {{ $item->is_checked ? 'text-slate-900' : 'text-slate-400' }}">
                        {{ $item->name }}
                    </span>
                    @if($item->is_checked)
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-2 h-2 rounded-full bg-brand-600"></div>
                        <span class="text-[9px] font-black text-brand-600 uppercase tracking-widest">System Verified &
                            Locked</span>
                    </div>
                    @else
                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-2 italic">Phase
                        Pending Activation</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-2 py-32 text-center border-2 border-dashed border-slate-100 rounded-[40px]">
                <p class="text-slate-300 text-xs font-black uppercase tracking-[0.4em] italic uppercase">No high-level
                    milestones mapped to current project arc.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Verification Footer -->
    <div
        class="mt-24 pt-8 border-t border-slate-100 flex justify-between items-center text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">
        <p>© 2026 Developed By Krizia Technologies @ 2026 Interior Touch Structural Intel Node. All Rights Reserved.</p>
        <p>Verification Checksum: {{ hash('sha256', $client->id . now()) }}</p>
    </div>
</body>

</html>