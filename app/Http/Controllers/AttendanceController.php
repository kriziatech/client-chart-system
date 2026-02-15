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
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $today = Carbon::today();

        // Prevent double check-in
        $existing = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already checked in for today.'], 400);
        }

        $user = auth()->user();
        $dailyRate = $user->daily_rate ?? 0;

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'client_id' => $request->client_id,
            'date' => $today,
            'check_in_time' => now(),
            'check_in_lat' => $request->lat,
            'check_in_lng' => $request->lng,
            'check_in_address' => 'GPS: ' . $request->lat . ', ' . $request->lng,
            'status' => 'present',
            'daily_cost' => $dailyRate,
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
        $view = $request->get('view', 'list');
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $query = Attendance::with(['user', 'client'])->latest('date');

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // For Calendar View, we need all records for the month
        if ($view === 'calendar') {
            $query->whereMonth('date', $month)->whereYear('date', $year);
            $attendances = $query->get();
        }
        else {
            $attendances = $query->paginate(20);
        }

        $users = \App\Models\User::orderBy('name')->get();
        $projects = \App\Models\Client::orderBy('first_name')->get();

        // Calculate Analytics for the selected filters
        $analytics = [
            'total_present' => Attendance::whereMonth('date', $month)->whereYear('date', $year)->where('status', 'present')->count(),
            'total_cost' => Attendance::whereMonth('date', $month)->whereYear('date', $year)->sum('daily_cost'),
            'project_breakdown' => Attendance::whereMonth('date', $month)->whereYear('date', $year)
            ->whereNotNull('client_id')
            ->selectRaw('client_id, SUM(daily_cost) as cost')
            ->groupBy('client_id')
            ->with('client')
            ->get()
        ];

        return view('attendances.index', compact('attendances', 'users', 'projects', 'view', 'month', 'year', 'analytics'));
    }
}