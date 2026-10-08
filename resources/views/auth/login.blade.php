@extends('layouts.app')

@section('title', 'Login — WOYuk!')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gray-900 text-white">
    <div class="w-full max-w-md glass-card-dark p-6 sm:p-8 rounded-3xl border border-gray-800 shadow-2xl space-y-6">
        
        <!-- 1. Header & Label Salam -->
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl font-black italic tracking-wider text-orange-500 uppercase">WOYuk!</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">Sugeng Rawuh Maneh</p>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-300 text-xs">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-red-500/20 border border-red-500/50 rounded-xl text-red-300 text-xs">
                {{ session('error') }}
            </div>
        @endif

        <!-- 2. Form Textbox & Tombol Masuk Utama -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Textbox Email (Warna Teks Hitam Pekat) -->
            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="masukkan email..."
                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-all placeholder-gray-400">
                @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Textbox Password (Warna Teks Hitam Pekat) -->
            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-all placeholder-gray-400">
                @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Checkbox Elingi Aku -->
            <div class="flex items-center justify-between text-xs text-gray-400 pt-1">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-gray-800 border-gray-700 text-orange-500 focus:ring-0">
                    <span class="ml-2 font-medium">Elingi Aku</span>
                </label>
            </div>

            <!-- 3. Tombol Masuk Utama -->
            <button type="submit" 
                class="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold py-3.5 rounded-xl uppercase tracking-wider text-xs sm:text-sm transition-all duration-200 sport-glow cursor-pointer shadow-lg shadow-orange-600/30">
                Masuk / Login
            </button>
        </form>

        <!-- 4. Divider Pembatas -->
        <div class="relative text-center my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-800"></div>
            </div>
            <span class="relative bg-gray-900 px-3 text-[10px] text-gray-500 uppercase font-mono font-bold tracking-widest">Utawa</span>
        </div>

        <!-- 5. Opsi Masuk Pakai Google -->
        <a href="{{ route('auth.google') }}" 
            class="w-full flex items-center justify-center gap-3 bg-gray-800 hover:bg-gray-700 text-gray-200 font-bold py-3 rounded-xl border border-gray-700 text-xs sm:text-sm transition-all duration-200 cursor-pointer shadow-md">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.2 9 5 12 5z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.8z"/>
                <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 10.8 0 12s.7 2.3 1.9 4.7l3.7-2.9z"/>
                <path fill="#34A853" d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.2-6.4-5.2L1.9 16C3.7 19.7 7.5 23 12 23z"/>
            </svg>
            Masuk nganggo Google
        </a>

        <!-- 6. Footer Link Registrasi -->
        <div class="pt-2 text-center text-xs text-gray-400 border-t border-gray-800/80">
            Durung duwe akun? 
            <a href="{{ route('register') }}" class="text-orange-400 font-bold hover:underline ml-1">
                Gawe akun
            </a>
        </div>

    </div>
</div>
@endsection