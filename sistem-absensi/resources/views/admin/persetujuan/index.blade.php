@extends('layouts.admin.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')

<div class="space-y-6 sm:space-y-8" x-data="approvalModal()">

    {{-- ======================================== --}}
    {{-- HEADER --}}
    {{-- ======================================== --}}
    <section class="flex flex-col gap-1 sm:gap-2">
        

        <h1 class="text-2xl sm:text-[34px] font-bold text-slate-900 leading-tight">
            Persetujuan Pengajuan
        </h1>

        <p class="text-xs sm:text-[15px] text-slate-500">
            Pengelolaan Izin, Sakit, WFH, WFC, dan Dinas Pegawai
        </p>
    </section>

    {{-- ======================================== --}}
    {{-- SUMMARY CARD (2 Kolom di HP, 4 di Desktop) --}}
    {{-- ======================================== --}}
    <section class="grid grid-cols-2 gap-3.5 sm:gap-6 md:grid-cols-2 xl:grid-cols-4">

        {{-- Pending --}}
        <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-sm sm:shadow-card">
            <div class="flex">
                <div class="w-1.5 bg-orange-500 flex-shrink-0"></div>
                <div class="flex flex-1 items-center justify-between p-3.5 sm:p-6 min-w-0">
                    <div>
                        <p class="text-[11px] sm:text-sm font-semibold uppercase tracking-wide text-slate-400 sm:text-slate-500">
                            Pending
                        </p>
                        <h2 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-bold text-slate-900" x-text="counts.pending">
                            {{ $pending }}
                        </h2>
                    </div>
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-2xl sm:rounded-full bg-orange-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diproses --}}
        <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-sm sm:shadow-card">
            <div class="flex">
                <div class="w-1.5 bg-blue-600 flex-shrink-0"></div>
                <div class="flex flex-1 items-center justify-between p-3.5 sm:p-6 min-w-0">
                    <div>
                        <p class="text-[11px] sm:text-sm font-semibold uppercase tracking-wide text-slate-400 sm:text-slate-500">
                            Diproses
                        </p>
                        <h2 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-bold text-slate-900" x-text="counts.diproses">
                            {{ $diproses }}
                        </h2>
                    </div>
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-2xl sm:rounded-full bg-blue-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M20 8A8 8 0 006.34 5.34L4 10m16 4l-2.34 4.66A8 8 0 014 16"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-sm sm:shadow-card">
            <div class="flex">
                <div class="w-1.5 bg-green-600 flex-shrink-0"></div>
                <div class="flex flex-1 items-center justify-between p-3.5 sm:p-6 min-w-0">
                    <div>
                        <p class="text-[11px] sm:text-sm font-semibold uppercase tracking-wide text-slate-400 sm:text-slate-500">
                            Disetujui
                        </p>
                        <h2 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-bold text-slate-900" x-text="counts.disetujui">
                            {{ $disetujui }}
                        </h2>
                    </div>
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-2xl sm:rounded-full bg-green-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-sm sm:shadow-card">
            <div class="flex">
                <div class="w-1.5 bg-red-600 flex-shrink-0"></div>
                <div class="flex flex-1 items-center justify-between p-3.5 sm:p-6 min-w-0">
                    <div>
                        <p class="text-[11px] sm:text-sm font-semibold uppercase tracking-wide text-slate-400 sm:text-slate-500">
                            Ditolak
                        </p>
                        <h2 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-bold text-slate-900" x-text="counts.ditolak">
                            {{ $ditolak }}
                        </h2>
                    </div>
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-2xl sm:rounded-full bg-red-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </section>

    {{-- ======================================== --}}
    {{-- TAB STATUS & ACTION --}}
    {{-- ======================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        {{-- TABS (Scrollable di HP) --}}
        <div class="overflow-x-auto pb-1 sm:pb-0">
            <div class="inline-flex items-center gap-1 rounded-2xl bg-slate-100 p-1.5 min-w-full sm:min-w-0">
                <button
                    type="button"
                    data-status=""
                    class="approval-tab rounded-xl px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-slate-600 transition whitespace-nowrap">
                    Semua
                </button>

                <button
                    type="button"
                    data-status="Pending"
                    class="approval-tab rounded-xl px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-slate-600 transition whitespace-nowrap">
                    Menunggu
                </button>

                <button
                    type="button"
                    data-status="Diproses"
                    class="approval-tab rounded-xl px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-slate-600 transition whitespace-nowrap">
                    Diproses
                </button>

                <button
                    type="button"
                    data-status="Disetujui"
                    class="approval-tab rounded-xl px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-slate-600 transition whitespace-nowrap">
                    Disetujui
                </button>

                <button
                    type="button"
                    data-status="Ditolak"
                    class="approval-tab rounded-xl px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-slate-600 transition whitespace-nowrap">
                    Ditolak
                </button>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="grid grid-cols-2 gap-2.5 sm:flex sm:gap-3">
            <button
                type="button"
                id="open-filter-modal"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 sm:px-6 py-2.5 text-xs sm:text-sm font-medium text-slate-700 transition hover:bg-slate-50 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter</span>
            </button>

            <button
                type="button"
                id="open-export-modal"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 sm:px-6 py-2.5 text-xs sm:text-sm font-medium text-slate-700 transition hover:bg-slate-50 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export</span>
            </button>
        </div>

    </div>

    {{-- ======================================== --}}
    {{-- TABLE / CARD CONTAINER --}}
    {{-- ======================================== --}}
    <div id="approval-table">
        @include('admin.persetujuan.partials.table', [
            'approvals' => $approvals
        ])
    </div>

    {{-- DETAIL MODAL --}}
    <div x-show="showDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4" @click.self="closeDetail()">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl sm:rounded-[28px] bg-white shadow-2xl ring-1 ring-slate-200" @click.stop>
            <div class="border-b border-slate-200 px-5 sm:px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Detail Pengajuan</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Informasi lengkap pengajuan yang dipilih.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeDetail()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid gap-5 sm:gap-6 grid-cols-1 lg:grid-cols-[180px_1fr] items-center">
                    <div class="flex flex-col items-center justify-center text-center">
                        <template x-if="detailData.foto_profile">
                            <img :src="detailData.foto_profile" alt="Foto Pegawai" class="mx-auto h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover shadow-md border-2 border-white" />
                        </template>
                        <template x-if="!detailData.foto_profile">
                            <div class="mx-auto flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center rounded-full bg-slate-100 text-2xl sm:text-3xl font-semibold text-slate-700 border border-slate-200" x-text="detailInitials()"></div>
                        </template>
                        <div class="mt-3 text-base sm:text-lg font-bold text-slate-900" x-text="detailData.nama_pegawai"></div>
                        <div class="text-xs text-slate-500" x-text="detailData.divisi_name"></div>
                    </div>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5 text-xs sm:text-sm">
                            <div class="text-slate-500">Department</div>
                            <div class="font-medium text-slate-900" x-text="detailData.divisi_name || '-' "></div>

                            <div class="text-slate-500">Jenis Pengajuan</div>
                            <div class="font-medium text-slate-900" x-text="detailData.jenis_pengajuan || '-' "></div>

                            <div class="text-slate-500">Tanggal Pengajuan</div>
                            <div class="font-medium text-slate-900" x-text="detailData.tanggal_pengajuan || '-' "></div>

                            <div class="text-slate-500">Status</div>
                            <div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="statusClass(detailData.status_pengajuan)"
                                    x-text="detailData.status_pengajuan || '-' "></span>
                            </div>

                            <div class="text-slate-500">Keterangan</div>
                            <div class="font-medium text-slate-900 break-words" x-text="detailData.keterangan || '-' "></div>

                            <div class="text-slate-500">Lampiran</div>
                            <div>
                                <template x-if="detailData.lampiran_url">
                                    <a :href="detailData.lampiran_url" target="_blank" download class="inline-flex items-center gap-1.5 font-medium text-primary hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="truncate max-w-[140px] sm:max-w-[160px]" :title="detailData.lampiran_name" x-text="detailData.lampiran_name || 'Unduh Lampiran'"></span>
                                    </a>
                                </template>
                                <template x-if="!detailData.lampiran_url">
                                    <span class="text-slate-400">Tidak ada lampiran</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-white px-5 sm:px-6 py-4 flex items-center justify-end gap-2.5">
                <template x-if="detailData.status_pengajuan === 'Pending'">
                    <div class="grid grid-cols-2 gap-2.5 w-full sm:w-auto sm:flex sm:items-center">
                        <button type="button"
                            @click="confirmApprove(detailData.approval_id, detailData.jenis_pengajuan, detailData.nama_pegawai)"
                            class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition hover:bg-green-700 shadow-sm">
                            Setujui
                        </button>
                        <button type="button"
                            @click="openRejectModal(detailData.approval_id, detailData.jenis_pengajuan, detailData.nama_pegawai)"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition hover:bg-red-700 shadow-sm">
                            Tolak
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- REJECT REASON MODAL --}}
    <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4" @click.self="closeRejectModal()">
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl sm:rounded-[24px] bg-white shadow-2xl ring-1 ring-slate-200" @click.stop>
            <div class="border-b border-slate-200 px-5 sm:px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Alasan Penolakan</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500" x-text="'Tolak pengajuan ' + rejectData.jenis_pengajuan + ' untuk ' + rejectData.nama_pegawai"></p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeRejectModal()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-5 sm:px-6 py-4">
                <label for="alasan_penolakan" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">
                    Masukkan Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="alasan_penolakan" x-model="rejectReason" rows="3"
                    class="w-full rounded-2xl border border-slate-300 p-3 text-xs sm:text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none"
                    placeholder="Contoh: Kuota izin bulan ini sudah habis..."></textarea>
            </div>
            <div class="border-t border-slate-200 bg-white px-5 sm:px-6 py-4 grid grid-cols-2 gap-3 sm:flex sm:justify-end">
                <button type="button" @click="closeRejectModal()"
                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Batal
                </button>
                <button type="button" @click="submitReject()"
                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition hover:bg-red-700 shadow-sm"
                    :disabled="isProcessing">
                    <span x-show="!isProcessing">Tolak Pengajuan</span>
                    <span x-show="isProcessing">Memproses...</span>
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ======================================== --}}
{{-- MODAL: FILTER --}}
{{-- ======================================== --}}
<div
    id="filter-modal"
    class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="filter-modal-title">

    <div class="flex min-h-full items-center justify-center">
        <div class="relative w-full max-w-lg overflow-hidden rounded-3xl sm:rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 sm:px-6 py-4">
                <div>
                    <h2 id="filter-modal-title" class="text-base sm:text-lg font-bold text-slate-900">
                        Filter Pengajuan
                    </h2>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">
                        Saring data pengajuan sesuai kebutuhan Anda.
                    </p>
                </div>
                <button type="button" id="close-filter-modal" class="flex items-center justify-center rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="filter-form" class="space-y-4 px-5 sm:px-6 py-5">
                <div class="space-y-3.5">
                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Jenis Pengajuan</label>
                        <select name="jenis_pengajuan" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-primary outline-none">
                            <option value="">Semua</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="WFH">WFH</option>
                            <option value="WFC">WFC</option>
                            <option value="Dinas">Dinas</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-4 grid grid-cols-2 gap-3 sm:flex sm:justify-end">
                    <button type="button" id="reset-filter" class="w-full sm:w-auto rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </button>
                    <button type="submit" class="w-full sm:w-auto rounded-2xl bg-primary px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition hover:bg-primary-hover shadow-sm">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================== --}}
{{-- MODAL: EXPORT --}}
{{-- ======================================== --}}
<div
    id="export-modal"
    class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="export-modal-title">

    <div class="flex min-h-full items-center justify-center">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl sm:rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 sm:px-8 py-5 sm:py-6">
                <div>
                    <h2 id="export-modal-title" class="text-base sm:text-lg font-bold text-slate-900">
                        Export Pengajuan
                    </h2>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Pilih format dan filter untuk unduh data pengajuan persetujuan.
                    </p>
                </div>
                <button type="button" id="close-export-modal" class="flex items-center justify-center rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Tutup modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="export-form" class="space-y-5 sm:space-y-6 px-5 sm:px-8 py-5 sm:py-6">
                <div class="grid gap-5 sm:gap-6 grid-cols-1 md:grid-cols-2">
                    <div class="space-y-3 sm:space-y-4">
                        <p class="text-xs sm:text-sm font-semibold text-slate-900">Format Export</p>
                        <div class="space-y-3">
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="xlsx" class="mt-1 h-4 w-4 text-primary focus:ring-primary" checked />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900">Excel (.xlsx)</p>
                                    <p class="text-xs text-slate-500">Unduh file Excel dengan data pengajuan persetujuan.</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="csv" class="mt-1 h-4 w-4 text-primary focus:ring-primary" />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900">CSV</p>
                                    <p class="text-xs text-slate-500">Unduh file CSV yang mudah diolah.</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="pdf" class="mt-1 h-4 w-4 text-primary focus:ring-primary" />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900">PDF</p>
                                    <p class="text-xs text-slate-500">Unduh file PDF yang siap dicetak.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <p class="text-xs sm:text-sm font-semibold text-slate-900">Rentang Tanggal</p>
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none" />
                            </div>
                            <div>
                                <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-3 sm:gap-4 border-t border-slate-200 pt-5 sm:pt-6 grid-cols-1 sm:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">Semua</option>
                            <option value="Pending">Pending</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Jenis Pengajuan</label>
                        <select name="jenis_pengajuan" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">Semua</option>
                            @foreach($jenisPengajuan as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-slate-700">Pegawai</label>
                        <select name="pegawai_id" class="w-full rounded-2xl border border-slate-300 bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm text-slate-900 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">Semua</option>
                            @foreach($pegawai as $p)
                                <option value="{{ $p->pegawai_id }}">{{ $p->nama_pegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-5 sm:pt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancel-export" class="w-full sm:w-auto rounded-2xl border border-slate-300 bg-white px-6 py-3 text-xs sm:text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" id="export-submit-btn" class="w-full sm:w-auto rounded-2xl bg-primary px-6 py-3 text-xs sm:text-sm font-semibold text-white transition hover:bg-primary-hover shadow-sm">
                        Unduh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function approvalModal() {
        return {
            showDetail: false,
            showRejectModal: false,
            rejectReason: '',
            isProcessing: false,
            counts: {
                pending: {{ $pending }},
                diproses: {{ $diproses }},
                disetujui: {{ $disetujui }},
                ditolak: {{ $ditolak }}
            },
            detailData: {
                approval_id: null,
                nama_pegawai: '',
                divisi_name: '',
                jenis_pengajuan: '',
                tanggal_pengajuan: '',
                status_pengajuan: '',
                keterangan: '',
                lampiran_path: null,
                lampiran_url: null,
                lampiran_name: null,
                foto_profile: null
            },
            rejectData: {
                approval_id: null,
                jenis_pengajuan: '',
                nama_pegawai: ''
            },
            openApproval(event) {
                const button = event.currentTarget;
                const data = JSON.parse(button.getAttribute('data-approval'));
                this.detailData = data;
                this.showDetail = true;
            },
            closeDetail() {
                this.showDetail = false;
            },
            detailInitials() {
                if (!this.detailData.nama_pegawai) return 'U';
                return this.detailData.nama_pegawai.substring(0, 1).toUpperCase();
            },
            statusClass(status) {
                switch(status) {
                    case 'Pending': return 'bg-yellow-100 text-yellow-700';
                    case 'Diproses': return 'bg-blue-100 text-blue-700';
                    case 'Disetujui': return 'bg-green-100 text-green-700';
                    case 'Ditolak': return 'bg-red-100 text-red-700';
                    default: return 'bg-slate-100 text-slate-700';
                }
            },
            confirmApprove(approvalId, jenisPengajuan, namaPegawai) {
                if (confirm('Apakah Anda yakin ingin MENYETUJUI pengajuan ' + jenisPengajuan + ' dari ' + namaPegawai + '?')) {
                    this.processApproval(approvalId, 'setujui');
                }
            },
            openRejectModal(approvalId, jenisPengajuan, namaPegawai) {
                this.rejectData = {
                    approval_id: approvalId,
                    jenis_pengajuan: jenisPengajuan,
                    nama_pegawai: namaPegawai
                };
                this.rejectReason = '';
                this.showRejectModal = true;
            },
            closeRejectModal() {
                this.showRejectModal = false;
                this.rejectReason = '';
            },
            submitReject() {
                if (!this.rejectReason.trim()) {
                    alert('Mohon isi alasan penolakan.');
                    return;
                }
                this.processApproval(this.rejectData.approval_id, 'tolak', this.rejectReason);
            },
            processApproval(approvalId, action, alasan = null) {
                this.isProcessing = true;

                const url = '{{ route("admin.persetujuan.process", ":id") }}'
                    .replace(':id', approvalId);

                const bodyData = {
                    status_approval: action === 'setujui' ? 'Disetujui' : 'Ditolak',
                    _token: '{{ csrf_token() }}'
                };

                if (alasan) {
                    bodyData.catatan_admin = alasan;
                }

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(bodyData)
                })
                .then(async res => {
                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan.');
                    }

                    return data;
                })
                .then(data => {
                    this.isProcessing = false;

                    if (data.status === 'success') {
                        alert(data.message);

                        this.closeRejectModal();
                        this.closeDetail();

                        window.location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat memproses pengajuan.');
                    }
                })
                .catch(err => {
                    this.isProcessing = false;
                    console.error(err);
                    alert(err.message || 'Gagal menghubungi server.');
                });
            }
        };
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.approval-tab');
    const tableContainer = document.getElementById('approval-table');

    const filterModal = document.getElementById('filter-modal');
    const openFilterModal = document.getElementById('open-filter-modal');
    const closeFilterModal = document.getElementById('close-filter-modal');
    const resetFilter = document.getElementById('reset-filter');
    const filterForm = document.getElementById('filter-form');

    function openFilter() {
        filterModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeFilter() {
        filterModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    openFilterModal.addEventListener('click', openFilter);
    closeFilterModal.addEventListener('click', closeFilter);

    resetFilter.addEventListener('click', function () {
        filterForm.reset();
        loadApprovals('');
        closeFilter();
    });

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(filterForm);
        const tanggalAwal = formData.get('tanggal_awal');
        const tanggalAkhir = formData.get('tanggal_akhir');
        const jenisPengajuan = formData.get('jenis_pengajuan');

        const url = new URL('{{ route("admin.persetujuan") }}', window.location.origin);
        if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal);
        if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir);
        if (jenisPengajuan) url.searchParams.set('jenis_pengajuan', jenisPengajuan);

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal memfilter pengajuan.');
            return response.json();
        })
        .then(data => {
            tableContainer.innerHTML = data.html;
            if (window.Alpine) window.Alpine.initTree(tableContainer);
            if (data.counts) {
                const rootElem = document.querySelector('[x-data]');
                if (rootElem && rootElem._x_dataStack && rootElem._x_dataStack[0]) {
                    rootElem._x_dataStack[0].counts = data.counts;
                }
            }
            window.history.pushState({}, '', url.toString());
            closeFilter();
        })
        .catch(error => {
            console.error(error);
            alert('Gagal menerapkan filter.');
        });
    });

    const exportModal = document.getElementById('export-modal');
    const openExportModal = document.getElementById('open-export-modal');
    const closeExportModal = document.getElementById('close-export-modal');
    const cancelExport = document.getElementById('cancel-export');
    const exportForm = document.getElementById('export-form');
    const exportSubmitBtn = document.getElementById('export-submit-btn');
    let exportIsLoading = false;

    function setExportLoading(loading) {
        exportIsLoading = loading;
        if (exportSubmitBtn) {
            exportSubmitBtn.disabled = loading;
            exportSubmitBtn.textContent = loading ? 'Menyiapkan...' : 'Unduh';
        }
    }

    function openExport() {
        setExportLoading(false);
        exportModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeExport() {
        setExportLoading(false);
        exportModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        exportForm.reset();
    }

    openExportModal.addEventListener('click', openExport);
    closeExportModal.addEventListener('click', closeExport);
    cancelExport.addEventListener('click', closeExport);

    exportForm.addEventListener('submit', function (event) {
        event.preventDefault();
        if (exportIsLoading) {
            return;
        }

        const formData = new FormData(exportForm);
        const format = formData.get('format');
        const tanggalAwal = formData.get('tanggal_awal');
        const tanggalAkhir = formData.get('tanggal_akhir');
        const status = formData.get('status');
        const jenisPengajuan = formData.get('jenis_pengajuan');
        const pegawaiId = formData.get('pegawai_id');

        if (!format) {
            alert('Silakan pilih format export terlebih dahulu.');
            return;
        }

        setExportLoading(true);

        let exportUrl = '{{ route("admin.persetujuan.export.excel") }}';
        if (format === 'csv') {
            exportUrl = '{{ route("admin.persetujuan.export.csv") }}';
        } else if (format === 'pdf') {
            exportUrl = '{{ route("admin.persetujuan.export.pdf") }}';
        }

        const url = new URL(exportUrl, window.location.origin);
        if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal);
        if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir);
        if (status) url.searchParams.set('status', status);
        if (jenisPengajuan) url.searchParams.set('jenis_pengajuan', jenisPengajuan);
        if (pegawaiId) url.searchParams.set('pegawai_id', pegawaiId);

        window.location.href = url.toString();
        setTimeout(() => {
            setExportLoading(false);
        }, 1500);
    });

    function setActiveTab(activeTab) {
        tabs.forEach(function (tab) {
            tab.classList.remove('bg-white', 'text-primary', 'shadow-sm');
            tab.classList.add('text-slate-600');
        });
        activeTab.classList.remove('text-slate-600');
        activeTab.classList.add('bg-white', 'text-primary', 'shadow-sm');
    }

    function loadApprovals(status, pushState = true) {
        const url = new URL('{{ route("admin.persetujuan") }}', window.location.origin);
        if (status) url.searchParams.set('status', status);

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data pengajuan.');
            return response.json();
        })
        .then(data => {
            tableContainer.innerHTML = data.html;
            if (window.Alpine) window.Alpine.initTree(tableContainer);
            if (data.counts) {
                const rootElem = document.querySelector('[x-data]');
                if (rootElem && rootElem._x_dataStack && rootElem._x_dataStack[0]) {
                    rootElem._x_dataStack[0].counts = data.counts;
                }
            }
            if (pushState) window.history.pushState({}, '', url.toString());
        })
        .catch(error => {
            console.error(error);
            alert('Gagal memuat data pengajuan.');
        });
    }

    window.refreshApprovalTable = function () {
        const currentActiveTab = Array.from(tabs).find(t => t.classList.contains('bg-white'));
        const status = currentActiveTab ? currentActiveTab.dataset.status : (new URLSearchParams(window.location.search).get('status') || '');
        loadApprovals(status, false);
    };

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const status = this.dataset.status;
            setActiveTab(this);
            loadApprovals(status);
        });
    });

    const currentStatus = new URLSearchParams(window.location.search).get('status') || '';
    const activeTab = Array.from(tabs).find(function (tab) {
        return tab.dataset.status === currentStatus;
    });

    if (activeTab) {
        setActiveTab(activeTab);
    } else {
        setActiveTab(tabs[0]);
    }
});
</script>
@endpush

@endsection