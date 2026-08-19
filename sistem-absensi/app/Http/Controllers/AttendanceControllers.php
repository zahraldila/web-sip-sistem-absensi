<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\logHelpers; // Wajib panggil helper log activity
use Carbon\Carbon;

class AttendanceControllers extends Controller
{
    /**
     * Proses Check-in Absensi Pegawai
     */
    public function checkIn(Request $request)
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user(); 
        
        // Pastikan user memiliki relasi dengan pegawai
        $pegawaiId = $user->pegawai_id ?? session('pegawai_id'); 

        // 2. (Opsional) Validasi input dari mobile app
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            // 'foto_selfie' => 'required|image' // Buka komen jika ada upload foto
        ]);

        $activeSchedule = DB::table('jadwal_kerja')->orderByDesc('jadwal_id')->first();
        $jadwalId = $activeSchedule ? $activeSchedule->jadwal_id : 1;

        // 3. Simpan data absensi ke database
        DB::table('absensi')->insert([
            'pegawai_id'       => $pegawaiId,
            'tanggal_absensi'  => Carbon::today(),
            'jam_checkin'      => Carbon::now(),
            'status_kehadiran' => 'Hadir', // Sesuaikan dengan logika bisnismu
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'skema_kerja'      => $request->skema_kerja ?? 'WFO',
            'jadwal_id'        => $jadwalId,
            // 'foto_selfie'   => $pathFoto,
        ]);

        // ---------------------------------------------------------
        // INJEKSI LOG ACTIVITY: Mencatat aktivitas check-in
        // ---------------------------------------------------------
        logHelpers::record($user->akun_id, 'Melakukan check-in absensi');
        // ---------------------------------------------------------

        // Karena ini biasanya diakses dari Mobile App, kita kembalikan response JSON
        return response()->json([
            'status'  => 'success',
            'message' => 'Check-in berhasil dicatat!'
        ], 200);
    }

    /**
     * Proses Check-out Absensi Pegawai
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai_id ?? session('pegawai_id');
        $tanggalHariIni = Carbon::today();

        // 1. (Opsional) Validasi input lokasi check-out
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 2. Update kolom jam_checkout di record absensi hari ini
        $affected = DB::table('absensi')
            ->where('pegawai_id', $pegawaiId)
            ->where('tanggal_absensi', $tanggalHariIni)
            ->update([
                'jam_checkout' => Carbon::now(),
                // Bisa juga ditambahkan validasi update koordinat checkout jika perlu
            ]);

        if ($affected === 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data absensi hari ini tidak ditemukan (Belum check-in).'
            ], 404);
        }

        // ---------------------------------------------------------
        // INJEKSI LOG ACTIVITY: Mencatat aktivitas check-out
        // ---------------------------------------------------------
        logHelpers::record($user->akun_id, 'Melakukan check-out absensi');
        // ---------------------------------------------------------

        return response()->json([
            'status'  => 'success',
            'message' => 'Check-out berhasil dicatat!'
        ], 200);
    }
}