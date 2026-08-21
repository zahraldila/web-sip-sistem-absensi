<?php

namespace App\Exports;

use App\Models\Approval;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApprovalExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query(): Builder
    {
        $query = Approval::query()
            ->with(['pegawai.masterDivisi', 'pegawai.masterJabatan'])
            ->whereHas('pegawai', function ($q) {
                $q->where('status', 'Aktif')
                  ->whereDoesntHave('akun', function ($q2) {
                      $q2->where('role', 'admin');
                  });
            });

        if (!empty($this->filters['tanggal_awal'])) {
            $query->whereDate(
                'tanggal_pengajuan',
                '>=',
                $this->filters['tanggal_awal']
            );
        }

        if (!empty($this->filters['tanggal_akhir'])) {
            $query->whereDate(
                'tanggal_pengajuan',
                '<=',
                $this->filters['tanggal_akhir']
            );
        }

        if (!empty($this->filters['status']) && $this->filters['status'] !== 'Semua') {
            $query->where(
                'status_pengajuan',
                $this->filters['status']
            );
        }

        if (!empty($this->filters['pegawai_id']) && $this->filters['pegawai_id'] !== 'Semua') {
            $query->where(
                'pegawai_id',
                $this->filters['pegawai_id']
            );
        }

        if (!empty($this->filters['jenis_pengajuan']) && $this->filters['jenis_pengajuan'] !== 'Semua') {
            $query->where(
                'jenis_pengajuan',
                $this->filters['jenis_pengajuan']
            );
        }

        return $query->orderByDesc('tanggal_pengajuan');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Divisi',
            'Jenis Pengajuan',
            'Tanggal Pengajuan',
            'Status',
            'Keterangan',
        ];
    }

    public function map($approval): array
    {
        static $no = 0;

        $no++;

        $formattedDate = $approval->tanggal_pengajuan;
        if ($formattedDate) {
            try {
                $formattedDate = Carbon::parse($approval->tanggal_pengajuan)->translatedFormat('d F Y');
            } catch (\Throwable $e) {
                $formattedDate = $approval->tanggal_pengajuan;
            }
        } else {
            $formattedDate = '-';
        }

        return [
            $no,
            $approval->pegawai?->nama_pegawai ?? '-',
            $approval->pegawai?->masterDivisi?->nama_divisi ?? $approval->pegawai?->jabatan ?? '-',
            $approval->jenis_pengajuan ?? '-',
            $formattedDate,
            $approval->status_pengajuan ?? '-',
            $approval->keterangan ?? '-',
        ];
    }
}