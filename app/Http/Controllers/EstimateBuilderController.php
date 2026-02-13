<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Quotation;

class EstimateBuilderController extends Controller
{
    public function index()
    {
        return view('estimate-builder.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'rooms' => 'required|array',
            'rooms.*.type' => 'required|string',
            'rooms.*.area' => 'required|numeric|min:1',
            'quality' => 'required|in:Standard,Premium,Luxury',
        ]);

        $rates = [
            'Standard' => 1200,
            'Premium' => 2200,
            'Luxury' => 4500
        ];

        $rate = $rates[$request->quality];
        $totalArea = 0;
        $roomBreakdown = [];

        foreach ($request->rooms as $room) {
            $roomTotal = $room['area'] * $rate;
            $totalArea += $room['area'];
            $roomBreakdown[] = [
                'type' => $room['type'],
                'area' => $room['area'],
                'estimate' => $roomTotal
            ];
        }

        $subtotal = $totalArea * $rate;
        $gst = $subtotal * 0.18;
        $total = $subtotal + $gst;

        return response()->json([
            'breakdown' => $roomBreakdown,
            'subtotal' => $subtotal,
            'gst' => $gst,
            'total' => $total,
            'rate_applied' => $rate
        ]);
    }
}