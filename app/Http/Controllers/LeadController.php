<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;

class LeadController extends Controller
{
    /**
     * Display all leads with stats
     */
    public function index(Request $request)
    {
        $query = Lead::with('assignedTo')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->get();
        $users = User::all();

        // Stats for the top cards
        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'New')->count(),
            'contacted' => Lead::where('status', 'Contacted')->count(),
            'visited' => Lead::where('status', 'Visited')->count(),
            'quote_sent' => Lead::where('status', 'Quote Sent')->count(),
            'won' => Lead::where('status', 'Won')->count(),
            'lost' => Lead::where('status', 'Lost')->count(),
            'needs_attention' => Lead::whereNotIn('status', ['Won', 'Lost'])
            ->where(function ($q) {
            $q->where('last_follow_up_at', '<', now()->subDays(5))
                ->orWhere(function ($q2) {
                $q2->whereNull('last_follow_up_at')
                    ->where('created_at', '<', now()->subDays(5));
            }
            );
        })->count(),
            'total_pipeline_value' => Lead::whereNotIn('status', ['Won', 'Lost'])->sum('budget'),
        ];

        return view('leads.index', compact('leads', 'users', 'stats'));
    }

    /**
     * Store a new lead
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'work_description' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:' . implode(',', Lead::STATUSES),
            'next_follow_up_at' => 'nullable|date',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $validated['offline_uuid'] = (string)\Illuminate\Support\Str::uuid();
        $validated['status'] = $validated['status'] ?? 'New';

        $lead = Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead "' . $lead->name . '" created successfully!');
    }

    /**
     * Update an existing lead
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'work_description' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|date',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully!');
    }

    /**
     * Update lead status (AJAX)
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Lead::STATUSES),
        ]);

        $oldStatus = $lead->status;
        $lead->update([
            'status' => $request->status,
            'last_follow_up_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Lead moved from {$oldStatus} to {$request->status}",
                'lead' => $lead->fresh()->load('assignedTo'),
            ]);
        }

        return redirect()->route('leads.index')->with('success', "Lead moved to {$request->status}");
    }

    /**
     * Add a note to a lead (AJAX)
     */
    public function addNote(Request $request, Lead $lead)
    {
        $request->validate(['note' => 'required|string|max:1000']);

        $metadata = $lead->metadata ?? [];
        $noteHistory = $metadata['note_history'] ?? [];
        $noteHistory[] = [
            'text' => $request->note,
            'by' => auth()->user()->name,
            'at' => now()->format('M d, Y h:i A'),
        ];
        $metadata['note_history'] = $noteHistory;

        $lead->update([
            'notes' => $request->note,
            'metadata' => $metadata,
            'last_follow_up_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'note_history' => $noteHistory,
            ]);
        }

        return redirect()->route('leads.index')->with('success', 'Note added.');
    }

    /**
     * Set follow-up date (AJAX)
     */
    public function setFollowUp(Request $request, Lead $lead)
    {
        $request->validate(['next_follow_up_at' => 'required|date|after:now']);

        $lead->update([
            'next_follow_up_at' => $request->next_follow_up_at,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'next_follow_up_at' => $lead->next_follow_up_at->format('M d, Y h:i A')]);
        }

        return redirect()->route('leads.index')->with('success', 'Follow-up scheduled.');
    }

    /**
     * Save site requirements (AJAX)
     */
    public function saveRequirements(Request $request, Lead $lead)
    {
        $metadata = $lead->metadata ?? [];
        $metadata['requirements'] = $request->input('requirements', []);

        // Also update budget if provided in requirements
        if (isset($metadata['requirements']['budget_range'])) {
            $budgetMap = [
                'below 10 Lakhs' => 800000,
                '10-20 Lakhs' => 1500000,
                '20-40 Lakhs' => 3000000,
                '40-60 Lakhs' => 5000000,
                '60 Lakhs+' => 7500000,
            ];
            if (!$lead->budget && isset($budgetMap[$metadata['requirements']['budget_range']])) {
                $lead->budget = $budgetMap[$metadata['requirements']['budget_range']];
            }
        }

        $lead->update(['metadata' => $metadata]);

        return response()->json([
            'success' => true,
            'message' => 'Requirements saved successfully.',
            'lead' => $lead->fresh()
        ]);
    }

    /**
     * Delete a lead
     */
    public function destroy(Lead $lead)
    {
        $name = $lead->name;
        $lead->delete();

        return redirect()->route('leads.index')->with('success', "Lead \"{$name}\" deleted.");
    }

    /**
     * Sync leads (offline-first support)
     */
    public function sync(Request $request)
    {
        $payload = $request->input('leads', []);
        $syncedIds = [];

        foreach ($payload as $leadData) {
            $lead = Lead::updateOrCreate(
            ['offline_uuid' => $leadData['offline_uuid']],
            [
                'name' => $leadData['name'],
                'email' => $leadData['email'] ?? null,
                'phone' => $leadData['phone'] ?? null,
                'whatsapp' => $leadData['whatsapp'] ?? null,
                'status' => $leadData['status'] ?? 'New',
                'source' => $leadData['source'] ?? null,
                'address' => $leadData['address'] ?? null,
                'location' => $leadData['location'] ?? null,
                'budget' => $leadData['budget'] ?? null,
                'work_description' => $leadData['work_description'] ?? null,
                'notes' => $leadData['notes'] ?? null,
                'metadata' => $leadData['metadata'] ?? [],
                'last_follow_up_at' => isset($leadData['last_follow_up_at']) ? now()->parse($leadData['last_follow_up_at']) : null,
                'next_follow_up_at' => isset($leadData['next_follow_up_at']) ? now()->parse($leadData['next_follow_up_at']) : null,
                'score' => $leadData['score'] ?? 0,
                'temperature' => $leadData['temperature'] ?? 'Warm',
            ]
            );
            $syncedIds[] = $lead->offline_uuid;
        }

        return response()->json([
            'success' => true,
            'synced_uuids' => $syncedIds,
            'all_leads' => Lead::all()
        ]);
    }
    /**
     * Print requirements dossier
     */
    public function printRequirements(Lead $lead)
    {
        $requirements = $lead->metadata['requirements'] ?? [];
        return view('leads.requirements-print', compact('lead', 'requirements'));
    }
}