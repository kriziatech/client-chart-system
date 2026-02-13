@extends('layouts.app')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700 print:p-0">
    <!-- Top Action Bar (Non-Printable) -->
    <div class="max-w-5xl mx-auto mb-8 no-print">
        <div
            class="bg-white dark:bg-dark-surface rounded-3xl border border-ui-border dark:border-dark-border shadow-premium overflow-hidden">
            <div
                class="px-8 py-5 flex justify-between items-center bg-slate-50/50 dark:bg-dark-bg/50 border-b border-ui-border dark:border-dark-border">
                <div class="flex items-center gap-4">
                    <h2 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white">Proposal
                        Review (Ref #{{ $quotation->quotation_number }})</h2>
                    <span
                        class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                        {{ $quotation->status === 'approved' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' : 
                           ($quotation->status === 'rejected' ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10' : 
                           ($quotation->status === 'sent' ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10' : 'bg-slate-50 text-slate-500 dark:bg-slate-500/10')) }}">
                        {{ $quotation->status }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    @if(auth()->user()->role === 'viewer' && $quotation->status === 'sent')
                    <button onclick="approveQuotation()"
                        class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-700 transition-all shadow-lg shadow-brand-500/20 active:scale-95">
                        Approve & Signature
                    </button>
                    @endif

                    @if(!auth()->user()->isViewer() && $quotation->status === 'draft')
                    <form action="{{ route('quotations.updateStatus', $quotation) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="sent">
                        <button type="submit"
                            class="px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 active:scale-95 transition-all">
                            Release to Client
                        </button>
                    </form>
                    @endif

                    <button onclick="shareQuotation()"
                        class="px-5 py-2.5 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 017.008 2.899 9.825 9.825 0 012.879 7.03c-.002 5.45-4.437 9.884-9.89 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z">
                            </path>
                        </svg>
                        WhatsApp
                    </button>

                    <button onclick="window.print()"
                        class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-ui-muted">Client Account</p>
                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $quotation->client->first_name }} {{
                        $quotation->client->last_name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-ui-muted">Issued Valuation</p>
                    <p class="text-sm font-black text-brand-600">₹{{ number_format($quotation->total_amount, 2) }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-ui-muted">Validation End</p>
                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $quotation->valid_until ?
                        $quotation->valid_until->format('d M, Y') : 'Rolling' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Printable Quotation (Paper Style) -->
    <div
        class="max-w-5xl mx-auto bg-white p-16 shadow-2xl print:shadow-none print:p-0 min-h-[1100px] border border-slate-100 flex flex-col font-['Roboto'] scale-[0.98] origin-top transition-transform hover:scale-100 duration-500">
        <!-- Brand Header -->
        <div class="flex justify-between items-start mb-16">
            <div class="space-y-2">
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">{{ config('app.name') }}</h1>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Project Charter & Valuation
                </p>
                <div class="pt-4 text-xs font-bold text-slate-600 space-y-1">
                    <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                        Contact: +91 91155 00057</p>
                    <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> URL:
                        www.interiortouch.in</p>
                </div>
            </div>
            <div class="text-right">
                <div class="bg-slate-900 text-white px-6 py-3 inline-block rounded-2xl mb-6">
                    <h2 class="text-2xl font-black uppercase tracking-widest">Quotation</h2>
                </div>
                <div class="text-xs font-bold space-y-1.5">
                    <p><span class="text-slate-400 uppercase tracking-widest mr-2">Serial ID:</span> <span
                            class="text-slate-900 font-black">#{{ $quotation->quotation_number }}</span></p>
                    <p><span class="text-slate-400 uppercase tracking-widest mr-2">Emission:</span> <span
                            class="text-slate-900">{{ $quotation->date->format('d M, Y') }}</span></p>
                    <p><span class="text-slate-400 uppercase tracking-widest mr-2">Validity:</span> <span
                            class="text-slate-900">{{ $quotation->valid_until ? $quotation->valid_until->format('d M,
                            Y') : '30 Calendar Days' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Stakeholders -->
        <div class="grid grid-cols-2 gap-16 mb-16">
            <div class="p-8 bg-slate-50 rounded-3xl">
                <h4
                    class="text-[10px] font-black text-brand-600 uppercase tracking-widest mb-4 border-b border-brand-100 pb-2">
                    Issued To (Stakeholder)</h4>
                <div class="space-y-1">
                    <p class="text-lg font-black text-slate-900">{{ $quotation->client->first_name }} {{
                        $quotation->client->last_name }}</p>
                    <p class="text-xs font-bold text-slate-500">Dossier Ref: #{{ $quotation->client->file_number }}</p>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed mt-2">{{ $quotation->client->address }}
                    </p>
                    <p class="text-xs font-black text-slate-900 mt-2">M: {{ $quotation->client->mobile }}</p>
                </div>
            </div>
            <div class="py-8">
                <h4
                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                    Scope Summary</h4>
                <p class="text-xs font-medium text-slate-700 leading-relaxed italic pr-4">{{
                    $quotation->client->work_description ?: 'No specific project summary provided in primary charter.'
                    }}</p>
            </div>
        </div>

        <!-- Ledger Items -->
        <div class="flex-grow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">
                        <th class="px-5 py-4 first:rounded-l-2xl">Ref</th>
                        <th class="px-5 py-4">Detailed Description</th>
                        <th class="px-5 py-4 text-center">Unit</th>
                        <th class="px-5 py-4 text-right">Qty</th>
                        <th class="px-5 py-4 text-right">Base Rate</th>
                        <th class="px-5 py-4 text-right last:rounded-r-2xl">Net Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($quotation->items as $index => $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-6 text-[10px] font-bold text-slate-400 font-mono">{{ sprintf('%02d', $index +
                            1) }}</td>
                        <td class="px-5 py-6">
                            <p class="text-sm font-black text-slate-900 leading-tight">{{ $item->description }}</p>
                            <span
                                class="inline-block mt-1.5 text-[9px] font-black uppercase tracking-widest text-slate-400 border border-slate-100 px-2 py-0.5 rounded leading-none">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td
                            class="px-5 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                            {{ $item->unit ?: 'pcs' }}
                        </td>
                        <td class="px-5 py-6 text-right text-sm font-bold text-slate-700">
                            {{ round($item->quantity, 2) }}
                        </td>
                        <td class="px-5 py-6 text-right text-sm font-bold text-slate-700">₹{{ number_format($item->rate)
                            }}</td>
                        <td class="px-5 py-6 text-right text-sm font-black text-slate-900">₹{{
                            number_format($item->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Valuation Summary -->
        <div class="mt-16 flex justify-between items-start pt-12 border-t-2 border-slate-900 border-dotted">
            <div class="w-1/2">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6">Terms & Mutual
                    Indemnity</h4>
                <div class="text-[11px] font-medium text-slate-500 leading-loose italic pr-12 space-y-2">
                    {!! nl2br(e($quotation->notes ?: "1. Advance: 50% required upon mobilization.\n2. Taxes: GST as per
                    statutory norms.\n3. Alterations: Deviations from BOQ billed at standard rates.")) !!}
                </div>
            </div>
            <div class="w-5/12 space-y-4">
                <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                    <span class="uppercase tracking-widest">Consolidated Base</span>
                    <span class="text-slate-900">₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center bg-slate-50 px-6 py-3 rounded-2xl">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">GST Levy (18.0)</span>
                    <span class="text-xs font-black text-slate-900">₹{{ number_format($quotation->tax_amount, 2)
                        }}</span>
                </div>
                <div class="flex justify-between items-center pt-6">
                    <span class="text-[11px] font-black text-brand-600 uppercase tracking-[0.2em]">Grand
                        Valuation</span>
                    <span class="text-3xl font-black text-brand-600 tracking-tighter">₹{{
                        number_format($quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Authorization Block -->
        <div class="mt-24 flex justify-between items-end">
            <div class="w-48 text-center space-y-6">
                <!-- Accept Signature Area -->
                <div class="h-16 flex items-end justify-center">
                    @if($quotation->signature_data)
                    <img src="{{ $quotation->signature_data }}" class="max-h-full opacity-80" alt="Client Signature">
                    @else
                    <div class="w-32 border-b border-slate-200 border-dotted"></div>
                    @endif
                </div>
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Client Endorsement</p>
                </div>
            </div>
            <div class="w-64 text-center">
                <p class="text-[11px] font-black text-slate-400 italic mb-8">Generated by InteriorTouch ERP</p>
                <div class="pt-6 border-t-2 border-slate-900">
                    <p class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Authorized Signatory</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shareQuotation() {
        const quoteNum = '{{ $quotation->quotation_number }}';
        const clientName = '{{ $quotation->client->first_name }} {{ $quotation->client->last_name }}';
        const total = '₹{{ number_format($quotation->total_amount, 2) }}';
        const link = window.location.href;

        const message = `*InteriorTouch - Proposal Notification*\n\n` +
            `Ref ID: ${quoteNum}\n` +
            `Client: ${clientName}\n` +
            `Evaluation: ${total}\n\n` +
            `Review detailed project charter here:\n${link}`;

        window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
    }

    let canvas, ctx, drawing = false;

    function approveQuotation() {
        document.getElementById('signatureModal').classList.remove('hidden');
        document.getElementById('signatureModal').classList.add('flex');
        initCanvas();
    }

    function closeModal() {
        document.getElementById('signatureModal').classList.add('hidden');
        document.getElementById('signatureModal').classList.remove('flex');
    }

    function initCanvas() {
        canvas = document.getElementById('signaturePad');
        ctx = canvas.getContext('2d');

        // Handle resize/retina
        const ratio = window.devicePixelRatio || 1;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);

        ctx.strokeStyle = '#023E8A';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';

        const getPos = (e) => {
            const rect = canvas.getBoundingClientRect();
            return {
                x: (e.clientX || e.touches[0].clientX) - rect.left,
                y: (e.clientY || e.touches[0].clientY) - rect.top
            };
        };

        const start = (e) => { drawing = true; draw(e); };
        const end = () => { drawing = false; ctx.beginPath(); };
        const draw = (e) => {
            if (!drawing) return;
            e.preventDefault();
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        };

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('touchstart', start);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('mouseup', end);
        canvas.addEventListener('touchend', end);
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function submitSignature() {
        const signatureData = canvas.toDataURL('image/png');
        const form = document.getElementById('approvalForm');
        document.getElementById('signatureInput').value = signatureData;
        form.submit();
    }
</script>

<!-- Signature Modal -->
<div id="signatureModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
    <div
        class="bg-white dark:bg-dark-surface w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden border border-ui-border">
        <div class="p-10">
            <h3 class="text-2xl font-black text-ui-primary dark:text-white tracking-tight mb-2">Authorize Proposal</h3>
            <p class="text-sm text-ui-muted mb-8">Please provide your digital endorsement below to initialize
                mobilization.</p>

            <div class="bg-slate-50 dark:bg-dark-bg rounded-2xl border-2 border-dashed border-ui-border p-4">
                <canvas id="signaturePad" class="w-full h-48 bg-transparent cursor-crosshair touch-none"></canvas>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" onclick="clearSignature()"
                    class="text-[10px] font-black uppercase text-rose-500 hover:underline tracking-widest">Clear
                    Canvas</button>
                <div class="flex gap-3">
                    <button onclick="closeModal()"
                        class="px-6 py-3 text-xs font-black uppercase tracking-widest text-ui-muted hover:text-ui-primary transition-colors">Abort</button>
                    <button onclick="submitSignature()"
                        class="px-8 py-3 bg-brand-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-brand-500/20 hover:bg-brand-700 transition-all active:scale-95">Confirm
                        & Sign</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="approvalForm" action="{{ route('quotations.updateStatus', $quotation) }}" method="POST" class="hidden">
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="approved">
    <input type="hidden" name="signature_data" id="signatureInput">
</form>

<style>
    @font-face {
        font-family: 'Roboto';
        src: url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: white !important;
        }

        .min-h-screen {
            background: white !important;
            px: 0 !important;
        }

        .no-print {
            display: none !important;
        }

        .print\:shadow-none {
            box-shadow: none !important;
        }

        .print\:p-0 {
            padding: 0 !important;
        }

        .scale-\[0\.98\] {
            scale: 1 !important;
            transform: none !important;
        }
    }
</style>
@endsection