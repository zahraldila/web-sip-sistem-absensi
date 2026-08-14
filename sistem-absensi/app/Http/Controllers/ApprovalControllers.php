<?php

namespace App\Http\Controllers;

use App\Exports\ApprovalExport;
use App\Models\Approval;
use App\Models\Pegawai; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\logHelpers;
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
                'counts' => [
                    'pending' => $pending,
                    'diproses' => $diproses,
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                ],
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
     * Setujui pengajuan oleh Admin
     */
    public function approve(Request $request, $pengajuanId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi login telah berakhir. Silakan login kembali.',
            ], 401);
        }

        $pengajuan = DB::table('pengajuan')->where('pengajuan_id', $pengajuanId)->first();
        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengajuan tidak ditemukan.',
            ], 404);
        }

        // Pastikan hanya pengajuan berstatus 'Pending' yang bisa disetujui
        if ($pengajuan->status_pengajuan !== 'Pending') {
            return response()->json([
                'status' => 'error',
                'message' => "Pengajuan ini sudah berstatus {$pengajuan->status_pengajuan} dan tidak dapat diproses lagi.",
            ], 422);
        }

        $now = Carbon::now();
        $jenisPengajuan = $pengajuan->jenis_pengajuan ?? 'Pengajuan';

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel 'approval'
            DB::table('approval')->insert([
                'pengajuan_id'     => $pengajuanId,
                'akun_id'          => $user->akun_id,
                'status_approval'  => 'Disetujui',
                'catatan_admin'    => $request->catatan_admin ?? null,
                'tanggal_approval' => $now,
            ]);

            // 2. Update status pada tabel 'pengajuan'
            DB::table('pengajuan')
                ->where('pengajuan_id', $pengajuanId)
                ->update([
                    'status_pengajuan' => 'Disetujui',
                ]);

            // Ensure notifikasi id sequence is set correctly
            // Sequence sync handled by migration; removed direct setval.

            // 3. Simpan record notifikasi untuk pegawai pemilik pengajuan
            if ($pengajuan->pegawai_id) {
                DB::table('notifikasi')->insert([
                    'pegawai_id'    => $pengajuan->pegawai_id,
                    'judul'         => "Pengajuan {$jenisPengajuan} Disetujui",
                    'isi_pesan'     => "Pengajuan {$jenisPengajuan} Anda telah disetujui oleh admin.",
                    'tanggal_kirim' => $now,
                ]);
            }

            // 4. Injeksi log aktivitas
            logHelpers::record($user->akun_id, "Menyetujui pengajuan {$jenisPengajuan} (ID: {$pengajuanId})");

            DB::commit();

            // Ambil data statistik counter terkini
            $counts = [
                'pending'   => DB::table('pengajuan')->where('status_pengajuan', 'Pending')->count(),
                'diproses'  => DB::table('pengajuan')->where('status_pengajuan', 'Diproses')->count(),
                'disetujui' => DB::table('pengajuan')->where('status_pengajuan', 'Disetujui')->count(),
                'ditolak'   => DB::table('pengajuan')->where('status_pengajuan', 'Ditolak')->count(),
            ];

            return response()->json([
                'status'  => 'success',
                'message' => "Pengajuan {$jenisPengajuan} berhasil disetujui.",
                'counts'  => $counts,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyetujui pengajuan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tolak pengajuan oleh Admin (wajib menyertakan alasan)
     */
    public function reject(Request $request, $pengajuanId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi login telah berakhir. Silakan login kembali.',
            ], 401);
        }

        // Validasi alasan penolakan wajib diisi
        $request->validate([
            'catatan_admin' => 'required|string|min:3|max:1000',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
            'catatan_admin.min'      => 'Alasan penolakan minimal harus berisi 3 karakter.',
            'catatan_admin.max'      => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')->where('pengajuan_id', $pengajuanId)->first();
        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengajuan tidak ditemukan.',
            ], 404);
        }

        // Pastikan hanya pengajuan berstatus 'Pending' yang bisa ditolak
        if ($pengajuan->status_pengajuan !== 'Pending') {
            return response()->json([
                'status' => 'error',
                'message' => "Pengajuan ini sudah berstatus {$pengajuan->status_pengajuan} dan tidak dapat diproses lagi.",
            ], 422);
        }

        $now = Carbon::now();
        $jenisPengajuan = $pengajuan->jenis_pengajuan ?? 'Pengajuan';
        $alasanPenolakan = trim($request->catatan_admin);

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel 'approval'
            DB::table('approval')->insert([
                'pengajuan_id'     => $pengajuanId,
                'akun_id'          => $user->akun_id,
                'status_approval'  => 'Ditolak',
                'catatan_admin'    => $alasanPenolakan,
                'tanggal_approval' => $now,
            ]);

            // 2. Update status pada tabel 'pengajuan'
            DB::table('pengajuan')
                ->where('pengajuan_id', $pengajuanId)
                ->update([
                    'status_pengajuan' => 'Ditolak',
                ]);

            // Ensure notifikasi id sequence is set correctly
            // Sequence sync handled by migration; removed direct setval.

            // 3. Simpan record notifikasi untuk pegawai pemilik pengajuan
            if ($pengajuan->pegawai_id) {
                DB::table('notifikasi')->insert([
                    'pegawai_id'    => $pengajuan->pegawai_id,
                    'judul'         => "Pengajuan {$jenisPengajuan} Ditolak",
                    'isi_pesan'     => "Pengajuan {$jenisPengajuan} Anda ditolak. Alasan: {$alasanPenolakan}",
                    'tanggal_kirim' => $now,
                ]);
            }

            // 4. Injeksi log aktivitas
            logHelpers::record($user->akun_id, "Menolak pengajuan {$jenisPengajuan} (ID: {$pengajuanId}). Alasan: {$alasanPenolakan}");

            DB::commit();

            // Ambil data statistik counter terkini
            $counts = [
                'pending'   => DB::table('pengajuan')->where('status_pengajuan', 'Pending')->count(),
                'diproses'  => DB::table('pengajuan')->where('status_pengajuan', 'Diproses')->count(),
                'disetujui' => DB::table('pengajuan')->where('status_pengajuan', 'Disetujui')->count(),
                'ditolak'   => DB::table('pengajuan')->where('status_pengajuan', 'Ditolak')->count(),
            ];

            return response()->json([
                'status'  => 'success',
                'message' => "Pengajuan {$jenisPengajuan} berhasil ditolak.",
                'counts'  => $counts,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menolak pengajuan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint terpadu untuk pemrosesan status approval
     */
    public function process(Request $request, $pengajuanId)
    {
        $status = $request->input('status_approval');
        if ($status === 'Disetujui') {
            return $this->approve($request, $pengajuanId);
        } elseif ($status === 'Ditolak') {
            return $this->reject($request, $pengajuanId);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Status approval tidak valid. Pilih Disetujui atau Ditolak.',
        ], 422);
    }
}