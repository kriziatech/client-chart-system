<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Handover;
use App\Models\HandoverChecklistItem;
use App\Models\ProjectFeedback;
use Illuminate\Http\Request;

class HandoverController extends Controller
{
    /**
     * Store Handover Checklist Item
     */
    public function storeChecklistItem(Request $request, Handover $handover)
    {
        $request->validate(['item_name' => 'required|string']);

        HandoverChecklistItem::create([
            'handover_id' => $handover->id,
            'item_name' => $request->item_name
        ]);

        return back()->with('success', 'Item added to handover checklist.');
    }

    /**
     * Update Handover Checklist Status (Client)
     */
    public function updateChecklistStatus(Request $request, HandoverChecklistItem $item)
    {
        $item->update(['is_completed' => !$item->is_completed]);
        return back();
    }

    /**
     * Finalize Handover & Generate Warranty
     */
    public function completeHandover(Request $request, Client $client)
    {
        $handover = $client->handover ?? Handover::create(['client_id' => $client->id]);

        $request->validate([
            'warranty_years' => 'required|integer|min:1',
            'client_signature' => 'required|string'
        ]);

        $handover->update([
            'status' => 'completed',
            'handover_date' => now(),
            'warranty_years' => $request->warranty_years,
            'warranty_expiry' => now()->addYears($request->warranty_years),
            'client_signature' => $request->client_signature
        ]);

        // Mark client/project as completed? 
        // $client->update(['status' => 'Completed']);

        return back()->with('success', 'Project handover completed! Warranty active.');
    }

    /**
     * Submit Project Feedback
     */
    public function storeFeedback(Request $request, Client $client)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        ProjectFeedback::updateOrCreate(
        ['client_id' => $client->id],
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending'
        ]
        );

        return back()->with('success', 'Thank you for your feedback!');
    }
}