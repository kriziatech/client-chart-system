<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DailyReport;
use App\Models\DailyReportImage;
use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExecutionController extends Controller
{
    /**
     * Store Daily Progress Report
     */
    public function storeDPR(Request $request, Client $client)
    {
        $request->validate([
            'content' => 'required|string',
            'report_date' => 'required|date',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:4096'
        ]);

        $report = DailyReport::create([
            'client_id' => $client->id,
            'report_date' => $request->report_date,
            'content' => $request->content,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('dpr', 'public');
                DailyReportImage::create([
                    'daily_report_id' => $report->id,
                    'image_path' => $path
                ]);
            }
        }

        return back()->with('success', 'Daily Progress Report shared with client.');
    }

    /**
     * Store Change Request
     */
    public function storeChangeRequest(Request $request, Client $client)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cost_impact' => 'required|numeric'
        ]);

        ChangeRequest::create([
            'client_id' => $client->id,
            'title' => $request->title,
            'description' => $request->description,
            'cost_impact' => $request->cost_impact,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Change request submitted for client approval.');
    }

    /**
     * Approve/Reject Change Request (Client View)
     */
    public function updateChangeRequestStatus(Request $request, ChangeRequest $changeRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $changeRequest->update([
            'status' => $request->status,
            'approved_at' => $request->status === 'approved' ? now() : null
        ]);

        return back()->with('success', 'Change request ' . $request->status . '.');
    }
}