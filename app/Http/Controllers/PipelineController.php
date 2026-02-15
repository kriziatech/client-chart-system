<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;

class PipelineController extends Controller
{
    /**
     * Pipeline stages for the Kanban board
     */
    private const STAGES = ['New', 'Contacted', 'Visited', 'Quote Sent', 'Won', 'Lost'];

    /**
     * Display the Sales Pipeline (Kanban Board)
     */
    public function index(Request $request)
    {
        $leads = Lead::with('assignedTo')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($lead) {
            $lead->days_inactive = $lead->days_inactive;
            $lead->needs_attention = $lead->needs_attention;
            $lead->formatted_budget = $lead->formatted_budget;
            return $lead;
        });

        // Group leads by status for the board
        $pipeline = [];
        foreach (self::STAGES as $stage) {
            $stageLeads = $leads->where('status', $stage)->values();
            $pipeline[$stage] = [
                'leads' => $stageLeads,
                'count' => $stageLeads->count(),
                'value' => $stageLeads->sum('budget'),
            ];
        }

        // Overall stats
        $stats = [
            'total_leads' => $leads->count(),
            'pipeline_value' => $leads->whereNotIn('status', ['Won', 'Lost'])->sum('budget'),
            'won_value' => $leads->where('status', 'Won')->sum('budget'),
            'conversion_rate' => $leads->count() > 0
            ? round(($leads->where('status', 'Won')->count() / $leads->count()) * 100, 1)
            : 0,
            'needs_attention' => $leads->where('needs_attention', true)->count(),
        ];

        $users = User::all();

        return view('pipeline.index', compact('pipeline', 'stats', 'users', 'leads'));
    }
}