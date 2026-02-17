<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $clientId = $request->get('client_id');
        $quotations = Quotation::when($clientId, function ($q) use ($clientId) {
            return $q->where('client_id', $clientId);
        })->with('client')->latest()->get();

        return view('quotations.index', compact('quotations'));
    }

    public function create(Request $request)
    {
        $clients = Client::all();
        $leads = Lead::whereNotIn('status', ['Won', 'Lost'])->get();
        $selectedClientId = $request->get('client_id');
        $selectedLeadId = $request->get('lead_id');
        return view('quotations.boq-builder', compact('clients', 'leads', 'selectedClientId', 'selectedLeadId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'lead_id' => 'nullable|exists:leads,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'gst_percentage' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['client_id']) && empty($validated['lead_id'])) {
            return back()->withErrors(['client_id' => 'Please select a Client or a Lead.'])->withInput();
        }

        return DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum(function ($item) {
                    return $item['quantity'] * $item['rate'];
                }
                );

                $taxAmount = ($subtotal - $validated['discount_amount']) * ($validated['gst_percentage'] / 100);
                $totalAmount = $subtotal - $validated['discount_amount'] + $taxAmount;

                $quotation = Quotation::create([
                    'client_id' => $validated['client_id'] ?? null,
                    'lead_id' => $validated['lead_id'] ?? null,
                    'quotation_number' => 'TEMP-' . Str::uuid(), // Temporary, will be updated immediately
                    'date' => $validated['date'],
                    'valid_until' => $validated['valid_until'] ?? null,
                    'subtotal' => $subtotal,
                    'discount_amount' => $validated['discount_amount'],
                    'gst_percentage' => $validated['gst_percentage'],
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'draft',
                    'version' => 1,
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Update with sequential ID format
                $quotation->update([
                    'quotation_number' => 'Q-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT)
                ]);

                foreach ($validated['items'] as $item) {
                    $quotation->items()->create([
                        'description' => $item['description'],
                        'category' => $item['category'],
                        'type' => 'work', // default
                        'quantity' => $item['quantity'],
                        'rate' => $item['rate'],
                        'amount' => $item['quantity'] * $item['rate'],
                    ]);
                }

                return redirect()->route('quotations.show', $quotation->id)
                    ->with('success', 'Quotation/BOQ created successfully.');
            });
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'items', 'versions']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $clients = Client::all();
        $leads = Lead::whereNotIn('status', ['Won', 'Lost'])->get();
        $quotation->load('items');
        return view('quotations.boq-builder', compact('quotation', 'clients', 'leads'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'gst_percentage' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'create_version' => 'nullable|boolean'
        ]);

        return DB::transaction(function () use ($validated, $quotation, $request) {
            // Determine if we should create a new version
            // If already sent or approved, or explicitly requested
            $shouldCreateVersion = $request->has('create_version') || $quotation->status !== 'draft';

            $subtotal = collect($validated['items'])->sum(function ($item) {
                    return $item['quantity'] * $item['rate'];
                }
                );

                $taxAmount = ($subtotal - $validated['discount_amount']) * ($validated['gst_percentage'] / 100);
                $totalAmount = $subtotal - $validated['discount_amount'] + $taxAmount;

                if ($shouldCreateVersion) {
                    // Create new version
                    $newQuotation = Quotation::create([
                        'client_id' => $quotation->client_id,
                        'quotation_number' => $quotation->quotation_number, // Same number, different ID/version
                        'date' => $validated['date'],
                        'valid_until' => $validated['valid_until'] ?? null,
                        'subtotal' => $subtotal,
                        'discount_amount' => $validated['discount_amount'],
                        'gst_percentage' => $validated['gst_percentage'],
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'status' => 'draft', // Reset to draft
                        'version' => $quotation->version + 1,
                        'parent_id' => $quotation->parent_id ?? $quotation->id,
                        'notes' => $validated['notes'] ?? null,
                    ]);

                    foreach ($validated['items'] as $item) {
                        $newQuotation->items()->create([
                            'description' => $item['description'],
                            'category' => $item['category'],
                            'type' => 'work',
                            'quantity' => $item['quantity'],
                            'rate' => $item['rate'],
                            'amount' => $item['quantity'] * $item['rate'],
                        ]);
                    }

                    return redirect()->route('quotations.show', $newQuotation->id)
                        ->with('success', 'Quotation version v' . $newQuotation->version . ' created successfully.');
                }
                else {
                    // Update existing draft
                    $quotation->update([
                        'date' => $validated['date'],
                        'valid_until' => $validated['valid_until'] ?? null,
                        'subtotal' => $subtotal,
                        'discount_amount' => $validated['discount_amount'],
                        'gst_percentage' => $validated['gst_percentage'],
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'notes' => $validated['notes'],
                    ]);

                    $quotation->items()->delete();
                    foreach ($validated['items'] as $item) {
                        $quotation->items()->create([
                            'description' => $item['description'],
                            'category' => $item['category'],
                            'type' => 'work',
                            'quantity' => $item['quantity'],
                            'rate' => $item['rate'],
                            'amount' => $item['quantity'] * $item['rate'],
                        ]);
                    }

                    return redirect()->route('quotations.show', $quotation->id)
                        ->with('success', 'Quotation updated successfully.');
                }
            });
    }

    public function approve(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'signature_data' => 'required|string', // Base64 signature
        ]);

        $quotation->update([
            'status' => 'accepted',
            'signed_at' => now(),
            'signature_data' => $validated['signature_data'],
        ]);

        return back()->with('success', 'Quotation approved and signed successfully.');
    }

    /**
     * Convert an approved quotation to a Project (Client)
     */
    public function convertToProject(Quotation $quotation)
    {
        if ($quotation->status !== 'accepted') {
            return back()->with('error', 'Only accepted quotations can be converted to projects.');
        }

        if ($quotation->client_id) {
            return redirect()->route('clients.show', $quotation->client_id)
                ->with('info', 'This quotation is already linked to a project.');
        }

        return DB::transaction(function () use ($quotation) {
            $lead = $quotation->lead;

            if (!$lead) {
                return back()->with('error', 'No lead associated with this quotation.');
            }

            // Create Project (Client)
            $client = Client::create([
                'first_name' => explode(' ', $lead->name)[0],
                'last_name' => str_contains($lead->name, ' ') ? substr($lead->name, strpos($lead->name, ' ') + 1) : '',
                'mobile' => $lead->phone,
                'email' => $lead->email,
                'address' => $lead->address ?: $lead->location,
                'status' => 'Sales', // Initial stage after conversion
                'user_id' => $lead->assigned_to_id ?: auth()->id(),
                'start_date' => now(),
            ]);

            // Auto-generate project number
            $client->update(['file_number' => 'P-' . str_pad($client->id, 4, '0', STR_PAD_LEFT)]);

            // Link quotation to the new project
            $quotation->update(['client_id' => $client->id]);

            // Mark lead as Won
            $lead->update(['status' => 'Won']);

            return redirect()->route('clients.show', $client->id)
                ->with('success', 'Project created successfully from quotation!');
        });
    }

    public function destroy(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'remark' => 'required|string|min:5',
            'confirmation' => 'required|string|in:DELETE',
        ], [
            'confirmation.in' => 'Please type "DELETE" to confirm deletion.',
            'remark.required' => 'A deletion remark is mandatory.',
            'remark.min' => 'The remark must be at least 5 characters.',
        ]);

        $quotation->update([
            'deletion_remark' => $validated['remark']
        ]);

        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }
}