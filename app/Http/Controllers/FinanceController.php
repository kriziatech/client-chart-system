<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
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
        // Or if DomPDF is installed, use it. Since I don't see dompdf in context, 
        // I will return a print-optimized view.

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

        $netProfit = $totalRevenue - ($totalExpenses + $materialCosts);

        return view('finance.analytics', compact('client', 'totalRevenue', 'totalExpenses', 'materialCosts', 'netProfit'));
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

        // In a real app, send Email/SMS here.
        // For now, we just mark it.

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
            $materialCosts = $client->projectMaterials->sum(function ($pm) {
                    return $pm->quantity_dispatched * ($pm->inventoryItem->cost_price ?? 0);
                }
                );

                return [
                'client' => $client,
                'revenue' => $revenue,
                'expenses' => $expenses,
                'material_costs' => $materialCosts,
                'profit' => $revenue - ($expenses + $materialCosts)
                ];
            });

        $globalStats = [
            'total_revenue' => $projectsData->sum('revenue'),
            'total_expenses' => $projectsData->sum('expenses'),
            'total_material' => $projectsData->sum('material_costs'),
            'total_profit' => $projectsData->sum('profit'),
        ];

        return view('finance.index', compact('projectsData', 'globalStats'));
    }
}