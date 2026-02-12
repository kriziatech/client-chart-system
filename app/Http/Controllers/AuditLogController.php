<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('model')) {
            $query->where('model_type', 'like', '%' . $request->model . '%');
        }

        $logs = $query->paginate(50);
        $users = \App\Models\User::orderBy('name')->get();

        return view('audit-logs.index', compact('logs', 'users'));
    }

    /**
     * API endpoint for live polling — returns latest logs as JSON
     */
    public function latest(Request $request)
    {
        $afterId = $request->input('after_id', 0);

        $logs = AuditLog::with('user')
            ->where('id', '>', $afterId)
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'description' => $log->description,
                    'user_name' => $log->user?->name ?? 'System',
                    'model_name' => $log->model_name,
                    'model_id' => $log->model_id,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'ip_address' => $log->ip_address,
                    'created_at' => $log->created_at->format('d M, Y h:i:s A'),
                    'time_ago' => $log->created_at->diffForHumans(),
                ];
            });

        return response()->json($logs);
    }
}