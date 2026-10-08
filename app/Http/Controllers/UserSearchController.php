<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\DB;

class UserSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Search users by Name, Username, or Public ID
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([
                'status' => 'success',
                'data' => []
            ]);
        }

        // Cari berdasarkan public_id (users) atau username/name (user_profiles)
        $users = User::select('users.id', 'users.public_id', 'p.name', 'p.username', 'p.avatar', 'p.bio')
            ->join('user_profiles as p', 'users.id', '=', 'p.user_id')
            ->where(function ($q) use ($query) {
                $q->where('users.public_id', 'LIKE', "%{$query}%")
                  ->orWhere('p.username', 'LIKE', "%{$query}%")
                  ->orWhere('p.name', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get();

        // Format data pratinjau profil
        $formatted = $users->map(function ($u) {
            return [
                'public_id' => $u->public_id,
                'name' => $u->name ?? 'Pengguna WOYuk!',
                'username' => $u->username ?? 'user',
                'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : null,
                'bio' => $u->bio ? \Illuminate\Support\Str::limit($u->bio, 60) : 'Member WOYuk!',
                'profile_url' => route('user.profile.show', ['public_id' => $u->public_id])
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted
        ]);
    }

    /**
     * Tampilkan Halaman Profil Publik Pengguna Lain berdasarkan public_id
     */
    public function showProfile($public_id)
    {
        $targetUser = User::where('public_id', $public_id)->firstOrFail();
        
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $targetUser->id],
            ['name' => 'User ' . $targetUser->public_id]
        );

        // Ambil statistik ringkas aktivitas publik (opsional)
        $totalActivities = DB::table('activities')
            ->where('user_id', $targetUser->id)
            ->where('status', 'completed')
            ->count();

        $totalDistanceKm = DB::table('activities')
            ->where('user_id', $targetUser->id)
            ->where('status', 'completed')
            ->sum(DB::raw('distance_meters / 1000'));

        return view('profile.public_show', compact('targetUser', 'profile', 'totalActivities', 'totalDistanceKm'));
    }
}