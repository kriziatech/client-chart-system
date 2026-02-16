<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class PaymentRequestController extends Controller
{
    public function index()
    {
        $query = PaymentRequest::with(['client', 'quotation'])->latest();

        if (auth()->user()->isViewer()) {
            $clientIds = Client::where('user_id', auth()->id())->pluck('id');
            $query->whereIn('client_id', $clientIds);
        }

        $requests = $query->paginate(15);
        return view('payment-requests.index', compact('requests'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('first_name')->get();
        $selectedClientId = $request->query('client_id');
        $quotations = [];

        if ($selectedClientId) {
            $quotations = \App\Models\Quotation::where('client_id', $selectedClientId)->whereIn('status', ['approved', 'accepted'])->get();
        }

        return view('payment-requests.create', compact('clients', 'selectedClientId', 'quotations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'amount' => 'required|numeric|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        PaymentRequest::create($validated);

        return redirect()->route('clients.show', $request->client_id)
            ->with('success', 'Payment request sent to client successfully.');
    }

    public function updateStatus(Request $request, PaymentRequest $paymentRequest)
    {
        if (auth()->user()->isViewer() && $paymentRequest->client->user_id !== auth()->id()) {
            abort(403);
        }
        $oldStatus = $paymentRequest->status;
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $paymentRequest->update(['status' => $request->status]);

        // Audit Log Entry
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => ucfirst($request->status),
            'module' => 'PaymentRequest',
            'model_type' => get_class($paymentRequest),
            'model_id' => $paymentRequest->id,
            'description' => "Payment Request \"{$paymentRequest->title}\" was marked as {$request->status}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'status' => 'success',
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        // If marked as paid, we could optionally create a ledger entry in Payment model
        if ($request->status === 'paid') {
            $receiptNumber = 'REC-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

            \App\Models\Payment::create([
                'client_id' => $paymentRequest->client_id,
                'receipt_number' => $receiptNumber,
                'name' => auth()->user()->name,
                'amount' => $paymentRequest->amount,
                'date' => now(),
                'purpose' => "Against Request: " . $paymentRequest->title,
                'payment_method' => 'Bank/Transfer', // Default for portal requests
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(PaymentRequest $paymentRequest)
    {
        if (auth()->user()->isViewer() && $paymentRequest->client->user_id !== auth()->id()) {
            abort(403);
        }
        $clientId = $paymentRequest->client_id;
        $paymentRequest->delete();
        return redirect()->route('clients.show', $clientId)->with('success', 'Request deleted.');
    }
}