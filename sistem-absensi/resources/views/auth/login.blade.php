@extends('layouts.auth.app')

@section('title', 'Login | Sistem Absensi')

@section('content')
<style>
    /* Override browser autofill background & text colors */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #f8fafc inset !important;
        -webkit-text-fill-color: #1e293b !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
<div class="rounded-3xl sm:rounded-[40px] border border-slate-100 bg-white px-5 py-7 sm:px-8 sm:py-10 shadow-2xl">

    {{-- Logo --}}
    <div class="mb-5 sm:mb-6 flex justify-center">
        <img
            src="{{ company_logo_url() }}"
            alt="Logo SIP"
            class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl object-cover shadow-lg"
            onerror="this.onerror=null; this.src='https://via.placeholder.com/150/000000/FFFFFF?text=SIP';" />
    </div>

    {{-- Heading --}}
    <h1 class="text-center text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Selamat Datang</h1>
    <p class="mt-1.5 sm:mt-2 text-center text-sm sm:text-base font-medium text-slate-500">Silahkan masuk ke akun anda</p>

    {{-- Status Alert (e.g. Password reset success) --}}
    @if (session('status'))
        <div class="mt-4 sm:mt-5 flex items-center gap-2 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-xs sm:text-sm text-green-700">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Error Banner (General login error / Offline error) --}}
    <div id="login-error-banner" @if (!session('error') && !$errors->has('message')) style="display: none;" @endif class="mt-4 sm:mt-5 flex items-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs sm:text-sm text-red-700">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        <span id="login-error-message">{{ session('error') ?? $errors->first('message') }}</span>
    </div>

    <form method="POST" action="{{ url('/login') }}" novalidate class="mt-6 sm:mt-8 space-y-4 sm:space-y-5" x-data="{ showPassword: false }">
        @csrf

        {{-- Username / Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-xs sm:text-sm font-semibold text-slate-700">Username atau Email</label>
            <div class="flex items-center gap-2.5 sm:gap-3 rounded-2xl border @error('email') border-red-400 bg-red-50/30 ring-1 ring-red-300 @else border-slate-300 bg-slate-50 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary @enderror px-3.5 py-3 sm:px-4 sm:py-3.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 @error('email') text-red-400 @else text-slate-400 @enderror" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <input
                    type="text"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan username atau email"
                    autofocus
                    class="w-full border-none bg-transparent p-0 text-sm text-slate-800 placeholder-slate-400 outline-none focus:outline-none focus:ring-0" />
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="mb-1.5 block text-xs sm:text-sm font-semibold text-slate-700">Password</label>
            <div class="flex items-center gap-2.5 sm:gap-3 rounded-2xl border @error('password') border-red-400 bg-red-50/30 ring-1 ring-red-300 @else border-slate-300 bg-slate-50 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary @enderror px-3.5 py-3 sm:px-4 sm:py-3.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 @error('password') text-red-400 @else text-slate-400 @enderror" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="4" y="11" width="16" height="9" rx="2" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                <input
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    id="password"
                    placeholder="Masukkan password akun"
                    class="w-full border-none bg-transparent p-0 text-sm text-slate-800 placeholder-slate-400 outline-none focus:outline-none focus:ring-0" />
                <button type="button" @click="showPassword = !showPassword" class="shrink-0 p-1 text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                    <i :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex flex-wrap items-center justify-between gap-2 text-xs sm:text-sm">
            <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
                <span>Ingat Saya</span>
            </label>
            <a href="#" class="font-semibold text-primary hover:underline">Lupa Kata Sandi?</a>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full rounded-2xl bg-primary py-3.5 sm:py-4 text-sm sm:text-base font-bold text-white shadow-lg transition hover:bg-primary-hover active:scale-95">
            Masuk
        </button>

        {{-- Help --}}
        <p class="text-center text-xs sm:text-sm text-slate-500 pt-1">
            Butuh bantuan?
            <a href="#" class="font-semibold text-primary hover:underline">Hubungi IT Support</a>
        </p>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!navigator.onLine) {
                    e.preventDefault();
                    var banner = document.getElementById('login-error-banner');
                    var msg = document.getElementById('login-error-message');
                    if (banner && msg) {
                        msg.textContent = 'Gagal terhubung ke server. Silakan periksa koneksi internet Anda dan coba lagi.';
                        banner.style.display = 'flex';
                    }
                }
            });
        }
    });
</script>
@endsection