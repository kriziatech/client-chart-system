<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirement Dossier - {{ $lead->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-print-color-adjust: exact;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
                background: white;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen p-8 lg:p-12">

    <!-- Print Header -->
    <div
        class="max-w-4xl mx-auto bg-white shadow-xl rounded-[2.5rem] overflow-hidden print:shadow-none print:rounded-none">

        <!-- Toolbar -->
        <div class="bg-slate-900 text-white p-4 flex justify-between items-center no-print">
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.index') }}"
                    class="text-slate-400 hover:text-white transition text-xs font-bold uppercase tracking-widest">
                    &larr; Back to Leads
                </a>
            </div>
            <button onclick="window.print()"
                class="bg-brand-500 hover:bg-brand-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Dossier
            </button>
        </div>

        <!-- Document Header -->
        <div class="p-10 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start">
            <div>
                <img src="https://placehold.co/150x50/3b82f6/white?text=INTERIOR+TOUCH" alt="Logo"
                    class="h-10 mb-6 opacity-80 mix-blend-multiply">
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Requirement Dossier</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Document ID: {{
                    strtoupper(substr($lead->offline_uuid, 0, 8)) }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block bg-white border border-slate-200 p-4 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Client Profile</p>
                    <h2 class="text-xl font-bold text-slate-900">{{ $lead->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium">{{ $lead->phone }}</p>
                    <p class="text-sm text-slate-500 font-medium">{{ $lead->email }}</p>
                    <p class="text-xs text-slate-400 mt-2 max-w-[200px]">{{ $lead->location }}</p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="p-10 grid grid-cols-2 gap-10">

            <!-- Property Info -->
            <div class="col-span-2 md:col-span-1 space-y-2">
                <h3
                    class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-100 pb-2 mb-4">
                    Property Identity</h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Project Type</p>
                        <p class="font-bold text-slate-800">{{ $requirements['project_type'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Property Type</p>
                        <p class="font-bold text-slate-800">{{ $requirements['property_type'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Carpet Area</p>
                        <p class="font-bold text-slate-800">{{ $requirements['area'] ?? '—' }} sqft</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Bedrooms</p>
                        <p class="font-bold text-slate-800">{{ $requirements['bedrooms'] ?? '—' }} BHK</p>
                    </div>
                </div>
            </div>

            <!-- Budget & Logistics -->
            <div class="col-span-2 md:col-span-1 space-y-2">
                <h3
                    class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] border-b border-emerald-100 pb-2 mb-4">
                    Budget & Timeline</h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Budget Range</p>
                        <p class="font-bold text-slate-800">{{ $requirements['budget_range'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Flexibility</p>
                        <p class="font-bold text-slate-800">{{ $requirements['budget_flexibility'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Timeline</p>
                        <p class="font-bold text-slate-800">{{ $requirements['timeline'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Expected Start</p>
                        <p class="font-bold text-slate-800">{{ $requirements['start_date'] ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Requirements -->
            <div class="col-span-2 space-y-2 mt-4">
                <h3
                    class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] border-b border-indigo-100 pb-2 mb-4">
                    Detailed Specifications</h3>

                <div class="grid grid-cols-3 gap-6">
                    <!-- Living Room -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Living Room</p>
                        <ul class="space-y-2">
                            @forelse($requirements['living_room_items'] ?? [] as $item)
                            <li class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> {{ $item }}
                            </li>
                            @empty
                            <li class="text-xs text-slate-400 italic">No specific requirements</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Kitchen -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Kitchen</p>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] text-slate-400">Type</p>
                                <p class="text-xs font-bold text-slate-700">{{ $requirements['kitchen_type'] ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400">Finishes</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @forelse($requirements['kitchen_finish'] ?? [] as $finish)
                                    <span
                                        class="text-[10px] px-2 py-0.5 bg-white border rounded text-slate-600 font-medium">{{
                                        $finish }}</span>
                                    @empty
                                    <span class="text-[10px] text-slate-400 italic">None selected</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bathroom -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Bathroom /
                            Utilities</p>
                        <ul class="space-y-2">
                            @forelse($requirements['bathroom_needs'] ?? [] as $item)
                            <li class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> {{ $item }}
                            </li>
                            @empty
                            <li class="text-xs text-slate-400 italic">No specific requirements</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Priorities & Products -->
            <div class="col-span-2 grid grid-cols-2 gap-10 mt-4">
                <div>
                    <h3
                        class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em] border-b border-amber-100 pb-2 mb-4">
                        Key Priorities</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($requirements['priorities'] ?? [] as $prio)
                        <span
                            class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-xs font-bold uppercase tracking-wide">{{
                            $prio }}</span>
                        @empty
                        <span class="text-xs text-slate-400 italic">No priorities listed</span>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h3
                        class="text-[10px] font-black text-violet-600 uppercase tracking-[0.2em] border-b border-violet-100 pb-2 mb-4">
                        Product Requirements</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($requirements['products'] ?? [] as $prod)
                        <span
                            class="px-3 py-1 bg-violet-50 text-violet-700 border border-violet-100 rounded-lg text-xs font-bold uppercase tracking-wide">{{
                            $prod }}</span>
                        @empty
                        <span class="text-xs text-slate-400 italic">No products listed</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if(!empty($requirements['notes']))
            <div class="col-span-2 mt-6">
                <div class="bg-yellow-50/50 border border-yellow-100 p-6 rounded-2xl">
                    <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-2">Disposition Notes
                    </p>
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">{{ $requirements['notes'] }}</p>
                </div>
            </div>
            @endif

        </div>

        <!-- Footer -->
        <div
            class="bg-slate-50 p-8 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-400 uppercase tracking-widest font-medium">
            <p>Generated on {{ now()->format('d M, Y h:i A') }}</p>
            <p>Confidential Document • Internal Use Only</p>
        </div>
    </div>

    <!-- Print Optimization -->
    <style>
        .kb-container {
            max-width: 100%;
            box-shadow: none;
        }
    </style>
</body>

</html>