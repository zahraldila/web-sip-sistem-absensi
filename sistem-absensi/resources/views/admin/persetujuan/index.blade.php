@extends('layouts.admin.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')

<div class="space-y-8" x-data="approvalModal()">

    {{-- ======================================== --}}
    {{-- HEADER --}}
    {{-- ======================================== --}}
    <section class="flex flex-col gap-2">

        <p class="text-sm font-medium text-slate-500">
            PT Selada Indonesia Produktif
        </p>

        <h1 class="text-[34px] font-bold text-slate-900">
            Persetujuan Pengajuan
        </h1>

        <p class="text-[15px] text-slate-500">
            Pengelolaan Izin, Sakit, WFH, WFC, dan Dinas Pegawai
        </p>

    </section>

    {{-- ======================================== --}}
    {{-- SUMMARY CARD --}}
    {{-- ======================================== --}}
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        {{-- Pending --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-card">

            <div class="flex">

                <div class="w-1.5 bg-orange-500"></div>

                <div class="flex flex-1 items-center justify-between p-6">

                    <div>

                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">
                            Pending
                        </p>

                        <h2 class="mt-2 text-4xl font-bold text-slate-900">
                            {{ $pending }}
                        </h2>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-orange-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- Diproses --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-card">

            <div class="flex">

                <div class="w-1.5 bg-blue-600"></div>

                <div class="flex flex-1 items-center justify-between p-6">

                    <div>

                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">
                            Diproses
                        </p>

                        <h2 class="mt-2 text-4xl font-bold text-slate-900">
                            {{ $diproses }}
                        </h2>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v6h6M20 20v-6h-6M20 8A8 8 0 006.34 5.34L4 10m16 4l-2.34 4.66A8 8 0 014 16"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- Disetujui --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-card">

            <div class="flex">

                <div class="w-1.5 bg-green-600"></div>

                <div class="flex flex-1 items-center justify-between p-6">

                    <div>

                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">
                            Disetujui
                        </p>

                        <h2 class="mt-2 text-4xl font-bold text-slate-900">
                            {{ $disetujui }}
                        </h2>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- Ditolak --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-card">

            <div class="flex">

                <div class="w-1.5 bg-red-600"></div>

                <div class="flex flex-1 items-center justify-between p-6">

                    <div>

                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">
                            Ditolak
                        </p>

                        <h2 class="mt-2 text-4xl font-bold text-slate-900">
                            {{ $ditolak }}
                        </h2>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ======================================== --}}
    {{-- TABLE CONTAINER --}}
    {{-- ======================================== --}}
    <section class="overflow-hidden rounded-3xl bg-white shadow-card border border-slate-200">

        <div class="flex flex-col gap-5 border-b border-slate-200 p-6 lg:flex-row lg:items-center lg:justify-between">

            {{-- TAB --}}
            <div
                id="approval-tabs"
                class="flex flex-wrap rounded-2xl bg-slate-100 p-2">

                {{-- SEMUA --}}
                <button
                    type="button"
                    data-status=""
                    class="approval-tab rounded-xl px-5 py-2 text-sm font-semibold transition">

                    Semua

                </button>


                {{-- MENUNGGU --}}
                <button
                    type="button"
                    data-status="Pending"
                    class="approval-tab rounded-xl px-5 py-2 text-sm font-medium text-slate-600 transition">

                    Menunggu

                </button>


                {{-- DIPROSES --}}
                <button
                    type="button"
                    data-status="Diproses"
                    class="approval-tab rounded-xl px-5 py-2 text-sm font-medium text-slate-600 transition">

                    Diproses

                </button>


                {{-- DISETUJUI --}}
                <button
                    type="button"
                    data-status="Disetujui"
                    class="approval-tab rounded-xl px-5 py-2 text-sm font-medium text-slate-600 transition">

                    Disetujui

                </button>


                {{-- DITOLAK --}}
                <button
                    type="button"
                    data-status="Ditolak"
                    class="approval-tab rounded-xl px-5 py-2 text-sm font-medium text-slate-600 transition">

                    Ditolak

                </button>

            </div>

            {{-- ACTION --}}
        <div class="flex gap-3">

            <button
                type="button"
                id="open-filter-modal"
                class="rounded-2xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                Filter

            </button>

            <button
                type="button"
                id="open-export-modal"
                class="rounded-2xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                Export

            </button>

        </div>

    </div>


    {{-- ======================================== --}}
{{-- TABLE --}}
{{-- ======================================== --}}

<div id="approval-table">

    @include('admin.persetujuan.partials.table', [
        'approvals' => $approvals
    ])

</div>

    {{-- DETAIL MODAL --}}
    <div x-show="showDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4 py-8" @click.self="closeDetail()">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-[24px] bg-white shadow-[0_35px_100px_rgba(15,23,42,0.16)] ring-1 ring-slate-200" @click.stop>
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Detail Pengajuan</h2>
                        <p class="mt-1 text-sm text-slate-500">Informasi lengkap pengajuan yang dipilih.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeDetail()" aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr] items-center">
                    <div class="flex flex-col items-center justify-center text-center">
                        <template x-if="detailData.foto_profile">
                            <img :src="detailData.foto_profile" alt="Foto Pegawai" class="mx-auto h-28 w-28 rounded-full object-cover" />
                        </template>
                        <template x-if="!detailData.foto_profile">
                            <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-slate-200 text-3xl font-semibold text-slate-700" x-text="detailInitials()"></div>
                        </template>
                        <div class="mt-4 text-lg font-semibold text-slate-900" x-text="detailData.nama_pegawai"></div>
                        <div class="mt-1 text-sm text-slate-500" x-text="detailData.divisi_name"></div>
                    </div>
                    <div class="grid gap-4">
                        <div class="grid grid-cols-2 gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-sm text-slate-500">Department</div>
                            <div class="font-medium text-slate-900" x-text="detailData.divisi_name || '-' "></div>

                            <div class="text-sm text-slate-500">Jenis Pengajuan</div>
                            <div class="font-medium text-slate-900" x-text="detailData.jenis_pengajuan || '-' "></div>

                            <div class="text-sm text-slate-500">Tanggal Pengajuan</div>
                            <div class="font-medium text-slate-900" x-text="detailData.tanggal_pengajuan || '-' "></div>

                            <div class="text-sm text-slate-500">Status</div>
                            <div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="statusClass(detailData.status_pengajuan)"
                                    x-text="detailData.status_pengajuan || '-' "></span>
                            </div>

                            <div class="text-sm text-slate-500">Keterangan</div>
                            <div class="font-medium text-slate-900 break-words" x-text="detailData.keterangan || '-' "></div>

                            <div class="text-sm text-slate-500">Lampiran</div>
                            <div>
                                <template x-if="detailData.lampiran_url">
                                    <a :href="detailData.lampiran_url" target="_blank" download class="inline-flex items-center gap-1.5 font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="truncate max-w-[160px]" :title="detailData.lampiran_name" x-text="detailData.lampiran_name || 'Unduh Lampiran'"></span>
                                    </a>
                                </template>
                                <template x-if="!detailData.lampiran_url">
                                    <span class="text-sm text-slate-400">Tidak ada lampiran</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-white px-6 py-4 text-right">
                <button type="button" @click="closeDetail()"
                    class="inline-flex items-center justify-center rounded-[10px] border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:bg-slate-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
    function statusClass(status) {
        switch (status) {
            case 'Pending': return 'bg-yellow-100 text-yellow-700';
            case 'Diproses': return 'bg-blue-100 text-blue-700';
            case 'Disetujui': return 'bg-green-100 text-green-700';
            case 'Ditolak': return 'bg-red-100 text-red-700';
            default: return 'bg-slate-100 text-slate-700';
        }
    }

    function approvalModal() {
        return {
            showDetail: false,
            detailData: {
                approval_id: '',
                nama_pegawai: '',
                divisi_name: '',
                jenis_pengajuan: '',
                tanggal_pengajuan: '',
                status_pengajuan: '',
                keterangan: '',
                lampiran_path: null,
                lampiran_url: null,
                lampiran_name: null,
                foto_profile: ''
            },
            detailInitials() {
                if (!this.detailData.nama_pegawai || this.detailData.nama_pegawai === '-') {
                    return '-';
                }
                return this.detailData.nama_pegawai.split(' ').map(word => word[0]).join('').slice(0, 2).toUpperCase();
            },
            openApproval(event) {
                const raw = event.currentTarget.dataset.approval;
                if (raw) {
                    const data = JSON.parse(raw);
                    this.detailData = {
                        approval_id: data.approval_id || '',
                        nama_pegawai: data.nama_pegawai || '-',
                        divisi_name: data.divisi_name || '-',
                        jenis_pengajuan: data.jenis_pengajuan || '-',
                        tanggal_pengajuan: data.tanggal_pengajuan || '-',
                        status_pengajuan: data.status_pengajuan || '-',
                        keterangan: data.keterangan || '-',
                        lampiran_path: data.lampiran_path || null,
                        lampiran_url: data.lampiran_url || null,
                        lampiran_name: data.lampiran_name || (data.lampiran_path ? data.lampiran_path.split('/').pop() : null),
                        foto_profile: data.foto_profile || ''
                    };
                    this.showDetail = true;
                }
            },
            closeDetail() {
                this.showDetail = false;
            }
        };
    }
    </script>
</section>


{{-- ======================================== --}}
{{-- FILTER MODAL --}}
{{-- ======================================== --}}
@push('modals')

<div
    id="filter-modal"
    class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm"
    aria-labelledby="filter-modal-title"
    role="dialog"
    aria-modal="true">



    {{-- MODAL --}}
    <div class="relative z-10 flex min-h-full items-center justify-center p-4">

        <div
            id="filter-modal-content"
            class="relative z-10 w-full max-w-xl rounded-3xl bg-white shadow-2xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h2
                        id="filter-modal-title"
                        class="text-xl font-bold text-slate-900">

                        Filter Pengajuan

                    </h2>

                    <p class="mt-1 text-sm text-slate-500">

                        Atur filter untuk menampilkan data pengajuan.

                    </p>

                </div>


                {{-- CLOSE --}}
                <button
                    type="button"
                    id="close-filter-modal"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>


            {{-- FORM --}}
            <form id="filter-form">

                <div class="space-y-5 px-6 py-6">

                    {{-- JENIS PENGAJUAN --}}
                    <div>

                        <label
                            for="filter-jenis"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Jenis Pengajuan

                        </label>

                        <select
                            id="filter-jenis"
                            name="jenis_pengajuan"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua Jenis Pengajuan
                            </option>

                            @foreach($jenisPengajuan as $jenis)

                                <option value="{{ $jenis }}">
                                    {{ $jenis }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TANGGAL --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Rentang Tanggal

                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">

                            <div>

                                <label
                                    for="filter-tanggal-awal"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Awal

                                </label>

                                <input
                                    type="date"
                                    id="filter-tanggal-awal"
                                    name="tanggal_awal"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>


                            <div>

                                <label
                                    for="filter-tanggal-akhir"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Akhir

                                </label>

                                <input
                                    type="date"
                                    id="filter-tanggal-akhir"
                                    name="tanggal_akhir"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <label
                            for="filter-status"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Status

                        </label>

                        <select
                            id="filter-status"
                            name="status"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Diproses">
                                Diproses
                            </option>

                            <option value="Disetujui">
                                Disetujui
                            </option>

                            <option value="Ditolak">
                                Ditolak
                            </option>

                        </select>

                    </div>


                    {{-- PEGAWAI --}}
                    <div>

                        <label
                            for="filter-pegawai"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Pegawai

                        </label>

                        <select
                            id="filter-pegawai"
                            name="pegawai_id"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua Pegawai
                            </option>

                            @foreach($pegawai as $item)

                                <option value="{{ $item->pegawai_id }}">
                                    {{ $item->nama_pegawai }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex items-center justify-between border-t border-slate-200 px-6 py-5">

                    <button
                        type="button"
                        id="reset-filter"
                        class="rounded-2xl px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">

                        Reset

                    </button>


                    <div class="flex gap-3">

                        <button
                            type="button"
                            id="cancel-filter"
                            class="rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                            Batal

                        </button>

                        <button
                            type="submit"
                            class="rounded-2xl bg-[#123D91] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f3278]">

                            Terapkan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ======================================== --}}
{{-- EXPORT MODAL --}}
{{-- ======================================== --}}

<div
    id="export-modal"
    class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm"
    aria-labelledby="export-modal-title"
    role="dialog"
    aria-modal="true">

    <div class="relative flex min-h-full items-center justify-center p-4">

        <div
            id="export-modal-content"
            class="relative w-full max-w-xl rounded-3xl bg-white shadow-2xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h2
                        id="export-modal-title"
                        class="text-xl font-bold text-slate-900">

                        Export Pengajuan

                    </h2>

                    <p class="mt-1 text-sm text-slate-500">

                        Pilih format dan data yang ingin diekspor.

                    </p>

                </div>

                <button
                    type="button"
                    id="close-export-modal"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>


            {{-- FORM --}}
            <form id="export-form">

                <div class="space-y-5 px-6 py-6">

                    {{-- FORMAT --}}
                    <div>

                        <label class="mb-3 block text-sm font-semibold text-slate-700">

                            Format

                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            {{-- PDF --}}
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-300 p-4 transition hover:bg-slate-50">

                                <input
                                    type="radio"
                                    name="format"
                                    value="pdf"
                                    class="h-4 w-4 text-[#123D91] focus:ring-[#123D91]">

                                <div>

                                    <p class="text-sm font-semibold text-slate-800">
                                        PDF
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        Export sebagai PDF
                                    </p>

                                </div>

                            </label>


                            {{-- EXCEL --}}
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-300 p-4 transition hover:bg-slate-50">

                                <input
                                    type="radio"
                                    name="format"
                                    value="excel"
                                    class="h-4 w-4 text-[#123D91] focus:ring-[#123D91]">

                                <div>

                                    <p class="text-sm font-semibold text-slate-800">
                                        Excel
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        Export sebagai Excel
                                    </p>

                                </div>

                            </label>

                        </div>

                    </div>


                    {{-- RENTANG TANGGAL --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Rentang Tanggal

                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">

                            <div>

                                <label
                                    for="export-tanggal-awal"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Awal

                                </label>

                                <input
                                    type="date"
                                    id="export-tanggal-awal"
                                    name="tanggal_awal"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>


                            <div>

                                <label
                                    for="export-tanggal-akhir"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Akhir

                                </label>

                                <input
                                    type="date"
                                    id="export-tanggal-akhir"
                                    name="tanggal_akhir"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <label
                            for="export-status"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Status

                        </label>

                        <select
                            id="export-status"
                            name="status"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Diproses">
                                Diproses
                            </option>

                            <option value="Disetujui">
                                Disetujui
                            </option>

                            <option value="Ditolak">
                                Ditolak
                            </option>

                        </select>

                    </div>


                    {{-- PEGAWAI --}}
                    <div>

                        <label
                            for="export-pegawai"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Pegawai

                        </label>

                        <select
                            id="export-pegawai"
                            name="pegawai_id"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua Pegawai
                            </option>

                            @foreach($pegawai as $item)

                                <option value="{{ $item->pegawai_id }}">
                                    {{ $item->nama_pegawai }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-5">

                    <button
                        type="button"
                        id="cancel-export"
                        class="rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="rounded-2xl bg-[#123D91] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f3278]">

                        Unduh

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div
    id="export-modal"
    class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm"
    aria-labelledby="export-modal-title"
    role="dialog"
    aria-modal="true">

    <div class="relative flex min-h-full items-center justify-center p-4">

        <div
            id="export-modal-content"
            class="relative w-full max-w-xl rounded-3xl bg-white shadow-2xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h2
                        id="export-modal-title"
                        class="text-xl font-bold text-slate-900">

                        Export Pengajuan

                    </h2>

                    <p class="mt-1 text-sm text-slate-500">

                        Pilih format dan data yang ingin diekspor.

                    </p>

                </div>

                <button
                    type="button"
                    id="close-export-modal"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>


            {{-- FORM --}}
            <form id="export-form">

                <div class="space-y-5 px-6 py-6">

                    {{-- FORMAT --}}
                    <div>

                        <label class="mb-3 block text-sm font-semibold text-slate-700">

                            Format

                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            {{-- PDF --}}
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-300 p-4 transition hover:bg-slate-50">

                                <input
                                    type="radio"
                                    name="format"
                                    value="pdf"
                                    class="h-4 w-4 text-[#123D91] focus:ring-[#123D91]">

                                <div>

                                    <p class="text-sm font-semibold text-slate-800">
                                        PDF
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        Export sebagai PDF
                                    </p>

                                </div>

                            </label>


                            {{-- EXCEL --}}
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-300 p-4 transition hover:bg-slate-50">

                                <input
                                    type="radio"
                                    name="format"
                                    value="excel"
                                    class="h-4 w-4 text-[#123D91] focus:ring-[#123D91]">

                                <div>

                                    <p class="text-sm font-semibold text-slate-800">
                                        Excel
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        Export sebagai Excel
                                    </p>

                                </div>

                            </label>

                        </div>

                    </div>


                    {{-- RENTANG TANGGAL --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Rentang Tanggal

                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">

                            <div>

                                <label
                                    for="export-tanggal-awal"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Awal

                                </label>

                                <input
                                    type="date"
                                    id="export-tanggal-awal"
                                    name="tanggal_awal"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>


                            <div>

                                <label
                                    for="export-tanggal-akhir"
                                    class="mb-1 block text-xs text-slate-500">

                                    Tanggal Akhir

                                </label>

                                <input
                                    type="date"
                                    id="export-tanggal-akhir"
                                    name="tanggal_akhir"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <label
                            for="export-status"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Status

                        </label>

                        <select
                            id="export-status"
                            name="status"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Diproses">
                                Diproses
                            </option>

                            <option value="Disetujui">
                                Disetujui
                            </option>

                            <option value="Ditolak">
                                Ditolak
                            </option>

                        </select>

                    </div>


                    {{-- PEGAWAI --}}
                    <div>

                        <label
                            for="export-pegawai"
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Pegawai

                        </label>

                        <select
                            id="export-pegawai"
                            name="pegawai_id"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#123D91] focus:ring-2 focus:ring-[#123D91]/10">

                            <option value="">
                                Semua Pegawai
                            </option>

                            @foreach($pegawai as $item)

                                <option value="{{ $item->pegawai_id }}">
                                    {{ $item->nama_pegawai }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-5">

                    <button
                        type="button"
                        id="cancel-export"
                        class="rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="rounded-2xl bg-[#123D91] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f3278]">

                        Unduh

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabs = document.querySelectorAll('.approval-tab');
    const tableContainer = document.getElementById('approval-table');


    /*
    |--------------------------------------------------------------------------
    | FILTER MODAL
    |--------------------------------------------------------------------------
    */

    const filterModal = document.getElementById('filter-modal');
    const openFilterModal = document.getElementById('open-filter-modal');
    const closeFilterModal = document.getElementById('close-filter-modal');
    const cancelFilter = document.getElementById('cancel-filter');
    const filterForm = document.getElementById('filter-form');
    const resetFilter = document.getElementById('reset-filter');


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

    cancelFilter.addEventListener('click', closeFilter);


    /*
    |--------------------------------------------------------------------------
    | Tutup Filter ketika klik overlay
    |--------------------------------------------------------------------------
    */

    filterModal.addEventListener('click', function (event) {

        if (event.target === filterModal) {
            closeFilter();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | RESET FILTER
    |--------------------------------------------------------------------------
    */

    resetFilter.addEventListener('click', function () {

        filterForm.reset();

    });


    /*
    |--------------------------------------------------------------------------
    | TERAPKAN FILTER
    |--------------------------------------------------------------------------
    */

    filterForm.addEventListener('submit', function (event) {

        event.preventDefault();

        const formData = new FormData(filterForm);

        const params = new URLSearchParams();

        const status = formData.get('status');
        const jenis = formData.get('jenis_pengajuan');
        const tanggalAwal = formData.get('tanggal_awal');
        const tanggalAkhir = formData.get('tanggal_akhir');
        const pegawaiId = formData.get('pegawai_id');


        /*
        |--------------------------------------------------------------------------
        | Masukkan filter ke URL
        |--------------------------------------------------------------------------
        */

        if (status) {
            params.set('status', status);
        }

        if (jenis) {
            params.set('jenis_pengajuan', jenis);
        }

        if (tanggalAwal) {
            params.set('tanggal_awal', tanggalAwal);
        }

        if (tanggalAkhir) {
            params.set('tanggal_akhir', tanggalAkhir);
        }

        if (pegawaiId) {
            params.set('pegawai_id', pegawaiId);
        }


        /*
        |--------------------------------------------------------------------------
        | AJAX Request
        |--------------------------------------------------------------------------
        */

        const url = new URL(
            '{{ route("admin.persetujuan") }}',
            window.location.origin
        );

        url.search = params.toString();


        fetch(url.toString(), {

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }

        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Gagal menerapkan filter.');
            }

            return response.json();

        })
        .then(data => {

            /*
            |--------------------------------------------------------------------------
            | Update tabel
            |--------------------------------------------------------------------------
            */

            tableContainer.innerHTML = data.html;
            if (window.Alpine) {
                window.Alpine.initTree(tableContainer);
            }


            /*
            |--------------------------------------------------------------------------
            | Update URL
            |--------------------------------------------------------------------------
            */

            window.history.pushState(
                {},
                '',
                url.toString()
            );


            /*
            |--------------------------------------------------------------------------
            | Update tab aktif
            |--------------------------------------------------------------------------
            */

            const activeStatus = status || '';

            const activeTab = Array.from(tabs).find(function (tab) {

                return tab.dataset.status === activeStatus;

            });

            if (activeTab) {
                setActiveTab(activeTab);
            }


            /*
            |--------------------------------------------------------------------------
            | Tutup modal
            |--------------------------------------------------------------------------
            */

            closeFilter();

        })
        .catch(error => {

            console.error(error);

            alert('Gagal menerapkan filter.');

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EXPORT MODAL
    |--------------------------------------------------------------------------
    */

    const exportModal = document.getElementById('export-modal');
    const openExportModal = document.getElementById('open-export-modal');
    const closeExportModal = document.getElementById('close-export-modal');
    const cancelExport = document.getElementById('cancel-export');
    const exportForm = document.getElementById('export-form');


    function openExport() {

        exportModal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

    }


    function closeExport() {

    exportModal.classList.add('hidden');

    document.body.classList.remove('overflow-hidden');

    exportForm.reset();

    }


    openExportModal.addEventListener('click', openExport);

    closeExportModal.addEventListener('click', closeExport);

    cancelExport.addEventListener('click', closeExport);


    /*
    |--------------------------------------------------------------------------
    | SUBMIT EXPORT
    |--------------------------------------------------------------------------
    */

    exportForm.addEventListener('submit', function (event) {

        event.preventDefault();

        const formData = new FormData(exportForm);

        const format = formData.get('format');
        const tanggalAwal = formData.get('tanggal_awal');
        const tanggalAkhir = formData.get('tanggal_akhir');
        const status = formData.get('status');
        const pegawaiId = formData.get('pegawai_id');


        /*
        |--------------------------------------------------------------------------
        | Validasi Format
        |--------------------------------------------------------------------------
        */

        if (!format) {

            alert('Silakan pilih format export terlebih dahulu.');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | EXPORT EXCEL
        |--------------------------------------------------------------------------
        */

        if (format === 'excel') {

            const url = new URL(
                '{{ route("admin.persetujuan.export.excel") }}',
                window.location.origin
            );


            if (tanggalAwal) {

                url.searchParams.set(
                    'tanggal_awal',
                    tanggalAwal
                );

            }


            if (tanggalAkhir) {

                url.searchParams.set(
                    'tanggal_akhir',
                    tanggalAkhir
                );

            }


            if (status) {

                url.searchParams.set(
                    'status',
                    status
                );

            }


            if (pegawaiId) {

                url.searchParams.set(
                    'pegawai_id',
                    pegawaiId
                );

            }


            window.location.href = url.toString();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | ACTIVE TAB
    |--------------------------------------------------------------------------
    */

    function setActiveTab(activeTab) {

        tabs.forEach(function (tab) {

            tab.classList.remove(
                'bg-white',
                'text-[#123D91]',
                'shadow'
            );

            tab.classList.add(
                'text-slate-600'
            );

        });


        activeTab.classList.remove(
            'text-slate-600'
        );


        activeTab.classList.add(
            'bg-white',
            'text-[#123D91]',
            'shadow'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD APPROVALS
    |--------------------------------------------------------------------------
    */

    function loadApprovals(status, pushState = true) {

        const url = new URL(
            '{{ route("admin.persetujuan") }}',
            window.location.origin
        );


        if (status) {

            url.searchParams.set(
                'status',
                status
            );

        }


        fetch(url.toString(), {

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }

        })
        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Gagal mengambil data pengajuan.'
                );

            }

            return response.json();

        })
        .then(data => {

            tableContainer.innerHTML = data.html;
            if (window.Alpine) {
                window.Alpine.initTree(tableContainer);
            }


            if (pushState) {

                window.history.pushState(
                    {},
                    '',
                    url.toString()
                );

            }

        })
        .catch(error => {

            console.error(error);

            alert(
                'Gagal memuat data pengajuan.'
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | TAB CLICK
    |--------------------------------------------------------------------------
    */

    tabs.forEach(function (tab) {

        tab.addEventListener('click', function () {

            const status = this.dataset.status;

            setActiveTab(this);

            loadApprovals(status);

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Tentukan tab aktif saat halaman pertama kali dibuka
    |--------------------------------------------------------------------------
    */

    const currentStatus =
        new URLSearchParams(
            window.location.search
        ).get('status') || '';


    const activeTab =
        Array.from(tabs).find(function (tab) {

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