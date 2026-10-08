@extends('layouts.app')

@section('title', 'Dashboard — WOYuk!')

@push('styles')
<style>
    /* Keyframe Animasi Putar Ring Tombol Utama saat Transisi Mode */
    @keyframes spin-ring {
        0% { transform: rotate(0deg) scale(0.95); }
        50% { transform: rotate(180deg) scale(1.05); }
        100% { transform: rotate(360deg) scale(1); }
    }
    .animate-spin-ring {
        animation: spin-ring 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Dynamic Glow Accents */
    .glow-running { box-shadow: 0 0 35px -5px rgba(6, 182, 212, 0.4); }
    .glow-walking { box-shadow: 0 0 35px -5px rgba(16, 185, 129, 0.4); }
    .glow-cycling { box-shadow: 0 0 35px -5px rgba(249, 115, 22, 0.4); }

    /* Accordion Expand/Collapse Smooth Animation */
    .accordion-content {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-in-out, opacity 0.3s ease-in-out, margin 0.3s ease;
    }
    .accordion-content.open {
        max-height: 500px;
        opacity: 1;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
@include('components.navbar')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8 sm:space-y-10 pb-36">

    <!-- NOTIFIKASI ERROR / SUCCESS LARAVEL -->
    @if(session('errors'))
        <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-2xl text-xs font-bold text-center">
            {{ session('errors')->first() }}
        </div>
    @endif

    <!-- 1. TEXT STATUS TANPA LABEL BOX -->
    <div class="flex items-center justify-center sm:justify-start gap-2.5">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse shadow-md"></span>
        <span class="text-xs font-black uppercase tracking-widest text-emerald-400 font-mono">Sistem Siap Tracking</span>
    </div>

    <!-- 2. CARD STREAK & WELCOME BANNER -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <!-- Welcome Title & Subtitle -->
        <div class="lg:col-span-6 flex flex-col justify-center text-center sm:text-left space-y-2 sm:space-y-3">
            <h1 class="text-xl sm:text-3xl font-black italic uppercase tracking-wider text-white leading-tight">
                Sugeng Rawuh, <span id="user-display-name" class="text-cyan-400 transition-colors duration-300 inline">{{ $user->profile->name ?? 'Atlet' }}</span>!
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-400 leading-relaxed">
                Pilih aktivitas, urupno GPS, lan mulai obah dina iki.
            </p>
        </div>

        <!-- Classic Streak Emblem Card -->
        <div class="lg:col-span-6">
            <div class="glass-card-dark p-4 sm:p-5 rounded-3xl border border-gray-800/90 flex items-center justify-between shadow-xl h-full">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div id="streak-icon-bg" class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white shadow-lg transition-all duration-500 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest">STREAK AKTIF</div>
                        <div class="text-xl sm:text-2xl font-black text-white italic tracking-wider font-mono">
                            {{ $user->streak->current_streak ?? 0 }} <span class="text-[10px] sm:text-xs font-normal text-cyan-400 uppercase">HARI</span>
                        </div>
                    </div>
                </div>
                <div class="text-right border-l border-gray-800/80 pl-4 sm:pl-6">
                    <div class="text-[8px] sm:text-[9px] text-gray-500 uppercase tracking-wider">REKOR TERTINGGI</div>
                    <div class="text-xs sm:text-sm font-bold text-gray-200 font-mono">{{ $user->streak->longest_streak ?? 0 }} HARI</div>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. MAIN INTERACTION AREA: TOMBOL MULAI & ACCORDION METRICS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- CARD TOMBOL MULAI (HERO) -->
        <div class="lg:col-span-6 glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800/90 text-center relative overflow-hidden transition-all duration-500" id="hero-card">
            <div class="space-y-5 sm:space-y-6 relative z-10">
                <!-- Mode Badge -->
                <div>
                    <span id="selected-sport-label" class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold uppercase tracking-widest transition-all duration-300">
                        MODE: LARI
                    </span>
                </div>

                <!-- DIRECT FORM SUBMIT -->
                <form action="{{ route('activity.start') }}" method="POST" id="main-tracking-form">
                    @csrf
                    <input type="hidden" name="sport_type" id="main-sport-type" value="running">

                    <div class="relative w-40 h-40 sm:w-48 sm:h-48 mx-auto flex items-center justify-center">
                        <div id="hero-button-ring" class="absolute inset-0 rounded-full border-2 border-cyan-400/30 transition-all duration-500 pointer-events-none"></div>
                        
                        <button type="submit" id="hero-start-btn"
                            class="w-36 h-36 sm:w-44 sm:h-44 rounded-full bg-gradient-to-tr from-cyan-600 to-blue-500 hover:from-cyan-500 hover:to-blue-400 text-white font-black italic text-xl sm:text-2xl uppercase tracking-widest shadow-2xl glow-running border-4 border-cyan-300/30 transition-all duration-500 active:scale-95 hover:scale-105 flex items-center justify-center cursor-pointer">
                            MULAI
                        </button>
                    </div>
                </form>

                <p class="text-xs text-gray-300 max-w-xs mx-auto">
                    GPS Aktif • Minimal Validasi <strong class="text-white">10 Meter</strong>
                </p>
            </div>
        </div>

        <!-- BOX PROGRESS METRICS NAVBAR -->
        <div class="lg:col-span-6 glass-card-dark p-5 sm:p-6 rounded-3xl border border-gray-800/90 space-y-4">
            
            <button type="button" onclick="toggleMetricsAccordion()" class="w-full flex items-center justify-between p-2 rounded-2xl hover:bg-gray-900/50 transition-colors group focus:outline-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center text-cyan-400 flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="text-xs font-black italic uppercase text-white tracking-wider">TARGET HARI INI</div>
                        <div class="text-[10px] text-gray-300">Klik kanggo deleng selengkapnya</div>
                    </div>
                </div>

                <div id="accordion-arrow" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-300 group-hover:text-white transition-transform duration-300 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Preview Ringkas -->
            <div id="metrics-preview" class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-800/60 text-xs font-mono">
                <div>
                    <span class="text-[10px] text-gray-400 uppercase block">JARAK</span>
                    <span class="font-bold text-cyan-400">{{ $todayDistanceKm }} / {{$targetDistanceKm }} KM</span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 uppercase block">KALORI</span>
                    <span class="font-bold text-amber-400">{{ $todayCalories }} / {{$targetCalories }} KCAL</span>
                </div>
            </div>

            <!-- Accordion Content Detail Data -->
            <div id="metrics-accordion" class="accordion-content space-y-6">
                <div class="space-y-2 bg-gray-950/60 p-4 rounded-2xl border border-gray-800/80">
                    <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider">
                        <span class="text-gray-200">DISTANCE TODAY</span>
                        <span id="progress-distance-text" class="text-cyan-400 font-mono text-sm">{{ $todayDistanceKm }} / {{$targetDistanceKm }} KM</span>
                    </div>
                    
                    <div class="w-full bg-gray-900 h-3 rounded-full overflow-hidden p-0.5 border border-gray-800">
                        <div id="progress-distance-bar" class="bg-gradient-to-r from-cyan-600 to-blue-400 h-full rounded-full transition-all duration-500"
                            style="width: {{ $distanceProgressPercent }}%;"></div>
                    </div>
                    <div class="text-right text-[10px] text-gray-300 font-mono">{{ $distanceProgressPercent }}% Target Terpenuhi</div>
                </div>

                <div class="space-y-2 bg-gray-950/60 p-4 rounded-2xl border border-gray-800/80">
                    <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider">
                        <span class="text-gray-200">CALORIES TODAY</span>
                        <span id="progress-calories-text" class="text-amber-400 font-mono text-sm">{{ $todayCalories }} / {{$targetCalories }} KCAL</span>
                    </div>

                    <div class="w-full bg-gray-900 h-3 rounded-full overflow-hidden p-0.5 border border-gray-800">
                        <div id="progress-calories-bar" class="bg-gradient-to-r from-amber-500 to-yellow-400 h-full rounded-full transition-all duration-500"
                            style="width: {{ $caloriesProgressPercent }}%;"></div>
                    </div>
                    <div class="text-right text-[10px] text-gray-300 font-mono">{{ $caloriesProgressPercent }}% Target Terpenuhi</div>
                </div>
            </div>

        </div>

    </div>

</main>

<!-- 4. COMPACT FLOATING DOCK -->
<div class="fixed bottom-4 sm:bottom-6 inset-x-0 mx-auto z-40 w-max max-w-[95vw]">
    <div class="bg-gray-950/90 backdrop-blur-xl p-1.5 rounded-2xl border border-gray-800/90 shadow-2xl">
        <div class="flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-wider" id="sport-selector">

            <!-- Tab Lari (Default Active) -->
            <button type="button" onclick="selectSport('running')" id="tab-running"
                class="sport-tab py-2.5 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 text-white font-black bg-cyan-600 shadow-md cursor-pointer">
                <svg id="icon-running" class="w-4 h-4 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="tab-label inline text-xs">Lari</span>
            </button>

            <!-- Tab Jalan -->
            <button type="button" onclick="selectSport('walking')" id="tab-walking"
                class="sport-tab py-2.5 px-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 text-white font-black bg-transparent hover:bg-gray-800/50 cursor-pointer">
                <svg id="icon-walking" class="w-4 h-4 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <span class="tab-label hidden text-xs">Jalan</span>
            </button>

            <!-- Tab Bersepeda -->
            <button type="button" onclick="selectSport('cycling')" id="tab-cycling"
                class="sport-tab py-2.5 px-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 text-white font-black bg-transparent hover:bg-gray-800/50 cursor-pointer">
                <svg id="icon-cycling" class="w-4 h-4 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="tab-label hidden text-xs">Bersepeda</span>
            </button>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeSport = 'running';

    const sportThemes = {
        running: {
            label: 'MODE: LARI',
            btnBg: 'bg-gradient-to-tr from-cyan-600 to-blue-500 hover:from-cyan-500 hover:to-blue-400',
            glowClass: 'glow-running',
            borderColor: 'border-cyan-300/30',
            badgeClass: 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400',
            activeTabBg: 'bg-cyan-600 shadow-md',
            nameColor: 'text-cyan-400'
        },
        walking: {
            label: 'MODE: JALAN',
            btnBg: 'bg-gradient-to-tr from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400',
            glowClass: 'glow-walking',
            borderColor: 'border-emerald-300/30',
            badgeClass: 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400',
            activeTabBg: 'bg-emerald-600 shadow-md',
            nameColor: 'text-emerald-400'
        },
        cycling: {
            label: 'MODE: BERSEPEDA',
            btnBg: 'bg-gradient-to-tr from-orange-600 to-amber-500 hover:from-orange-500 hover:to-amber-400',
            glowClass: 'glow-cycling',
            borderColor: 'border-orange-300/30',
            badgeClass: 'bg-orange-500/10 border-orange-500/30 text-orange-400',
            activeTabBg: 'bg-orange-600 shadow-md',
            nameColor: 'text-orange-400'
        }
    };

    function selectSport(sport) {
        if (activeSport === sport) return;
        activeSport = sport;

        const theme = sportThemes[sport];

        document.querySelectorAll('.sport-tab').forEach(tab => {
            const label = tab.querySelector('.tab-label');
            
            if (tab.id === `tab-${sport}`) {
                tab.className = `sport-tab py-2.5 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 text-white font-black ${theme.activeTabBg} cursor-pointer`;
                if(label) label.classList.remove('hidden');
            } else {
                tab.className = 'sport-tab py-2.5 px-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 text-white font-black bg-transparent hover:bg-gray-800/50 cursor-pointer';
                if(label) label.classList.add('hidden');
            }
        });

        const ring = document.getElementById('hero-button-ring');
        ring.classList.remove('animate-spin-ring');
        void ring.offsetWidth;
        ring.classList.add('animate-spin-ring');

        const startBtn = document.getElementById('hero-start-btn');
        startBtn.className = `w-36 h-36 sm:w-44 sm:h-44 rounded-full ${theme.btnBg} text-white font-black italic text-xl sm:text-2xl uppercase tracking-widest shadow-2xl ${theme.glowClass} border-4 ${theme.borderColor} transition-all duration-500 active:scale-95 hover:scale-105 flex items-center justify-center cursor-pointer`;

        const labelEl = document.getElementById('selected-sport-label');
        labelEl.innerText = theme.label;
        labelEl.className = `inline-block px-4 py-1.5 rounded-full ${theme.badgeClass} text-xs font-bold uppercase tracking-widest transition-all duration-300`;

        document.getElementById('user-display-name').className = `${theme.nameColor} transition-colors duration-300 inline`;
        document.getElementById('main-sport-type').value = sport;
    }

    function toggleMetricsAccordion() {
        const accordion = document.getElementById('metrics-accordion');
        const arrow = document.getElementById('accordion-arrow');
        const preview = document.getElementById('metrics-preview');

        if (accordion.classList.contains('open')) {
            accordion.classList.remove('open');
            arrow.style.transform = 'rotate(0deg)';
            preview.classList.remove('hidden');
        } else {
            accordion.classList.add('open');
            arrow.style.transform = 'rotate(180deg)';
            preview.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection