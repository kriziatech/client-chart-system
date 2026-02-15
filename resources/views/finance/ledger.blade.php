<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Ledger - {{ $client->first_name }} {{ $client->last_name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans p-8 md:p-12">

    {{-- Controls --}}
    <div class="fixed top-4 right-4 flex gap-2 no-print">
        <button onclick="window.print()"
            class="bg-brand-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-brand-700 transition">Print
            / Save PDF</button>
        <button onclick="window.close()"
            class="bg-white text-slate-500 px-6 py-2 rounded-lg font-bold shadow-lg hover:text-slate-700 transition">Close</button>
    </div>

    {{-- Header --}}
    <div class="mb-12 border-b-2 border-slate-900 pb-6">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tight mb-2">Project Ledger</h1>
                <p class="text-slate-500 font-medium">Financial Report & Statement</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ $client->first_name }} {{ $client->last_name }}</div>
                <div class="text-sm text-slate-500">File: {{ $client->file_number }}</div>
                <div class="text-sm text-slate-500">Date: {{ now()->format('d M, Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Executive Summary --}}
    <div class="grid grid-cols-4 gap-6 mb-12">
        <div class="p-6 bg-slate-100 rounded-2xl border border-slate-200">
            <div class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Total Received</div>
            <div class="text-3xl font-black text-slate-900">₹{{ number_format($client->total_client_received) }}</div>
        </div>
        <div class="p-6 bg-slate-100 rounded-2xl border border-slate-200">
            <div class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Total Vendor Paid</div>
            <div class="text-3xl font-black text-rose-600">₹{{ number_format($client->total_vendor_paid) }}</div>
        </div>
        <div class="p-6 bg-slate-100 rounded-2xl border border-slate-200">
            <div class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Total Material Cost</div>
            <div class="text-3xl font-black text-amber-600">₹{{ number_format($client->total_material_cost) }}</div>
        </div>
        <div class="p-6 border-2 border-emerald-500 bg-emerald-50 rounded-2xl">
            <div class="text-xs font-black uppercase tracking-widest text-emerald-600 mb-1">Net Profit</div>
            <div class="text-3xl font-black text-emerald-700">₹{{ number_format($client->real_time_profit) }}</div>
        </div>
    </div>

    {{-- 1. Client Payments Received --}}
    <div class="mb-12">
        <h3 class="text-xl font-bold uppercase tracking-widest border-b border-slate-200 pb-2 mb-4 text-emerald-700">1.
            Client Receipts (Credit)</h3>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 font-bold uppercase text-xs text-slate-500">
                <tr>
                    <th class="p-3 rounded-l-lg">Date</th>
                    <th class="p-3">Reference</th>
                    <th class="p-3 text-right rounded-r-lg">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($client->payments as $pay)
                <tr>
                    <td class="p-3">{{ $pay->payment_date->format('d M, Y') }}</td>
                    <td class="p-3">#PAY-{{ $pay->id }}</td>
                    <td class="p-3 text-right font-bold">₹{{ number_format($pay->amount) }}</td>
                </tr>
                @endforeach
                <tr class="bg-emerald-50 font-bold">
                    <td class="p-3" colspan="2">Total Received</td>
                    <td class="p-3 text-right">₹{{ number_format($client->total_client_received) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- 2. Vendor Payments --}}
    <div class="mb-12">
        <h3 class="text-xl font-bold uppercase tracking-widest border-b border-slate-200 pb-2 mb-4 text-rose-700">2.
            Vendor Payments (Debit)</h3>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 font-bold uppercase text-xs text-slate-500">
                <tr>
                    <th class="p-3 rounded-l-lg">Date</th>
                    <th class="p-3">Vendor</th>
                    <th class="p-3">Work Type</th>
                    <th class="p-3 text-right rounded-r-lg">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($client->vendorPayments as $pay)
                <tr>
                    <td class="p-3">{{ $pay->payment_date->format('d M, Y') }}</td>
                    <td class="p-3 font-bold">{{ $pay->vendor->name ?? 'Unknown' }}</td>
                    <td class="p-3">{{ $pay->work_type }}</td>
                    <td class="p-3 text-right font-bold">₹{{ number_format($pay->amount) }}</td>
                </tr>
                @endforeach
                <tr class="bg-rose-50 font-bold text-rose-900">
                    <td class="p-3" colspan="3">Total Vendor Paid</td>
                    <td class="p-3 text-right">₹{{ number_format($client->total_vendor_paid) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- 3. Material Procurement --}}
    <div class="mb-12">
        <h3 class="text-xl font-bold uppercase tracking-widest border-b border-slate-200 pb-2 mb-4 text-amber-700">3.
            Material Procurement (Debit)</h3>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 font-bold uppercase text-xs text-slate-500">
                <tr>
                    <th class="p-3 rounded-l-lg">Date</th>
                    <th class="p-3">Supplier</th>
                    <th class="p-3">Item Details</th>
                    <th class="p-3 text-right">Qty</th>
                    <th class="p-3 text-right">Rate</th>
                    <th class="p-3 text-right rounded-r-lg">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($client->materialInwards as $inward)
                <tr>
                    <td class="p-3">{{ $inward->inward_date->format('d M, Y') }}</td>
                    <td class="p-3 font-bold">{{ $inward->supplier_name }}</td>
                    <td class="p-3">{{ $inward->item_name }}</td>
                    <td class="p-3 text-right">{{ $inward->quantity + 0 }} {{ $inward->unit }}</td>
                    <td class="p-3 text-right">₹{{ number_format($inward->rate) }}</td>
                    <td class="p-3 text-right font-bold">₹{{ number_format($inward->total_amount) }}</td>
                </tr>
                @endforeach
                <tr class="bg-amber-50 font-bold text-amber-900">
                    <td class="p-3" colspan="5">Total Material Cost</td>
                    <td class="p-3 text-right">₹{{ number_format($client->total_material_cost) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-20 border-t border-slate-300 pt-6 text-center text-xs text-slate-400">
        <p>Generated by Architecture CRM System • {{ now() }}</p>
    </div>

</body>

</html>