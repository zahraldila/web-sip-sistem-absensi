<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\logHelpers; // Impor helper log
use Carbon\Carbon;

class ApprovalControllers extends Controller
{
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