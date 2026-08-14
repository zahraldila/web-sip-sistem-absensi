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
                                <p class="text-xs text-slate-400">{{ $approval->pegawai?->masterDivisi?->nama_divisi ?? '-' }}</p>
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
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" @click.prevent="openApproval($event)" data-approval="{{ json_encode([
                                'pengajuan_id' => $approval->pengajuan_id,
                                'approval_id' => $approval->pengajuan_id,
                                'nama_pegawai' => $approval->pegawai->nama_pegawai ?? '-',
                                'divisi_name' => $approval->pegawai->masterDivisi->nama_divisi ?? '-',
                                'jenis_pengajuan' => $approval->jenis_pengajuan ?? '-',
                                'tanggal_pengajuan' => $approval->tanggal_pengajuan ? \Carbon\Carbon::parse($approval->tanggal_pengajuan)->format('d F Y') : '-',
                                'status_pengajuan' => $approval->status_pengajuan ?? '-',
                                'keterangan' => $approval->keterangan ?? '-',
                                'lampiran_path' => $approval->lampiran ?? null,
                                'lampiran_url' => $approval->lampiran ? supabase_submission_url($approval->lampiran) : null,
                                'lampiran_name' => $approval->lampiran ? basename($approval->lampiran) : null,
                                'foto_profile' => $photoUrl
                            ], JSON_HEX_APOS | JSON_HEX_QUOT) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-3.5 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">Detail</button>


                        </div>
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

<div class="flex flex-col gap-4 border-t border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
    <p class="text-sm text-slate-500">
        Menampilkan {{ $approvals->firstItem() ?? 0 }} - {{ $approvals->lastItem() ?? 0 }} dari {{ $approvals->total() }} data
    </p>

    <nav class="inline-flex overflow-hidden rounded-[16px] border border-slate-200 bg-white shadow-sm" aria-label="Pagination">
        <a
            class="inline-flex h-[46px] w-[46px] items-center justify-center border-r border-slate-200 text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-l-[16px] {{ $approvals->onFirstPage() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
            href="{{ $approvals->onFirstPage() ? '#' : $approvals->previousPageUrl() }}"
            aria-disabled="{{ $approvals->onFirstPage() ? 'true' : 'false' }}"
        >
            ‹
        </a>

        @php
            $currentPage = $approvals->currentPage();
            $lastPage = $approvals->lastPage();
            $paginationElements = [];

            if ($lastPage <= 7) {
                for ($i = 1; $i <= $lastPage; $i++) {
                    $paginationElements[] = $i;
                }
            } else {
                if ($currentPage <= 4) {
                    for ($i = 1; $i <= 5; $i++) {
                        $paginationElements[] = $i;
                    }
                    $paginationElements[] = '...';
                    $paginationElements[] = $lastPage;
                } elseif ($currentPage >= $lastPage - 3) {
                    $paginationElements[] = 1;
                    $paginationElements[] = '...';
                    for ($i = $lastPage - 4; $i <= $lastPage; $i++) {
                        $paginationElements[] = $i;
                    }
                } else {
                    $paginationElements[] = 1;
                    $paginationElements[] = '...';
                    for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                        $paginationElements[] = $i;
                    }
                    $paginationElements[] = '...';
                    $paginationElements[] = $lastPage;
                }
            }
        @endphp

        @foreach ($paginationElements as $element)
            @if ($element === '...')
                <span class="inline-flex h-[46px] min-w-[50px] items-center justify-center border-r border-slate-200 px-4 text-sm font-medium text-slate-400 bg-white select-none">
                    ...
                </span>
            @else
                <a
                    class="inline-flex h-[46px] min-w-[50px] items-center justify-center border-r border-slate-200 px-4 text-sm font-medium transition hover:bg-slate-50 {{ $element === $currentPage ? 'bg-slate-100 text-[#123D91]' : 'bg-white text-slate-700' }}"
                    href="{{ $approvals->url($element) }}"
                >
                    {{ $element }}
                </a>
            @endif
        @endforeach

        <a
            class="inline-flex h-[46px] w-[46px] items-center justify-center text-lg font-medium text-slate-700 transition hover:bg-slate-50 rounded-r-[16px] {{ ! $approvals->hasMorePages() ? 'cursor-not-allowed bg-slate-100 text-slate-400' : '' }}"
            href="{{ $approvals->hasMorePages() ? $approvals->nextPageUrl() : '#' }}"
            aria-disabled="{{ ! $approvals->hasMorePages() ? 'true' : 'false' }}"
        >
            ›
        </a>
    </nav>
</div>