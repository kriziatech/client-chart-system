<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\InventoryItem;
use App\Models\ProjectMaterial;
use Illuminate\Http\Request;

class ProjectMaterialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity_dispatched' => 'required|numeric|min:0.01',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (auth()->user()->isViewer()) {
            $client = Client::find($request->client_id);
            if ($client && $client->user_id !== auth()->id()) {
                abort(403);
            }
        }

        ProjectMaterial::create($validated);

        return back()->with('success', 'Material dispatch recorded.');
    }

    public function updateStatus(Request $request, ProjectMaterial $material)
    {
        if (auth()->user()->isViewer() && $material->client->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'status' => 'required|in:Stocked,In Use,Consumed'
        ]);

        $material->update(['status' => $request->status]);

        return back()->with('success', 'Material status updated.');
    }

    public function destroy(ProjectMaterial $material)
    {
        if (auth()->user()->isViewer() && $material->client->user_id !== auth()->id()) {
            abort(403);
        }
        $material->delete();
        return back()->with('success', 'Dispatch entry removed.');
    }
}