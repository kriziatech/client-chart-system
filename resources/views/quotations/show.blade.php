@extends('layouts.app')

@section('content')
<div class="h-full bg-gray-50 dark:bg-gray-900" x-data="quotationShow()">
    <x-journey-header stage="Estimate & Quotation"
        nextStep="{{ $quotation->status === 'accepted' ? 'Convert this approved estimate to a live Project Workspace' : 'Get client approval and signature' }}"
        progress="30" statusColor="{{ $quotation->status === 'accepted' ? 'green' : 'blue' }}" />

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('quotations.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $quotation?->quotation_number }}
                        </h1>
                        <span
                            class="px-2 py-0.5 text-[10px] font-bold bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400 rounded uppercase tracking-wider">
                            v{{ $quotation?->version }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Issued to {{ $quotation?->client?->first_name ?? $quotation?->lead?->name ?? 'Unknown' }} {{
                        $quotation?->client?->last_name ?? '' }} on {{
                        $quotation?->date?->format('M d, Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($quotation->status == 'draft')
                <a href="{{ route('quotations.edit', $quotation->id) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                    Edit Draft
                </a>
                @else
                <a href="{{ route('quotations.edit', $quotation->id) }}"
                    class="px-4 py-2 text-sm font-medium text-brand-600 bg-brand-50 border border-brand-200 rounded-lg hover:bg-brand-100 dark:bg-brand-900/30 dark:text-brand-400 dark:border-brand-800">
                    Revise & Create v{{ $quotation->version + 1 }}
                </a>
                @endif

                @if($quotation->status === 'accepted' && !$quotation->client_id)
                <form action="{{ route('quotations.convertToProject', $quotation->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 text-sm font-black text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        🚀 CREATE PROJECT
                    </button>
                </form>
                @endif

                @if($quotation->status !== 'accepted')
                <button @click="showSignaturePad = true"
                    class="px-6 py-2 text-sm font-bold text-white bg-brand-600 rounded-lg hover:bg-brand-700 shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    Approve & Sign
                </button>
                @endif

                @if(auth()->user()->role->name === 'admin' || auth()->id() === 1)
                <button type="button" @click="openDeleteModal()"
                    class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all border border-rose-100 dark:border-rose-900/30"
                    title="Delete Quote">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left: Main Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Status & High Level Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</p>
                            <span
                                @class([ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1'
                                , 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'=>
                                $quotation->status == 'draft',
                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' =>
                                $quotation->status == 'accepted',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $quotation->status
                                == 'sent',
                                ])>
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Valid Until</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                {{ $quotation->valid_until ? $quotation->valid_until->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-1.173-1.173c2.274-1.275 4.39-2.275 6.347-3.003L12 12V3c0-1.105.895-2 2-2h1">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Value</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                ₹@indian_format($quotation->total_amount)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- BOQ Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <h3
                            class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Detailed Bill of Quantities
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($quotation->items->groupBy('category') as $category => $items)
                        <div
                            class="bg-gray-50/50 dark:bg-gray-900/20 px-4 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                            {{ $category ?: 'General' }}
                        </div>
                        @foreach($items as $item)
                        <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->description }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-tighter">{{
                                    $item->type }} / {{ $item->unit ?: 'Nos' }}</p>
                            </div>
                            <div class="flex items-center gap-8">
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Quantity
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $item->quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Rate</p>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium">
                                        ₹@indian_format($item->rate)</p>
                                </div>
                                <div class="text-right w-24">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Amount</p>
                                    <p class="text-sm text-brand-600 dark:text-brand-400 font-boldital">
                                        ₹@indian_format($item->amount)</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>

                    <!-- Totals Section -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <div class="max-w-xs ml-auto space-y-3">
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span class="font-bold">₹@indian_format($quotation->subtotal)</span>
                            </div>
                            @if($quotation->discount_amount > 0)
                            <div class="flex justify-between text-sm text-emerald-600">
                                <span>Discount</span>
                                <span class="font-bold">-₹@indian_format($quotation->discount_amount)</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>GST ({{ $quotation->gst_percentage }}%)</span>
                                <span class="font-bold">₹@indian_format($quotation->tax_amount)</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-300 dark:border-gray-600">
                                <span class="font-bold text-gray-900 dark:text-white">Total Amount</span>
                                <span
                                    class="font-black text-xl text-brand-600 dark:text-brand-400">₹@indian_format($quotation->total_amount)</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($quotation->notes)
                <div
                    class="bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl p-6">
                    <h4 class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase tracking-widest mb-2">
                        Terms & Notes</h4>
                    <p class="text-sm text-amber-900/70 dark:text-amber-300/70 whitespace-pre-wrap leading-relaxed">{{
                        $quotation->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Right: Metadata & History -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Client Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Client Detail</h4>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center text-brand-700 dark:text-brand-400 font-bold">
                            @if($quotation->client)
                            {{ substr($quotation->client->first_name, 0, 1) }}{{ substr($quotation->client->last_name,
                            0, 1) }}
                            @elseif($quotation->lead)
                            {{ substr($quotation->lead->name, 0, 1) }}
                            @else
                            ?
                            @endif
                        </div>
                        <div>
                            @if($quotation->client)
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $quotation->client->first_name
                                }} {{ $quotation->client->last_name }}</p>
                            <p class="text-xs text-gray-500">#{{ $quotation->client->file_number }}</p>
                            @elseif($quotation->lead)
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $quotation->lead->name }}</p>
                            <p class="text-xs text-gray-500">#{{ $quotation->lead->lead_number }}</p>
                            @else
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Unknown Entity</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Version History -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Version History</h4>
                    <div class="space-y-4">
                        @php
                        $allVersions = collect([$quotation->parent ?: $quotation])
                        ->concat($quotation->parent ? $quotation->parent->versions : $quotation->versions)
                        ->sortByDesc('version');
                        @endphp

                        @foreach($allVersions as $version)
                        <a href="{{ route('quotations.show', $version->id) }}"
                            @class([ 'flex items-center justify-between p-3 rounded-xl transition-all border'
                            , 'bg-brand-50 border-brand-200 dark:bg-brand-900/20 dark:border-brand-800'=> $version->id
                            == $quotation->id,
                            'border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50' => $version->id !=
                            $quotation->id
                            ])>
                            <div>
                                <p @class([ 'text-xs font-bold' , 'text-brand-700 dark:text-brand-400'=> $version->id ==
                                    $quotation->id,
                                    'text-gray-900 dark:text-white' => $version->id != $quotation->id
                                    ])>Version {{ $version->version }}</p>
                                <p class="text-[10px] text-gray-400">{{ $version->created_at->format('M d, Y') }}</p>
                            </div>
                            <span @class([ 'px-1.5 py-0.5 text-[8px] font-black uppercase rounded'
                                , 'bg-green-100 text-green-700'=> $version->status == 'accepted',
                                'bg-gray-100 text-gray-500' => $version->status != 'accepted'
                                ])>
                                {{ $version->status }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>

                @if($quotation->signature_data)
                <!-- Completion Evidence -->
                <div
                    class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl p-6">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-4">
                        Digital Approval</h4>
                    <img src="{{ $quotation->signature_data }}" class="max-h-24 mx-auto dark:invert dark:opacity-80"
                        alt="Signature">
                    <p class="text-[10px] text-emerald-700 dark:text-emerald-500 text-center mt-3 font-medium italic">
                        Signed on {{ $quotation->signed_at->format('M d, Y h:i A') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Signature Pad Modal -->
    <div x-show="showSignaturePad"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl"
            @click.away="showSignaturePad = false">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Draw Signature</h3>
                <button @click="showSignaturePad = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">By signing, you agree to the Bill of Quantities
                    and costings provided above.</p>

                <div
                    class="relative bg-gray-50 dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 h-48">
                    <canvas id="signature-canvas" class="w-full h-full cursor-crosshair"></canvas>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="clearSignature()"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                        Clear
                    </button>
                    <button @click="saveSignature()"
                        class="flex-1 px-4 py-3 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                        Confirm & Sign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for signature submission -->
    <form id="signature-form" action="{{ route('quotations.approve', $quotation->id) }}" method="POST"
        style="display:none">
        @csrf
        <input type="hidden" name="signature_data" id="signature_data_input">
    </form>
</div>

<script>
    function quotationShow() {
        return {
            showSignaturePad: false,
            canvas: null,
            ctx: null,
            drawing: false,

            init() {
                this.$watch('showSignaturePad', value => {
                    if (value) {
                        this.$nextTick(() => {
                            this.initCanvas();
                        });
                    }
                });
            },

            initCanvas() {
                this.canvas = document.getElementById('signature-canvas');
                this.ctx = this.canvas.getContext('2d');

                // Adjust for DPI
                const rect = this.canvas.getBoundingClientRect();
                this.canvas.width = rect.width;
                this.canvas.height = rect.height;

                this.ctx.lineWidth = 3;
                this.ctx.lineJoin = 'round';
                this.ctx.lineCap = 'round';
                this.ctx.strokeStyle = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#ffffff' : '#000000';

                const startDrawing = (e) => {
                    this.drawing = true;
                    const pos = this.getMousePos(e);
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                };

                const draw = (e) => {
                    if (!this.drawing) return;
                    const pos = this.getMousePos(e);
                    this.ctx.lineTo(pos.x, pos.y);
                    this.ctx.stroke();
                };

                const stopDrawing = () => {
                    this.drawing = false;
                };

                this.canvas.addEventListener('mousedown', startDrawing);
                this.canvas.addEventListener('mousemove', draw);
                this.canvas.addEventListener('mouseup', stopDrawing);
                this.canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e.touches[0]); });
                this.canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e.touches[0]); });
                this.canvas.addEventListener('touchend', stopDrawing);
            },

            getMousePos(e) {
                const rect = this.canvas.getBoundingClientRect();
                return {
                    x: (e.clientX || e.pageX) - rect.left,
                    y: (e.clientY || e.pageY) - rect.top
                };
            },

            clearSignature() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            },

            saveSignature() {
                const dataUrl = this.canvas.toDataURL();
                document.getElementById('signature_data_input').value = dataUrl;
                document.getElementById('signature-form').submit();
            }
        }
    }
</script>
@endsection