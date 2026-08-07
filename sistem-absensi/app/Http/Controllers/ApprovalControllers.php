<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalControllers extends Controller
{
    public function index()
    {
        $pending = Approval::where('status_pengajuan', 'Pending')->count();

        $diproses = Approval::where('status_pengajuan', 'Diproses')->count();

        $disetujui = Approval::where('status_pengajuan', 'Disetujui')->count();

        $ditolak = Approval::where('status_pengajuan', 'Ditolak')->count();

        $approvals = Approval::with('pegawai')
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(5);

        return view(
            'admin.persetujuan.index',
            compact(
                'pending',
                'diproses',
                'disetujui',
                'ditolak',
                'approvals'
            )
        );
    }

    public function show(Approval $approval)
    {
        return view(
            'admin.persetujuan.detail',
            compact('approval')
        );
    }
}