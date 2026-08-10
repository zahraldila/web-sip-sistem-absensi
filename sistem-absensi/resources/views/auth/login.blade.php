@extends('layouts.auth.app')

@section('title', 'Login | Sistem Absensi')

@section('content')
<div class="rounded-[40px] border border-slate-100 bg-white px-8 py-10 shadow-2xl sm:px-10">

    {{-- Logo --}}
    <div class="mb-6 flex justify-center">
        <img
            src="{{ asset('images/logo-sip.png') }}"
            alt="Logo SIP"
            class="h-20 w-20 rounded-2xl object-cover shadow-lg" />
    </div>

    {{-- Heading --}}
    <h1 class="text-center text-3xl font-extrabold tracking-tight text-slate-900">Selamat Datang</h1>
    <p class="mt-2 text-center text-base font-medium text-slate-500">Silahkan masuk ke akun anda</p>

    {{-- Status / Error --}}
    @if (session('status'))
    <div class="mt-5 rounded-xl bg-green-50 px-4 py-2 text-sm text-green-700">
        {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="mt-5 rounded-xl bg-red-50 px-4 py-2 text-sm text-red-700">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="mt-8 space-y-5" x-data="{ showPassword: false }">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
            <div class="flex items-center gap-3 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5 focus-within:border-[#123D91] focus-within:ring-1 focus-within:ring-[#123D91]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                </svg>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border-none bg-transparent p-0 text-sm text-slate-800 placeholder-slate-400 outline-none focus:outline-none focus:ring-0" />
            </div>
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
            <div class="flex items-center gap-3 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5 focus-within:border-[#123D91] focus-within:ring-1 focus-within:ring-[#123D91]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="4" y="11" width="16" height="9" rx="2" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                <input
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    id="password"
                    required
                    class="w-full border-none bg-transparent p-0 text-sm text-slate-800 placeholder-slate-400 outline-none focus:outline-none focus:ring-0" />
                <button type="button" @click="showPassword = !showPassword" class="shrink-0 text-slate-400 hover:text-slate-600">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                        <circle cx="12" cy="12" r="3" stroke-width="2" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-7 0-11-7-11-7a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#123D91] focus:ring-[#123D91]" />
                Ingat Saya
            </label>
            <a href="#" class="font-semibold text-[#123D91] hover:underline">Lupa Kata Sandi?</a>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full rounded-2xl bg-[#123D91] py-4 text-base font-bold text-white shadow-lg transition hover:bg-[#0F3277] active:scale-95">
            Masuk
        </button>

        {{-- Help --}}
        <p class="text-center text-sm text-slate-500">
            Butuh bantuan?
            <a href="#" class="font-semibold text-[#123D91] hover:underline">Hubungi IT Support</a>
        </p>
    </form>
</div>
@endsection
