@extends('layouts.app')

@section('title', 'Arsip & Riwayat Aktivitas — WOYuk!')

@push('styles')
<style>
    #recap-map {
        height: 320px;
        width: 100%;
        border-radius: 1rem;
    }
    
    /* Animasi Smooth untuk Item Daftar Aktivitas */
    .archive-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .archive-card.hidden-filter {
        display: none !important;
        opacity: 0;
        transform: scale(0.95);
    }
</style>
@endpush

@section('content')
<!-- Navigation Bar Universal -->
@include('components.navbar')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 pb-28">

    <!-- HEADER SECTION & SMOOTH FILTER TABS -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-800 pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black italic uppercase text-white tracking-wider flex items-center gap-3">
                <span class="w-3 h-8 bg-orange-600 rounded-full inline-block"></span>
                Arsip & Riwayat Aktivitas
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Rekapitulasi seluruh pencapaian olahraga lan jalur rute GPS sampeyan.</p>
        </div>

        <!-- SMOOTH CLIENT-SIDE FILTER TAB (TEKS HITAM PADA MODUL AKTIF) -->
        <div class="flex items-center gap-1.5 bg-gray-950 p-1.5 rounded-2xl border border-gray-800 max-w-full overflow-x-auto" id="archive-filter-container">
            <button type="button" onclick="filterArchive('all', this)"
                class="filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 bg-orange-500 text-black shadow-md cursor-pointer">
                Semua
            </button>
            <button type="button" onclick="filterArchive('running', this)"
                class="filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 text-gray-400 hover:text-white bg-transparent cursor-pointer">
                Lari
            </button>
            <button type="button" onclick="filterArchive('walking', this)"
                class="filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 text-gray-400 hover:text-white bg-transparent cursor-pointer">
                Jalan
            </button>
            <button type="button" onclick="filterArchive('cycling', this)"
                class="filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 text-gray-400 hover:text-white bg-transparent cursor-pointer">
                Bersepeda
            </button>
        </div>
    </div>

    <!-- RECAP SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card-dark p-6 rounded-3xl border border-gray-800/90 sport-glow">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TOTAL JARAK</div>
            <div class="text-3xl font-black italic text-orange-400 font-mono mt-1" id="sum-distance">
                {{ number_format($totalDistanceKm, 2, ',', '.') }} <span class="text-xs text-gray-400 font-normal">KM</span>
            </div>
        </div>
        <div class="glass-card-dark p-6 rounded-3xl border border-gray-800/90">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TOTAL KALORI</div>
            <div class="text-3xl font-black italic text-amber-400 font-mono mt-1" id="sum-calories">
                {{ number_format($totalCalories, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">KCAL</span>
            </div>
        </div>
        <div class="glass-card-dark p-6 rounded-3xl border border-gray-800/90">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TOTAL WAKTU AKTIF</div>
            <div class="text-3xl font-black italic text-emerald-400 font-mono mt-1" id="sum-time">
                {{ floor($totalDurationSec / 3600) }}J {{ floor(($totalDurationSec % 3600) / 60) }}M
            </div>
        </div>
    </div>

    <!-- LIST OF ACTIVITIES TABLE / CARDS -->
    <div class="glass-card-dark rounded-3xl border border-gray-800/90 overflow-hidden">
        @if($activities->count() > 0)
            <div class="divide-y divide-gray-800/80" id="archive-list-container">
                @foreach($activities as$act)
                    <div class="archive-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-gray-800/20 transition-all duration-300" data-sport="{{ strtolower($act->sport_type) }}">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400 font-bold uppercase text-xs flex-shrink-0">
                                {{ substr($act->sport_type, 0, 3) }}
                            </div>
                            <div>
                                <div class="text-sm font-black italic uppercase text-white tracking-wide">
                                    {{ ucfirst($act->sport_type) }} — {{ \Carbon\Carbon::parse($act->start_time)->translatedFormat('d M Y, H:i') }} WIB
                                </div>
                                <div class="text-xs text-gray-400 font-mono mt-1">
                                   Durasi: <span class="text-gray-200 font-bold">{{ gmdate('H:i:s', $act->duration_seconds) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-3 gap-6 text-center font-mono">
                            <div>
                                <div class="text-[9px] text-gray-500 uppercase">Jarak</div>
                                <div class="text-sm font-bold text-orange-400">{{ number_format($act->distance_meters / 1000, 2, ',', '.') }} KM</div>
                            </div>
                            <div>
                                <div class="text-[9px] text-gray-500 uppercase">Pace / Speed</div>
                                <div class="text-sm font-bold text-amber-400">
                                    {{ $act->sport_type === 'cycling' ? $act->avg_speed_kmh . ' KM/H' : ($act->avg_pace_min_km ?? '00:00') . ' MIN/KM' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-[9px] text-gray-500 uppercase">Kalori</div>
                                <div class="text-sm font-bold text-emerald-400">{{ $act->calories_burned }} KCAL</div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div>
                            <button type="button" onclick="openRouteModal({{ $act->id }})"
                                class="w-full md:w-auto px-5 py-2.5 bg-gray-900 hover:bg-orange-600 text-gray-200 hover:text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all border border-gray-700/80 hover:border-orange-500 shadow-sm cursor-pointer">
                                Lihat Rute
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State untuk Filter -->
            <div id="no-filtered-data" class="hidden p-12 text-center text-gray-500 text-xs uppercase tracking-wider font-bold">
                Tidak ada riwayat untuk kategori olahraga ini.
            </div>

            <!-- Pagination -->
            <div class="p-5 border-t border-gray-800">
                {{ $activities->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500 text-xs uppercase tracking-wider font-bold">
                Belum ada riwayat aktivitas yang dicatat.
            </div>
        @endif
    </div>

</main>

<!-- MODAL VISUALISASI PETA RUTE -->
<div id="route-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm hidden">
    <div class="glass-card-dark max-w-lg w-full p-6 sm:p-8 rounded-3xl border border-gray-800/90 space-y-5">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <h3 id="modal-title" class="text-lg font-black italic uppercase text-orange-400">Visualisasi Rute GPS</h3>
            <button type="button" onclick="closeRouteModal()" class="text-gray-400 hover:text-white text-xl font-bold px-2 cursor-pointer">&times;</button>
        </div>

        <div id="recap-map" class="border border-gray-800 rounded-2xl overflow-hidden"></div>

        <button type="button" onclick="closeRouteModal()"
            class="w-full py-3 bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white font-bold rounded-xl text-xs uppercase tracking-wider border border-gray-700/80 transition-colors cursor-pointer">
            Tutup
        </button>
    </div>
</div>

@push('scripts')
<script>
    let recapMap = null;
    let recapPolyline = null;

    // Smooth Filter Functionality
    function filterArchive(sport, btnElement) {
        // Update Styling Tombol Filter secara Instan & Smooth
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.className = "filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 text-gray-400 hover:text-white bg-transparent cursor-pointer";
        });
        btnElement.className = "filter-btn px-4 py-2 rounded-xl text-xs font-black uppercase transition-all duration-300 bg-orange-500 text-black shadow-md cursor-pointer";

        // Filter Card Aktivitas dengan Animasi Fade
        const cards = document.querySelectorAll('.archive-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardSport = card.getAttribute('data-sport');
            if (sport === 'all' || cardSport === sport) {
                card.classList.remove('hidden-filter');
                visibleCount++;
            } else {
                card.classList.add('hidden-filter');
            }
        });

        // Toggle Pesan Kosong
        const noData = document.getElementById('no-filtered-data');
        if (noData) {
            if (visibleCount === 0) {
                noData.classList.remove('hidden');
            } else {
                noData.classList.add('hidden');
            }
        }
    }

    function openRouteModal(activityId) {
        document.getElementById('route-modal').classList.remove('hidden');

        if (!recapMap) {
            recapMap = L.map('recap-map', { zoomControl: false, attributionControl: false }).setView([-6.200000, 106.816666], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(recapMap);
        }

        fetch(`/api/activity/${activityId}/route`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.points.length > 0) {
                    const latLngs = data.points.map(pt => [pt.latitude, pt.longitude]);

                    if (recapPolyline) recapMap.removeLayer(recapPolyline);

                    recapPolyline = L.polyline(latLngs, { color: '#f97316', weight: 5, opacity: 0.9 }).addTo(recapMap);
                    recapMap.fitBounds(recapPolyline.getBounds(), { padding: [20, 20] });
                } else {
                    alert('Tidak ada data koordinat rute untuk aktivitas ini.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengambil data rute.');
            });
    }

    function closeRouteModal() {
        document.getElementById('route-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection