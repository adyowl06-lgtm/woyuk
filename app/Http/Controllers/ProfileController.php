<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserProfile;
use App\UserSetting;
use Exception;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();

        // Buat data profil & setting default jika belum ada di DB
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->email]
        );

        $setting = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            ['timezone' => 'Asia/Jakarta']
        );

        return view('profile.edit', compact('user', 'profile', 'setting'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight_kg' => 'nullable|numeric|min:20|max:300',
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'gender' => 'nullable|in:male,female',
        ]);

        $user = Auth::user();
        
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->name,
                'weight_kg' => $request->weight_kg,
                'height_cm' => $request->height_cm,
                'gender' => $request->gender,
            ]
        );

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        
        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            ['timezone' => $request->timezone]
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}