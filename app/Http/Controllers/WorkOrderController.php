<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['client', 'vendor'])->latest()->paginate(15);
        return view('work_orders.index', compact('workOrders'));
    }

    public function create(Request $request)
    {
        $clients = Client::all();
        $vendors = Vendor::all();
        $selectedClient = $request->has('client_id') ?Client::find($request->client_id) : null;

        return view('work_orders.create', compact('clients', 'vendors', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'title' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:draft,sent,accepted,completed,cancelled',
        ]);

        $workOrder = WorkOrder::create($request->all());

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order created successfully.');
    }

    public function show(WorkOrder $workOrder)
    {
        return view('work_orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $clients = Client::all();
        $vendors = Vendor::all();
        return view('work_orders.edit', compact('workOrder', 'clients', 'vendors'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:draft,sent,accepted,completed,cancelled',
        ]);

        $workOrder->update($request->all());

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order updated successfully.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();
        return redirect()->route('work-orders.index')
            ->with('success', 'Work Order deleted.');
    }
}