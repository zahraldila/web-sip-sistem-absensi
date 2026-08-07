@extends('layouts.admin.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')

<div class="space-y-8">

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
            <div class="flex flex-wrap rounded-2xl bg-slate-100 p-2">

                <button class="rounded-xl bg-white px-5 py-2 text-sm font-semibold text-[#123D91] shadow">
                    Semua
                </button>

                <button class="px-5 py-2 text-sm text-slate-600">
                    Menunggu
                </button>

                <button class="px-5 py-2 text-sm text-slate-600">
                    Diproses
                </button>

                <button class="px-5 py-2 text-sm text-slate-600">
                    Disetujui
                </button>

                <button class="px-5 py-2 text-sm text-slate-600">
                    Ditolak
                </button>

            </div>

            {{-- ACTION --}}
            <div class="flex gap-3">

                <button
                    class="rounded-2xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                    Filter

                </button>

                <button
                    class="rounded-2xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                    Export

                </button>

            </div>

        </div>

        <div class="overflow-x-auto">

    <table class="min-w-full">

        <thead class="bg-slate-50">

            <tr>

                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500">
                    Karyawan
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500">
                    Jenis Pengajuan
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500">
                    Tanggal Pengajuan
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500">
                    Status
                </th>

                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-500">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($approvals as $approval)

                @php

                    $statusClass = match($approval->status_pengajuan){

                        'Pending'
                            => 'bg-yellow-100 text-yellow-700',

                        'Diproses'
                            => 'bg-blue-100 text-blue-700',

                        'Disetujui'
                            => 'bg-green-100 text-green-700',

                        'Ditolak'
                            => 'bg-red-100 text-red-700',

                        default
                            => 'bg-slate-100 text-slate-700'

                    };

                @endphp

                <tr
                    class="border-t border-slate-100 transition hover:bg-slate-50">

                    {{-- ========================= --}}
                    {{-- PEGAWAI --}}
                    {{-- ========================= --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            {{-- Avatar --}}
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#123D91] font-semibold text-white">

                                {{ strtoupper(substr($approval->pegawai->nama_pegawai ?? 'U',0,1)) }}

                            </div>

                            <div>

                                <p class="font-semibold text-slate-900">

                                    {{ $approval->pegawai->nama_pegawai ?? '-' }}

                                </p>

                                <p class="text-sm text-slate-400">

                                    {{ $approval->pegawai->jabatan ?? '-' }}

                                </p>

                            </div>

                        </div>

                    </td>

                    {{-- ========================= --}}
                    {{-- JENIS --}}
                    {{-- ========================= --}}
                    <td class="px-6 py-5">

                        <span class="font-medium text-slate-700">

                            {{ $approval->jenis_pengajuan }}

                        </span>

                    </td>

                    {{-- ========================= --}}
                    {{-- TANGGAL --}}
                    {{-- ========================= --}}
                    <td class="px-6 py-5">

                        {{ \Carbon\Carbon::parse($approval->tanggal_pengajuan)->translatedFormat('d F Y') }}

                    </td>

                    {{-- ========================= --}}
                    {{-- STATUS --}}
                    {{-- ========================= --}}
                    <td class="px-6 py-5">

                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">

                            {{ $approval->status_pengajuan }}

                        </span>

                    </td>

                    {{-- ========================= --}}
                    {{-- AKSI --}}
                    {{-- ========================= --}}
                    <td class="px-6 py-5 text-center">

                        <a
                            href="{{ route('admin.persetujuan.detail',$approval) }}"
                            class="inline-flex items-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">

                            Detail

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5"
                        class="py-16 text-center">

                        <div class="flex flex-col items-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 text-slate-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-2a4 4 0 018 0v2M7 17h10M5 17h14"/>

                            </svg>

                            <p class="mt-4 text-slate-400">

                                Belum ada data pengajuan.

                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- ======================================== --}}
{{-- PAGINATION --}}
{{-- ======================================== --}}
<div
    class="flex flex-col items-center justify-between gap-4 border-t border-slate-200 p-6 lg:flex-row">

    <p class="text-sm text-slate-500">

        Menampilkan

        <span class="font-semibold">

            {{ $approvals->firstItem() ?? 0 }}

        </span>

        -

        <span class="font-semibold">

            {{ $approvals->lastItem() ?? 0 }}

        </span>

        dari

        <span class="font-semibold">

            {{ $approvals->total() }}

        </span>

        data

    </p>

    {{ $approvals->links() }}

</div>

</section>

</div>

@endsection