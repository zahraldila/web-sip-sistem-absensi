@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

@php
    $savedColor = $savedColor ?? '#123D91';
    $savedLogo  = $savedLogo ?? asset('images/logo-sip.png');
    $activeTab  = $activeTab ?? 'branding';
    $daftarLokasi = $daftarLokasi ?? [];
@endphp

<div class="space-y-8" x-data="settingsApp('{{ $savedColor }}', '{{ $savedLogo }}', '{{ $activeTab }}')">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[32px] font-bold text-slate-900">Settings</h1>
            <p class="mt-1 text-[15px] text-slate-500">
                Kelola identitas visual, warna tema, serta konfigurasi kantor cabang secara dinamis.
            </p>
        </div>

        @if($activeTab === 'branding')
        {{-- Step indicator for Branding --}}
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

    {{-- ===== FLASH MESSAGE ===== --}}
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
    {{-- TAB 1: TAMPILAN & BRANDING --}}
    {{-- ======================================================== --}}
    @if($activeTab === 'branding')
    <div class="grid grid-cols-1 gap-8 xl:grid-cols-5">

        {{-- LEFT: Settings (3 cols) --}}
        <div class="xl:col-span-3 space-y-6">

            <form id="brandingForm"
                  action="{{ route('admin.tampilan-branding.simpan') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- Card: Logo Perusahaan --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Logo Perusahaan</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Format: PNG, JPG, SVG, WebP. Maks 2MB.</p>
                        </div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-primary">Branding</span>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row items-center gap-6">
                        {{-- Logo Preview Box --}}
                        <div class="relative flex h-28 w-28 flex-shrink-0 items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-2 overflow-hidden shadow-inner">
                            <img :src="logoPreview" alt="Logo Preview" class="max-h-full max-w-full object-contain">
                            <div x-show="logoFile" class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-green-500 text-white text-[9px] font-bold">
                                âœ“
                            </div>
                        </div>

                        {{-- Upload controls --}}
                        <div class="flex-1 space-y-3 w-full sm:w-auto">
                            <div>
                                <label for="logoInput" class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Pilih Gambar Logo</span>
                                </label>
                                <input id="logoInput" type="file" name="logo" accept="image/*" class="hidden" @change="onLogoChange($event)">
                            </div>
                            <p class="text-xs text-slate-400">Rekomendasi rasio 1:1 atau horizontal dengan latar belakang transparan.</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Warna Utama (Primary Color) --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Warna Utama Sistem</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Warna ini digunakan pada tombol, badge, dan elemen interaktif.</p>
                        </div>
                        <div class="h-6 w-6 rounded-full border-2 border-white shadow-sm" :style="'background-color: ' + color"></div>
                    </div>

                    <div class="mt-6 space-y-4">
                        {{-- Presets --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Palet Rekomendasi</label>
                            <div class="mt-2.5 flex flex-wrap gap-2.5">
                                <template x-for="p in presets" :key="p">
                                    <button type="button"
                                            @click="setColor(p)"
                                            class="h-9 w-9 rounded-xl border-2 transition transform hover:scale-105"
                                            :class="color.toUpperCase() === p.toUpperCase() ? 'border-slate-800 ring-2 ring-slate-400' : 'border-transparent'"
                                            :style="'background-color: ' + p">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Custom Input --}}
                        <div class="pt-2 flex items-center gap-3">
                            <input type="color" x-model="color" @input="onColorInput" class="h-10 w-12 cursor-pointer rounded-xl border border-slate-200 bg-white p-1">
                            <input type="text" x-model="color" @input="onColorInput" name="primary_color" maxlength="7"
                                   class="w-32 rounded-xl border border-slate-200 px-3 py-2 text-sm font-mono uppercase font-bold text-slate-800 focus:border-primary focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="showResetModal = true" class="px-4 py-2.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                        Kembalikan Default
                    </button>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md hover:bg-primary/90 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>

        {{-- RIGHT: Live Preview (2 cols) --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-primary"></i>
                    <span>Pratinjau Langsung</span>
                </h3>

                {{-- Mock Sidebar / Header Card --}}
                <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center overflow-hidden">
                            <img :src="logoPreview" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900">SIP Absensi</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Perusahaan Preview</div>
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <button type="button" class="w-full py-2 rounded-lg text-white font-bold text-xs shadow-xs flex items-center justify-center gap-2" :style="'background-color: ' + color">
                            <i class="fa-solid fa-check"></i>
                            <span>Tombol Utama Aktif</span>
                        </button>

                        <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100 text-xs">
                            <span class="text-slate-500 font-medium">Status Kehadiran</span>
                            <span class="font-bold px-2 py-0.5 rounded-full text-[10px]" :style="'background-color: ' + color + '15; color: ' + color">
                                Hadir Tepat Waktu
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
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

                {{-- Action Buttons (Edit / Custom Delete Modal) --}}
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
    {{-- TELEPORTED MODALS (100% FULL SCREEN VIEWPORT OVERLAY) --}}
    {{-- ======================================================== --}}

    {{-- 1. MODAL TAMBAH / EDIT KANTOR CABANG --}}
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

    {{-- 2. MODAL CUSTOM CONFIRM DELETE KANTOR CABANG --}}
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

    {{-- 3. MODAL RESET BRANDING --}}
    <template x-teleport="body">
        <div x-show="showResetModal"
             x-transition.opacity.duration.300ms
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             x-cloak
             style="display: none;">
            <div @click.away="showResetModal = false"
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-7 relative border border-slate-100 text-center">
                <div class="h-16 w-16 mx-auto rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Reset Pengaturan Branding?</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Logo dan warna tema akan dikembalikan ke pengaturan awal sistem.
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <button type="button" @click="showResetModal = false" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <form action="{{ route('admin.tampilan-branding.reset') }}" method="POST" class="w-1/2">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-100 transition">
                            Ya, Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('settingsApp', (savedColor, savedLogo, activeTab) => ({
            activeTab: activeTab || 'branding',
            color: savedColor,
            savedColor: savedColor,
            logoPreview: savedLogo,
            savedLogo: savedLogo,
            logoFile: null,
            hasChanges: false,
            showResetModal: false,

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

            // Delete location modal states
            showDeleteLokasiModal: false,
            deleteLokasiData: { id: '', nama: '' },

            presets: [
                '#123D91', '#0F766E', '#4338CA', '#1D4ED8',
                '#B91C1C', '#C2410C', '#047857', '#0E7490',
            ],

            onColorInput() {
                this.hasChanges = (this.color.toUpperCase() !== this.savedColor.toUpperCase()) || !!this.logoFile;
            },

            setColor(hex) {
                this.color = hex;
                this.onColorInput();
            },

            onLogoChange(e) {
                const file = e.target.files[0];
                if (file) {
                    this.logoFile = file;
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        this.logoPreview = ev.target.result;
                        this.hasChanges = true;
                    };
                    reader.readAsDataURL(file);
                }
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
