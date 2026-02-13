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
        $paymentRequest->update(['last_reminder_sent_at' => now()]);

        // In a real app, send Email/SMS here.
        // For now, we just mark it.

        return back()->with('success', 'Automated reminder sent to ' . $paymentRequest->client->first_name);
    }
}