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
            ->whereNotNull('jam_checkin')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 3. Sedang Bekerja (Check in ada, checkout masih null)
        $sedangBekerja = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->whereNotNull('jam_checkin')
            ->whereNull('jam_checkout')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 4. Sudah Check Out (Check out sudah terisi)
        $sudahCheckOut = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->whereNotNull('jam_checkout')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 5. WFO Count
        $wfoCount = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->where('skema_kerja', 'WFO')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 6. WFH/WFC Count
        $wfhCount = DB::table('absensi')
            ->whereDate('tanggal_absensi', $date)
            ->whereIn('skema_kerja', ['WFH', 'WFC'])
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 7. Sakit & Izin
        $sakitCount = DB::table('pengajuan')
            ->whereDate('tanggal_pengajuan', $date)
            ->where('jenis_pengajuan', 'Sakit')
            ->where('status_pengajuan', 'Disetujui')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        $izinCount = DB::table('pengajuan')
            ->whereDate('tanggal_pengajuan', $date)
            ->where('jenis_pengajuan', 'Izin')
            ->where('status_pengajuan', 'Disetujui')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        // 8. Belum Hadir / Belum Check In
        $belumHadir = max(0, $totalPegawai - $totalHadir - $sakitCount - $izinCount);

        // 9. Live Attendance Feed (Activities: Check In & Check Out)
        $liveCheckIns = DB::table('absensi')
            ->join('pegawai', 'absensi.pegawai_id', '=', 'pegawai.pegawai_id')
            ->leftJoin('master_divisi', 'pegawai.divisi_id', '=', 'master_divisi.divisi_id')
            ->leftJoin('master_jabatan', 'pegawai.jabatan_id', '=', 'master_jabatan.jabatan_id')
            ->leftJoin('jadwal_kerja', 'absensi.jadwal_id', '=', 'jadwal_kerja.jadwal_id')
            ->whereDate('absensi.tanggal_absensi', $date)
            ->whereNotNull('absensi.jam_checkin')
            ->select(
                'absensi.absensi_id',
                'absensi.jam_checkin',
                'absensi.jam_checkout',
                'absensi.skema_kerja',
                'absensi.status_kehadiran',
                'pegawai.nama_pegawai',
                'pegawai.foto_profile',
                'master_divisi.nama_divisi',
                'master_jabatan.nama_jabatan',
                'jadwal_kerja.jam_masuk',
                'jadwal_kerja.jam_pulang'
            )
            ->orderByRaw('COALESCE(absensi.jam_checkout, absensi.jam_checkin) DESC')
            ->get()
            ->map(function ($item) {
                $hasCheckOut = !empty($item->jam_checkout);
                
                $checkinTime = $item->jam_checkin ? Carbon::parse($item->jam_checkin)->format('H:i:s') : '-';
                $checkoutTime = $item->jam_checkout ? Carbon::parse($item->jam_checkout)->format('H:i:s') : '-';

                // Tipe aktivitas terbaru untuk event/feed
                $tipeAktivitas = $hasCheckOut ? 'checkout' : 'checkin';
                $waktuTerbaru = $hasCheckOut ? $checkoutTime : $checkinTime;

                // Durasi kerja jika sudah checkout
                $durasiKerja = '-';
                if ($item->jam_checkin && $item->jam_checkout) {
                    $diffMins = Carbon::parse($item->jam_checkin)->diffInMinutes(Carbon::parse($item->jam_checkout));
                    $hours = intdiv($diffMins, 60);
                    $mins = $diffMins % 60;
                    $durasiKerja = "{$hours} Jam {$mins} Menit";
                }

                // Format work schema label
                $skema = strtoupper($item->skema_kerja ?? 'WFO');
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
                    $skemaLabel = $item->skema_kerja ?? 'Remote';
                    $lokasi = 'Remote';
                }

                // Format work hours
                $jamKerja = '08:30 - 17:30';
                if ($item->jam_masuk && $item->jam_pulang) {
                    $masuk = Carbon::parse($item->jam_masuk)->format('H:i');
                    $pulang = Carbon::parse($item->jam_pulang)->format('H:i');
                    $jamKerja = "{$masuk} - {$pulang}";
                }

                // Status Kehadiran (Tepat Waktu / Terlambat)
                $statusKehadiran = 'Tepat Waktu';
                if ($item->jam_checkin && $item->jam_masuk) {
                    $checkInTimeParsed = Carbon::parse($item->jam_checkin)->format('H:i:s');
                    $jamMasukTimeParsed = Carbon::parse($item->jam_masuk)->format('H:i:s');
                    if ($checkInTimeParsed > $jamMasukTimeParsed) {
                        $statusKehadiran = 'Terlambat';
                    }
                }

                return [
                    'id' => $item->absensi_id,
                    'nama' => $item->nama_pegawai,
                    'tipe' => $tipeAktivitas, // 'checkin' atau 'checkout'
                    'waktu' => $waktuTerbaru,
                    'jam_checkin' => $checkinTime,
                    'jam_checkout' => $checkoutTime,
                    'durasi' => $durasiKerja,
                    'has_checkout' => $hasCheckOut,
                    'skema' => $skema,
                    'skema_label' => $skemaLabel,
                    'lokasi' => $lokasi,
                    'status_kehadiran' => $statusKehadiran,
                    'status_kerja' => $hasCheckOut ? 'Sudah Pulang' : 'Sedang Bekerja',
                    'divisi' => $item->nama_divisi ?? 'IT',
                    'jabatan' => $item->nama_jabatan ?? 'Staff',
                    'jam_kerja' => $jamKerja,
                    'foto_profile' => (function() use ($item) {
                        if (!$item->foto_profile) {
                            return null;
                        }
                        if (str_starts_with($item->foto_profile, 'http://') || str_starts_with($item->foto_profile, 'https://')) {
                            return $item->foto_profile;
                        }
                        $projectRef = 'fxovkmcrdeezrotwqjhb';
                        $dbUser = env('DB_USERNAME', '');
                        if (str_contains($dbUser, '.')) {
                            $parts = explode('.', $dbUser);
                            $projectRef = end($parts);
                        }
                        return "https://{$projectRef}.supabase.co/storage/v1/object/public/" . ltrim($item->foto_profile, '/');
                    })(),
                ];
            });

        return [
            'totalPegawai' => $totalPegawai,
            'totalHadir' => $totalHadir,
            'sedangBekerja' => $sedangBekerja,
            'sudahCheckOut' => $sudahCheckOut,
            'wfoCount' => $wfoCount,
            'wfhCount' => $wfhCount,
            'sakitCount' => $sakitCount,
            'belumHadir' => $belumHadir,
            'liveCheckIns' => $liveCheckIns,
        ];
    }
}