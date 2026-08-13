{{-- ======================================== --}}
{{-- TABLE --}}
{{-- ======================================== --}}

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Karyawan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Jenis Pengajuan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Tanggal Pengajuan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
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
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="flex items-center gap-4">
                            @php $photoUrl = supabase_public_url($approval->pegawai?->foto_profile); @endphp
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $approval->pegawai?->nama_pegawai }}" class="h-10 w-10 rounded-full object-cover" />
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-semibold text-slate-700">
                                    {{ strtoupper(substr($approval->pegawai?->nama_pegawai ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-slate-900">{{ $approval->pegawai?->nama_pegawai ?? '-' }}</p>
                                <p class="text-sm text-slate-400">{{ $approval->pegawai?->jabatan ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <span class="font-medium text-slate-700">{{ $approval->jenis_pengajuan ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        @if($approval->tanggal_pengajuan)
                            {{ \Carbon\Carbon::parse($approval->tanggal_pengajuan)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $approval->status_pengajuan ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.persetujuan.detail', $approval) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <p class="text-slate-400">Belum ada data pengajuan.</p>
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