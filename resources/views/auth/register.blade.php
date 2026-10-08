@extends('layouts.app')

@section('title', 'Daftar Akun — WOYuk!')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-gray-900 text-white">
    <div class="w-full max-w-md glass-card-dark p-8 rounded-2xl border border-gray-800 shadow-2xl">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black italic tracking-wider text-orange-500 uppercase">WOYuk!</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">Gawe Akun Nyar</p>
        </div>

        @if($errors->has('error'))
            <div class="mb-4 p-3 bg-red-500/20 border border-red-500/50 rounded-lg text-red-300 text-xs">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Textbox Email (Warna Teks Hitam Pekat) -->
            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="masukkan email..."
                    class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-all placeholder-gray-400">
                @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Textbox Password (Warna Teks Hitam Pekat) -->
            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-all placeholder-gray-400">
                @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Textbox Konfirmasi Password (Warna Teks Hitam Pekat) -->
            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                    class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-orange-500 transition-all placeholder-gray-400">
            </div>

            <button type="submit" 
                class="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold py-3 rounded-lg uppercase tracking-wider text-sm transition-all duration-200 sport-glow mt-2 cursor-pointer">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            Wis duwe akun? <a href="{{ route('login') }}" class="text-orange-400 font-bold hover:underline">Mbuet / Login</a>
        </div>
    </div>
</div>
@endsection