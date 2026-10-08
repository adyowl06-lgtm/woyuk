@push('styles')
<style>
    /* Keyframe Animasi Getaran (Shake) Alert Modal */
    @keyframes alertShake {
        0% { transform: scale(0.9) translateX(0); opacity: 0; }
        20% { transform: scale(1) translateX(-10px); opacity: 1; }
        40% { transform: scale(1) translateX(10px); }
        60% { transform: scale(1) translateX(-6px); }
        80% { transform: scale(1) translateX(6px); }
        100% { transform: scale(1) translateX(0); }
    }

    .animate-alert-shake {
        animation: alertShake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }

    /* Tooltip Pointer Segitiga Menunjuk Tepat ke Tombol di Atasnya */
    .popover-tail::before {
        content: '';
        position: absolute;
        top: -8px;
        right: 14px;
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #0f172a; /* Warna Biru Gelap Slate-900 */
        z-index: 60;
    }

    /* Animasi Smooth Slide Dropdown Mobile */
    .mobile-menu-animated {
        transform-origin: top right;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .mobile-menu-animated.menu-closed {
        opacity: 0;
        transform: scale(0.85) translateY(-10px);
        pointer-events: none;
    }
    .mobile-menu-animated.menu-open {
        opacity: 1;
        transform: scale(1) translateY(0);
        pointer-events: auto;
    }
</style>
@endpush

<nav class="sticky top-0 z-50 glass-card-dark border-b border-gray-800/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 relative">
            
            <!-- KIRI: BRANDING LOGO & NAVIGASI DROPDOWN LANDING -->
            <div class="flex items-center gap-6">
                <a href="{{ Auth::check() ? route('dashboard') : url('/') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-orange-600/20 border border-orange-500/40 flex items-center justify-center text-orange-500 group-hover:scale-105 transition-transform sport-glow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-black italic tracking-widest text-orange-500 uppercase font-sans">WOYuk!</span>
                </a>

                @auth
                <!-- BADGE PUBLIC USER ID (SAAT LOGIN) -->
                <div class="hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-gray-900 border border-gray-700 text-[11px] font-mono text-gray-300">
                    ID: <span class="text-orange-400 font-bold ml-1.5">{{ Auth::user()->public_id ?? '0000000000' }}</span>
                </div>
                @else
                <!-- NAVIGASI TEKS TANPA BOX (KHUSUS LANDING PAGE DESKTOP) -->
                <div class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-wider text-gray-300 ml-4">
                    
                    <!-- 1. Scroll Smooth ke Fitur-Fitur -->
                    <button type="button" onclick="scrollToFiturSection()" class="hover:text-orange-400 transition-colors flex items-center gap-1 cursor-pointer bg-transparent border-0 p-0 text-xs font-bold uppercase tracking-wider text-gray-300">
                        Fitur-Fitur
                    </button>

                    <!-- 2. Dropdown Aktivitas -->
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('activities-dropdown', 'arrow-activities')" class="hover:text-orange-400 transition-colors flex items-center gap-1.5 focus:outline-none cursor-pointer py-2">
                            <span>Aktivitas</span>
                            <svg id="arrow-activities" class="w-3.5 h-3.5 text-orange-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Box Mini Dropdown Aktivitas Desktop -->
                        <div id="activities-dropdown" class="hidden absolute top-full left-0 mt-2 w-48 bg-slate-900 rounded-2xl border border-slate-700 shadow-2xl p-2 z-50 space-y-1">
                            <button type="button" onclick="showLoginAlert('Lari')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 text-white font-bold text-xs uppercase transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Lari</span>
                            </button>
                            <button type="button" onclick="showLoginAlert('Jalan')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 text-white font-bold text-xs uppercase transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Jalan</span>
                            </button>
                            <button type="button" onclick="showLoginAlert('Bersepeda')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 text-white font-bold text-xs uppercase transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Bersepeda</span>
                            </button>
                        </div>
                    </div>

                </div>
                @endauth
            </div>

            <!-- KANAN DESKTOP: MENU NAVIGASI -->
            <div class="hidden md:flex items-center space-x-2 sm:space-x-3 text-xs font-bold uppercase tracking-wider">
                @auth
                    <!-- TOMBOL ADMIN PANEL (KHUSUS ADMIN) -->
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-2 border {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white border-purple-500 shadow-lg' : 'text-purple-300 hover:text-white bg-purple-950/60 hover:bg-purple-900/80 border-purple-800/80' }}">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Admin Panel</span>
                        </a>
                    @endif

                    <!-- TOMBOL TRIGGER PENCARIAN USER -->
                    <button type="button" onclick="openSearchModal()" class="px-3.5 py-2 rounded-xl text-gray-200 hover:text-white bg-gray-900/80 hover:bg-gray-800 border border-gray-700/80 transition-all flex items-center gap-2 cursor-pointer shadow-sm">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Cari User</span>
                    </button>

                    <!-- DASHBOARD -->
                    <a href="{{ route('dashboard') }}" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-2 border {{ request()->routeIs('dashboard') ? 'bg-orange-600 text-white border-orange-500 shadow-lg sport-glow' : 'text-gray-200 hover:text-white bg-gray-900/80 hover:bg-gray-800 border-gray-700/80' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Home</span>
                    </a>

                    <!-- PROFIL & PENGATURAN -->
                    <a href="{{ route('profile.edit') }}" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-2 border {{ request()->routeIs('profile.edit') ? 'bg-orange-600 text-white border-orange-500 shadow-lg sport-glow' : 'text-gray-200 hover:text-white bg-gray-900/80 hover:bg-gray-800 border-gray-700/80' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Profil & Settings</span>
                    </a>

                    <!-- ARSIP -->
                    <a href="{{ route('archive.index') }}" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-2 border {{ request()->routeIs('archive.index') ? 'bg-orange-600 text-white border-orange-500 shadow-lg sport-glow' : 'text-gray-200 hover:text-white bg-gray-900/80 hover:bg-gray-800 border-gray-700/80' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('archive.index') ? 'text-white' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Arsip</span>
                    </a>

                    <!-- LOGOUT -->
                    <form action="{{ route('logout') }}" method="POST" class="inline ml-1">
                        @csrf
                        <button type="submit" title="Logout" class="px-3 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/30 rounded-xl text-xs font-bold uppercase tracking-wider transition-all cursor-pointer">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-gray-300 hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all sport-glow">Daftar</a>
                @endauth
            </div>

            <!-- KANAN MOBILE: WRAPPER UNTUK TOMBOL TITIK TIGA & DROPDOWN BOX -->
            <div class="relative md:hidden flex items-center">
                <button type="button" onclick="toggleMobileNavbar()" id="mobile-menu-btn" class="p-2.5 rounded-xl bg-gray-900/80 border border-gray-700/80 text-orange-400 hover:text-white active:scale-95 transition-all duration-300 focus:outline-none cursor-pointer">
                    <!-- Ikon Titik Tiga (Default) -->
                    <svg id="icon-dots" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                    <!-- Ikon Panah Ke Atas (Tersembunyi) -->
                    <svg id="icon-arrow-up" class="w-6 h-6 hidden transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                    </svg>
                </button>

                <!-- DROPDOWN MENU MOBILE -->
                <div id="mobile-navbar-dropdown" class="mobile-menu-animated menu-closed popover-tail absolute top-full mt-3 right-0 w-64 bg-slate-900 border border-slate-700 shadow-2xl rounded-2xl p-3.5 z-50 space-y-2 text-xs font-bold uppercase tracking-wider">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-2.5 rounded-xl transition-all text-white border {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 border-purple-500 shadow-lg' : 'bg-purple-950/80 border-purple-800/80 hover:bg-purple-900/80' }}">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Admin Panel</span>
                            </a>
                        @endif

                        <button type="button" onclick="toggleMobileNavbar(); openSearchModal();" class="w-full flex items-center gap-3 p-2.5 rounded-xl transition-all text-white bg-slate-800/80 hover:bg-slate-800">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari User</span>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2.5 rounded-xl transition-all text-white hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-orange-600 shadow-lg' : 'bg-slate-800/80' }}">
                            <span>Beranda</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2.5 rounded-xl transition-all text-white hover:bg-slate-800 {{ request()->routeIs('profile.edit') ? 'bg-orange-600 shadow-lg' : 'bg-slate-800/80' }}">
                            <span>Profil & Settings</span>
                        </a>
                        <a href="{{ route('archive.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl transition-all text-white hover:bg-slate-800 {{ request()->routeIs('archive.index') ? 'bg-orange-600 shadow-lg' : 'bg-slate-800/80' }}">
                            <span>Arsip</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full pt-1">
                            @csrf
                            <button type="submit" class="w-full p-2.5 bg-red-600/20 text-red-400 border border-red-500/30 rounded-xl font-bold uppercase transition-all">Logout</button>
                        </form>
                    @else
                        <button type="button" onclick="scrollToFiturSection(); toggleMobileNavbar();" class="w-full text-left p-2.5 text-white hover:bg-slate-800 bg-slate-800/80 rounded-xl border border-slate-700/80 font-bold uppercase text-xs">Fitur-Fitur</button>
                        
                        <div class="p-2.5 bg-slate-800/80 rounded-xl border border-slate-700/80 space-y-2">
                            <div class="text-orange-400 font-bold mb-1">Aktivitas Olahraga:</div>
                            
                            <button type="button" onclick="showLoginAlert('Lari')" class="w-full text-left py-1.5 text-white hover:text-orange-400 flex items-center gap-2.5 transition-colors cursor-pointer">
                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Lari</span>
                            </button>
                            <button type="button" onclick="showLoginAlert('Jalan')" class="w-full text-left py-1.5 text-white hover:text-amber-400 flex items-center gap-2.5 transition-colors cursor-pointer">
                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Jalan</span>
                            </button>
                            <button type="button" onclick="showLoginAlert('Bersepeda')" class="w-full text-left py-1.5 text-white hover:text-emerald-400 flex items-center gap-2.5 transition-colors cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Bersepeda</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('login') }}" class="block p-2.5 text-center text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700">Masuk</a>
                            <a href="{{ route('register') }}" class="block p-2.5 text-center bg-orange-600 hover:bg-orange-500 text-white rounded-xl sport-glow">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</nav>

<!-- MODAL ALERT GLASSMORPHISM DENGAN ANIMASI GETARAN (ALERT SHAKE) -->
<div id="login-alert-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md hidden transition-all">
    <div id="alert-card-box" class="glass-card-dark max-w-md w-full p-6 sm:p-8 rounded-3xl border border-gray-800 shadow-2xl space-y-5 text-center relative">
        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400 mx-auto">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <div>
            <h3 class="text-xl font-black italic uppercase text-white tracking-wider">Mlebu Akun Disik!</h3>
            <p class="text-xs text-gray-300 mt-2 leading-relaxed" id="alert-modal-text">
                Sampeyan kudu login dhisik kanggo ngakses fitur tracking olahraga iki.
            </p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closeLoginAlert()" class="w-1/2 py-3 bg-gray-900 hover:bg-gray-800 text-gray-300 font-bold rounded-xl text-xs uppercase tracking-wider border border-gray-700 transition-colors cursor-pointer">
                Batal
            </button>
            <a href="{{ route('login') }}" class="w-1/2 py-3 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition-all sport-glow cursor-pointer text-center block">
                Mlebu / Login
            </a>
        </div>
    </div>
</div>

<script>
    function scrollToFiturSection() {
        const fiturEl = document.getElementById('fitur-section');
        if (fiturEl) {
            fiturEl.scrollIntoView({ behavior: 'smooth' });
        } else {
            window.location.href = "{{ url('/') }}#fitur-section";
        }
    }

    function toggleDropdown(id, arrowId) {
        const dropdown = document.getElementById(id);
        const arrow = document.getElementById(arrowId);
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            if(arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            if(arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }

    function toggleMobileNavbar() {
        const dropdown = document.getElementById('mobile-navbar-dropdown');
        const iconDots = document.getElementById('icon-dots');
        const iconArrowUp = document.getElementById('icon-arrow-up');

        if (dropdown.classList.contains('menu-closed')) {
            dropdown.classList.remove('menu-closed');
            dropdown.classList.add('menu-open');
            iconDots.classList.add('hidden');
            iconArrowUp.classList.remove('hidden');
        } else {
            dropdown.classList.remove('menu-open');
            dropdown.classList.add('menu-closed');
            iconArrowUp.classList.add('hidden');
            iconDots.classList.remove('hidden');
        }
    }

    function showLoginAlert(sportName) {
        const modal = document.getElementById('login-alert-modal');
        const box = document.getElementById('alert-card-box');

        document.getElementById('alert-modal-text').innerHTML = `Sampeyan kudu mlebu/login dhisik kanggo miwiti aktivitas <strong class="text-orange-400 uppercase">${sportName}</strong>!`;
        
        modal.classList.remove('hidden');

        box.classList.remove('animate-alert-shake');
        void box.offsetWidth;
        box.classList.add('animate-alert-shake');
    }

    function closeLoginAlert() {
        document.getElementById('login-alert-modal').classList.add('hidden');
    }
</script>