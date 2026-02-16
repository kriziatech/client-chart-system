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

        // 1. Key Metrics with Real Financial Data
        if ($isViewer) {
            $clientIds = Client::where('user_id', $user->id)->pluck('id');
            $totalProjects = $clientIds->count();

            // Income
            $totalRevenue = Payment::whereIn('client_id', $clientIds)->sum('amount');

            // Expenses (Vendor + Material + General Expenses)
            $vendorExpenses = \App\Models\VendorPayment::whereIn('client_id', $clientIds)->sum('amount');
            $materialExpenses = \App\Models\MaterialPayment::whereIn('client_id', $clientIds)->sum('amount_paid');
            $generalExpenses = \App\Models\Expense::whereIn('client_id', $clientIds)->sum('amount');

            $totalExpenses = $vendorExpenses + $materialExpenses + $generalExpenses;
            $netProfit = $totalRevenue - $totalExpenses;

            $activeTasks = Task::whereIn('client_id', $clientIds)
                ->whereIn('status', ['Pending', 'In Progress'])
                ->count();
            $completedProjects = Client::where('user_id', $user->id)
                ->where('delivery_date', '<', now())
                ->count();

            // Last Month Financials
            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();

            $lastMonthRevenue = Payment::whereIn('client_id', $clientIds)
                ->whereBetween('date', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount'); // revenue
        }
        else {
            $totalProjects = Client::count();

            // Income
            $totalRevenue = Payment::sum('amount');

            // Expenses
            $vendorExpenses = \App\Models\VendorPayment::sum('amount');
            $materialExpenses = \App\Models\MaterialPayment::sum('amount_paid');
            $generalExpenses = \App\Models\Expense::sum('amount');

            $totalExpenses = $vendorExpenses + $materialExpenses + $generalExpenses;
            $netProfit = $totalRevenue - $totalExpenses;

            $activeTasks = Task::whereIn('status', ['Pending', 'In Progress'])->count();
            $completedProjects = Client::where('delivery_date', '<', now())->count();

            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();

            $lastMonthRevenue = Payment::whereBetween('date', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount');
        }

        // Trend calculation
        $revenueGrowth = $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        // Generate sparkline (simulating daily totals for the last 7 days)
        $sparklineQuery = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dailySum = Payment::whereDate('date', $date);
            if ($isViewer) {
                $dailySum->whereIn('client_id', $clientIds ?? []);
            }
            $sparklineQuery[] = $dailySum->sum('amount');
        }

        // 2. Chart Data: Financial Trends (Income vs Expense - Last 6 Months)
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $months[] = $month->format('M Y');

            // Monthly Income
            $incQuery = Payment::whereBetween('date', [$start, $end]);

            // Monthly Expense
            $expVendor = \App\Models\VendorPayment::whereBetween('payment_date', [$start, $end]);
            $expMat = \App\Models\MaterialPayment::whereBetween('payment_date', [$start, $end]);
            $expGen = \App\Models\Expense::whereBetween('date', [$start, $end]);

            if ($isViewer) {
                $incQuery->whereIn('client_id', $clientIds ?? []);
                $expVendor->whereIn('client_id', $clientIds ?? []);
                $expMat->whereIn('client_id', $clientIds ?? []);
                $expGen->whereIn('client_id', $clientIds ?? []);
            }

            $incomeData[] = $incQuery->sum('amount');
            $expenseData[] = $expVendor->sum('amount') + $expMat->sum('amount_paid') + $expGen->sum('amount');
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
        $rpQuery = Client::latest()->take(3); // Reduced to 3
        if ($isViewer) {
            $rpQuery->where('user_id', $user->id);
        }
        $recentProjects = $rpQuery->get();

        $qQuery = \App\Models\Quotation::with('client')->latest()->take(3);

        if ($isViewer) {
            $qQuery->whereIn('client_id', $clientIds ?? []);
        }

        $recentQuotations = $qQuery->get();

        // Recent Materials (Inwards)
        $rmQuery = \App\Models\MaterialInward::with(['client'])->latest()->take(3); // Reduced to 3
        if ($isViewer) {
            $rmQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentMaterials = $rmQuery->get();

        $drQuery = \App\Models\DailyReport::with('client')->latest()->take(3); // Reduced to 3
        if ($isViewer) {
            $drQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentReports = $drQuery->get();

        $payQuery = \App\Models\Payment::with('client')->latest()->take(3); // Reduced to 3
        if ($isViewer) {
            $payQuery->whereIn('client_id', $clientIds ?? []);
        }
        $recentPayments = $payQuery->get();

        return view('dashboard', compact(
            'totalProjects', 'totalRevenue', 'totalExpenses', 'netProfit', 'activeTasks', 'completedProjects',
            'months', 'incomeData', 'expenseData',
            'taskLabels', 'taskData',
            'recentProjects', 'recentQuotations',
            'recentMaterials', 'revenueGrowth', 'sparklineQuery',
            'recentReports', 'recentPayments'
        ));
    }
}