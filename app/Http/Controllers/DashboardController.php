<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Activity;
use App\Streak;
use App\ActivityTarget;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['profile', 'setting', 'streak', 'activeTarget']);

        // Ambil zona waktu lokal pengguna
        $userTimezone = $user->setting->timezone ?? 'Asia/Jakarta';
        $todayDate = Carbon::now($userTimezone)->toDateString();

        // Total akumulasi hari ini (Status completed)
        $todayActivities = Activity::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('start_time', $todayDate)
            ->get();

        $todayDistanceMeters = $todayActivities->sum('distance_meters');
        $todayDistanceKm = round($todayDistanceMeters / 1000, 2);
        $todayCalories = $todayActivities->sum('calories_burned');

        // Target Harian User
        $targetDistanceKm = $user->activeTarget->daily_distance_km ?? 5.00;
        $targetCalories = $user->activeTarget->daily_calories ?? 300;

        // Perhitungan Persentase Progress
        $distanceProgressPercent = min(100, round(($todayDistanceKm / $targetDistanceKm) * 100));
        $caloriesProgressPercent = min(100, round(($todayCalories / $targetCalories) * 100));

        return view('dashboard', compact(
            'user',
            'todayDistanceKm',
            'todayCalories',
            'targetDistanceKm',
            'targetCalories',
            'distanceProgressPercent',
            'caloriesProgressPercent'
        ));
    }
}