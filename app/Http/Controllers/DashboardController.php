<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use App\Models\Payment;
use App\Models\VendorPayment;
use App\Models\MaterialPayment;
use App\Models\Expense;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isViewer = $user->isViewer() || $user->isClient();

        // 1. Core Financial Metrics
        $clientsQuery = Client::query();
        if ($isViewer) {
            $clientsQuery->where('user_id', $user->id);
        }
        $clients = $clientsQuery->get();

        $totalRevenue = 0;
        $totalExpenses = 0;
        $totalBudget = 0;
        $overduePayments = 0;
        $totalProjects = $clients->count();

        $vendorTotal = 0;
        $materialTotal = 0;
        $generalTotal = 0;

        foreach ($clients as $client) {
            $received = $client->total_client_received;
            $vendor = $client->total_vendor_paid;
            $material = $client->total_material_cost;
            $genExp = $client->expenses()->sum('amount');
            $budget = $client->total_budget;

            $totalRevenue += $received;
            $vendorTotal += $vendor;
            $materialTotal += $material;
            $generalTotal += $genExp;
            $totalBudget += $budget;

            $balance = $client->outstanding_balance;
            if ($balance > 0) {
                $overduePayments += $balance;
            }
        }

        $totalExpenses = $vendorTotal + $materialTotal + $generalTotal;
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // 2. Project Status & Completion Rates
        $activeTasks = Task::query();
        if ($isViewer) {
            $activeTasks->whereIn('client_id', $clients->pluck('id'));
        }
        $activeTasksCount = (clone $activeTasks)->whereIn('status', ['Pending', 'In Progress'])->count();

        $completedProjectsCount = $clients->where('status', 'Completed')->count();

        // Completion Rate (Average task completion %)
        $totalTasksCount = (clone $activeTasks)->count();
        $completedTasksCount = (clone $activeTasks)->where('status', 'Completed')->count();
        $completionRate = $totalTasksCount > 0 ? ($completedTasksCount / $totalTasksCount) * 100 : 0;

        // 3. Trends (Last 6 Months)
        $months = [];
        $incomeData = [];
        $expenseData = [];
        $completionData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $months[] = $month->format('M');

            $monthlyInc = Payment::whereBetween('date', [$start, $end]);
            $monthlyExpV = VendorPayment::whereBetween('payment_date', [$start, $end]);
            $monthlyExpM = MaterialPayment::whereBetween('payment_date', [$start, $end]);
            $monthlyExpG = Expense::whereBetween('date', [$start, $end]);

            // Completion count (projects delivered in that month)
            $monthlyComp = Client::whereBetween('delivery_date', [$start, $end]);

            if ($isViewer) {
                $ids = $clients->pluck('id');
                $monthlyInc->whereIn('client_id', $ids);
                $monthlyExpV->whereIn('client_id', $ids);
                $monthlyExpM->whereIn('client_id', $ids);
                $monthlyExpG->whereIn('client_id', $ids);
                $monthlyComp->where('user_id', $user->id);
            }

            $incomeData[] = $monthlyInc->sum('amount');
            $expenseData[] = $monthlyExpV->sum('amount') + $monthlyExpM->sum('amount_paid') + $monthlyExpG->sum('amount');
            $completionData[] = $monthlyComp->count();
        }

        // 4. Alerts & Snapshot
        $alerts = [];
        foreach ($clients as $client) {
            $risk = $client->risk_analysis;
            if ($risk['level'] === 'High') {
                $alerts[] = [
                    'type' => 'critical',
                    'title' => 'Project at High Risk',
                    'desc' => $client->first_name . ': ' . ($risk['reasons'][0] ?? 'Past delivery date'),
                    'client_id' => $client->id
                ];
            }

            $totalSpent = $client->total_vendor_paid + $client->total_material_cost + $client->expenses()->sum('amount');
            if ($client->total_budget > 0 && $totalSpent > $client->total_budget) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Budget Overrun',
                    'desc' => $client->first_name . ' has exceeded locked budget.',
                    'client_id' => $client->id
                ];
            }

            if ($client->outstanding_balance > 100000) { // Significant overdue
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'Heavy Overdue',
                    'desc' => $client->first_name . ' has ₹' . number_format($client->outstanding_balance) . ' pending.',
                    'client_id' => $client->id
                ];
            }
        }

        // Top Clients
        $topClients = $clients->sortByDesc(fn($c) => $c->total_client_received)->take(5);

        // Expense Distribution
        $expenseDist = [
            'Vendor' => $vendorTotal,
            'Material' => $materialTotal,
            'General' => $generalTotal
        ];

        return view('dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'activeProjects' => $totalProjects,
            'overduePayments' => $overduePayments,
            'completionRate' => $completionRate,
            'profitMargin' => $profitMargin,
            'months' => $months,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'completionData' => $completionData,
            'topClients' => $topClients,
            'alerts' => array_slice($alerts, 0, 5),
            'expenseDist' => $expenseDist,
            'recentPayments' => Payment::with('client')->latest()->take(3)->get()
        ]);
    }
    public function timeline()
    {
        $clients = Client::with(['tasks' => function ($q) {
            $q->orderBy('start_date');
        }])->where('status', '!=', 'Completed')->get();

        return view('timeline', compact('clients'));
    }
}