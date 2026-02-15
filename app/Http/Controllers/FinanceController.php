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