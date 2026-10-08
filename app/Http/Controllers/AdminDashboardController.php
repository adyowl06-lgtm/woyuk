<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserProfile;
use App\UserSetting;
use App\Streak;
use App\ActivityTarget;
use App\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // 1. Total Pengguna (Termasuk Admin & Non-Admin)
        $totalUsers = User::count();

        // 2. Total Pengguna Selain Admin (Bagi pembagi rumus statistik)
        $nonAdminUsersCount = User::where('role', '!=', 'admin')->count();
        if ($nonAdminUsersCount < 1) {
            $nonAdminUsersCount = 1; // Mencegah error Division by Zero
        }

        // 3. Query Aktivitas Valid (Status Completed & Jarak >= 10 meter)
        $activitiesQuery = Activity::where('status', 'completed')
            ->where('distance_meters', '>=', 10);

        $totalRunning = (clone $activitiesQuery)->where('sport_type', 'running')->count();
        $totalWalking = (clone $activitiesQuery)->where('sport_type', 'walking')->count();
        $totalCycling = (clone $activitiesQuery)->where('sport_type', 'cycling')->count();
        $totalCompletedActivities = $totalRunning + $totalWalking + $totalCycling;

        // 4. Kalkulasi Diagram Batang Aktivitas Per Hari (Senin - Minggu)
        // Formula: (Total Sesi Valid per Hari * 5) / Total Non-Admin Users
        $daysOfWeek = [
            'Monday'    => 'Sen',
            'Tuesday'   => 'Sel',
            'Wednesday' => 'Rab',
            'Thursday'  => 'Kam',
            'Friday'    => 'Jum',
            'Saturday'  => 'Sab',
            'Sunday'    => 'Min'
        ];

        // Ambil rekap aktivitas per hari dari database
        $dailyCountsRaw = (clone $activitiesQuery)
            ->select(DB::raw('DAYNAME(start_time) as day_name'), DB::raw('COUNT(*) as total'))
            ->groupBy('day_name')
            ->pluck('total', 'day_name')
            ->toArray();

        $chartData = [];
        $maxPercent = 0;
        $peakDayName = 'Belum Ada Data';

        foreach ($daysOfWeek as $fullDay => $shortDay) {
            $count = $dailyCountsRaw[$fullDay] ?? 0;
            
            // Rumus: (Aktivitas * 5) / Jumlah Non-Admin User
            $calculatedValue = ($count * 5) / $nonAdminUsersCount;
            // Dikonversi ke persen (Maksimal Cap 100% untuk UI Bar)
            $percentage = min(100, round($calculatedValue, 1));

            if ($percentage > $maxPercent) {
                $maxPercent = $percentage;
                $peakDayName = $fullDay;
            }

            $chartData[] = [
                'full_day'   => $fullDay,
                'short_day'  => $shortDay,
                'count'      => $count,
                'percentage' => $percentage
            ];
        }

        // Translation Hari Puncak
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $peakDayTranslated = $dayMap[$peakDayName] ?? 'Belum Ada Data';

        // 5. List 5 User Terbaru
        $latestUsers = User::with('profile')->orderBy('id', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'nonAdminUsersCount',
            'totalCompletedActivities',
            'totalRunning',
            'totalWalking',
            'totalCycling',
            'chartData',
            'peakDayTranslated',
            'latestUsers'
        ));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password admin minimal 8 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            $baseUsername = explode('@', $user->email)[0];
            $cleanBase = Str::slug($baseUsername);
            $username = ($cleanBase ?: 'admin') . '_' . rand(100, 999);

            UserProfile::create([
                'user_id' => $user->id,
                'name' => trim($request->name),
                'username' => $username,
                'weight_kg' => 60.00,
            ]);

            UserSetting::create([
                'user_id' => $user->id,
                'theme' => 'dark',
                'language' => 'id',
                'timezone' => 'Asia/Jakarta',
            ]);

            Streak::create([
                'user_id' => $user->id,
                'current_streak' => 0,
                'longest_streak' => 0,
            ]);

            ActivityTarget::create([
                'user_id' => $user->id,
                'daily_distance_km' => 5.00,
                'daily_calories' => 300,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->back()->with('success_admin', 'Admin baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['admin_error' => 'Gagal menambah admin: ' . $e->getMessage()]);
        }
    }
}