<?php

namespace App\Http\Controllers;

use App\Exports\ApprovalExport;
use App\Models\Approval;
use App\Models\Pegawai; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\logHelpers; // Impor helper log
use Carbon\Carbon;

class ApprovalControllers extends Controller
{
    public function index(Request $request)
    {
        $pending = Approval::where('status_pengajuan', 'Pending')->count();
    
        $diproses = Approval::where('status_pengajuan', 'Diproses')->count();
    
        $disetujui = Approval::where('status_pengajuan', 'Disetujui')->count();
    
        $ditolak = Approval::where('status_pengajuan', 'Ditolak')->count();
    
        $query = Approval::with('pegawai.masterDivisi');
    
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

    /**
     * Proses persetujuan atau penolakan pengajuan oleh Admin
     */
    public function process(Request $request, $pengajuanId)
    {
        // 1. Ambil data admin yang sedang login
        $user = Auth::user();

        // (Opsional) Keamanan ekstra: Pastikan hanya admin yang bisa memproses
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak. Hanya admin yang dapat melakukan approval.'], 403);
        }

        // 2. Validasi input dari web
        $request->validate([
            'status_approval' => 'required|string|in:Disetujui,Ditolak', // Validasi enum status
            'catatan_admin'   => 'nullable|string',
        ]);

        // 3. Gunakan Database Transaction
        DB::beginTransaction();
        try {
            // 4. Simpan riwayat persetujuan ke tabel 'approval'
            DB::table('approval')->insert([
                'pengajuan_id'     => $pengajuanId,
                'akun_id'          => $user->akun_id, // ID admin yang menyetujui
                'status_approval'  => $request->status_approval,
                'catatan_admin'    => $request->catatan_admin,
                'tanggal_approval' => Carbon::now(),
            ]);

            // 5. Update status di tabel 'pengajuan'
            DB::table('pengajuan')
                ->where('pengajuan_id', $pengajuanId)
                ->update([
                    'status_pengajuan' => $request->status_approval
                ]);

            DB::commit(); // Simpan permanen ke database

            // ---------------------------------------------------------
            // INJEKSI LOG ACTIVITY: Mencatat bahwa admin melakukan approval
            // ---------------------------------------------------------
            // Contoh output di log: "Memberikan approval (Disetujui) untuk pengajuan ID 5"
            logHelpers::record($user->akun_id, "Memberikan approval ({$request->status_approval}) untuk pengajuan ID {$pengajuanId}");
            // ---------------------------------------------------------

            return response()->json([
                'status'  => 'success',
                'message' => "Pengajuan berhasil {$request->status_approval}!"
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua query jika terjadi error
            
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat memproses approval.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}