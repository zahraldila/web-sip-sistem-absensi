@extends('layouts.admin.app')

@section('title', 'Dashboard Kehadiran Pegawai')

@section('content')

<div class="space-y-8">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-[34px] font-bold text-slate-900">
                Dashboard Kehadiran Pegawai
            </h1>

            <p class="mt-2 text-[15px] text-slate-500">
                Monitoring kehadiran pegawai secara real-time.
            </p>

        </div>

        <button
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#123D91] px-6 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#0E337A]">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5h2m-1-1v16m8-8H4"/>

            </svg>

            Edit Jam Masuk

        </button>

    </section>



    {{-- ===================================================== --}}
    {{-- SUMMARY CARD --}}
    {{-- ===================================================== --}}

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        {{-- Total Pegawai --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Pegawai
                    </p>

                    <h2 class="mt-3 text-4xl font-bold text-slate-900">
                        10
                    </h2>

                    <p class="mt-3 text-sm text-slate-400">
                        Total karyawan aktif
                    </p>

                </div>

                <div class="rounded-2xl bg-blue-50 p-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-[#123D91]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5V4H2v16h5"/>

                    </svg>

                </div>

            </div>

        </div>



        {{-- Hadir Hari Ini --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Hadir Hari Ini
                    </p>

                    <h2 class="mt-3 text-4xl font-bold text-green-600">
                        8
                    </h2>

                    <p class="mt-3 text-sm text-slate-400">
                        Sudah melakukan check in
                    </p>

                </div>

                <div class="rounded-2xl bg-green-100 p-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-green-600"
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



        {{-- WFO --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        WFO
                    </p>

                    <h2 class="mt-3 text-4xl font-bold text-orange-500">
                        6
                    </h2>

                    <p class="mt-3 text-sm text-slate-400">
                        Work From Office
                    </p>

                </div>

                <div class="rounded-2xl bg-orange-100 p-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-orange-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12h18"/>

                    </svg>

                </div>

            </div>

        </div>



        {{-- WFH/WFC --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        WFH / WFC
                    </p>

                    <h2 class="mt-3 text-4xl font-bold text-purple-600">
                        2
                    </h2>

                    <p class="mt-3 text-sm text-slate-400">
                        Remote Working
                    </p>

                </div>

                <div class="rounded-2xl bg-purple-100 p-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.75 17L15 12l-5.25-5"/>

                    </svg>

                </div>

            </div>

        </div>

    </section>
        {{-- ===================================================== --}}
    {{-- STATISTIK & LIVE CHECK IN --}}
    {{-- ===================================================== --}}
    <section class="grid gap-6 xl:grid-cols-3">

        {{-- ============================= --}}
        {{-- STATISTIK --}}
        {{-- ============================= --}}
        <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-card">

            <div class="mb-6 flex items-center justify-between">

                <div>

                    <h2 class="text-xl font-semibold text-slate-900">
                        Statistik Kehadiran
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Rekap kehadiran pegawai minggu ini
                    </p>

                </div>

                <select
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm outline-none">

                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>

                </select>

            </div>


            {{-- Dummy Chart --}}
            <div
                class="flex h-[340px] items-end justify-between gap-3 rounded-2xl bg-slate-50 p-6">

                @php
                    $bars = [120,210,170,250,190,300,240];
                    $days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                @endphp

                @foreach($bars as $index=>$height)

                    <div class="flex flex-1 flex-col items-center">

                        <div
                            class="w-full rounded-t-xl bg-[#123D91]"
                            style="height: {{ $height }}px">
                        </div>

                        <span class="mt-3 text-xs text-slate-500">
                            {{ $days[$index] }}
                        </span>

                    </div>

                @endforeach

            </div>

        </div>



        {{-- ============================= --}}
        {{-- LIVE CHECK IN --}}
        {{-- ============================= --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="mb-5">

                <h2 class="text-xl font-semibold text-slate-900">
                    Live Check In
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pegawai yang baru melakukan absensi
                </p>

            </div>

            <div class="space-y-4">

                @php

                $pegawai = [

                    [
                        'nama'=>'Devina',
                        'status'=>'WFO',
                        'jam'=>'08:05 WIB'
                    ],

                    [
                        'nama'=>'Anun',
                        'status'=>'WFH',
                        'jam'=>'08:10 WIB'
                    ],

                    [
                        'nama'=>'Gisel',
                        'status'=>'WFO',
                        'jam'=>'08:18 WIB'
                    ],

                    [
                        'nama'=>'Ama',
                        'status'=>'WFC',
                        'jam'=>'08:23 WIB'
                    ],

                    [
                        'nama'=>'Farida',
                        'status'=>'WFO',
                        'jam'=>'08:31 WIB'
                    ],

                ];

                @endphp


                @foreach($pegawai as $item)

                    <div
                        class="flex items-center justify-between rounded-2xl border border-slate-100 p-4 transition hover:bg-slate-50">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#123D91] text-lg font-bold text-white">

                                {{ strtoupper(substr($item['nama'],0,1)) }}

                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">

                                    {{ $item['nama'] }}

                                </h3>

                                <div class="mt-1 flex items-center gap-2">

                                    <span
                                        class="rounded-full bg-green-100 px-2 py-1 text-[11px] font-medium text-green-700">

                                        Check In

                                    </span>

                                    <span
                                        class="rounded-full bg-blue-100 px-2 py-1 text-[11px] font-medium text-blue-700">

                                        {{ $item['status'] }}

                                    </span>

                                </div>

                            </div>

                        </div>

                        <div class="text-right">

                            <p class="text-xs text-slate-400">

                                Waktu

                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-900">

                                {{ $item['jam'] }}

                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>
        {{-- ===================================================== --}}
    {{-- APPROVAL & AKTIVITAS --}}
    {{-- ===================================================== --}}
    <section class="grid gap-6 xl:grid-cols-3">

        {{-- ======================================== --}}
        {{-- MENUNGGU PERSETUJUAN --}}
        {{-- ======================================== --}}
        <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-card">

            <div class="mb-6 flex items-center justify-between">

                <div>

                    <h2 class="text-xl font-semibold text-slate-900">
                        Menunggu Persetujuan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pengajuan pegawai yang membutuhkan approval
                    </p>

                </div>

                <button
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">

                    Lihat Semua

                </button>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-slate-200">

                            <th class="pb-4 text-left text-sm font-semibold text-slate-500">
                                Nama
                            </th>

                            <th class="pb-4 text-left text-sm font-semibold text-slate-500">
                                Pengajuan
                            </th>

                            <th class="pb-4 text-left text-sm font-semibold text-slate-500">
                                Tanggal
                            </th>

                            <th class="pb-4 text-left text-sm font-semibold text-slate-500">
                                Status
                            </th>

                            <th class="pb-4 text-right text-sm font-semibold text-slate-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php

                        $approval = [

                            [
                                'nama'=>'Anun',
                                'jenis'=>'WFH',
                                'tanggal'=>'10 Juli 2026',
                                'status'=>'Pending'
                            ],

                            [
                                'nama'=>'Gisel',
                                'jenis'=>'Izin',
                                'tanggal'=>'10 Juli 2026',
                                'status'=>'Pending'
                            ],

                            [
                                'nama'=>'Ama',
                                'jenis'=>'Cuti',
                                'tanggal'=>'11 Juli 2026',
                                'status'=>'Pending'
                            ],

                            [
                                'nama'=>'Farida',
                                'jenis'=>'Dinas',
                                'tanggal'=>'11 Juli 2026',
                                'status'=>'Pending'
                            ],

                        ];

                        @endphp

                        @foreach($approval as $item)

                        <tr class="border-b border-slate-100">

                            <td class="py-5">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#123D91] font-semibold text-white">

                                        {{ strtoupper(substr($item['nama'],0,1)) }}

                                    </div>

                                    <span class="font-medium">

                                        {{ $item['nama'] }}

                                    </span>

                                </div>

                            </td>

                            <td>

                                {{ $item['jenis'] }}

                            </td>

                            <td>

                                {{ $item['tanggal'] }}

                            </td>

                            <td>

                                <span
                                    class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">

                                    {{ $item['status'] }}

                                </span>

                            </td>

                            <td>

                                <div class="flex justify-end gap-2">

                                    <button
                                        class="rounded-lg bg-green-500 px-4 py-2 text-xs font-semibold text-white hover:bg-green-600">

                                        Setujui

                                    </button>

                                    <button
                                        class="rounded-lg bg-red-500 px-4 py-2 text-xs font-semibold text-white hover:bg-red-600">

                                        Tolak

                                    </button>

                                </div>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>



        {{-- ======================================== --}}
        {{-- AKTIVITAS TERBARU --}}
        {{-- ======================================== --}}
        <div class="rounded-3xl bg-white p-6 shadow-card">

            <div class="mb-6">

                <h2 class="text-xl font-semibold text-slate-900">

                    Aktivitas Terbaru

                </h2>

                <p class="mt-1 text-sm text-slate-500">

                    Update aktivitas sistem

                </p>

            </div>

            <div class="space-y-5">

                @php

                $activities = [

                    [
                        'title'=>'Devina berhasil Check In',
                        'time'=>'2 menit lalu',
                        'color'=>'green'
                    ],

                    [
                        'title'=>'Anun mengajukan WFH',
                        'time'=>'10 menit lalu',
                        'color'=>'blue'
                    ],

                    [
                        'title'=>'Gisel mengajukan Izin',
                        'time'=>'20 menit lalu',
                        'color'=>'yellow'
                    ],

                    [
                        'title'=>'Ama berhasil Check Out',
                        'time'=>'35 menit lalu',
                        'color'=>'purple'
                    ],

                ];

                @endphp

                @foreach($activities as $activity)

                <div class="flex gap-4">

                    <div
                        class="mt-2 h-3 w-3 rounded-full bg-{{ $activity['color'] }}-500">
                    </div>

                    <div>

                        <h4 class="font-medium text-slate-900">

                            {{ $activity['title'] }}

                        </h4>

                        <p class="mt-1 text-sm text-slate-500">

                            {{ $activity['time'] }}

                        </p>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </section>

</div>

@endsection