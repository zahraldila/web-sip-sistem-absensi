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
        // Branch filter: 'all', 'sulaksana', 'cikawao'
        $cabang = strtolower($request->query('cabang', 'all'));
        
        $data = $this->fetchStats($date, $cabang);
        
        return view('dashboard-tv.index', array_merge($data, [
            'selectedDate' => $date,
            'selectedCabang' => $cabang,
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
        $cabang = strtolower($request->query('cabang', 'all'));
        
        $data = $this->fetchStats($date, $cabang);
        
        return response()->json($data);
    }

    /**
     * Helper method to query stats and live check-ins for a specific date and branch.
     *
     * @param  string  $date
     * @param  string  $cabang
     * @return array
     */
    private function fetchStats($date, $cabang = 'all')
    {
        // 1. Total Pegawai (Aktif)
        $totalPegawai = DB::table('pegawai')
            ->where(function ($query) {
                $query->where('status', 'Aktif')
                      ->orWhereNull('status')
                      ->orWhere('status', '');
            })
            ->count();

        // 2. Fetch all raw attendance records for the date
        $allAttendances = DB::table('absensi')
            ->join('pegawai', 'absensi.pegawai_id', '=', 'pegawai.pegawai_id')
            ->leftJoin('master_divisi', 'pegawai.divisi_id', '=', 'master_divisi.divisi_id')
            ->leftJoin('master_jabatan', 'pegawai.jabatan_id', '=', 'master_jabatan.jabatan_id')
            ->leftJoin('jadwal_kerja', 'absensi.jadwal_id', '=', 'jadwal_kerja.jadwal_id')
            ->whereDate('absensi.tanggal_absensi', $date)
            ->whereNotNull('absensi.jam_checkin')
            ->select(
                'absensi.absensi_id',
                'absensi.pegawai_id',
                'absensi.jam_checkin',
                'absensi.jam_checkout',
                'absensi.skema_kerja',
                'absensi.status_kehadiran',
                'absensi.latitude',
                'absensi.longitude',
                'absensi.catatan',
                'pegawai.nama_pegawai',
                'pegawai.foto_profile',
                'master_divisi.nama_divisi',
                'master_jabatan.nama_jabatan',
                'jadwal_kerja.jam_masuk',
                'jadwal_kerja.jam_pulang'
            )
            ->orderByRaw('COALESCE(absensi.jam_checkout, absensi.jam_checkin) DESC')
            ->get();

        // 3. Map records with cabang determination (Sulaksana / Cikawao / Remote)
        $mappedAttendances = $allAttendances->map(function ($item) {
            $catatan = strtolower($item->catatan ?? '');
            
            // Deteksi cabang berdasarkan SSID Wi-Fi kantor
            $branch = 'sulaksana'; // default
            $branchLabel = 'Kantor Sulaksana';
            
            if (str_contains($catatan, 'ideahub') || str_contains($catatan, 'cikawao')) {
                $branch = 'cikawao';
                $branchLabel = 'Kantor Cikawao';
            } elseif (str_contains($catatan, 'sip') || str_contains($catatan, 'sulaksana')) {
                $branch = 'sulaksana';
                $branchLabel = 'Kantor Sulaksana';
            } else {
                // Cek koordinat GPS jika ada
                if ($item->latitude && $item->longitude) {
                    $lat = (float) $item->latitude;
                    $long = (float) $item->longitude;
                    $distCikawao = sqrt(pow($lat - (-6.927558), 2) + pow($long - 107.614570, 2));
                    $distSulaksana = sqrt(pow($lat - (-6.910194), 2) + pow($long - 107.650728, 2));
                    if ($distCikawao < $distSulaksana && $distCikawao < 0.01) {
                        $branch = 'cikawao';
                        $branchLabel = 'Kantor Cikawao';
                    } elseif ($distSulaksana < 0.01) {
                        $branch = 'sulaksana';
                        $branchLabel = 'Kantor Sulaksana';
                    } elseif (in_array(strtoupper($item->skema_kerja ?? ''), ['WFH', 'WFC'])) {
                        $branch = 'remote';
                        $branchLabel = 'Remote (WFH/WFC)';
                    }
                } elseif (in_array(strtoupper($item->skema_kerja ?? ''), ['WFH', 'WFC'])) {
                    $branch = 'remote';
                    $branchLabel = 'Remote (WFH/WFC)';
                }
            }

            $hasCheckOut = !empty($item->jam_checkout);
            $checkinTime = $item->jam_checkin ? Carbon::parse($item->jam_checkin)->format('H:i:s') : '-';
            $checkoutTime = $item->jam_checkout ? Carbon::parse($item->jam_checkout)->format('H:i:s') : '-';
            $tipeAktivitas = $hasCheckOut ? 'checkout' : 'checkin';
            $waktuTerbaru = $hasCheckOut ? $checkoutTime : $checkinTime;

            $durasiKerja = '-';
            if ($item->jam_checkin && $item->jam_checkout) {
                $diffMins = Carbon::parse($item->jam_checkin)->diffInMinutes(Carbon::parse($item->jam_checkout));
                $hours = intdiv($diffMins, 60);
                $mins = $diffMins % 60;
                $durasiKerja = "{$hours} Jam {$mins} Menit";
            }

            $skema = strtoupper($item->skema_kerja ?? 'WFO');
            if ($skema === 'WFO') {
                $skemaLabel = 'Work From Office';
                $lokasi = $branchLabel;
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

            $jamKerja = '08:30 - 17:30';
            if ($item->jam_masuk && $item->jam_pulang) {
                $masuk = Carbon::parse($item->jam_masuk)->format('H:i');
                $pulang = Carbon::parse($item->jam_pulang)->format('H:i');
                $jamKerja = "{$masuk} - {$pulang}";
            }

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
                'pegawai_id' => $item->pegawai_id,
                'nama' => $item->nama_pegawai,
                'tipe' => $tipeAktivitas,
                'waktu' => $waktuTerbaru,
                'jam_checkin' => $checkinTime,
                'jam_checkout' => $checkoutTime,
                'durasi' => $durasiKerja,
                'has_checkout' => $hasCheckOut,
                'skema' => $skema,
                'skema_label' => $skemaLabel,
                'cabang' => $branch,
                'cabang_label' => $branchLabel,
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

        // Filter list based on selected cabang ('all', 'sulaksana', 'cikawao')
        $filteredList = $mappedAttendances;
        if ($cabang === 'sulaksana') {
            $filteredList = $mappedAttendances->filter(fn($i) => $i['cabang'] === 'sulaksana')->values();
        } elseif ($cabang === 'cikawao') {
            $filteredList = $mappedAttendances->filter(fn($i) => $i['cabang'] === 'cikawao')->values();
        }

        // Summary counts based on filtered branch or total
        $totalHadir = $filteredList->pluck('pegawai_id')->unique()->count();
        $sedangBekerja = $filteredList->where('has_checkout', false)->pluck('pegawai_id')->unique()->count();
        $sudahCheckOut = $filteredList->where('has_checkout', true)->pluck('pegawai_id')->unique()->count();
        $wfoCount = $filteredList->where('skema', 'WFO')->pluck('pegawai_id')->unique()->count();
        $wfhCount = $filteredList->whereIn('skema', ['WFH', 'WFC'])->pluck('pegawai_id')->unique()->count();

        // Sakit & Izin
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

        // Belum Hadir
        if ($cabang === 'all') {
            $belumHadir = max(0, $totalPegawai - $totalHadir - $sakitCount - $izinCount);
        } else {
            // For branch view, show total not yet in this branch
            $belumHadir = max(0, $totalPegawai - $totalHadir);
        }

        return [
            'totalPegawai' => $totalPegawai,
            'totalHadir' => $totalHadir,
            'sedangBekerja' => $sedangBekerja,
            'sudahCheckOut' => $sudahCheckOut,
            'wfoCount' => $wfoCount,
            'wfhCount' => $wfhCount,
            'sakitCount' => $sakitCount,
            'belumHadir' => $belumHadir,
            'liveCheckIns' => $filteredList,
            'activeCabang' => $cabang,
        ];
    }
}