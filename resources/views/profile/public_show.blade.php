@extends('layouts.app')

@section('title', $profile->name . ' — WOYuk!')

@section('content')
@include('components.dashboard-navbar')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <!-- Banner & Header Card -->
    <div class="glass-card-dark border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="h-32 bg-gradient-to-r from-orange-600/30 via-orange-500/10 to-gray-900 border-b border-gray-800 relative"></div>
        
        <div class="px-6 pb-6 relative flex flex-col sm:flex-row items-center sm:items-end justify-between -mt-12 gap-4">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                <div class="w-24 h-24 rounded-2xl bg-gray-900 border-2 border-orange-500 flex items-center justify-center overflow-hidden shadow-xl sport-glow">
                    @if($profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-black text-orange-500 uppercase">{{ substr($profile->name, 0, 2) }}</span>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white italic tracking-wide">{{ $profile->name }}</h1>
                    <p class="text-xs font-mono text-orange-400">@ {{ $profile->username }}</p>
                </div>
            </div>

            <div class="px-3 py-1.5 rounded-full bg-gray-800/80 border border-gray-700 text-xs font-mono text-gray-300">
                Public ID: <span class="text-orange-400 font-bold">{{ $targetUser->public_id }}</span>
            </div>
        </div>

        <div class="px-6 pb-6 border-t border-gray-800/60 pt-4">
            <p class="text-sm text-gray-300 italic">{{ $profile->bio ?? 'Member aktif WOYuk!' }}</p>
        </div>
    </div>

    <!-- Ringkasan Statistik Olahraga Publik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="glass-card-dark border border-gray-800 p-6 rounded-2xl space-y-2">
            <span class="text-xs font-mono text-gray-400 uppercase tracking-widest">Total Aktivitas</span>
            <div class="text-3xl font-black text-orange-500 font-sans">{{ $totalActivities }} <span class="text-xs text-gray-400 font-normal">Sesi</span></div>
        </div>

        <div class="glass-card-dark border border-gray-800 p-6 rounded-2xl space-y-2">
            <span class="text-xs font-mono text-gray-400 uppercase tracking-widest">Total Jarak Tempuh</span>
            <div class="text-3xl font-black text-orange-500 font-sans">{{ number_format($totalDistanceKm, 2) }} <span class="text-xs text-gray-400 font-normal">KM</span></div>
        </div>
    </div>
</div>
@endsection