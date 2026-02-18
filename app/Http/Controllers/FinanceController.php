<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\Vendor;
use App\Models\VendorPayment;
use App\Models\MaterialInward;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Store New Vendor
     */
    public function storeVendor(Request $request)
    {
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Vendor::create($validated);

        return back()->with('success', 'Vendor created successfully.');
    }

    /**
     * Store Vendor Payment
     */
    public function storeVendorPayment(Request $request, Client $client)
    {
        // Permission Check: Only Admin or Editor (Project Manager)
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        // Lock Check
        if ($client->financials->is_locked) {
            return back()->with('error', 'Project financials are LOCKED. Unlock to add payments.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:0',
            'work_type' => 'required|string',
            'payment_date' => 'required|date',
            'payment_mode' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $client->vendorPayments()->create($validated);

        return back()->with('success', 'Vendor payment recorded successfully.');
    }

    /**
     * Store Direct Client Payment
     */
    public function storeClientPayment(Request $request, Client $client)
    {
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'payment_method' => 'required|string',
            'purpose' => 'nullable|string'
        ]);

        $receiptNumber = 'REC-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

        \App\Models\Payment::create([
            'client_id' => $client->id,
            'receipt_number' => $receiptNumber,
            'name' => auth()->user()->name,
            'amount' => $request->amount,
            'date' => $request->date,
            'purpose' => $request->purpose ?? 'Direct Payment',
            'payment_method' => $request->payment_method,
        ]);

        return back()->with('success', 'Client payment recorded successfully.');
    }

    /**
     * Store Material Inward (Bill Entry)
     */
    public function storeMaterialInward(Request $request, Client $client)
    {
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        if ($client->financials->is_locked) {
            return back()->with('error', 'Project financials are LOCKED. Unlock to add material.');
        }

        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'item_name' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'inward_date' => 'required|date',
            'bill_number' => 'nullable|string'
        ]);

        // Auto calculate total
        $validated['total_amount'] = $validated['quantity'] * $validated['rate'];

        $client->materialInwards()->create($validated);

        return back()->with('success', 'Material bill recorded. Don\'t forget to add payment.');
    }

    /**
     * Store Material Payment
     */
    public function storeMaterialPayment(Request $request, Client $client)
    {
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }

        // We allow payments even if locked? No, better strict.
        if ($client->financials->is_locked) {
            return back()->with('error', 'Project financials are LOCKED.');
        }

        $validated = $request->validate([
            'material_inward_id' => 'nullable|exists:material_inwards,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_mode' => 'nullable|string',
            'supplier_name' => 'nullable|string' // If generic payment
        ]);

        $client->materialPayments()->create($validated);

        return back()->with('success', 'Material payment recorded.');
    }

    /**
     * Toggle Profit Lock
     */
    public function toggleLock(Client $client)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admins can lock/unlock projects.');
        }

        $financials = $client->financials;
        $financials->is_locked = !$financials->is_locked;
        $financials->save();

        $status = $financials->is_locked ? 'LOCKED 🔐' : 'UNLOCKED 🔓';
        return back()->with('success', "Project financials are now $status.");
    }

    /**
     * Store Project Expense
     */
    public function storeExpense(Request $request, Client $client)
    {
        if (auth()->user()->isViewer() && $client->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'description' => 'required|string',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create([
            'client_id' => $client->id,
            'description' => $request->description,
            'category' => $request->category,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return back()->with('success', 'Expense logged successfully.');
    }

    /**
     * Download Financial Ledger PDF
     */
    public function downloadLedger(Client $client)
    {
        if (auth()->user()->isViewer() && $client->user_id !== auth()->id()) {
            abort(403);
        }

        // We could use a library like DomPDF, but for now we'll create a print-friendly view
        // that automatically triggers print dialog or looks like a report.
        $client->load(['vendorPayments.vendor', 'materialInwards.payments', 'payments', 'financials']);

        return view('finance.ledger', compact('client'));
    }

    /**
     * View Financial Analytics (Manager)
     */
    public function analytics(Client $client)
    {
        if (auth()->user()->isViewer() && $client->user_id !== auth()->id()) {
            abort(403);
        }
        $totalRevenue = Payment::where('client_id', $client->id)->sum('amount');
        $totalExpenses = Expense::where('client_id', $client->id)->sum('amount');

        // Material costs from site inventory

        $materialCosts = $client->projectMaterials->sum(function ($item) {
            return $item->quantity_dispatched * ($item->inventoryItem->cost_price ?? 0);
        });

        // Vendor payments
        $vendorPayments = $client->vendorPayments->sum('amount');

        $netProfit = $totalRevenue - ($totalExpenses + $materialCosts + $vendorPayments);

        return view('finance.analytics', compact('client', 'totalRevenue', 'totalExpenses', 'materialCosts', 'vendorPayments', 'netProfit'));
    }

    /**
     * Send Payment Reminder
     */
    public function sendReminder(PaymentRequest $paymentRequest)
    {
        if (auth()->user()->isViewer() && $paymentRequest->client->user_id !== auth()->id()) {
            abort(403);
        }
        $paymentRequest->update(['last_reminder_sent_at' => now()]);

        return back()->with('success', 'Automated reminder sent to ' . $paymentRequest->client->first_name);
    }

    /**
     * View Global Finance Summary
     */
    public function summary()
    {
        $clients = Client::with(['expenses', 'projectMaterials.inventoryItem', 'payments'])->get();

        $projectsData = $clients->map(function ($client) {
            $revenue = $client->payments->sum('amount');
            $expenses = $client->expenses->sum('amount');
            $vendorPayments = $client->vendorPayments->sum('amount');
            $materialCosts = $client->projectMaterials->sum(function ($pm) {
                    return $pm->quantity_dispatched * ($pm->inventoryItem->cost_price ?? 0);
                }
                );

                return [
                'client' => $client,
                'revenue' => $revenue,
                'expenses' => $expenses,
                'vendor_payments' => $vendorPayments,
                'material_costs' => $materialCosts,
                'profit' => $revenue - ($expenses + $vendorPayments + $materialCosts)
                ];
            });

        $globalStats = [
            'total_revenue' => $projectsData->sum('revenue'),
            'total_expenses' => $projectsData->sum('expenses'),
            'total_vendor_payments' => $projectsData->sum('vendor_payments'),
            'total_material' => $projectsData->sum('material_costs'),
            'total_profit' => $projectsData->sum('profit'),
        ];

        return view('finance.index', compact('projectsData', 'globalStats'));
    }


    /**
     * Delete Vendor Payment
     */
    public function destroyVendorPayment(Request $request, VendorPayment $payment)
    {
        if (auth()->user()->isViewer() && $payment->client->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'remark' => ['required', 'string', 'min:5'],
            'confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        $payment->update(['deletion_remark' => $validated['remark']]);
        $payment->delete();

        return back()->with('success', 'Vendor payment deleted successfully.');
    }

    /**
     * Delete Material Inward
     */
    public function destroyMaterialInward(Request $request, MaterialInward $inward)
    {
        if (auth()->user()->isViewer() && $inward->client->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'remark' => ['required', 'string', 'min:5'],
            'confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        $inward->update(['deletion_remark' => $validated['remark']]);
        $inward->delete();

        return back()->with('success', 'Material inward record deleted successfully.');
    }

    /**
     * Delete Expense
     */
    public function destroyExpense(Request $request, Expense $expense)
    {
        if (auth()->user()->isViewer() && $expense->client->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'remark' => ['required', 'string', 'min:5'],
            'confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        $expense->update(['deletion_remark' => $validated['remark']]);
        $expense->delete();

        return back()->with('success', 'Expense record deleted successfully.');
    }
}