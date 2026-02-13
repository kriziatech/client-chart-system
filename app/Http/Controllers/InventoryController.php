<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::orderBy('name')->paginate(20);
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'stock_alert_level' => 'nullable|integer|min:0',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Material added to catalog.');
    }

    public function edit(InventoryItem $inventory)
    {
        return view('inventory.edit', ['item' => $inventory]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'stock_alert_level' => 'nullable|integer|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Material updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Material removed.');
    }
}