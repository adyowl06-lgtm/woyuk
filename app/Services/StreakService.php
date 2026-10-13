<?php

namespace App\Services;

use App\Streak;
use Carbon\Carbon;

class StreakService
{
    /**
     * Memperbarui Streak Harian Pengguna Saat Aktivitas Valid Selesai
     */
    public static function updateStreak(int $userId): void
    {
        $streak = Streak::firstOrCreate(
            ['user_id' => $userId],
            ['current_streak' => 0, 'longest_streak' => 0, 'last_qualifying_date' => null]
        );

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        if ($streak->last_qualifying_date === $today) {
            // Sudah menambah streak hari ini
            return;
        }

        if ($streak->last_qualifying_date === $yesterday) {
            // Melanjutkan streak kemarin
            $streak->current_streak += 1;
        } else {
            // Streak terputus, mulai dari 1
            $streak->current_streak = 1;
        }

        if ($streak->current_streak > $streak->longest_streak) {
            $streak->longest_streak = $streak->current_streak;
        }

        $streak->last_qualifying_date = $today;
        $streak->save();
    }
}