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
        $clients = Client::latest()->paginate(10);
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

        return view('clients.create', ['client' => $client]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'file_number' => 'required|string|unique:clients,file_number',
        ]);

        $client = DB::transaction(function () use ($request) {
            $client = Client::create($request->only([
                'first_name', 'last_name', 'file_number', 'mobile',
                'address', 'work_description', 'start_date', 'delivery_date'
            ]));

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
                $client->comments()->createMany(array_values($request->input('comments')));
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
        $client->load(['checklistItems', 'siteInfo', 'permission', 'comments', 'payments', 'tasks']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load(['checklistItems', 'siteInfo', 'permission', 'comments', 'payments', 'tasks']);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'file_number' => 'required|string|unique:clients,file_number,' . $client->id,
        ]);

        DB::transaction(function () use ($request, $client) {
            $client->update($request->only([
                'first_name', 'last_name', 'file_number', 'mobile',
                'address', 'work_description', 'start_date', 'delivery_date'
            ]));

            $client->siteInfo()->updateOrCreate([], $request->input('site_info', []));
            $client->permission()->updateOrCreate([], $request->input('permission', []));

            // Sync Checklist Items
            $inputChecklist = $request->input('checklist_items', []);
            $inputChecklistIds = array_filter(array_column($inputChecklist, 'id'));
            $client->checklistItems()->whereNotIn('id', $inputChecklistIds)->delete();
            foreach ($inputChecklist as $row) {
                $row['is_checked'] = isset($row['is_checked']) ? true : false;
                if (isset($row['id']) && $row['id']) {
                    $client->checklistItems()->where('id', $row['id'])->update(Arr::except($row, ['id']));
                } else {
                    $client->checklistItems()->create($row);
                }
            }

            // Sync Comments
            $inputComments = $request->input('comments', []);
            $inputCommentIds = array_filter(array_column($inputComments, 'id'));
            $client->comments()->whereNotIn('id', $inputCommentIds)->delete();
            foreach ($inputComments as $row) {
                if (isset($row['id']) && $row['id']) {
                    $client->comments()->where('id', $row['id'])->update(Arr::except($row, ['id']));
                } else {
                    $client->comments()->create($row);
                }
            }

            // Sync Payments
            $inputPayments = $request->input('payments', []);
            $inputPaymentIds = array_filter(array_column($inputPayments, 'id'));
            $client->payments()->whereNotIn('id', $inputPaymentIds)->delete();
            foreach ($inputPayments as $row) {
                if (isset($row['id']) && $row['id']) {
                    $client->payments()->where('id', $row['id'])->update(Arr::except($row, ['id']));
                } else {
                    $client->payments()->create($row);
                }
            }

            // Sync Tasks
            $inputTasks = $request->input('tasks', []);
            $inputTaskIds = array_filter(array_column($inputTasks, 'id'));
            $client->tasks()->whereNotIn('id', $inputTaskIds)->delete();
            foreach ($inputTasks as $row) {
                if (isset($row['id']) && $row['id']) {
                    $client->tasks()->where('id', $row['id'])->update(Arr::except($row, ['id']));
                } else {
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
        $client->load(['checklistItems', 'siteInfo', 'permission', 'comments', 'payments', 'tasks']);
        return view('clients.print', compact('client'));
    }
}