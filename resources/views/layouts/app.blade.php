<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WOYuk! — Obah Bareng, Konsisten Bendino')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet JS & CSS (Khusus Peta Tracking GPS) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* Custom Glassmorphism Styles */
        .glass-card-dark {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .sport-glow {
            box-shadow: 0 0 25px -5px rgba(249, 115, 22, 0.4);
        }

        /* ANIMASI OVERLAY GLOBAL LOADING SCREEN */
        #global-loading-screen {
            transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
        }

        #global-loading-screen.loading-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.08); opacity: 1; }
        }

        .animate-pulse-glow {
            animation: pulseGlow 1.5s infinite ease-in-out;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-950 text-gray-100 antialiased min-h-screen flex flex-col font-sans selection:bg-orange-500 selection:text-white">

    <!-- GLOBAL OVERLAY LOADING SCREEN (OTOMATIS DI SEMUA HALAMAN) -->
    <div id="global-loading-screen" class="fixed inset-0 z-[9999] bg-gray-950/90 backdrop-blur-2xl flex flex-col items-center justify-center space-y-6">
        
        <!-- Logo Brand & Glowing Spinner Ring -->
        <div class="relative flex items-center justify-center">
            <!-- Ring Outer Glow Spin -->
            <div class="w-24 h-24 rounded-full border-4 border-orange-500/20 border-t-orange-500 animate-spin"></div>
            
            <!-- Logo WOYuk Central Icon -->
            <div class="absolute w-12 h-12 rounded-2xl bg-orange-600/20 border border-orange-500/50 flex items-center justify-center text-orange-500 animate-pulse-glow shadow-xl sport-glow">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>

        <!-- Text Label Loading -->
        <div class="text-center space-y-1.5">
            <div class="text-xl font-black italic tracking-widest text-orange-500 uppercase font-sans animate-pulse">
                WOYuk!
            </div>
            <p class="text-xs font-mono text-gray-400 uppercase tracking-widest">
                Ngenteni Sedela...
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-grow">
        @yield('content')
    </div>

    <!-- MODAL SEARCH USER PALETTE -->
    <div id="search-modal" class="fixed inset-0 z-[9990] bg-gray-950/80 backdrop-blur-md flex items-start justify-center pt-20 px-4 hidden transition-all duration-200">
        <div class="w-full max-w-xl glass-card-dark border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
            <!-- Header Search Input -->
            <div class="p-4 border-b border-gray-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="user-search-input" placeholder="Cari Nama, Username, atau Public ID 10-digit..." class="w-full bg-transparent text-sm text-gray-100 placeholder-gray-500 focus:outline-none">
                
                <!-- TOMBOL CLOSING IKON 'X' -->
                <button type="button" onclick="closeSearchModal()" class="p-1.5 rounded-xl text-gray-400 hover:text-orange-400 bg-gray-900/80 hover:bg-gray-800 border border-gray-700/80 transition-all flex items-center justify-center shrink-0 cursor-pointer" title="Tutup Modal (ESC)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- List Pratinjau Profil -->
            <div id="search-results-container" class="p-4 max-h-[60vh] overflow-y-auto space-y-2">
                <p class="text-xs text-gray-500 text-center py-6">Ketikkan minimal 2 karakter untuk mencari penggiat olahraga lainnya...</p>
            </div>
        </div>
    </div>

    <!-- GLOBAL JAVASCRIPT UNTUK CONTROL LOADING SCREEN & SEARCH MODAL -->
    <script>
        function hideGlobalLoading() {
            const loadingScreen = document.getElementById('global-loading-screen');
            if (loadingScreen) {
                loadingScreen.classList.add('loading-hidden');
            }
        }

        function showGlobalLoading() {
            const loadingScreen = document.getElementById('global-loading-screen');
            if (loadingScreen) {
                loadingScreen.classList.remove('loading-hidden');
            }
        }

        window.addEventListener('load', function() {
            setTimeout(hideGlobalLoading, 300);
        });

        document.addEventListener('submit', function(e) {
            showGlobalLoading();
        });

        // HANYA AKAN TAMPILKAN LOADING APABILA MERUPAKAN NAVIGASI HALAMAN (BUKAN LINK ANCHOR / BUTTON)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:') && link.target !== '_blank' && !link.hasAttribute('data-no-loader')) {
                    showGlobalLoading();
                }
            }
        });

        // HANDLER SEARCH MODAL & AJAX
        let searchDebounceTimer = null;

        function openSearchModal() {
            const modal = document.getElementById('search-modal');
            const input = document.getElementById('user-search-input');
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => input.focus(), 100);
            }
        }

        function closeSearchModal() {
            const modal = document.getElementById('search-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSearchModal();
        });

        const searchInput = document.getElementById('user-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                const container = document.getElementById('search-results-container');

                clearTimeout(searchDebounceTimer);

                if (query.length < 2) {
                    container.innerHTML = `<p class="text-xs text-gray-500 text-center py-6">Ketikkan minimal 2 karakter untuk mencari penggiat olahraga lainnya...</p>`;
                    return;
                }

                container.innerHTML = `<div class="text-center py-6 text-xs text-orange-400 font-mono animate-pulse">Nggoleki user...</div>`;

                searchDebounceTimer = setTimeout(() => {
                    fetch(`{{ route('api.users.search') }}?q=${encodeURIComponent(query)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.data || res.data.length === 0) {
                            container.innerHTML = `<p class="text-xs text-gray-500 text-center py-6">User ora ditemokake.</p>`;
                            return;
                        }

                        container.innerHTML = res.data.map(u => `
                            <a href="${u.profile_url}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-900/60 hover:bg-orange-600/10 border border-gray-800/80 hover:border-orange-500/40 transition-all group">
                                <div class="w-10 h-10 rounded-full bg-orange-600/20 border border-orange-500/30 flex items-center justify-center overflow-hidden shrink-0">
                                    ${u.avatar_url 
                                        ? `<img src="${u.avatar_url}" class="w-full h-full object-cover">` 
                                        : `<span class="text-xs font-bold text-orange-400 uppercase">${u.name.substring(0, 2)}</span>`}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-200 group-hover:text-orange-400 truncate">${u.name}</h4>
                                        <span class="text-[10px] font-mono text-gray-400 bg-gray-800 px-1.5 py-0.5 rounded">ID: ${u.public_id}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 truncate">@${u.username} • ${u.bio}</p>
                                </div>
                            </a>
                        `).join('');
                    })
                    .catch(() => {
                        container.innerHTML = `<p class="text-xs text-red-400 text-center py-6">Gagal mengambil data.</p>`;
                    });
                }, 300);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>