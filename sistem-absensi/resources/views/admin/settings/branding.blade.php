@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

@php
    $savedColor = $savedColor ?? '#123D91';
    $savedLogo  = $savedLogo ?? asset('images/logo-sip.png');
    $activeTab  = $activeTab ?? 'branding';
    $daftarLokasi = $daftarLokasi ?? [];
@endphp

<div class="space-y-8" x-data="settingsMasterApp('{{ $savedColor }}', '{{ $savedLogo }}', '{{ $activeTab }}')">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[32px] font-bold text-slate-900">Settings</h1>
            <p class="mt-1 text-[15px] text-slate-500">
                @if($activeTab === 'branding')
                Sesuaikan identitas visual sistem, pratinjau perubahan sebelum disimpan.
                @else
                Kelola daftar kantor cabang, titik koordinat GPS presensi, radius kehadiran, serta jaringan Wi-Fi kantor.
                @endif
            </p>
        </div>

        @if($activeTab === 'branding')
        {{-- Step indicator --}}
        <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold"
                  :class="hasChanges ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500'">1</span>
            <span :class="hasChanges ? 'text-primary' : ''">Ubah</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold"
                  :class="hasChanges ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500'">2</span>
            <span :class="hasChanges ? 'text-primary' : ''">Pratinjau</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold"
                  :class="hasChanges ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-500'">3</span>
            <span :class="hasChanges ? 'text-green-600' : ''">Simpan</span>
        </div>
        @else
        <button type="button" @click="openModalTambah()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-primary text-white font-bold text-sm shadow-md hover:bg-primary/90 transition">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Tambah Kantor Cabang</span>
        </button>
        @endif
    </div>

    {{-- ===== TAB SWITCHER ===== --}}
    <div class="flex items-center gap-2 border-b border-slate-200 pb-4">
        <a href="{{ route('admin.tampilan-branding', ['tab' => 'branding']) }}"
           class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold transition {{ $activeTab === 'branding' ? 'bg-primary text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fa-solid fa-palette text-sm"></i>
            <span>Tampilan & Branding</span>
        </a>
        <a href="{{ route('admin.tampilan-branding', ['tab' => 'lokasi']) }}"
           class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold transition {{ $activeTab === 'lokasi' ? 'bg-primary text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fa-solid fa-building-circle-check text-sm"></i>
            <span>Lokasi Kantor & Cabang</span>
            <span class="ml-1 px-2.5 py-0.5 rounded-full text-xs {{ $activeTab === 'lokasi' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ count($daftarLokasi) }}</span>
        </a>
    </div>

    {{-- ===== FLASH ===== --}}
    @if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- TAB 1: TAMPILAN & BRANDING (EXACT 100% ORIGINAL DESIGN) --}}
    {{-- ======================================================== --}}
    @if($activeTab === 'branding')
    <div class="grid grid-cols-1 gap-8 xl:grid-cols-5">

        {{-- LEFT: Settings (3 cols) --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- LOGO --}}
            <div class="rounded-3xl bg-white p-8 shadow-card">
                <h2 class="text-lg font-bold text-slate-900">Logo Perusahaan</h2>
                <p class="mt-1 text-sm text-slate-500 mb-6">
                    Pilih file logo baru, pratinjau akan langsung berubah di sebelah kanan.
                </p>
                <div class="flex items-start gap-6">
                    <div class="relative flex-shrink-0">
                        <div class="h-24 w-24 overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center">
                            <img :src="logoPreviewUrl" alt="Logo preview"
                                 class="h-20 w-20 object-contain"
                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/80/E2E8F0/94A3B8?text=LOGO'">
                        </div>
                        <div x-show="logoChanged"
                             class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-green-500 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800 mb-1">Unggah Logo Baru</p>
                        <p class="text-xs text-slate-500 mb-4">PNG, SVG, atau JPG. Disarankan 256x256px, maks 2MB.</p>
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Pilih File
                            <input id="logoInput" type="file" class="hidden" accept="image/png,image/jpeg,image/svg+xml" @change="onLogoChange">
                        </label>
                        <p x-show="logoFileName" x-text="logoFileName ? '-- ' + logoFileName : ''"
                           class="mt-3 text-xs font-medium text-primary"></p>
                    </div>
                </div>
            </div>

            {{-- WARNA --}}
            <div class="rounded-3xl bg-white p-8 shadow-card">
                <h2 class="text-lg font-bold text-slate-900">Warna Utama</h2>
                <p class="mt-1 text-sm text-slate-500 mb-6">
                    Pilih warna brand perusahaan, pratinjau akan langsung berubah.
                </p>
                <div class="mb-6 flex items-center gap-4">
                    <button type="button" @click="openPicker()"
                            class="group relative flex h-14 w-14 items-center justify-center rounded-2xl border-2 border-slate-200 transition hover:border-slate-400 hover:shadow-md"
                            :style="`background-color: ${selectedHex}`"
                            title="Klik untuk ganti warna">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white opacity-0 group-hover:opacity-100 transition drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                    <div>
                        <p class="font-mono text-xl font-bold text-slate-900" x-text="selectedHex.toUpperCase()"></p>
                        <p class="text-xs text-slate-500">Klik kotak warna untuk mengubah</p>
                    </div>
                    <div x-show="colorChanged"
                         class="ml-auto flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-primary">
                        <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                        Warna diubah
                    </div>
                </div>

                {{-- Quick palette --}}
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Pilih cepat:</p>
                <div class="flex items-center gap-3 flex-wrap">
                    @foreach(['#123D91','#1D4ED8','#059669','#D97706','#DC2626','#7C3AED','#0F172A','#0891B2'] as $c)
                    <button type="button" @click="quickColor('{{ $c }}')"
                            class="relative flex h-10 w-10 items-center justify-center rounded-full border-2 transition hover:scale-110 shadow-sm"
                            :class="selectedHex.toUpperCase() === '{{ $c }}' ? 'border-slate-800 ring-2 ring-offset-2 ring-slate-800 scale-110' : 'border-white'"
                            style="background-color: {{ $c }}">
                        <svg x-show="selectedHex.toUpperCase() === '{{ $c }}'"
                             xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                    @endforeach

                    {{-- Tombol + untuk Buka Picker --}}
                    <button type="button" @click="openPicker()"
                            class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-dashed border-slate-300 text-slate-400 transition hover:border-slate-500 hover:text-slate-600 hover:scale-110"
                            title="Kustom Warna Lainnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex items-center justify-between pt-2">
                <button type="button" @click="confirmReset = true"
                        class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>

                <button type="button" @click="onSubmit()"
                        :disabled="!hasChanges"
                        :class="hasChanges ? 'bg-primary hover:bg-primary-hover shadow-lg shadow-blue-500/20 text-white cursor-pointer' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="flex items-center gap-2 rounded-2xl px-8 py-3 text-sm font-semibold transition">
                    <svg x-show="hasChanges" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="hasChanges ? 'Simpan Perubahan' : 'Belum Ada Perubahan'"></span>
                </button>
            </div>

        </div>

        {{-- RIGHT: Live Preview (2 cols) --}}
        <div class="xl:col-span-2 space-y-6">

            <div>
                <p class="text-base font-bold text-slate-800 mb-1">Pratinjau Langsung</p>
                <p class="text-xs text-slate-400 mb-4">Perubahan warna dan logo akan langsung terlihat di sini.</p>
            </div>

            {{-- MOCKUP DASHBOARD --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-card space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400">Tampilan Dashboard</span>
                </div>

                {{-- Header Mockup --}}
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <img :src="logoPreviewUrl" alt="Logo" class="h-7 w-7 object-contain rounded-lg">
                        <div class="h-3 w-16 rounded bg-slate-800"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-20 rounded-full bg-slate-100"></div>
                        <div class="h-6 w-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold"
                             :style="`background-color: ${selectedHex}`">A</div>
                    </div>
                </div>

                {{-- Body Mockup --}}
                <div class="grid grid-cols-3 gap-3">
                    {{-- Mini Sidebar --}}
                    <div class="col-span-1 space-y-2 rounded-2xl bg-slate-50 p-2.5">
                        <div class="h-4 rounded-lg flex items-center gap-1.5 px-2 text-[9px] font-semibold text-white"
                             :style="`background-color: ${selectedHex}`">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        </div>
                        <div class="h-2.5 w-12 rounded bg-slate-200"></div>
                        <div class="h-2.5 w-14 rounded bg-slate-200"></div>
                        <div class="h-2.5 w-10 rounded bg-slate-200"></div>
                        <div class="mt-4 pt-2 border-t border-slate-200">
                            <div class="h-4 rounded-lg flex items-center justify-center text-[8px] font-semibold text-white"
                                 :style="`background-color: ${selectedHex}`">Logout</div>
                        </div>
                    </div>

                    {{-- Mini Content --}}
                    <div class="col-span-2 space-y-2.5">
                        <div class="h-3.5 w-24 rounded bg-slate-200"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-xl border border-slate-100 p-2 space-y-1">
                                <div class="h-2 w-8 rounded bg-slate-200"></div>
                                <div class="h-4 w-12 rounded" :style="`background-color: ${selectedHex}`"></div>
                            </div>
                            <div class="rounded-xl border border-slate-100 p-2 space-y-1">
                                <div class="h-2 w-8 rounded bg-slate-200"></div>
                                <div class="h-4 w-12 rounded bg-emerald-500"></div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-100 p-2 space-y-1.5">
                            <div class="h-2 w-full rounded bg-slate-100"></div>
                            <div class="h-2 w-3/4 rounded bg-slate-100"></div>
                            <div class="h-3 w-14 rounded" :style="`background-color: ${selectedHex}`"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MOCKUP LOGIN --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-card space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400">Halaman Login</span>
                </div>

                <div class="flex flex-col items-center justify-center py-4 px-6 bg-slate-50 rounded-2xl">
                    <div class="w-full max-w-[200px] bg-white rounded-2xl p-4 shadow-sm border border-slate-100 space-y-3 text-center">
                        <img :src="logoPreviewUrl" alt="Logo" class="h-8 w-8 object-contain mx-auto rounded-lg">
                        <div class="h-2.5 w-16 rounded bg-slate-800 mx-auto"></div>
                        <div class="space-y-1.5">
                            <div class="h-4 w-full rounded-lg bg-slate-100 border border-slate-200"></div>
                            <div class="h-4 w-full rounded-lg bg-slate-100 border border-slate-200"></div>
                        </div>
                        <div class="h-5 w-full rounded-lg flex items-center justify-center text-[9px] font-bold text-white shadow-sm"
                             :style="`background-color: ${selectedHex}`">Masuk</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Form tersembunyi untuk submit data branding --}}
    <form x-ref="brandingForm" method="POST" action="{{ route('admin.tampilan-branding.simpan') }}" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="hidden" name="primary_color" :value="selectedHex">
        <input type="file" name="logo" x-ref="hiddenLogoInput" class="hidden">
    </form>
    @endif

    {{-- ======================================================== --}}
    {{-- TAB 2: LOKASI KANTOR & CABANG (DYNAMIC CRUD) --}}
    {{-- ======================================================== --}}
    @if($activeTab === 'lokasi')
    <div class="space-y-6">

        {{-- Top Description & Quick Guide --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Manajemen Lokasi Kantor & Cabang</h2>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Setiap kantor cabang yang ditambahkan di sini akan <strong class="text-slate-700">otomatis muncul sebagai tab di Dashboard TV</strong> dan digunakan untuk verifikasi radius geo-location presensi pegawai.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3.5 py-1.5 rounded-xl border border-slate-200">
                    Total: {{ count($daftarLokasi) }} Cabang
                </span>
            </div>
        </div>

        {{-- Location Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($daftarLokasi as $lokasi)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-5">
                <div>
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-primary flex items-center justify-center font-bold text-xl flex-shrink-0">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 leading-tight">{{ $lokasi->nama_kantor }}</h3>
                                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 mt-1 inline-block">
                                    ID Cabang: {{ $lokasi->lokasi_id }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Details --}}
                    <div class="mt-5 space-y-2.5 text-xs text-slate-600 divide-y divide-slate-50">
                        {{-- Coordinates --}}
                        <div class="pt-1 flex items-center justify-between">
                            <span class="text-slate-400 font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-slate-400"></i> Koordinat GPS
                            </span>
                            <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" target="_blank" class="font-mono font-bold text-primary hover:underline flex items-center gap-1">
                                <span>{{ number_format((float)$lokasi->latitude, 4) }}, {{ number_format((float)$lokasi->longitude, 4) }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- Radius --}}
                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-slate-400 font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-notch text-slate-400"></i> Radius Kehadiran
                            </span>
                            <span class="font-bold text-slate-800">{{ $lokasi->radius_meter }} Meter</span>
                        </div>

                        {{-- Wi-Fi SSIDs --}}
                        <div class="pt-2 flex flex-col gap-1.5">
                            <span class="text-slate-400 font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-wifi text-slate-400"></i> Wi-Fi Kantor Terdaftar
                            </span>
                            <div class="flex flex-wrap gap-1.5 mt-0.5">
                                @forelse($lokasi->wifis as $w)
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] font-bold border border-slate-200">
                                    {{ $w->ssid }}
                                </span>
                                @empty
                                <span class="text-slate-400 italic text-[11px] font-medium">Belum ada Wi-Fi ditautkan</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button"
                            @click="openModalEdit({{ json_encode($lokasi) }})"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-primary hover:bg-blue-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>

                    <button type="button"
                            @click="confirmDeleteLokasi({{ $lokasi->lokasi_id }}, '{{ addslashes($lokasi->nama_kantor) }}')"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 bg-white p-12 text-center">
                <div class="h-16 w-16 mx-auto rounded-full bg-slate-50 flex items-center justify-center text-slate-300 text-2xl mb-3">
                    <i class="fa-solid fa-building-circle-xmark"></i>
                </div>
                <h4 class="text-base font-bold text-slate-700">Belum Ada Kantor Cabang</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Tambahkan kantor cabang baru agar presensi geo-location dan Dashboard TV otomatis terkonfigurasi.</p>
                <button type="button" @click="openModalTambah()" class="mt-4 px-5 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm">
                    + Tambah Kantor Cabang Pertama
                </button>
            </div>
            @endforelse
        </div>

    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- TELEPORTED MODALS --}}
    {{-- ======================================================== --}}

    {{-- MODAL 1: COLOR PICKER (FOR BRANDING TAB) --}}
    <template x-teleport="body">
        <div x-show="colorPickerOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center" aria-modal="true" role="dialog">
            <div @click="colorPickerOpen = false" x-show="colorPickerOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div x-show="colorPickerOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative z-50 w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-base font-bold text-slate-900">Pilih Warna</p>
                    <button @click="colorPickerOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Saturation/Value Area --}}
                <div class="relative h-44 w-full cursor-crosshair rounded-2xl overflow-hidden shadow-inner"
                     x-ref="svArea"
                     :style="`background-color: ${hueColor}; background-image: linear-gradient(to right, #fff, transparent), linear-gradient(to top, #000, transparent);`"
                     @mousedown="startDragSV"
                     @mousemove.window="dragSV"
                     @mouseup.window="stopDragSV">
                    <div class="pointer-events-none absolute h-4 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow-md"
                         :style="`left: ${saturation}%; top: ${100 - value}%; background-color: ${tempHex};`"></div>
                </div>

                {{-- Hue Slider --}}
                <div class="relative h-4 w-full cursor-pointer rounded-full"
                     x-ref="hueArea"
                     style="background: linear-gradient(to right, #f00 0%, #ff0 17%, #0f0 33%, #0ff 50%, #00f 67%, #f0f 83%, #f00 100%);"
                     @mousedown="startDragHue"
                     @mousemove.window="dragHue"
                     @mouseup.window="stopDragHue">
                    <div class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white bg-white shadow"
                         :style="`left: ${(hue / 360) * 100}%;`"></div>
                </div>

                {{-- HEX Input & Preview --}}
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 flex-shrink-0 rounded-xl border border-slate-200 shadow-sm"
                         :style="`background-color: ${tempHex}`"></div>
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-mono text-sm text-slate-400">#</span>
                        <input type="text"
                               :value="tempHex.replace('#', '')"
                               @input="tempHex = '#' + $event.target.value.toUpperCase(); updateHsvFromHex()"
                               maxlength="6"
                               class="w-full rounded-xl border border-slate-200 pl-7 pr-3 py-2 font-mono text-sm font-bold uppercase text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                </div>

                {{-- Quick Swatches in Picker --}}
                <div class="pt-1">
                    <p class="text-xs font-semibold text-slate-400 mb-2">Preset Cepat:</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        @foreach(['#123D91','#1D4ED8','#059669','#D97706','#DC2626','#7C3AED','#0F172A','#0891B2'] as $c)
                        <div @click="quickColor('{{ $c }}')"
                             class="h-7 w-7 cursor-pointer rounded-full border-2 border-white shadow transition hover:scale-110"
                             style="background-color: {{ $c }}"></div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="colorPickerOpen = false" class="w-1/2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button @click="applyColor()" class="w-1/2 rounded-xl px-4 py-2.5 text-xs font-semibold text-white shadow transition hover:opacity-90" :style="`background-color: ${tempHex}`">Terapkan</button>
                </div>
            </div>
        </div>
    </template>

    {{-- MODAL 2: CONFIRM RESET BRANDING --}}
    <template x-teleport="body">
        <div x-show="confirmReset" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center" aria-modal="true" role="dialog">
            <div @click="confirmReset = false" x-show="confirmReset" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div x-show="confirmReset"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative z-50 w-full max-w-sm rounded-3xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-slate-900">Reset ke Default?</h3>
                <p class="mb-6 text-sm text-slate-500">Logo dan warna akan dikembalikan ke pengaturan awal. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="confirmReset = false" class="w-1/2 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Batal</button>
                    <form method="POST" action="{{ route('admin.tampilan-branding.reset') }}" class="w-1/2 inline">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-red-500 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-red-600">Ya, Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- MODAL 3: TAMBAH / EDIT KANTOR CABANG --}}
    <template x-teleport="body">
        <div x-show="showLokasiModal"
             x-transition.opacity.duration.300ms
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
             x-cloak
             style="display: none;">
            <div @click.away="showLokasiModal = false"
                 class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-7 relative border border-slate-100 my-auto">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center text-lg">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900" x-text="isEdit ? 'Edit Kantor Cabang' : 'Tambah Kantor Cabang Baru'"></h3>
                            <p class="text-xs text-slate-400 mt-0.5">Konfigurasi koordinat GPS dan jaringan Wi-Fi</p>
                        </div>
                    </div>
                    <button @click="showLokasiModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-50">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.settings.lokasi.simpan') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <input type="hidden" name="lokasi_id" x-model="formLokasi.lokasi_id">

                    {{-- Nama Kantor --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700">Nama Kantor Cabang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kantor" x-model="formLokasi.nama_kantor" required
                               placeholder="Contoh: Kantor Cabang Jakarta"
                               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>

                    {{-- Latitude & Longitude --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-700">Latitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="latitude" x-model="formLokasi.latitude" required
                                   placeholder="Contoh: -6.910194"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700">Longitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="longitude" x-model="formLokasi.longitude" required
                                   placeholder="Contoh: 107.650728"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>

                    {{-- Radius Meter --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700">Radius Presensi (Meter) <span class="text-rose-500">*</span></label>
                        <input type="number" name="radius_meter" x-model="formLokasi.radius_meter" required min="1"
                               placeholder="Contoh: 100"
                               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>

                    {{-- Wi-Fi SSIDs --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700">Nama SSID Wi-Fi Kantor <span class="text-xs text-slate-400 font-normal">(Pisahkan dengan koma jika lebih dari satu)</span></label>
                        <input type="text" name="wifi_ssids" x-model="formLokasi.wifi_ssids"
                               placeholder="Contoh: SIP, SIP-5G"
                               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showLokasiModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-xs font-bold shadow-md hover:bg-primary/90 transition">
                            Simpan Kantor Cabang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>

    {{-- MODAL 4: CUSTOM CONFIRM DELETE KANTOR CABANG --}}
    <template x-teleport="body">
        <div x-show="showDeleteLokasiModal"
             x-transition.opacity.duration.300ms
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             x-cloak
             style="display: none;">
            <div @click.away="showDeleteLokasiModal = false"
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-7 relative border border-slate-100 text-center">
                
                {{-- Danger Warning Icon --}}
                <div class="h-16 w-16 mx-auto rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-trash-can"></i>
                </div>

                <h3 class="text-lg font-extrabold text-slate-900">Hapus Kantor Cabang?</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus kantor cabang <strong class="text-slate-800 font-bold" x-text="deleteLokasiData.nama"></strong>? Seluruh tautan Wi-Fi terkait akan dilepaskan dan tindakan ini tidak dapat dibatalkan.
                </p>

                <form id="deleteLokasiForm" :action="'/admin/settings/lokasi/' + deleteLokasiData.id" method="POST" class="mt-6 flex items-center justify-center gap-3">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" @click="showDeleteLokasiModal = false" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-100 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-trash text-xs"></i>
                        <span>Ya, Hapus Cabang</span>
                    </button>
                </form>

            </div>
        </div>
    </template>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('settingsMasterApp', (initialColor, initialLogo, initialTab) => ({
        activeTab: initialTab || 'branding',
        savedColor:     initialColor,
        savedLogo:      initialLogo,
        selectedHex:    initialColor,
        logoPreviewUrl: initialLogo,
        logoFileName:   '',
        logoFile:       null,
        colorPickerOpen: false,
        confirmReset:    false,
        tempHex:    initialColor,
        hue:        0,
        saturation: 0,
        value:      100,
        isDraggingSV:  false,
        isDraggingHue: false,

        // Location modal states
        showLokasiModal: false,
        isEdit: false,
        formLokasi: {
            lokasi_id: '',
            nama_kantor: '',
            latitude: '',
            longitude: '',
            radius_meter: 100,
            wifi_ssids: '',
        },
        showDeleteLokasiModal: false,
        deleteLokasiData: { id: '', nama: '' },

        get hasChanges() {
            return this.selectedHex.toUpperCase() !== this.savedColor.toUpperCase() || this.logoFile !== null;
        },
        get colorChanged() {
            return this.selectedHex.toUpperCase() !== this.savedColor.toUpperCase();
        },
        get logoChanged() {
            return this.logoFile !== null;
        },
        get hueColor() {
            return `hsl(${this.hue}, 100%, 50%)`;
        },

        init() {
            this.hexToHsv(this.selectedHex);
        },

        onLogoChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file logo melebihi batas maksimal 2 MB. Silakan pilih file yang lebih kecil.');
                event.target.value = '';
                return;
            }

            this.logoFile       = file;
            this.logoFileName   = file.name;
            this.logoPreviewUrl = URL.createObjectURL(file);
        },

        onSubmit() {
            if (this.logoFile) {
                const dt = new DataTransfer();
                dt.items.add(this.logoFile);
                this.$refs.hiddenLogoInput.files = dt.files;
            }
            this.$refs.brandingForm.submit();
        },

        openPicker() {
            this.tempHex = this.selectedHex;
            this.hexToHsv(this.tempHex);
            this.colorPickerOpen = true;
        },
        applyColor() {
            this.selectedHex = this.tempHex.startsWith('#') ? this.tempHex : '#' + this.tempHex;
            this.colorPickerOpen = false;
        },
        quickColor(hex) {
            this.selectedHex = hex;
            this.tempHex     = hex;
            this.hexToHsv(hex);
        },
        updateHsvFromHex() {
            const v = this.tempHex.startsWith('#') ? this.tempHex : '#' + this.tempHex;
            if (/^#[0-9A-Fa-f]{6}$/.test(v)) this.hexToHsv(v);
        },
        updateHexFromHsv() {
            let h=this.hue, s=this.saturation/100, v=this.value/100;
            let c=v*s, x=c*(1-Math.abs((h/60)%2-1)), m=v-c;
            let r=0,g=0,b=0;
            if(h<60){r=c;g=x;b=0;}
            else if(h<120){r=x;g=c;b=0;}
            else if(h<180){r=0;g=c;b=x;}
            else if(h<240){r=0;g=x;b=c;}
            else if(h<300){r=x;g=0;b=c;}
            else{r=c;g=0;b=x;}
            r=Math.round((r+m)*255);g=Math.round((g+m)*255);b=Math.round((b+m)*255);
            this.tempHex='#'+((1<<24|r<<16|g<<8|b).toString(16).slice(1)).toUpperCase();
        },
        hexToHsv(hex) {
            let r=parseInt(hex.slice(1,3),16)/255;
            let g=parseInt(hex.slice(3,5),16)/255;
            let b=parseInt(hex.slice(5,7),16)/255;
            let max=Math.max(r,g,b),min=Math.min(r,g,b),d=max-min;
            let h=0,s=max===0?0:d/max,v=max;
            if(max!==min){
                switch(max){
                    case r:h=(g-b)/d+(g<b?6:0);break;
                    case g:h=(b-r)/d+2;break;
                    case b:h=(r-g)/d+4;break;
                }
                h/=6;
            }
            this.hue=Math.round(h*360);
            this.saturation=Math.round(s*100);
            this.value=Math.round(v*100);
        },

        startDragSV(e){this.isDraggingSV=true;this.updateSV(e);},
        dragSV(e){if(!this.isDraggingSV)return;this.updateSV(e);},
        stopDragSV(){this.isDraggingSV=false;},
        updateSV(e){
            let rect=this.$refs.svArea.getBoundingClientRect();
            let x=Math.max(0,Math.min(e.clientX-rect.left,rect.width));
            let y=Math.max(0,Math.min(e.clientY-rect.top,rect.height));
            this.saturation=Math.round((x/rect.width)*100);
            this.value=Math.round((1-y/rect.height)*100);
            this.updateHexFromHsv();
        },

        startDragHue(e){this.isDraggingHue=true;this.updateHue(e);},
        dragHue(e){if(!this.isDraggingHue)return;this.updateHue(e);},
        stopDragHue(){this.isDraggingHue=false;},
        updateHue(e){
            let rect=this.$refs.hueArea.getBoundingClientRect();
            let x=Math.max(0,Math.min(e.clientX-rect.left,rect.width));
            this.hue=Math.round((x/rect.width)*360);
            this.updateHexFromHsv();
        },

        openModalTambah() {
            this.isEdit = false;
            this.formLokasi = {
                lokasi_id: '',
                nama_kantor: '',
                latitude: '',
                longitude: '',
                radius_meter: 100,
                wifi_ssids: '',
            };
            this.showLokasiModal = true;
        },

        openModalEdit(lokasi) {
            this.isEdit = true;
            const ssids = (lokasi.wifis || []).map(w => w.ssid).join(', ');
            this.formLokasi = {
                lokasi_id: lokasi.lokasi_id,
                nama_kantor: lokasi.nama_kantor,
                latitude: lokasi.latitude,
                longitude: lokasi.longitude,
                radius_meter: lokasi.radius_meter,
                wifi_ssids: ssids,
            };
            this.showLokasiModal = true;
        },

        confirmDeleteLokasi(id, nama) {
            this.deleteLokasiData = { id: id, nama: nama };
            this.showDeleteLokasiModal = true;
        }
    }));
});
</script>

@endsection