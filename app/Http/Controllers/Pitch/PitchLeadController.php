<?php

namespace App\Http\Controllers\Pitch;

use App\Http\Controllers\Controller;
use App\Models\Pitch\PitchLead;
use App\Models\Pitch\PitchLeadActivity;
use App\Models\User;
use App\Models\Pitch\PitchLeadSite;
use App\Models\Pitch\PitchLeadVisit;
use App\Models\Pitch\PitchDesignConcept;
use App\Models\Pitch\PitchDesignAsset;
use App\Models\Pitch\PitchDesignFeedback;
use App\Models\Client;
use App\Notifications\ProjectHandoffNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PitchLeadController extends Controller
{
    public function index()
    {
        $leads = PitchLead::with('assignedTo')->latest()->get();
        return view('pitch.leads.index', compact('leads'));
    }

    public function create()
    {
        $users = User::all();
        return view('pitch.leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'source' => 'nullable|string|max:255',
            'work_description' => 'nullable|string',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $lead = PitchLead::create($validated);

        PitchLeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'Created',
            'new_status' => 'New',
            'notes' => 'Lead manually entered into system.'
        ]);

        return redirect()->route('pitch.leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(PitchLead $lead)
    {
        $lead->load(['sites', 'activities.user', 'visits.user', 'assignedTo', 'concepts.assets.feedback.user']);
        return view('pitch.leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, PitchLead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:New,In Progress,Won,Lost,Archived',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $lead->status;
        
        if ($lead->is_converted && auth()->user()->isSales()) {
            return back()->with('error', 'Converted leads are read-only for sales team.');
        }

        $lead->update(['status' => $validated['status']]);

        PitchLeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'Status Updated',
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'notes' => $validated['notes']
        ]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function storeSite(Request $request, PitchLead $lead)
    {
        if ($lead->is_converted && auth()->user()->isSales()) {
            return back()->with('error', 'Converted leads are read-only for sales team.');
        }

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'plot_size' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $lead->sites()->create($validated);

        PitchLeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'Site Added',
            'notes' => 'New operational site added: ' . $validated['address']
        ]);

        return back()->with('success', 'Site added successfully.');
    }

    public function storeVisit(Request $request, PitchLead $lead)
    {
        if ($lead->is_converted && auth()->user()->isSales()) {
            return back()->with('error', 'Converted leads are read-only for sales team.');
        }

        $validated = $request->validate([
            'visit_date' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $lead->visits()->create(array_merge($validated, ['user_id' => auth()->id()]));

        PitchLeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'Visit Scheduled',
            'notes' => 'New visit scheduled for ' . $validated['visit_date'] . ': ' . $validated['purpose']
        ]);

        return back()->with('success', 'Visit scheduled successfully.');
    }

    public function convert(PitchLead $lead)
    {
        if ($lead->is_converted) {
            return back()->with('error', 'Lead is already converted.');
        }

        if ($lead->status !== 'Won') {
            return back()->with('error', 'Only "Won" leads can be converted into projects.');
        }

        try {
            return DB::transaction(function () use ($lead) {
                // Mapping Lead to Client
                $client = Client::create([
                    'first_name' => $lead->name,
                    'mobile' => $lead->phone,
                    'address' => $lead->sites()->first()?->address ?? 'Original Lead Address: ' . $lead->address,
                    'work_description' => $lead->work_description,
                    'user_id' => $lead->assigned_to_id,
                ]);

                $lead->update([
                    'is_converted' => true,
                    'converted_at' => now(),
                    'converted_client_id' => $client->id,
                    'status' => 'Converted'
                ]);

                PitchLeadActivity::create([
                    'lead_id' => $lead->id,
                    'user_id' => auth()->id(),
                    'action' => 'Converted to Project',
                    'notes' => 'System conversion to Client ID: ' . $client->id
                ]);

                // Notify Assigned PM if exists
                if ($lead->assignedTo) {
                    $lead->assignedTo->notify(new ProjectHandoffNotification($lead, $client));
                }

                return redirect()->route('clients.show', $client->id)->with('success', 'Lead successfully converted to Project!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Conversion failed: ' . $e->getMessage());
        }
    }

    /**
     * Design Module Methods
     */
    public function storeDesignConcept(Request $request, PitchLead $lead)
    {
        $version = $lead->concepts()->max('version') + 1;
        
        $concept = $lead->concepts()->create([
            'version' => $version,
            'status' => 'Pending',
            'notes' => $request->notes
        ]);

        return back()->with('success', "Design concept revision v{$version} initialized.");
    }

    public function storeDesignAsset(Request $request, PitchDesignConcept $concept)
    {
        $validated = $request->validate([
            'type' => 'required|in:Moodboard,2D Drawing,3D Render,Material Selection',
            'title' => 'required|string|max:255',
            'file' => 'required|file|image|max:10240', // 10MB max
            'description' => 'nullable|string'
        ]);

        $path = $request->file('file')->store('pitch/designs', 'public');

        $asset = $concept->assets()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'file_path' => $path,
            'description' => $validated['description']
        ]);

        return back()->with('success', 'Design asset uploaded successfully.');
    }

    public function storeDesignFeedback(Request $request, PitchDesignAsset $asset)
    {
        $validated = $request->validate(['comment' => 'required|string']);

        $asset->feedback()->create([
            'user_id' => auth()->id(),
            'comment' => $validated['comment']
        ]);

        return back()->with('success', 'Feedback recorded.');
    }

    public function updateDesignStatus(Request $request, PitchDesignConcept $concept)
    {
        $validated = $request->validate(['status' => 'required|in:Approved,Changes Required']);

        $concept->update(['status' => $validated['status']]);

        return back()->with('success', "Design concept status updated to: {$validated['status']}");
    }
}