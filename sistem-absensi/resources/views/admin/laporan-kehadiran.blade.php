@extends('layouts.admin.app')

@section('title', 'Laporan Kehadiran')

@section('content')
<div x-data="{ filterOpen: false, exportModalOpen: false, exportFormat: 'excel' }">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Laporan Kehadiran</h1>
        <p class="mt-1 text-sm text-gray-600">Ringkasan Laporan Kehadiran Karyawan secara menyeluruh.</p>
    </div>

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan-kehadiran') }}">
            <div class="grid gap-4 lg:grid-cols-[1fr_auto_auto] lg:items-end">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Cari Karyawan</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 12.65z" />
                            </svg>
                        </span>
                        <input
                            id="search"
                            name="search"
                            type="text"
                            value="{{ request('search') }}"
                            placeholder="Cari nama, mode, tanggal, status, atau lokasi..."
                            class="w-full rounded-2xl border border-gray-300 bg-white py-3 pl-10 pr-4 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="relative" @click.outside="filterOpen = false">
                        <button
                            type="button"
                            @click="filterOpen = !filterOpen"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>

                        <div x-show="filterOpen" x-transition x-cloak class="absolute right-0 top-full z-20 mt-2 w-[320px] rounded-2xl border border-gray-200 bg-white p-4 shadow-xl">
                            <div class="space-y-3">
                                <x-forms.select name="status" label="Status">
                                    <option value="Semua" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua</option>
                                    <option value="Hadir" {{ request('status') === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    <option value="Tidak Hadir" {{ request('status') === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                </x-forms.select>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <x-forms.date-picker name="start_date" label="Tanggal Awal" value="{{ request('start_date') }}" />
                                    <x-forms.date-picker name="end_date" label="Tanggal Akhir" value="{{ request('end_date') }}" />
                                </div>

                                <x-forms.select name="mode_kerja" label="Mode Kerja">
                                    <option value="Semua" {{ request('mode_kerja') === 'Semua' ? 'selected' : '' }}>Semua</option>
                                    <option value="WFO" {{ request('mode_kerja') === 'WFO' ? 'selected' : '' }}>WFO</option>
                                    <option value="WFH" {{ request('mode_kerja') === 'WFH' ? 'selected' : '' }}>WFH</option>
                                </x-forms.select>
                            </div>

                            <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-200 pt-3">
                                <button
                                    type="button"
                                    @click="window.location.href = '{{ route('admin.laporan-kehadiran') }}'"
                                    class="rounded-2xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                    Reset
                                </button>
                                <button
                                    type="submit"
                                    class="rounded-2xl bg-[#123D91] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0F3277]">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button
                        type="button"
                        @click="exportModalOpen = true"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 whitespace-nowrap">
                        Export
                    </button>
                </div>
            </div>
        </form>
    </div>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Karyawan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Keluar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Durasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Mode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Lokasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($attendances as $attendance)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @php $photoUrl = supabase_public_url($attendance->pegawai?->foto_profile); @endphp
                                    @if ($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $attendance->pegawai?->nama_pegawai ?? '-' }}" class="h-10 w-10 rounded-full object-cover" />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-semibold text-slate-700">
                                            {{ getInitials($attendance->pegawai?->nama_pegawai) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-gray-900">{{ $attendance->pegawai?->nama_pegawai ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($attendance->tanggal_absensi)->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->jam_checkin ? \Carbon\Carbon::parse($attendance->jam_checkin)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->jam_checkout ? \Carbon\Carbon::parse($attendance->jam_checkout)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @php
                                    if ($attendance->jam_checkin && $attendance->jam_checkout) {
                                        $durationMinutes = \Carbon\Carbon::parse($attendance->jam_checkin)->diffInMinutes($attendance->jam_checkout);
                                        $hours = intdiv($durationMinutes, 60);
                                        $minutes = $durationMinutes % 60;
                                        echo trim(($hours ? $hours . ' Jam' : '') . ($minutes ? ' ' . $minutes . ' Menit' : '')) ?: '0 Menit';
                                    } else {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->skema_kerja ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->latitude !== null && $attendance->longitude !== null ? $attendance->latitude . ', ' . $attendance->longitude : '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <x-status-badge :status="$attendance->status_kehadiran ?? '-'" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-sm text-gray-500">
                                Tidak ada data kehadiran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col gap-4 border-t border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <p class="text-sm text-slate-500">
                Menampilkan {{ $attendances->firstItem() ?? 0 }} - {{ $attendances->lastItem() ?? 0 }} dari {{ $attendances->total() }} data
            </p>

            <nav class="inline-flex overflow-hidden rounded-[16px] border border-slate-200 bg-white shadow-sm" aria-label="Pagination">
                <a
                    class="inline-flex h-[46px] w-[46px] items-center justify-center border-r border-slate-200 text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-l-[16px] {{ $attendances->onFirstPage() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
                    href="{{ $attendances->onFirstPage() ? '#' : $attendances->withQueryString()->previousPageUrl() }}"
                    aria-disabled="{{ $attendances->onFirstPage() ? 'true' : 'false' }}"
                >
                    ‹
                </a>

                @for ($page = 1; $page <= $attendances->lastPage(); $page++)
                    <a
                        class="inline-flex h-[46px] min-w-[50px] items-center justify-center border-r border-slate-200 px-4 text-sm font-medium transition hover:bg-slate-50 {{ $page === $attendances->currentPage() ? 'bg-slate-100 text-[#123D91]' : 'bg-white text-slate-700' }}"
                        href="{{ $attendances->url($page) }}"
                    >
                        {{ $page }}
                    </a>
                @endfor

                <a
                    class="inline-flex h-[46px] w-[46px] items-center justify-center text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-r-[16px] {{ ! $attendances->hasMorePages() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
                    href="{{ $attendances->hasMorePages() ? $attendances->withQueryString()->nextPageUrl() : '#' }}"
                    aria-disabled="{{ ! $attendances->hasMorePages() ? 'true' : 'false' }}"
                >
                    ›
                </a>
            </nav>
        </div>
    </section>

    <div x-show="exportModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-8 py-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Export Laporan</h2>
                    <p class="mt-1 text-sm text-slate-500">Pilih format dan rentang filter untuk unduh laporan.</p>
                </div>
                <button type="button" @click="exportModalOpen = false" class="flex items-center justify-center rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form x-ref="exportForm" method="GET" action="{{ route('admin.laporan-kehadiran.export.excel') }}" class="space-y-6 px-8 py-6">
                <input type="hidden" name="search" value="{{ request('search') }}" />
                <input type="hidden" name="mode_kerja" value="{{ request('mode_kerja') }}" />

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-slate-900">Format Export</p>

                        <div class="space-y-3">
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="excel" class="mt-1 h-4 w-4 text-[#123D91]" x-model="exportFormat" checked />
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">Export Excel</p>
                                    <p class="text-xs text-slate-500">Unduh file Excel dengan data tabel.</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <input type="radio" name="format" value="pdf" class="mt-1 h-4 w-4 text-[#123D91]" x-model="exportFormat" />
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">Export PDF</p>
                                    <p class="text-xs text-slate-500">Unduh file PDF yang siap dicetak.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-slate-900">Rentang Tanggal & Filter</p>
                        <div class="space-y-3">
                            <x-forms.date-picker name="start_date" label="Tanggal Awal" value="{{ request('start_date') }}" />
                            <x-forms.date-picker name="end_date" label="Tanggal Akhir" value="{{ request('end_date') }}" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 border-t border-slate-200 pt-6 lg:grid-cols-2">
                    <x-forms.select name="status" label="Status">
                        <option value="Semua" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua</option>
                        <option value="Hadir" {{ request('status') === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="Tidak Hadir" {{ request('status') === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    </x-forms.select>

                    <x-forms.select name="pegawai_id" label="Pegawai">
                        <option value="Semua" {{ request('pegawai_id') === 'Semua' ? 'selected' : '' }}>Semua</option>
                        @foreach($pegawaiList as $pegawai)
                            <option value="{{ $pegawai->pegawai_id }}" {{ (string) request('pegawai_id') === (string) $pegawai->pegawai_id ? 'selected' : '' }}>{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                    <button type="button" @click="exportModalOpen = false" class="rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button
                        type="button"
                        @click.prevent="
                            $refs.exportForm.action = exportFormat === 'excel' ? '{{ route('admin.laporan-kehadiran.export.excel') }}' : '{{ route('admin.laporan-kehadiran.export.pdf') }}';
                            $refs.exportForm.submit();
                        "
                        class="rounded-3xl bg-[#123D91] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#0E337A]">
                        Unduh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
