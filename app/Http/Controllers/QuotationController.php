<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    public function index()
    {
        $query = Quotation::with('client')->latest();

        if (auth()->user()->role === 'viewer') {
            $clientIds = Client::where('user_id', auth()->id())->pluck('id');
            $query->whereIn('client_id', $clientIds);
        }

        $quotations = $query->paginate(10);
        return view('quotations.index', compact('quotations'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('first_name')->get();
        $selectedClientId = $request->query('client_id');

        // Generate a quotation number Q-YYYYMMDD-RAND
        $quotationNumber = 'Q-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        return view('quotations.create', compact('clients', 'selectedClientId', 'quotationNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quotation_number' => 'required|unique:quotations,quotation_number',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.type' => 'required|in:material,labour,work',
            'items.*.unit' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['rate'];
        }

        // Apply 18% GST by default for construction projects or can be dynamic
        $taxAmount = $subtotal * 0.18;
        $totalAmount = $subtotal + $taxAmount;

        $quotation = Quotation::create([
            'client_id' => $request->client_id,
            'quotation_number' => $request->quotation_number,
            'date' => $request->date,
            'valid_until' => $request->valid_until,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'status' => 'draft'
        ]);

        foreach ($request->items as $itemData) {
            $quotation->items()->create([
                'description' => $itemData['description'],
                'type' => $itemData['type'],
                'unit' => $itemData['unit'],
                'quantity' => $itemData['quantity'],
                'rate' => $itemData['rate'],
                'amount' => $itemData['quantity'] * $itemData['rate'],
            ]);
        }

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        // Restriction for viewers
        if (auth()->user()->role === 'viewer' && $quotation->client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this quotation.');
        }

        $quotation->load(['client', 'items']);
        return view('quotations.show', compact('quotation'));
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $oldStatus = $quotation->status;
        $request->validate([
            'status' => 'required|in:draft,sent,approved,rejected'
        ]);

        // ... (viewer check remains same)
        if (auth()->user()->role === 'viewer') {
            if ($quotation->client->user_id !== auth()->id()) {
                abort(403);
            }
            if (!in_array($request->status, ['approved', 'rejected'])) {
                abort(403, 'Invalid status update for client.');
            }
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'approved' && $request->has('signature_data')) {
            $updateData['signature_data'] = $request->signature_data;
            $updateData['signed_at'] = now();
        }

        $quotation->update($updateData);

        // Custom Log Entry for easier visibility
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => ucfirst($request->status),
            'module' => 'Quotation',
            'model_type' => get_class($quotation),
            'model_id' => $quotation->id,
            'description' => "Quotation #{$quotation->quotation_number} was {$request->status} by " . auth()->user()->name,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'status' => 'success',
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        $message = 'Quotation status updated to ' . ucfirst($request->status);
        if ($request->status === 'approved') {
            $message = 'Quotation approved! You can now proceed with the work.';
        }

        return back()->with('success', $message);
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }
}