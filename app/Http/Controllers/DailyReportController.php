<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DailyReport;
use App\Models\DailyReportImage;
use App\Models\Task;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{
    public function index(Client $client)
    {
        $reports = $client->dailyReports()->with(['images', 'tasks', 'expenses'])->latest('report_date')->get();
        $pendingTasks = $client->tasks()->where('status', '!=', 'Completed')->get();
        // Get expenses for this client that aren't linked to a report yet
        $unlinkedExpenses = $client->expenses()->whereNull('daily_report_id')->get();

        return view('daily_reports.index', compact('client', 'reports', 'pendingTasks', 'unlinkedExpenses'));
    }

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'report_date' => 'required|date',
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'tasks' => 'nullable|array',
            'tasks.*' => 'exists:tasks,id',
            'expenses' => 'nullable|array',
            'expenses.*' => 'exists:expenses,id',
        ]);

        return DB::transaction(function () use ($request, $client) {
            $report = $client->dailyReports()->create([
                'report_date' => $request->report_date,
                'content' => $request->content,
            ]);

            if ($request->has('tasks')) {
                foreach ($request->tasks as $taskId) {
                    $task = Task::find($taskId);
                    if ($task && isset($report) && $report) {
                        $task->update([
                            'daily_report_id' => $report->id,
                            'status' => 'Completed',
                        ]);
                    }
                }
            }

            if ($request->has('expenses')) {
                foreach ($request->expenses as $expenseId) {
                    $expense = Expense::find($expenseId);
                    if ($expense && isset($report) && $report->id) {
                        $expense->update([
                            'daily_report_id' => $report->id,
                        ]);
                    }
                }
            }

            if ($request->hasFile('images_before')) {
                foreach ($request->file('images_before') as $image) {
                    $path = $image->store('daily_reports', 'public');
                    $report->images()->create(['image_path' => $path, 'label' => 'before']);
                }
            }

            if ($request->hasFile('images_after')) {
                foreach ($request->file('images_after') as $image) {
                    $path = $image->store('daily_reports', 'public');
                    $report->images()->create(['image_path' => $path, 'label' => 'after']);
                }
            }

            if ($request->hasFile('images_progress')) {
                foreach ($request->file('images_progress') as $image) {
                    $path = $image->store('daily_reports', 'public');
                    $report->images()->create(['image_path' => $path, 'label' => 'progress']);
                }
            }

            return redirect()->route('clients.show', $client->id)
                ->with('success', 'Daily Progress Report submitted successfully.');
        });
    }

    public function update(Request $request, DailyReport $report)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $report->update($request->only('content'));

        return back()->with('success', 'Report updated.');
    }

    public function destroy(DailyReport $report)
    {
        $report->images->each(function ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        });

        // Unlink tasks and expenses
        Task::where('daily_report_id', $report->id)->update(['daily_report_id' => null, 'status' => 'Pending']); // Reset status? Maybe keep it completed.
        Expense::where('daily_report_id', $report->id)->update(['daily_report_id' => null]);

        $report->delete();

        return back()->with('success', 'Report deleted.');
    }
}