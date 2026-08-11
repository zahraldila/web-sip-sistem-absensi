<?php

namespace App\Http\Controllers\DashboardTv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TvDashboardController extends Controller
{
    /**
     * Display the TV Dashboard landing page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Default to current date, but allow ?date=YYYY-MM-DD for demo/testing
        $date = $request->query('date', now()->toDateString());
        
        $data = $this->fetchStats($date);
        
        return view('dashboard-tv.index', array_merge($data, [
            'selectedDate' => $date,
            'isDemo' => $request->has('date')
        ]));
    }

    /**
     * API endpoint to get real-time stats (used for Alpine.js polling).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        
        $data = $this->fetchStats($date);
        
        return response()->json($data);
    }

    /**
     * Helper method to query stats and live check-ins for a specific date.
     *
     * @param  string  $date
     * @return array
     */
    private function fetchStats($date)
    {
        // 1. Total Pegawai (Aktif)
        $totalPegawai = DB::table('pegawai')
            ->where(function ($query) {
                $query->where('status', 'Aktif')
                      ->orWhereNull('status')
                      ->orWhere('status', '');
            })
            ->count();

        // 2. Total Hadir (Unique pegawai_id that checked in today)
        $totalHadir = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 3. WFO Count (checked in as WFO)
        $wfoCount = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->where('skema_kerja', 'WFO')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 4. WFH/WFC Count (checked in as WFH or WFC)
        $wfhCount = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->whereIn('skema_kerja', ['WFH', 'WFC'])
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 5. Sakit Count (Approved sick leave for today)
        $sakitCount = DB::table('pengajuan')
            ->whereDate('tanggal_pengajuan', $date)
            ->where('jenis_pengajuan', 'Sakit')
            ->where('status_pengajuan', 'Disetujui')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 6. Belum Hadir = Total Pegawai - Total Hadir - Sakit
        $izinCount = DB::table('pengajuan')
            ->whereDate('tanggal_pengajuan', $date)
            ->where('jenis_pengajuan', 'Izin')
            ->where('status_pengajuan', 'Disetujui')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        $belumHadir = max(0, $totalPegawai - $totalHadir - $sakitCount - $izinCount);

        // 7. Live Check In List (joining absensi, pegawai, master_divisi, master_jabatan, and jadwal_kerja)
        $liveCheckIns = DB::table('absensi')
            ->join('pegawai', 'absensi.pegawai_id', '=', 'pegawai.pegawai_id')
            ->leftJoin('master_divisi', 'pegawai.divisi_id', '=', 'master_divisi.divisi_id')
            ->leftJoin('master_jabatan', 'pegawai.jabatan_id', '=', 'master_jabatan.jabatan_id')
            ->leftJoin('jadwal_kerja', 'absensi.jadwal_id', '=', 'jadwal_kerja.jadwal_id')
            ->whereDate('absensi.tanggal_absensi', $date)
            ->select(
                'absensi.absensi_id',
                'absensi.jam_checkin',
                'absensi.skema_kerja',
                'absensi.status_kehadiran',
                'pegawai.nama_pegawai',
                'pegawai.foto_profile',
                'master_divisi.nama_divisi',
                'master_jabatan.nama_jabatan',
                'jadwal_kerja.jam_masuk',
                'jadwal_kerja.jam_pulang'
            )
            ->orderBy('absensi.jam_checkin', 'desc')
            ->get()
            ->map(function ($item) {
                // Extract time component (HH:MM:SS)
                $time = '-';
                if ($item->jam_checkin) {
                    $time = Carbon::parse($item->jam_checkin)->format('H:i:s');
                }

                // Format work schema label
                $skema = strtoupper($item->skema_kerja);
                if ($skema === 'WFO') {
                    $skemaLabel = 'Work From Office';
                    $lokasi = 'Kantor';
                } elseif ($skema === 'WFH') {
                    $skemaLabel = 'Work From Home';
                    $lokasi = 'Rumah';
                } elseif ($skema === 'WFC') {
                    $skemaLabel = 'Work From Cafe';
                    $lokasi = 'Cafe';
                } else {
                    $skemaLabel = $item->skema_kerja;
                    $lokasi = 'Remote';
                }

                // Format work hours "HH:MM - HH:MM"
                $jamKerja = '08:30 - 17:30'; // default fallback
                if ($item->jam_masuk && $item->jam_pulang) {
                    $masuk = Carbon::parse($item->jam_masuk)->format('H:i');
                    $pulang = Carbon::parse($item->jam_pulang)->format('H:i');
                    $jamKerja = "{$masuk} - {$pulang}";
                }

                return [
                    'id' => $item->absensi_id,
                    'nama' => $item->nama_pegawai,
                    'waktu' => $time,
                    'skema' => $skema,
                    'skema_label' => $skemaLabel,
                    'lokasi' => $lokasi,
                    'status_kehadiran' => (function() use ($item) {
                        if ($item->jam_checkin && $item->jam_masuk) {
                            $checkInTime = Carbon::parse($item->jam_checkin)->format('H:i:s');
                            $jamMasukTime = Carbon::parse($item->jam_masuk)->format('H:i:s');
                            if ($checkInTime > $jamMasukTime) {
                                return 'Terlambat';
                            }
                        }
                        return $item->status_kehadiran ?? 'Tepat Waktu';
                    })(),
                    'divisi' => $item->nama_divisi ?? 'IT',
                    'jabatan' => $item->nama_jabatan ?? 'Staff',
                    'jam_kerja' => $jamKerja,
                    'foto_profile' => $item->foto_profile ? asset('storage/' . $item->foto_profile) : null,
                ];
            });

        return [
            'totalPegawai' => $totalPegawai,
            'totalHadir' => $totalHadir,
            'wfoCount' => $wfoCount,
            'wfhCount' => $wfhCount,
            'sakitCount' => $sakitCount,
            'belumHadir' => $belumHadir,
            'liveCheckIns' => $liveCheckIns,
        ];
    }
}
