<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Activity;
use App\ActivityPoint;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman Utama Arsip & Riwayat Aktivitas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sportFilter = $request->input('sport', 'all');

        $query = Activity::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('created_at');

        if ($sportFilter !== 'all' && in_array($sportFilter, ['running', 'walking', 'cycling'])) {
            $query->where('sport_type', $sportFilter);
        }

        $activities = $query->paginate(10);

        // Ringkasan Total Statistik
        $totalDistanceKm = Activity::where('user_id', $user->id)->where('status', 'completed')->sum('distance_meters') / 1000;
        $totalCalories = Activity::where('user_id', $user->id)->where('status', 'completed')->sum('calories_burned');
        $totalDurationSec = Activity::where('user_id', $user->id)->where('status', 'completed')->sum('duration_seconds');

        return view('archive.index', compact('activities', 'sportFilter', 'totalDistanceKm', 'totalCalories', 'totalDurationSec'));
    }

    /**
     * Fetch API Point Koordinat Rute untuk Modal Peta
     */
    public function getRoutePoints($id)
    {
        $activity = Activity::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $points = ActivityPoint::where('activity_id', $activity->id)
            ->orderBy('recorded_at', 'asc')
            ->get(['latitude', 'longitude', 'altitude']);

        return response()->json([
            'status' => 'success',
            'activity' => $activity,
            'points' => $points
        ]);
    }
}