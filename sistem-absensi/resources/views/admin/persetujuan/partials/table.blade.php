{{-- ======================================== --}}
{{-- TABLE --}}
{{-- ======================================== --}}

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
                    $statusClass = match($approval->status_pengajuan) {
                        'Pending' => 'bg-yellow-100 text-yellow-700',
                        'Diproses' => 'bg-blue-100 text-blue-700',
                        'Disetujui' => 'bg-green-100 text-green-700',
                        'Ditolak' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <tr class="border-t border-slate-100 transition hover:bg-slate-50">

                    {{-- KARYAWAN --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#123D91] font-semibold text-white">

                                {{ strtoupper(substr($approval->pegawai?->nama_pegawai ?? 'U', 0, 1)) }}

                            </div>

                            <div>

                                <p class="font-semibold text-slate-900">
                                    {{ $approval->pegawai?->nama_pegawai ?? '-' }}
                                </p>

                                <p class="text-sm text-slate-400">
                                    {{ $approval->pegawai?->jabatan ?? '-' }}
                                </p>

                            </div>

                        </div>

                    </td>

                    {{-- JENIS PENGAJUAN --}}
                    <td class="px-6 py-5">

                        <span class="font-medium text-slate-700">
                            {{ $approval->jenis_pengajuan ?? '-' }}
                        </span>

                    </td>

                    {{-- TANGGAL PENGAJUAN --}}
                    <td class="px-6 py-5">

                        @if($approval->tanggal_pengajuan)

                            {{ \Carbon\Carbon::parse($approval->tanggal_pengajuan)->translatedFormat('d F Y') }}

                        @else

                            -

                        @endif

                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-5">

                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">

                            {{ $approval->status_pengajuan ?? '-' }}

                        </span>

                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-5 text-center">

                        <a
                            href="{{ route('admin.persetujuan.detail', $approval) }}"
                            class="inline-flex items-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">

                            Detail

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="py-16 text-center">

                        <p class="text-slate-400">
                            Belum ada data pengajuan.
                        </p>

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

    {{ $approvals->onEachSide(1)->links() }}

</div>