@extends('layouts.app')

@section('title', 'WOYuk! — Obah Bareng, Konsisten Bendino')

@section('content')
<!-- Universal Navbar -->
@include('components.navbar')

<!-- HERO SECTION -->
<section class="relative min-h-[calc(100vh-80px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-gray-950">
    
    <!-- Background Glow Subtle Accent -->
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
        
        <!-- SISI KIRI: Jargon Jawa & Image Preview -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Badge Heritage Sport -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card-dark border border-orange-500/40 text-orange-400 text-xs font-bold uppercase tracking-widest shadow-md">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-ping"></span>
                WO, yuk olahraga!
            </div>

            <!-- Jargon Utama Bahasa Jawa (Teks Putih Kontras) -->
            <div class="space-y-4">
                <h1 class="text-4xl sm:text-6xl font-black italic tracking-tight text-gray-400 uppercase leading-none">
                    OBAH BARENG, <br>
                    <span class="text-orange-500">
                        KONSISTEN BENDINO.
                    </span>
                </h1>
                <p class="text-gray-300 text-base sm:text-lg font-normal max-w-xl leading-relaxed">
                    Lacak mlayu, mlaku, lan pit-pitanmu presisi tanpa batas.
                </p>
            </div>

            <!-- HERO IMAGE SECTION -->
            <div class="relative rounded-3xl overflow-hidden glass-card-dark border border-gray-800/90 p-2 max-w-xl shadow-2xl">
                <img src="{{ asset('assets/images/hero-sports.jpg') }}" 
                     alt="WOYuk! Sports Tracking" 
                     class="w-full h-64 sm:h-72 object-cover rounded-2xl transition-all hover:scale-105 duration-500"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-64 sm:h-72 bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl flex flex-col items-center justify-center text-gray-400 border border-gray-800 p-6 text-center\'><svg class=\'w-12 h-12 mb-3 text-orange-500/80\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M13 10V3L4 14h7v7l9-11h-7z\'/></svg><span class=\'text-xs font-bold uppercase tracking-wider text-gray-200\'>WOYuk! Sports Activity Image Placeholder</span></div>';">
            </div>

            <!-- Quick Sport Badges -->
            <div class="flex items-center gap-6 pt-2 text-xs font-bold uppercase tracking-wider text-gray-300">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500 shadow-sm shadow-orange-500"></span>
                    Lari
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 shadow-sm shadow-amber-500"></span>
                    Jalan
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500"></span>
                    Bersepeda
                </div>
            </div>

        </div>

        <!-- SISI KANAN: Glassmorphism Login Card -->
        <div class="lg:col-span-5">
            <div class="glass-card-dark p-8 sm:p-10 rounded-3xl border border-gray-800/90 shadow-2xl relative space-y-6">
                
                <div class="border-b border-gray-800 pb-4">
                    <h2 class="text-xl font-black italic uppercase text-white tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-orange-600 rounded-full inline-block"></span>
                        Mlebu Akun
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Mulai catet aktivitas olahragamu dina iki.</p>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-red-500/10 border border-red-500/40 rounded-2xl text-red-300 text-xs font-bold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-200 uppercase mb-2">Email</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Password</label>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-colors placeholder-gray-400">
                    </div>

                    <button type="submit" 
                        class="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold py-3.5 rounded-xl uppercase tracking-wider text-xs transition-all duration-200 sport-glow cursor-pointer">
                        Masuk / Login
                    </button>
                </form>

                <!-- Footer Card Link -->
                <div class="pt-4 border-t border-gray-800 text-center text-xs text-gray-400">
                    Durung duwe akun? 
                    <a href="{{ route('register') }}" class="text-orange-400 font-bold hover:underline ml-1">
                        Gawe akun anyar
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- SECTION FITUR-FITUR UTAMA PROJECT WOYUK! -->
<section id="fitur-section" class="py-16 sm:py-24 bg-gray-950 border-t border-gray-900 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-12">
        
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <h2 class="text-2xl sm:text-4xl font-black italic uppercase text-white tracking-wider">
                FITUR UNGGULAN <span class="text-orange-500">WOYUK!</span>
            </h2>
            <p class="text-xs sm:text-sm text-gray-400">
                Aplikasi pencatat aktivitas olahraga modern kanthi tampilan presisi lan responsif.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Fitur 1: Tracking GPS -->
            <div class="glass-card-dark p-6 rounded-3xl border border-gray-800 space-y-4 hover:border-orange-500/50 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-black italic uppercase text-white">Tracking GPS Realtime</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Pencatatan rute jalan, lari, lan bersepeda presisi kanthi peta interaktif Leaflet JS.</p>
            </div>

            <!-- Fitur 2: Daily Streak System -->
            <div class="glass-card-dark p-6 rounded-3xl border border-gray-800 space-y-4 hover:border-cyan-500/50 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-lg font-black italic uppercase text-white">Streak Active System</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Jaga konsistensi olahraga harian lan cetak rekor streak paling dhuwur saben dina.</p>
            </div>

            <!-- Fitur 3: Target Metrik -->
            <div class="glass-card-dark p-6 rounded-3xl border border-gray-800 space-y-4 hover:border-emerald-500/50 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-lg font-black italic uppercase text-white">Target Jarak & Kalori</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Pantau akumulasi jarak (KM) lan estimasi kalori (KCAL) terkuantifikasi harian.</p>
            </div>

            <!-- Fitur 4: Arsip & Visualisasi Rute -->
            <div class="glass-card-dark p-6 rounded-3xl border border-gray-800 space-y-4 hover:border-amber-500/50 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-black italic uppercase text-white">Arsip & Rekap Rute</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Riwayat aktivitas tersimpan rapi lengkap kanthi filter olahraga lan peta rekap rute GPS.</p>
            </div>

        </div>
    </div>
</section>
@endsection