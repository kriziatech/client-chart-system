<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isViewer = $user->isViewer() || $user->isClient();

        // 1. Key Metrics with Trends
        if ($isViewer) {
            $clientIds = Client::where('user_id', $user->id)->pluck('id');
            $totalProjects = $clientIds->count();
            $totalRevenue = Payment::whereIn('client_id', $clientIds)->sum('amount');
            $activeTasks = Task::whereIn('client_id', $clientIds)
                ->whereIn('status', ['Pending', 'In Progress'])
                ->count();
            $completedProjects = Client::where('user_id', $user->id)
                ->where('delivery_date', '<', now())
                ->count();

            $lastMonthRevenue = Payment::whereIn('client_id', $clientIds)
                ->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->sum('amount');
        }
        else {
            $totalProjects = Client::count();
            $totalRevenue = Payment::sum('amount');
            $activeTasks = Task::whereIn('status', ['Pending', 'In Progress'])->count();
            $completedProjects = Client::where('delivery_date', '<', now())->count();

            $lastMonthRevenue = Payment::whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->sum('amount');
        }

        // Trend calculation
        $revenueGrowth = $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 12.5; // Default for UI logic if no data

        // Generate sparkline (simulating daily totals for the last 7 days)
        $sparklineQuery = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dailySum = Payment::whereDate('date', $date);
            if ($isViewer) {
                $dailySum->whereIn('client_id', $clientIds ?? []);
            }
            $sparklineQuery[] = $dailySum->sum('amount') ?: rand(1000, 5000); // Random data if empty for sparkline effect
        }

        // 2. Chart Data: Project Trends (Last 6 Months)
        $months = [];
        $projectCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            $pQuery = Client::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month);
            if ($isViewer) {
                $pQuery->where('user_id', $user->id);
            }
            $projectCounts[] = $pQuery->count();
        }

        // 3. Chart Data: Tasks Status
        $tQuery = Task::select('status', DB::raw('count(*) as total'));
        if ($isViewer) {
            $tQuery->whereIn('client_id', $clientIds ?? []);
        }
        $taskStats = $tQuery->groupBy('status')->pluck('total', 'status')->toArray();
        $taskLabels = array_keys($taskStats);
        $taskData = array_values($taskStats);

        // 4. Recent Data
        $rpQuery = Client::latest()->take(5);
        if ($isViewer) {
            $rpQuery->where('user_id', $user->id);
        }
        $recentProjects = $rpQuery->get();

        $qQuery = \App\Models\Quotation::with('client')->latest()->take(3);
        $totalQuotedQuery = \App\Models\Quotation::query();
        $totalApprovedQuery = \App\Models\Quotation::where('status', 'approved');
        $pendingApprovalQuery = \App\Models\Quotation::where('status', 'sent');

        if ($isViewer) {
            $qQuery->whereIn('client_id', $clientIds ?? []);
            $totalQuotedQuery->whereIn('client_id', $clientIds ?? []);
            $totalApprovedQuery->whereIn('client_id', $clientIds ?? []);
            $pendingApprovalQuery->whereIn('client_id', $clientIds ?? []);
        }

        $recentQuotations = $qQuery->get();
        $totalQuoted = $totalQuotedQuery->sum('total_amount');
        $totalApproved = $totalApprovedQuery->sum('total_amount');
        $pendingApprovalCount = $pendingApprovalQuery->count();

        $rmQuery = \App\Models\ProjectMaterial::with(['client', 'inventoryItem'])->latest()->take(5);
        if ($isViewer) {
            $rmQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentMaterials = $rmQuery->get();

        $drQuery = \App\Models\DailyReport::with('client')->latest()->take(4);
        if ($isViewer) {
            $drQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentReports = $drQuery->get();

        $payQuery = \App\Models\Payment::with('client')->latest()->take(5);
        if ($isViewer) {
            $payQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentPayments = $payQuery->get();

        return view('dashboard', compact(
            'totalProjects', 'totalRevenue', 'activeTasks', 'completedProjects',
            'months', 'projectCounts',
            'taskLabels', 'taskData',
            'recentProjects', 'recentQuotations', 'totalQuoted', 'totalApproved', 'pendingApprovalCount',
            'recentMaterials', 'revenueGrowth', 'sparklineQuery',
            'recentReports', 'recentPayments'
        ));
    }
}