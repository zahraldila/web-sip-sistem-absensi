<?php

namespace App\Exports;

use App\Models\Approval;
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
            ->with('pegawai');

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

        if (!empty($this->filters['status'])) {
            $query->where(
                'status_pengajuan',
                $this->filters['status']
            );
        }

        if (!empty($this->filters['pegawai_id'])) {
            $query->where(
                'pegawai_id',
                $this->filters['pegawai_id']
            );
        }

        return $query->orderByDesc('tanggal_pengajuan');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Jabatan',
            'Jenis Pengajuan',
            'Tanggal Pengajuan',
            'Status',
        ];
    }

    public function map($approval): array
    {
        static $no = 0;

        $no++;

        return [
            $no,
            $approval->pegawai?->nama_pegawai ?? '-',
            $approval->pegawai?->jabatan ?? '-',
            $approval->jenis_pengajuan ?? '-',
            $approval->tanggal_pengajuan ?? '-',
            $approval->status_pengajuan ?? '-',
        ];
    }
}