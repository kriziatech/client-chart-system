<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    /**
     * Display a standalone task dashboard.
     */
    public function index(Request $request)
    {
        $query = Task::with(['client', 'dailyReport'])
            ->orderBy('deadline', 'asc');

        // Filter by Client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $tasks = $query->get();
        $clients = Client::all();

        // Grouping for the dashboard
        $groupedTasks = [
            'overdue' => $tasks->filter(fn($t) => $t->status !== 'Completed' && $t->deadline && $t->deadline->isPast())->values(),
            'today' => $tasks->filter(fn($t) => $t->status !== 'Completed' && $t->deadline && $t->deadline->isToday())->values(),
            'upcoming' => $tasks->filter(fn($t) => $t->status !== 'Completed' && $t->deadline && $t->deadline->isFuture())->values(),
            'no_deadline' => $tasks->filter(fn($t) => $t->status !== 'Completed' && !$t->deadline)->values(),
            'completed' => $tasks->filter(fn($t) => $t->status === 'Completed')->values(),
            'by_category' => $tasks->filter(fn($t) => $t->status !== 'Completed')->groupBy('category'),
        ];

        $categories = Task::distinct()->pluck('category')->filter()->values();

        return view('tasks.index', compact('groupedTasks', 'clients', 'categories'));
    }

    /**
     * Update task status (AJAX).
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed'
        ]);

        $task->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Task status updated.');
    }
}