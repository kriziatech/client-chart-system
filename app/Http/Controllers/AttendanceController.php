<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Get current status for logged-in user
     */
    public function status(Request $request)
    {
        $today = Carbon::today();
        // Check for existing attendance record for today
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['status' => 'not_checked_in']);
        }

        if ($attendance->check_in_time && !$attendance->check_out_time) {
            return response()->json([
                'status' => 'checked_in',
                'check_in_time' => $attendance->check_in_time->format('h:i A'),
                'duration' => $attendance->check_in_time->diffForHumans(null, true)
            ]);
        }

        return response()->json([
            'status' => 'checked_out',
            'check_in_time' => $attendance->check_in_time->format('h:i A'),
            'check_out_time' => $attendance->check_out_time->format('h:i A'),
            'duration' => $attendance->check_in_time->diffInHours($attendance->check_out_time) . ' hrs'
        ]);
    }

    /**
     * Check In Handler
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $today = Carbon::today();

        // Prevent double check-in
        $existing = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already checked in for today.'], 400);
        }

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'date' => $today,
            'check_in_time' => now(),
            'check_in_lat' => $request->lat,
            'check_in_lng' => $request->lng,
            'check_in_address' => 'GPS: ' . $request->lat . ', ' . $request->lng, // Could geocode later
            'status' => 'present'
        ]);

        return response()->json(['message' => 'Checked In successfully!', 'data' => $attendance]);
    }

    /**
     * Check Out Handler
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $today = Carbon::today();

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'No active check-in found.'], 400);
        }

        $attendance->update([
            'check_out_time' => now(),
            'check_out_lat' => $request->lat,
            'check_out_lng' => $request->lng,
            'check_out_address' => 'GPS: ' . $request->lat . ', ' . $request->lng, // Could geocode later
        ]);

        return response()->json(['message' => 'Checked Out successfully!', 'data' => $attendance]);
    }

    /**
     * Admin View: List all attendances
     */
    public function index(Request $request)
    {
        $query = Attendance::with('user')->latest('date');

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->paginate(20);
        $users = \App\Models\User::orderBy('name')->get();

        return view('attendances.index', compact('attendances', 'users'));
    }
}