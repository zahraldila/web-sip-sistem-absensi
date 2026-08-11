<?php

namespace App\Http\Controllers;

use App\Exports\ApprovalExport;
use App\Models\Approval;
use App\Models\Pegawai; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ApprovalControllers extends Controller
{
    public function index(Request $request)
    {
        $pending = Approval::where('status_pengajuan', 'Pending')->count();
    
        $diproses = Approval::where('status_pengajuan', 'Diproses')->count();
    
        $disetujui = Approval::where('status_pengajuan', 'Disetujui')->count();
    
        $ditolak = Approval::where('status_pengajuan', 'Ditolak')->count();
    
        $query = Approval::with('pegawai');
    
        /*
        |--------------------------------------------------------------------------
        | Filter Tab Status
        |--------------------------------------------------------------------------
        */
    
        $allowedStatuses = [
            'Pending',
            'Diproses',
            'Disetujui',
            'Ditolak',
        ];
    
        $status = $request->query('status');
    
        if (in_array($status, $allowedStatuses, true)) {
            $query->where('status_pengajuan', $status);
        } else {
            $status = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Modal
        |--------------------------------------------------------------------------
        */

        $jenis = $request->query('jenis_pengajuan');
        $tanggalAwal = $request->query('tanggal_awal');
        $tanggalAkhir = $request->query('tanggal_akhir');
        $pegawaiId = $request->query('pegawai_id');

        if ($jenis) {
            $query->where('jenis_pengajuan', $jenis);
        }

        if ($tanggalAwal) {
            $query->whereDate('tanggal_pengajuan', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('tanggal_pengajuan', '<=', $tanggalAkhir);
        }

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Data Pengajuan
        |--------------------------------------------------------------------------
        */
    
        $approvals = $query
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(5)
            ->withQueryString();

            $jenisPengajuan = Approval::query()
            ->whereNotNull('jenis_pengajuan')
            ->where('jenis_pengajuan', '!=', '')
            ->select('jenis_pengajuan')
            ->distinct()
            ->orderBy('jenis_pengajuan')
            ->pluck('jenis_pengajuan');
        
        $pegawai = Pegawai::query()
            ->orderBy('nama_pegawai')
            ->get([
                'pegawai_id',
                'nama_pegawai',
            ]);
    
        /*
        |--------------------------------------------------------------------------
        | AJAX Request
        |--------------------------------------------------------------------------
        */
    
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'admin.persetujuan.partials.table',
                    compact('approvals')
                )->render(),
            ]);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Full Page
        |--------------------------------------------------------------------------
        */
    
        return view(
            'admin.persetujuan.index',
            compact(
                'pending',
                'diproses',
                'disetujui',
                'ditolak',
                'approvals',
                'status',
                'jenisPengajuan',
                'pegawai'
            )
        );
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'tanggal_awal' => $request->query('tanggal_awal'),
            'tanggal_akhir' => $request->query('tanggal_akhir'),
            'status' => $request->query('status'),
            'pegawai_id' => $request->query('pegawai_id'),
        ];

        return Excel::download(
            new ApprovalExport($filters),
            'pengajuan-' . now()->format('Y-m-d-His') . '.xlsx'
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