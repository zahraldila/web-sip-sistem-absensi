@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

@php
    $savedColor = \App\Models\Setting::get('primary_color', '#123D91');
    $savedLogo  = asset(\App\Models\Setting::get('company_logo', 'images/logo-sip.png'));
@endphp

<div class="space-y-8" x-data="brandingApp('{{ $savedColor }}', '{{ $savedLogo }}')">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[32px] font-bold text-slate-900">Settings</h1>
            <p class="mt-1 text-[15px] text-slate-500">
                Sesuaikan identitas visual sistem — pratinjau perubahan sebelum disimpan.
            </p>
        </div>

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

    {{-- ===== MAIN GRID ===== --}}
    <div class="grid grid-cols-1 gap-8 xl:grid-cols-5">

        {{-- LEFT: Settings (3 cols) --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- LOGO --}}
            <div class="rounded-3xl bg-white p-8 shadow-card">
                <h2 class="text-lg font-bold text-slate-900">Logo Perusahaan</h2>
                <p class="mt-1 text-sm text-slate-500 mb-6">
                    Pilih file logo baru — pratinjau akan langsung berubah di sebelah kanan.
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
                        <p x-show="logoFileName" x-text="logoFileName ? '📎 ' + logoFileName : ''"
                           class="mt-3 text-xs font-medium text-primary"></p>
                    </div>
                </div>
            </div>

            {{-- WARNA --}}
            <div class="rounded-3xl bg-white p-8 shadow-card">
                <h2 class="text-lg font-bold text-slate-900">Warna Utama</h2>
                <p class="mt-1 text-sm text-slate-500 mb-6">
                    Pilih warna brand perusahaan — pratinjau akan langsung berubah.
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
                         class="ml-auto flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-600 border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Diubah
                    </div>
                </div>
                <div>
                    <p class="mb-3 text-sm font-medium text-slate-600">Pilih cepat:</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['#123D91','#1D4ED8','#059669','#D97706','#DC2626','#7C3AED','#0F172A','#0891B2'] as $hex)
                        <button type="button" @click="quickColor('{{ $hex }}')" title="{{ $hex }}"
                                class="relative h-9 w-9 flex items-center justify-center rounded-full border-2 border-white shadow transition hover:scale-110 hover:shadow-lg"
                                :class="selectedHex.toUpperCase() === '{{ strtoupper($hex) }}' ? 'ring-2 ring-offset-2 ring-slate-400' : ''"
                                style="background-color: {{ $hex }}">
                            <svg x-show="selectedHex.toUpperCase() === '{{ strtoupper($hex) }}'"
                                 xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white drop-shadow"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                        @endforeach
                        <button type="button" @click="openPicker()"
                                class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-400 transition hover:border-primary hover:text-primary hover:scale-110"
                                title="Kustom...">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Live Preview (2 cols) --}}
        <div class="xl:col-span-2">
            <div class="sticky top-24 space-y-4">

                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Pratinjau Langsung</h2>
                    <span x-show="hasChanges" x-transition
                          class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 border border-amber-200">
                        ● Ada perubahan
                    </span>
                </div>

                {{-- Ringkasan perubahan --}}
                <div x-show="hasChanges" x-transition class="rounded-2xl bg-white border border-slate-200 px-4 py-3 shadow-sm">
                    <p class="mb-2 text-xs font-bold text-slate-600 uppercase tracking-wide">Yang akan disimpan:</p>
                    <div class="space-y-2">
                        <div x-show="colorChanged" class="flex items-center gap-2.5">
                            <div class="h-5 w-5 flex-shrink-0 rounded-md border border-white shadow-sm" :style="`background-color: ${selectedHex}`"></div>
                            <div class="flex-1 text-xs text-slate-700">
                                <span class="font-semibold">Warna utama</span> diubah ke
                                <code class="ml-1 rounded bg-slate-100 px-1.5 py-0.5 font-mono font-bold" x-text="selectedHex.toUpperCase()"></code>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="h-4 w-4 rounded-sm border border-slate-200" :style="`background-color: ${savedColor}`" :title="`Sebelum: ${savedColor}`"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <div class="h-4 w-4 rounded-sm border border-slate-200" :style="`background-color: ${selectedHex}`" :title="`Setelah: ${selectedHex}`"></div>
                            </div>
                        </div>
                        <div x-show="logoChanged" class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1 text-xs text-slate-700">
                                <span class="font-semibold">Logo</span> diganti dengan
                                <span class="font-medium text-primary" x-text="logoFileName"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mini Sidebar Preview --}}
                <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-card bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-2">
                        <div class="flex gap-1.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-red-400"></div>
                            <div class="h-2.5 w-2.5 rounded-full bg-yellow-400"></div>
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <span class="ml-2 text-[10px] font-medium text-slate-400">Tampilan Dashboard</span>
                    </div>
                    <div class="flex" style="height: 270px;">
                        <div class="w-[130px] flex-shrink-0 flex flex-col bg-white border-r border-slate-100 py-3 px-2.5">
                            <div class="flex items-center gap-2 mb-4 px-1">
                                <div class="h-7 w-7 flex-shrink-0 overflow-hidden rounded-lg flex items-center justify-center"
                                     :style="`background-color: ${selectedHex}20`">
                                    <img :src="logoPreviewUrl" class="h-5 w-5 object-contain"
                                         onerror="this.onerror=null;this.src='https://via.placeholder.com/20/ffffff/ffffff?text=.'">
                                </div>
                                <div>
                                    <div class="h-2 rounded-sm bg-slate-800 mb-1" style="width:52px"></div>
                                    <div class="h-1.5 w-10 rounded-sm bg-slate-300"></div>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100 mb-3"></div>
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-1.5 rounded-lg px-2 py-1.5"
                                     :style="`background-color: ${selectedHex}15`">
                                    <div class="h-2.5 w-2.5 rounded-sm flex-shrink-0" :style="`background-color: ${selectedHex}`"></div>
                                    <div class="h-1.5 rounded-sm flex-1" :style="`background-color: ${selectedHex}99`"></div>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-lg px-2 py-1.5">
                                    <div class="h-2.5 w-2.5 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 rounded-sm bg-slate-200 flex-1"></div>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-lg px-2 py-1.5">
                                    <div class="h-2.5 w-2.5 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 w-[70%] rounded-sm bg-slate-200"></div>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-lg px-2 py-1.5">
                                    <div class="h-2.5 w-2.5 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 w-[80%] rounded-sm bg-slate-200"></div>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-lg px-2 py-1.5">
                                    <div class="h-2.5 w-2.5 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 w-[60%] rounded-sm bg-slate-200"></div>
                                </div>
                            </div>
                            <div class="mt-2 px-1">
                                <div class="rounded-lg py-2 text-center text-[9px] font-bold text-white"
                                     :style="`background-color: ${selectedHex}`">Logout</div>
                            </div>
                        </div>
                        <div class="flex-1 bg-[#F5F7FB] p-3 overflow-hidden">
                            <div class="mb-3 flex items-center justify-between rounded-xl bg-white px-3 py-2 shadow-sm border border-slate-100">
                                <div class="h-2 w-20 rounded bg-slate-200"></div>
                                <div class="h-6 w-6 rounded-full flex items-center justify-center text-white text-[8px] font-bold"
                                     :style="`background-color: ${selectedHex}`">A</div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div class="rounded-xl p-2.5 shadow-sm border"
                                     :style="`background-color: ${selectedHex}10; border-color: ${selectedHex}25`">
                                    <div class="h-1.5 w-12 rounded bg-slate-300 mb-2"></div>
                                    <div class="h-4 w-8 rounded" :style="`background-color: ${selectedHex}`"></div>
                                </div>
                                <div class="rounded-xl bg-green-50 p-2.5 shadow-sm border border-green-100">
                                    <div class="h-1.5 w-12 rounded bg-green-200 mb-2"></div>
                                    <div class="h-4 w-8 rounded bg-green-400"></div>
                                </div>
                            </div>
                            <div class="rounded-xl bg-white p-2 shadow-sm border border-slate-100">
                                <div class="h-1.5 w-full rounded bg-slate-100 mb-1.5"></div>
                                <div class="h-1.5 w-4/5 rounded bg-slate-100 mb-1.5"></div>
                                <div class="h-1.5 w-3/5 rounded bg-slate-100 mb-1.5"></div>
                                <div class="flex gap-1 mt-2">
                                    <div class="h-4 w-12 rounded-md" :style="`background-color: ${selectedHex}`"></div>
                                    <div class="h-4 w-12 rounded-md bg-slate-100 border border-slate-200"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mini Login Preview --}}
                <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-card bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-2">
                        <div class="flex gap-1.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-red-400"></div>
                            <div class="h-2.5 w-2.5 rounded-full bg-yellow-400"></div>
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <span class="ml-2 text-[10px] font-medium text-slate-400">Halaman Login</span>
                    </div>
                    <div class="flex items-center justify-center bg-[#F5F7FB] py-5 px-4">
                        <div class="w-full max-w-[200px] rounded-2xl bg-white px-5 py-5 shadow-lg border border-slate-100">
                            <div class="flex justify-center mb-3">
                                <div class="h-12 w-12 overflow-hidden rounded-2xl flex items-center justify-center"
                                     :style="`background-color: ${selectedHex}15`">
                                    <img :src="logoPreviewUrl" class="h-9 w-9 object-contain"
                                         onerror="this.onerror=null;this.src='https://via.placeholder.com/36/e2e8f0/94a3b8?text=?'">
                                </div>
                            </div>
                            <div class="h-2 w-24 mx-auto rounded bg-slate-800 mb-1"></div>
                            <div class="h-1.5 w-20 mx-auto rounded bg-slate-300 mb-4"></div>
                            <div class="space-y-2 mb-3">
                                <div class="h-7 rounded-lg border border-slate-200 bg-slate-50 px-2 flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 w-full rounded bg-slate-200"></div>
                                </div>
                                <div class="h-7 rounded-lg border border-slate-200 bg-slate-50 px-2 flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-sm bg-slate-300 flex-shrink-0"></div>
                                    <div class="h-1.5 w-full rounded bg-slate-200"></div>
                                </div>
                            </div>
                            <div class="h-7 w-full rounded-lg flex items-center justify-center"
                                 :style="`background-color: ${selectedHex}`">
                                <span class="text-[9px] font-bold text-white">Masuk</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <form method="POST"
                      action="{{ route('admin.tampilan-branding.simpan') }}"
                      enctype="multipart/form-data"
                      x-ref="brandingForm"
                      @submit.prevent="onSubmit">
                    @csrf
                    <input type="hidden" name="primary_color" :value="selectedHex">
                    <input type="file" id="hiddenLogoInput" name="logo" class="hidden" x-ref="hiddenLogoInput">
                    <div class="flex gap-3">
                        <button type="button" @click="confirmReset = true"
                                class="flex-1 rounded-2xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-600 transition hover:bg-red-50 hover:border-red-300 hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit" :disabled="!hasChanges"
                                class="flex-[2] rounded-2xl py-3 text-sm font-bold text-white shadow-md transition"
                                :class="hasChanges ? 'bg-primary hover:bg-primary-hover active:scale-95 cursor-pointer' : 'bg-slate-300 cursor-not-allowed'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="hasChanges ? 'Simpan Perubahan' : 'Belum Ada Perubahan'"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- MODAL: COLOR PICKER --}}
    <div x-show="colorPickerOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" aria-modal="true" role="dialog">
        <div x-show="colorPickerOpen" x-transition.opacity @click="colorPickerOpen = false" class="fixed inset-0 bg-white/60 backdrop-blur-sm"></div>
        <div x-show="colorPickerOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative z-50 w-full max-w-sm rounded-[2rem] bg-white p-8 shadow-2xl border border-slate-100">

            <h2 class="mb-6 text-center text-xl font-bold text-slate-900">Pilih Warna</h2>

            <div class="relative mb-4 h-44 w-full cursor-crosshair overflow-hidden rounded-2xl border border-slate-200"
                 x-ref="svArea" :style="`background-color: ${hueColor}`"
                 @mousedown="startDragSV" @mousemove.window="dragSV" @mouseup.window="stopDragSV">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-white to-transparent"></div>
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
                <div class="pointer-events-none absolute z-10 h-5 w-5 -translate-x-1/2 -translate-y-1/2 rounded-full border-[3px] border-white shadow-lg"
                     :style="`left: ${saturation}%; top: ${100 - value}%`"></div>
            </div>

            <div class="relative mb-6 h-4 w-full cursor-pointer rounded-full"
                 x-ref="hueArea"
                 style="background: linear-gradient(to right,#f00 0%,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,#f00 100%);"
                 @mousedown="startDragHue" @mousemove.window="dragHue" @mouseup.window="stopDragHue">
                <div class="pointer-events-none absolute top-1/2 -translate-y-1/2 -translate-x-1/2 h-6 w-6 rounded-full border-2 border-white shadow-lg"
                     :style="`left: ${(hue/360)*100}%; background-color: ${hueColor}`"></div>
            </div>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Kode HEX</label>
                    <input type="text" x-model="tempHex" @input="updateHsvFromHex" maxlength="7"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 font-mono text-sm font-semibold text-slate-900 uppercase focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div class="h-11 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-slate-200 p-1">
                    <div class="h-full w-full rounded-lg" :style="`background-color: ${tempHex}`"></div>
                </div>
            </div>

            <div class="mb-6">
                <p class="mb-2 text-xs font-semibold text-slate-500">Warna Cepat</p>
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(['#123D91','#1D4ED8','#059669','#D97706','#DC2626','#7C3AED','#0F172A','#0891B2'] as $c)
                    <div @click="quickColor('{{ $c }}')"
                         class="h-7 w-7 cursor-pointer rounded-full border-2 border-white shadow transition hover:scale-110"
                         style="background-color: {{ $c }}"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-center gap-3">
                <button @click="colorPickerOpen = false" class="rounded-full border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                <button @click="applyColor()" class="rounded-full px-6 py-2.5 text-sm font-semibold text-white shadow transition hover:opacity-90" :style="`background-color: ${tempHex}`">Terapkan</button>
            </div>
        </div>
    </div>

    {{-- MODAL: CONFIRM RESET --}}
    <div x-show="confirmReset" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" aria-modal="true" role="dialog">
        <div @click="confirmReset = false" x-show="confirmReset" x-transition.opacity class="fixed inset-0 bg-white/60 backdrop-blur-sm"></div>
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
                <button @click="confirmReset = false" class="rounded-full border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</button>
                <form method="POST" action="{{ route('admin.tampilan-branding.reset') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-full bg-red-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-red-600">Ya, Reset</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('brandingApp', (initialColor, initialLogo) => ({
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

        init() { this.hexToHsv(this.selectedHex); },

        onLogoChange(event) {
            const file = event.target.files[0];
            if (!file) return;
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
            // Submit the actual form element via x-ref
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
    }));
});
</script>

@endsection
