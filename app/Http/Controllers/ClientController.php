<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $query = Client::latest();

        // If viewer or client, only show their own projects
        if (auth()->user()->isViewer() || auth()->user()->isClient()) {
            $query->where('user_id', auth()->id());
        }

        $clients = $query->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $defaultItems = [
            ['name' => 'Civil Work', 'is_checked' => false],
            ['name' => 'Tiles', 'is_checked' => false],
            ['name' => 'Bathroom', 'is_checked' => false],
            ['name' => 'Doors', 'is_checked' => false],
            ['name' => 'Wardrobe', 'is_checked' => false],
            ['name' => 'Kitchen', 'is_checked' => false],
            ['name' => 'Paint', 'is_checked' => false],
            ['name' => 'Electrical', 'is_checked' => false],
            ['name' => 'Windows', 'is_checked' => false],
            ['name' => 'UPVC', 'is_checked' => false],
        ];

        $client = new Client();
        $client->setRelation('checklistItems', collect($defaultItems)->map(function ($item) {
            return new ChecklistItem($item);
        }));

        $users = \App\Models\User::orderBy('name')->get();

        return view('clients.create', compact('client', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'file_number' => 'nullable|string|unique:clients,file_number',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $client = DB::transaction(function () use ($request) {
            $client = Client::create($request->only([
                'first_name', 'last_name', 'file_number', 'mobile',
                'address', 'work_description', 'start_date', 'delivery_date', 'user_id'
            ]));

            // Auto-generate project number if not provided
            if (empty($client->file_number)) {
                $client->update(['file_number' => 'P-' . str_pad($client->id, 4, '0', STR_PAD_LEFT)]);
            }

            $client->siteInfo()->create($request->input('site_info', []));
            $client->permission()->create($request->input('permission', []));

            // Save Checklist Items
            if ($request->has('checklist_items')) {
                foreach ($request->checklist_items as $itemData) {
                    $itemData['is_checked'] = isset($itemData['is_checked']) ? true : false;
                    $client->checklistItems()->create($itemData);
                }
            }

            // Save Comments
            if ($request->has('comments')) {
                $client->comments()->createMany(array_values($request->input('comments', [])));
            }

            // Save Payments
            if ($request->has('payments')) {
                foreach ($request->payments as $paymentData) {
                    $client->payments()->create($paymentData);
                }
            }

            // Save Tasks
            if ($request->has('tasks')) {
                foreach ($request->tasks as $taskData) {
                    $client->tasks()->create($taskData);
                }
            }

            return $client;
        });

        return redirect()->route('clients.show', $client)->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        // Restriction for viewers and clients
        if ((auth()->user()->isViewer() || auth()->user()->isClient()) && $client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $client->load([
            'user',
            'checklistItems',
            'siteInfo',
            'permission',
            'comments',
            'payments',
            'tasks',
            'scopeOfWork.items',
            'projectMaterials.inventoryItem',
            'galleries',
            'paymentRequests',
            'handover.items',
            'feedback'
        ]);

        // Failsafe: Ensure relationships are collections (not null)
        if (is_null($client->projectMaterials)) {
            $client->setRelation('projectMaterials', collect([]));
        }
        if (is_null($client->paymentRequests)) {
            $client->setRelation('paymentRequests', collect([]));
        }
        if (is_null($client->galleries)) {
            $client->setRelation('galleries', collect([]));
        }

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load(['checklistItems', 'siteInfo', 'permission', 'comments', 'payments', 'tasks', 'scopeOfWork.items', 'projectMaterials.inventoryItem']);
        $users = \App\Models\User::orderBy('name')->get();
        return view('clients.edit', compact('client', 'users'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'file_number' => 'nullable|string|unique:clients,file_number,' . $client->id,
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request, $client) {
            $client->update($request->only([
                'first_name', 'last_name', 'file_number', 'mobile',
                'address', 'work_description', 'start_date', 'delivery_date', 'user_id'
            ]));

            $client->siteInfo()->updateOrCreate([], $request->input('site_info', []));
            $client->permission()->updateOrCreate([], $request->input('permission', []));

            // Sync Checklist Items
            $inputChecklist = $request->input('checklist_items', []);
            $inputChecklistIds = array_filter(array_column($inputChecklist, 'id'));

            // Delete removed items (one by one to trigger audit logs)
            $client->checklistItems()->whereNotIn('id', $inputChecklistIds)->get()->each->delete();

            foreach ($inputChecklist as $row) {
                $row['is_checked'] = isset($row['is_checked']) ? true : false;
                if (isset($row['id']) && $row['id']) {
                    $item = $client->checklistItems()->find($row['id']);
                    if ($item)
                        $item->update(Arr::except($row, ['id']));
                }
                else {
                    $client->checklistItems()->create($row);
                }
            }

            // Sync Comments
            $inputComments = $request->input('comments', []);
            $inputCommentIds = array_filter(array_column($inputComments, 'id'));
            $client->comments()->whereNotIn('id', $inputCommentIds)->get()->each->delete();

            foreach ($inputComments as $row) {
                if (isset($row['id']) && $row['id']) {
                    $item = $client->comments()->find($row['id']);
                    if ($item)
                        $item->update(Arr::except($row, ['id']));
                }
                else {
                    $client->comments()->create($row);
                }
            }

            // Sync Payments
            $inputPayments = $request->input('payments', []);
            $inputPaymentIds = array_filter(array_column($inputPayments, 'id'));
            $client->payments()->whereNotIn('id', $inputPaymentIds)->get()->each->delete();

            foreach ($inputPayments as $row) {
                if (isset($row['id']) && $row['id']) {
                    $item = $client->payments()->find($row['id']);
                    if ($item)
                        $item->update(Arr::except($row, ['id']));
                }
                else {
                    $client->payments()->create($row);
                }
            }

            // Sync Tasks
            $inputTasks = $request->input('tasks', []);
            $inputTaskIds = array_filter(array_column($inputTasks, 'id'));
            $client->tasks()->whereNotIn('id', $inputTaskIds)->get()->each->delete();

            foreach ($inputTasks as $row) {
                if (isset($row['id']) && $row['id']) {
                    $item = $client->tasks()->find($row['id']);
                    if ($item)
                        $item->update(Arr::except($row, ['id']));
                }
                else {
                    $client->tasks()->create($row);
                }
            }
        });

        return redirect()->route('clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted.');
    }

    public function print(Client $client)
    {
        $client->load(['checklistItems', 'siteInfo', 'permission', 'comments', 'payments', 'tasks', 'scopeOfWork.items', 'projectMaterials.inventoryItem']);
        return view('clients.print', compact('client'));
    }
}