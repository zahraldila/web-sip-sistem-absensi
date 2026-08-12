@extends('layouts.admin.app')

@section('title', 'Log Aktivitas')

@section('content')

<!-- Main Content Wrapper -->
<!-- Perbaikan: Menambahkan md:ml-64 (atau md:ml-72) agar memberi ruang untuk sidebar di layar besar -->
<!-- Ubah menjadi seperti ini: -->
<div class="p-6 bg-gray-50 min-h-screen transition-all duration-300">

    <!-- 1. Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau seluruh aktivitas admin dan karyawan secara real-time.</p>
        </div>
        <button class="bg-[#0b1442] hover:bg-blue-900 text-white px-5 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2">
            <!-- Icon Export -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Export
        </button>
    </div>

    <!-- 2. Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card 1: Total Aktivitas -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pegawai</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalPegawai }}</h3>
                <p class="text-xs text-gray-400">Total di sistem</p>
            </div>
        </div>
        <!-- Card 2: Hadir Hari Ini -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-500 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Hadir Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $hadirHariIni }}</h3>
                <p class="text-xs text-gray-400">Pegawai yang sudah check-in</p>
            </div>
        </div>
        <!-- Card 3: WFO / WFH -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-500 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Skema Kerja</p>
                <h3 class="text-lg font-bold text-gray-900">{{ $wfoCount }} WFO / {{ $wfhWfcCount }} WFH</h3>
                <p class="text-xs text-gray-400">Distribusi hari ini</p>
            </div>
        </div>
    </div>

    <!-- 3. Table & Side Widget Area -->
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Kolom Kiri: Tabel -->
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    <!-- LOOPING DATA LOG -->
                    @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($log->waktu_log)->format('H:i') }} WIB
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($log->waktu_log)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800">
                                {{ $log->nama_pegawai ?? $log->username }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if(strtolower($log->role) == 'admin')
                                <span class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-full font-medium">Admin</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-medium">Pegawai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $log->aktivitas }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Belum ada aktivitas yang tercatat.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Kolom Kanan: Widget Ringkasan -->
        <div class="w-full lg:w-72 bg-white rounded-xl shadow-sm border border-gray-100 p-6 self-start shrink-0">
            <h3 class="font-bold text-gray-800 mb-5">Ringkasan Hari ini</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-3 text-gray-600 font-medium">
                        <span class="text-blue-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg></span>
                        Login Terakhir
                    </div>
                    <!-- Kamu bisa mengganti angka statis ini nanti jika ada query khusus untuk jumlah login -->
                    <span class="font-bold text-gray-800">Aktif</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection