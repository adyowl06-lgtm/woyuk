@extends('layouts.app')

@section('title', 'Pengaturan Profil — WOYuk!')

@section('content')
@include('components.navbar')

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- HEADER SECTION -->
    <div class="flex items-center justify-between border-b border-gray-800 pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black italic uppercase text-white tracking-wider flex items-center gap-3">
                <span class="w-3 h-8 bg-orange-600 rounded-full inline-block"></span>
                Profil & Pengaturan
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Atur data diri lan preferensi zona wektu akun sampeyan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-gray-200 hover:text-white font-bold rounded-xl text-xs uppercase tracking-wider border border-gray-700/80 transition-all flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/40 rounded-2xl text-emerald-300 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- FORM 1: DATA PROFIL -->
        <div class="glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-800/80 pb-4">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black italic uppercase text-white tracking-wider">Data Diri</h2>
                    <p class="text-[10px] text-gray-400">Informasi fisik untuk kalkulasi kalori & metrik</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->profile->name ?? '') }}" required
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors placeholder-gray-400">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Berat (KG)</label>
                        <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $user->profile->weight_kg ?? '') }}" placeholder="65"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Tinggi (CM)</label>
                        <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $user->profile->height_cm ?? '') }}" placeholder="170"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors placeholder-gray-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Jenis Kelamin</label>
                    <select name="gender" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors">
                        <option value="" class="text-gray-900">-- Pilih --</option>
                        <option value="male" {{ (old('gender', $user->profile->gender ?? '') === 'male') ? 'selected' : '' }} class="text-gray-900">Laki-Laki</option>
                        <option value="female" {{ (old('gender', $user->profile->gender ?? '') === 'female') ? 'selected' : '' }} class="text-gray-900">Perempuan</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold py-3.5 rounded-xl uppercase tracking-wider text-xs transition-all sport-glow cursor-pointer">
                    Simpan Profil
                </button>
            </form>
        </div>

        <!-- FORM 2: PREFERENSI SETTINGS -->
        <div class="glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-800/80 pb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black italic uppercase text-white tracking-wider">Pengaturan Sistem</h2>
                    <p class="text-[10px] text-gray-400">Preferensi zona waktu untuk pencatatan streak</p>
                </div>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Zona Waktu (Timezone)</label>
                    <select name="timezone" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="Asia/Jakarta" {{ (old('timezone', $user->setting->timezone ?? '') === 'Asia/Jakarta') ? 'selected' : '' }} class="text-gray-900">Asia/Jakarta (WIB)</option>
                        <option value="Asia/Makassar" {{ (old('timezone', $user->setting->timezone ?? '') === 'Asia/Makassar') ? 'selected' : '' }} class="text-gray-900">Asia/Makassar (WITA)</option>
                        <option value="Asia/Jayapura" {{ (old('timezone', $user->setting->timezone ?? '') === 'Asia/Jayapura') ? 'selected' : '' }} class="text-gray-900">Asia/Jayapura (WIT)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-3.5 rounded-xl uppercase tracking-wider text-xs transition-all sport-glow cursor-pointer">
                    Simpan Pengaturan
                </button>
            </form>
        </div>

    </div>

</main>
@endsection