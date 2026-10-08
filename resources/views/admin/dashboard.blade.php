@extends('layouts.app')

@section('title', 'Admin Dashboard — WOYuk!')

@push('styles')
<style>
    /* Glow Accent Effect Khusus Card Statistik Admin */
    .glow-users { box-shadow: 0 0 35px -5px rgba(56, 189, 248, 0.35); }
    .glow-running { box-shadow: 0 0 35px -5px rgba(6, 182, 212, 0.35); }
    .glow-walking { box-shadow: 0 0 35px -5px rgba(16, 185, 129, 0.35); }
    .glow-cycling { box-shadow: 0 0 35px -5px rgba(249, 115, 22, 0.35); }
    .glow-peak { box-shadow: 0 0 35px -5px rgba(168, 85, 247, 0.35); }
</style>
@endpush

@section('content')
@include('components.navbar')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8 pb-24">

    <!-- HEADER TITLE & STATS STATUS (HANYA MUNCUL 1 KALI) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-800/80 pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/30 text-[10px] font-mono font-bold text-orange-400 uppercase tracking-widest">
                    SYSTEM CONTROL
                </span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black italic uppercase tracking-wider text-white">
                Dashboard <span class="text-orange-500">Administrator</span>
            </h1>
            <p class="text-xs text-gray-400">Ringkasan statistik real-time platform WOYuk!</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
            <div class="px-4 py-2 rounded-2xl glass-card-dark border border-gray-800 text-xs font-mono text-gray-300 text-center sm:text-right">
                Total Aktivitas Selesai: <strong class="text-orange-400 font-bold text-sm">{{ number_format($totalCompletedActivities) }}</strong>
            </div>

            <!-- TOMBOL TAMBAH ADMIN BARU -->
            <button type="button" onclick="openAddAdminModal()" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-lg cursor-pointer flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Admin</span>
            </button>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES / ERROR TAMBAH ADMIN -->
    @if(session('success_admin'))
        <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-300 p-4 rounded-2xl text-xs font-bold">
            {{ session('success_admin') }}
        </div>
    @endif
    @if($errors->has('admin_error'))
        <div class="bg-red-500/20 border border-red-500 text-red-300 p-4 rounded-2xl text-xs font-bold">
            {{ $errors->first('admin_error') }}
        </div>
    @endif

    <!-- GRID METRICS CARDS UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- CARD 1: TOTAL USERS -->
        <div class="glass-card-dark p-5 sm:p-6 rounded-3xl border border-gray-800/90 space-y-3 relative overflow-hidden group hover:border-sky-500/50 transition-all duration-300 hover:glow-users">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">TOTAL PENGGUNA</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-white font-mono tracking-tight">{{ number_format($totalUsers) }}</div>
                <p class="text-[11px] text-gray-400 mt-1">Member terdaftar di sistem</p>
            </div>
        </div>

        <!-- CARD 2: AKTIVITAS LARI -->
        <div class="glass-card-dark p-5 sm:p-6 rounded-3xl border border-gray-800/90 space-y-3 relative overflow-hidden group hover:border-cyan-500/50 transition-all duration-300 hover:glow-running">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">SESI LARI</span>
                <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-cyan-400 font-mono tracking-tight">{{ number_format($totalRunning) }}</div>
                <p class="text-[11px] text-gray-400 mt-1">Aktivitas Lari Selesai</p>
            </div>
        </div>

        <!-- CARD 3: AKTIVITAS JALAN -->
        <div class="glass-card-dark p-5 sm:p-6 rounded-3xl border border-gray-800/90 space-y-3 relative overflow-hidden group hover:border-emerald-500/50 transition-all duration-300 hover:glow-walking">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">SESI JALAN</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-emerald-400 font-mono tracking-tight">{{ number_format($totalWalking) }}</div>
                <p class="text-[11px] text-gray-400 mt-1">Aktivitas Jalan Selesai</p>
            </div>
        </div>

        <!-- CARD 4: AKTIVITAS BERSEPEDA -->
        <div class="glass-card-dark p-5 sm:p-6 rounded-3xl border border-gray-800/90 space-y-3 relative overflow-hidden group hover:border-orange-500/50 transition-all duration-300 hover:glow-cycling">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">SESI BERSEPEDA</span>
                <div class="w-10 h-10 rounded-2xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-orange-400 font-mono tracking-tight">{{ number_format($totalCycling) }}</div>
                <p class="text-[11px] text-gray-400 mt-1">Aktivitas Sepeda Selesai</p>
            </div>
        </div>

    </div>

    <!-- AREA DETAIL ANALYSIS: DIAGRAM BATANG & TABEL USER TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- BOX DIAGRAM BATANG (PEAK DAY BAR CHART) -->
        <div class="lg:col-span-5 glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800/90 space-y-6 hover:glow-peak transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black italic uppercase text-white tracking-wider">HARI PALING AKTIF</h3>
                        <p class="text-xs text-gray-400">Grafik rasio kegiatan pengguna</p>
                    </div>
                </div>
                <span class="text-[10px] font-mono text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full border border-purple-500/20">
                    Puncak: {{ $peakDayTranslated }}
                </span>
            </div>

            <!-- VISUAL DIAGRAM BATANG -->
            <div class="p-4 rounded-2xl bg-gray-950/60 border border-gray-800 space-y-3">
                <div class="h-44 flex items-end justify-between gap-2 pt-6 pb-2 px-2 border-b border-gray-800 relative">
                    <!-- Garis-garis kisi persentase (Grid Lines) -->
                    <div class="absolute inset-x-0 top-2 border-b border-gray-800/40 border-dashed text-[9px] font-mono text-gray-600 pl-1">100%</div>
                    <div class="absolute inset-x-0 top-1/2 border-b border-gray-800/40 border-dashed text-[9px] font-mono text-gray-600 pl-1">50%</div>

                    @foreach($chartData as $day)
                        <div class="flex-1 flex flex-col items-center h-full justify-end group relative z-10">
                            <!-- Tooltip On Hover -->
                            <div class="absolute -top-8 hidden group-hover:flex flex-col items-center z-20">
                                <span class="bg-gray-900 text-purple-300 text-[10px] font-mono px-2 py-0.5 rounded border border-purple-500/30 whitespace-nowrap shadow-lg">
                                    {{ $day['count'] }} Sesi ({{ $day['percentage'] }}%)
                                </span>
                            </div>

                            <!-- Batang Diagram (Bar) -->
                            <div class="w-full max-w-[28px] bg-gray-900 rounded-t-lg overflow-hidden flex items-end p-0.5 transition-all duration-500 h-full">
                                <div class="w-full bg-gradient-to-t from-purple-800 to-purple-500 rounded-t-md transition-all duration-700 group-hover:brightness-125"
                                     style="height: {{ max(6, $day['percentage']) }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Label Hari (Sen - Min) -->
                <div class="flex items-center justify-between gap-2 px-2 text-[10px] font-mono text-gray-400 uppercase font-bold text-center">
                    @foreach($chartData as $day)
                        <div class="flex-1 {{ $day['full_day'] === $peakDayTranslated ? 'text-purple-400 font-black' : '' }}">
                            {{ $day['short_day'] }}
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-[11px] text-gray-400 leading-relaxed italic">
                ©  <span class="text-purple-300 font-mono">Copyright 2026</span>
            </p>
        </div>

        <!-- TABEL LIST PENGGUNA TERBARU -->
        <div class="lg:col-span-7 glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800/90 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-800/80 pb-4">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-white tracking-wider">Pengguna Terbaru</h3>
                    <p class="text-xs text-gray-400">5 member terakhir yang mendaftar</p>
                </div>
                <span class="text-xs font-mono text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-full border border-orange-500/20">
                    Latest Users
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-gray-400 font-mono uppercase text-[10px] border-b border-gray-800">
                            <th class="pb-3">Pengguna</th>
                            <th class="pb-3">Public ID</th>
                            <th class="pb-3">Role</th>
                            <th class="pb-3 text-right">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($latestUsers as $u)
                            <tr class="hover:bg-gray-900/40 transition-colors">
                                <td class="py-3.5 pr-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-orange-600/20 border border-orange-500/30 flex items-center justify-center font-bold text-orange-400 text-xs shrink-0 overflow-hidden">
                                            @if($u->profile && $u->profile->avatar)
                                                <img src="{{ asset('storage/' . $u->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($u->profile->name ?? $u->email, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div class="truncate max-w-[120px] sm:max-w-[180px]">
                                            <div class="font-bold text-gray-200 truncate">{{ $u->profile->name ?? 'User' }}</div>
                                            <div class="text-[10px] text-gray-500 truncate">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 font-mono text-orange-400 font-bold">
                                    {{ $u->public_id }}
                                </td>
                                <td class="py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase {{ $u->role === 'admin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-gray-800 text-gray-400' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right font-mono text-gray-400 text-[10px]">
                                    {{ $u->created_at ? $u->created_at->diffForHumans() : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL FORM TAMBAH ADMIN BARU -->
    <div id="add-admin-modal" class="fixed inset-0 z-[9990] bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-md glass-card-dark border border-gray-800 rounded-3xl p-6 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                <h3 class="text-sm font-black italic uppercase text-white tracking-wider">Tambah Admin Baru</h3>
                <button type="button" onclick="closeAddAdminModal()" class="text-gray-400 hover:text-white text-xs">✕</button>
            </div>

            <form action="{{ route('admin.create') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1 font-mono uppercase text-[10px]">Nama Admin</label>
                    <input type="text" name="name" required placeholder="Contoh: Admin Utama" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1 font-mono uppercase text-[10px]">Email Admin</label>
                    <input type="email" name="email" required placeholder="adminbaru@woyuk.com" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1 font-mono uppercase text-[10px]">Password (Min 8 Karakter)</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-500">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" onclick="closeAddAdminModal()" class="w-1/2 py-2.5 bg-gray-900 hover:bg-gray-800 text-gray-300 font-bold rounded-xl uppercase">Batal</button>
                    <button type="submit" class="w-1/2 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl uppercase shadow-lg">Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddAdminModal() {
            document.getElementById('add-admin-modal').classList.remove('hidden');
        }
        function closeAddAdminModal() {
            document.getElementById('add-admin-modal').classList.add('hidden');
        }
    </script>

</main>
@endsection