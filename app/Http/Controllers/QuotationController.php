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
        // Include 'Won' leads because we might want to create a quote for a won lead to convert it
        $leads = Lead::whereNotIn('status', ['Lost'])->get();
        $selectedClientId = $request->get('client_id');
        $selectedLeadId = $request->get('lead_id');
        return view('quotations.boq-builder', compact('clients', 'leads', 'selectedClientId', 'selectedLeadId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'lead_id' => 'nullable|exists:leads,id',
            'project_type' => 'required|string|in:RES,COM',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'gst_percentage' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'nullable|numeric|min:0', // Can be calculated from area * units
            'items.*.area' => 'nullable|numeric|min:0',
            'items.*.no_of_units' => 'nullable|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'items.*.rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['client_id']) && empty($validated['lead_id'])) {
            return back()->withErrors(['client_id' => 'Please select a Client or a Lead.'])->withInput();
        }

        return DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum(function ($item) {
                    $area = $item['area'] ?? 0;
                    $units = $item['no_of_units'] ?? 1;
                    $quantity = ($area > 0) ? ($area * $units) : ($item['quantity'] ?? 0);
                    return $quantity * $item['rate'];
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

                // Generate Custom Quotation Number
                // Format: COMPANY_CODE/CLIENT_CODE/PROJECT_TYPE/DATE/RUNNING_NO
                $companyCode = 'ITPM'; // Hardcoded for now
    
                $clientCode = 'UNKNOWN';
                if (!empty($validated['client_id'])) {
                    $client = Client::find($validated['client_id']);
                    $clientCode = $client ? $client->file_number : 'ERR';
                }
                elseif (!empty($validated['lead_id'])) {
                    $clientCode = 'L-' . $validated['lead_id'];
                }

                $projectType = $validated['project_type'] ?? 'GEN'; // Default to GEN if missing
                $dateStr = date('Ymd');

                // Running Number (Reset daily)
                $todayCount = Quotation::whereDate('created_at', today())->count();
                $runningNo = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

                $customQuotationNumber = sprintf('%s/%s/%s/%s/%s', $companyCode, $clientCode, $projectType, $dateStr, $runningNo);

                // Update with custom format
                $quotation->update([
                    'quotation_number' => $customQuotationNumber
                ]);

                foreach ($validated['items'] as $item) {
                    $area = $item['area'] ?? 0;
                    $units = $item['no_of_units'] ?? 1;
                    $quantity = ($area > 0) ? ($area * $units) : ($item['quantity'] ?? 0);

                    $quotation->items()->create([
                        'description' => $item['description'],
                        'category' => $item['category'],
                        'type' => 'work', // default
                        'unit' => $item['unit'] ?? null,
                        'area' => $area,
                        'no_of_units' => $units,
                        'quantity' => $quantity,
                        'rate' => $item['rate'],
                        'amount' => $quantity * $item['rate'],
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
        // Include 'Won' leads
        $leads = Lead::whereNotIn('status', ['Lost'])->get();
        $quotation->load('items');
        return view('quotations.boq-builder', compact('quotation', 'clients', 'leads'));
    }

    public function update(Request $request, Quotation $quotation)
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
            'items.*.quantity' => 'nullable|numeric|min:0',
            'items.*.area' => 'nullable|numeric|min:0',
            'items.*.no_of_units' => 'nullable|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'items.*.rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'create_version' => 'nullable|boolean'
        ]);

        return DB::transaction(function () use ($validated, $quotation, $request) {
            // Determine if we should create a new version
            // If already sent or approved, or explicitly requested
            $shouldCreateVersion = $request->has('create_version') || $quotation->status !== 'draft';

            $subtotal = collect($validated['items'])->sum(function ($item) {
                    $area = $item['area'] ?? 0;
                    $units = $item['no_of_units'] ?? 1;
                    $quantity = ($area > 0) ? ($area * $units) : ($item['quantity'] ?? 0);
                    return $quantity * $item['rate'];
                }
                );

                $taxAmount = ($subtotal - $validated['discount_amount']) * ($validated['gst_percentage'] / 100);
                $totalAmount = $subtotal - $validated['discount_amount'] + $taxAmount;

                if ($shouldCreateVersion) {
                    $nextVersion = $quotation->version + 1;

                    // Check if next version already exists to prevent duplicate error
                    $existingNextVersion = Quotation::where('quotation_number', $quotation->quotation_number)
                        ->where('version', $nextVersion)
                        ->first();

                    if ($existingNextVersion) {
                        // Update the existing draft version instead of creating a duplicate
                        $newQuotation = $existingNextVersion;
                        $newQuotation->update([
                            'client_id' => $validated['client_id'] ?? $quotation->client_id,
                            'lead_id' => $validated['lead_id'] ?? $quotation->lead_id,
                            'date' => $validated['date'],
                            'valid_until' => $validated['valid_until'] ?? null,
                            'subtotal' => $subtotal,
                            'discount_amount' => $validated['discount_amount'],
                            'gst_percentage' => $validated['gst_percentage'],
                            'tax_amount' => $taxAmount,
                            'total_amount' => $totalAmount,
                            'notes' => $validated['notes'] ?? null,
                            // Status remains whatever it is, usually draft if it was being worked on
                        ]);

                        // Clear items to replace them
                        $newQuotation->items()->delete();
                    }
                    else {
                        // Create new version
                        $newQuotation = Quotation::create([
                            'client_id' => $validated['client_id'] ?? $quotation->client_id,
                            'lead_id' => $validated['lead_id'] ?? $quotation->lead_id,
                            'quotation_number' => $quotation->quotation_number, // Same number, different ID/version
                            'date' => $validated['date'],
                            'valid_until' => $validated['valid_until'] ?? null,
                            'subtotal' => $subtotal,
                            'discount_amount' => $validated['discount_amount'],
                            'gst_percentage' => $validated['gst_percentage'],
                            'tax_amount' => $taxAmount,
                            'total_amount' => $totalAmount,
                            'status' => 'draft', // Reset to draft
                            'version' => $nextVersion,
                            'parent_id' => $quotation->parent_id ?? $quotation->id,
                            'notes' => $validated['notes'] ?? null,
                        ]);
                    }

                    // Add items for the new/updated version
                    foreach ($validated['items'] as $item) {
                        $area = $item['area'] ?? 0;
                        $units = $item['no_of_units'] ?? 1;
                        $quantity = ($area > 0) ? ($area * $units) : ($item['quantity'] ?? 0);

                        $newQuotation->items()->create([
                            'description' => $item['description'],
                            'category' => $item['category'],
                            'type' => 'work',
                            'unit' => $item['unit'] ?? null,
                            'area' => $area,
                            'no_of_units' => $units,
                            'quantity' => $quantity,
                            'rate' => $item['rate'],
                            'amount' => $quantity * $item['rate'],
                        ]);
                    }

                    return redirect()->route('quotations.show', $newQuotation->id)
                        ->with('success', 'Quotation version v' . $newQuotation->version . ' saved successfully.');
                }
                else {
                    // Update existing draft
                    $quotation->update([
                        'client_id' => $validated['client_id'] ?? $quotation->client_id,
                        'lead_id' => $validated['lead_id'] ?? $quotation->lead_id,
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
                        $area = $item['area'] ?? 0;
                        $units = $item['no_of_units'] ?? 1;
                        $quantity = ($area > 0) ? ($area * $units) : ($item['quantity'] ?? 0);

                        $quotation->items()->create([
                            'description' => $item['description'],
                            'category' => $item['category'],
                            'type' => 'work',
                            'unit' => $item['unit'] ?? null,
                            'area' => $area,
                            'no_of_units' => $units,
                            'quantity' => $quantity,
                            'rate' => $item['rate'],
                            'amount' => $quantity * $item['rate'],
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
        try {
            if ($quotation->status !== 'accepted') {
                return back()->with('error', 'Only accepted quotations can be converted to projects.');
            }

            if ($quotation->client_id) {
                // If it's already linked to a client (project), just redirect there
                return redirect()->route('clients.show', $quotation->client_id)
                    ->with('info', 'This quotation is already linked to a project.');
            }

            return DB::transaction(function () use ($quotation) {
                $lead = $quotation->lead;

                if (!$lead) {
                    // If no lead, try to check if we can creating a client from scratch purely from quotation?
                    // For now, fail but with clear message.
                    return back()->with('error', 'Error: No Lead linked to this quotation. Please edit the quotation and assign a Lead first.');
                }

                // Create Project (Client)
                // Split name safely
                $nameParts = explode(' ', $lead->name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $client = Client::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'mobile' => $lead->phone,
                    'email' => $lead->email,
                    'address' => $lead->address ?: $lead->location,
                    'status' => 'Sales', // Initial stage after conversion
                    'user_id' => $lead->assigned_to_id ?: auth()->id(),
                    'start_date' => now(),
                ]);

                // Auto-generate project number
                $client->update(['file_number' => 'P-' . str_pad($client->id, 4, '0', STR_PAD_LEFT)]);

                // Link all quotations of this lead to the new project
                Quotation::where('lead_id', $lead->id)->update(['client_id' => $client->id]);

                // Mark lead as Won
                $lead->update(['status' => 'Won']);

                return redirect()->route('clients.show', $client->id)
                    ->with('success', 'Project created successfully from quotation!');
            });
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Project Conversion Failed: ' . $e->getMessage());
            return back()->with('error', 'Conversion Failed: ' . $e->getMessage());
        }
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