<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    /**
     * Public Read-Only View for Client
     */
    public function show(Client $client)
    {
        // Client UUID binding handles the lookup securely
        // Ensure relationships are loaded
        $client->load([
            'tasks',
            'checklistItems',
            'siteInfo',
            'dailyReports.images',
            'changeRequests',
            'projectMaterials.inventoryItem',
            'handover.checklistItems',
            'feedback',
            'scopeOfWork.items'
        ]);

        // Calculate progress
        $totalTasks = $client->tasks->count();
        $completedTasks = $client->tasks->where('status', 'Completed')->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('portal.index', compact('client', 'progress'));
    }

    /**
     * Generate PDF Report
     */
    public function downloadPdf(Client $client)
    {
        // Reuse existing print logic or create specific report?
        // User asked for "Export progress report (PDF)".
        // I can redirect to existing print view but maybe hide financials?
        // Existing print view (clients.print) likely shows everything.
        // I should probably clone print view and strip financials.

        // For MVP, I'll redirect to a new print view 'portal.print'
        return view('portal.print', compact('client'));
    }
}