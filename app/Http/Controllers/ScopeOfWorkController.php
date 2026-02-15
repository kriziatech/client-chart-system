<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ScopeOfWork;
use App\Models\ScopeItem;
use Illuminate\Http\Request;

class ScopeOfWorkController extends Controller
{
    /**
     * Define Project Scope (Creation)
     */
    public function store(Request $request, Client $client)
    {
        if (auth()->user()->isViewer() && $client->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate(['version_name' => 'required|string']);

        $sow = ScopeOfWork::updateOrCreate(
        ['client_id' => $client->id],
        ['version_name' => $request->version_name, 'exclusions' => $request->exclusions]
        );

        return back()->with('success', 'Project Scope of Work initialized.');
    }

    /**
     * Add Item to Scope
     */
    public function storeItem(Request $request, ScopeOfWork $scope)
    {
        if (auth()->user()->isViewer() && $scope->client->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'area_name' => 'required|string',
            'description' => 'required|string'
        ]);

        $scope->items()->create($request->all());

        return back()->with('success', 'Scope item added.');
    }
}