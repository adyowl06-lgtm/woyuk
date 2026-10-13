<?php

namespace App\Services;

use App\Activity;
use App\Services\StreakService;

class ActivityValidationService
{
    // Pengaturan minimal jarak (dalam satuan meter)
    const MIN_DISTANCE_METERS = 0.0; 

    public static function validateAndComplete(Activity $activity): array
    {
        if ($activity->distance_meters >= self::MIN_DISTANCE_METERS) {
            $activity->update([
                'status' => 'completed',
                'end_time' => now(),
            ]);

            // Update Streak Harian User
            StreakService::updateStreak($activity->user_id);

            return [
                'isValid' => true,
                'message' => 'Aktivitas tuntas! Streak sampeyan bertambah.'
            ];
        } else {
            $activity->update([
                'status' => 'discarded',
                'end_time' => now(),
            ]);

            return [
                'isValid' => false,
                'message' => 'Jarak kurang saka 10 meter (0,01 KM). Aktivitas ora dicathet ing streak.'
            ];
        }
    }
}