<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SportSelectorService;
use App\Activity;
use Carbon\Carbon;
use Exception;

class ActivitySessionController extends Controller
{
    /**
     * Inisialisasi Sesi Tracking Baru & Tampilkan Screen Tracking
     */
    public function start(Request $request)
    {
        $sportType = $request->input('sport_type', 'running');

        if (!SportSelectorService::isValid($sportType)) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Jenis olahraga tidak valid.']);
        }

        $sportConfig = SportSelectorService::getConfig($sportType);
        $user = Auth::user();

        try {
            // BATALKAN SELURUH AKTIVITAS GANTUNG/LAMA YANG MASIH STATUS 'ACTIVE'
            Activity::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // BUAT RECORD AKTIVITAS BARU DENGAN START_TIME SEGAR (WAKTU SEKARANG)
            $activeActivity = Activity::create([
                'user_id' => $user->id,
                'sport_type' => $sportType,
                'status' => 'active',
                'start_time' => Carbon::now(),
                'distance_meters' => 0.00,
                'duration_seconds' => 0,
                'avg_speed_kmh' => 0.00,
                'elevation_gain_m' => 0.00,
                'calories_burned' => 0,
            ]);

            return view('activity.tracking', compact('activeActivity', 'sportConfig'));

        } catch (Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Gagal memulai aktivitas: ' . $e->getMessage()]);
        }
    }

    /**
     * Hentikan & Validasi Aktivitas
     */
    public function stop(Request $request, $id)
    {
        $activity = Activity::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (!$activity) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Aktivitas ora ditemokake.']);
        }

        $result = \App\Services\ActivityValidationService::validateAndComplete($activity);

        return redirect()->route('dashboard')->with($result['isValid'] ? 'success' : 'warning', $result['message']);
    }
}