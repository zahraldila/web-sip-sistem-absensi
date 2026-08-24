<?php

namespace App\Http\Controllers\DashboardTv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TvDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $data = $this->fetchStats($date);
        
        return view('dashboard-tv.index', array_merge($data, [
            'selectedDate' => $date,
            'isDemo' => $request->has('date')
        ]));
    }

    public function getStats(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $data = $this->fetchStats($date);
        
        return response()->json($data);
    }

    private function fetchStats($date)
    {
        // 1. Fetch dynamic list of branches from database ordered by ID
        $branches = DB::table('lokasi_kantor')
            ->orderBy('lokasi_id', 'asc')
            ->get(['lokasi_id', 'nama_kantor', 'latitude', 'longitude', 'radius_meter']);

        // Fetch registered office Wi-Fis linked to locations (sorted by SSID length desc)
        $officeWifis = DB::table('wifi_kantor')
            ->whereNotNull('lokasi_id')
            ->where('aktif', true)
            ->get()
            ->sortByDesc(fn($w) => strlen($w->ssid));

        // 2. Total Pegawai (Aktif)
        $totalPegawai = DB::table('pegawai')
            ->where(function ($query) {
                $query->where('status', 'Aktif')
                      ->orWhereNull('status')
                      ->orWhere('status', '');
            })
            ->count();

        // 3. Fetch all raw attendance records for the date
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

        // 4. Map records with dynamic branch determination
        $mappedAttendances = $allAttendances->map(function ($item) use ($branches, $officeWifis) {
            $catatan = strtolower($item->catatan ?? '');
            
            $matchedLocationId = null;
            $matchedLocationName = 'Remote';

            // A. Check Wi-Fi match first (exact SSID match)
            foreach ($officeWifis as $wifi) {
                if (!empty($wifi->ssid) && str_contains($catatan, strtolower($wifi->ssid))) {
                    $matchedLocationId = $wifi->lokasi_id;
                    break;
                }
            }

            // B. If not matched by Wi-Fi, check Geo-Location coordinates against all branches
            if (!$matchedLocationId && !empty($item->latitude) && !empty($item->longitude)) {
                $lat = (float) $item->latitude;
                $long = (float) $item->longitude;

                $closestDist = PHP_FLOAT_MAX;
                $closestBranch = null;

                foreach ($branches as $branch) {
                    if ($branch->latitude && $branch->longitude) {
                        $bLat = (float) $branch->latitude;
                        $bLong = (float) $branch->longitude;
                        $dist = sqrt(pow($lat - $bLat, 2) + pow($long - $bLong, 2));
                        if ($dist < $closestDist) {
                            $closestDist = $dist;
                            $closestBranch = $branch;
                        }
                    }
                }

                // If within reasonable proximity (~5km)
                if ($closestBranch && $closestDist < 0.05) {
                    $matchedLocationId = $closestBranch->lokasi_id;
                }
            }

            // C. Fallback for WFO / WFH
            if (!$matchedLocationId) {
                if (in_array(strtoupper($item->skema_kerja ?? ''), ['WFH', 'WFC'])) {
                    $matchedLocationId = 'remote';
                    $matchedLocationName = 'Remote (WFH/WFC)';
                } else {
                    // Default headquarters is Kantor Sulaksana (ID: 1)
                    $sulaksana = $branches->firstWhere('nama_kantor', 'Kantor Sulaksana') 
                              ?? $branches->firstWhere('lokasi_id', 1) 
                              ?? $branches->first();
                    $matchedLocationId = $sulaksana?->lokasi_id ?? 1;
                }
            }

            // Resolve name
            if ($matchedLocationId !== 'remote') {
                $found = $branches->firstWhere('lokasi_id', (int)$matchedLocationId);
                $matchedLocationName = $found ? $found->nama_kantor : 'Kantor Sulaksana';
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
                $lokasi = $matchedLocationName;
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
                'cabang_id' => (string)$matchedLocationId,
                'cabang_label' => $matchedLocationName,
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

        // Global Summary counts
        $totalHadir = $mappedAttendances->pluck('pegawai_id')->unique()->count();
        $sedangBekerja = $mappedAttendances->where('has_checkout', false)->pluck('pegawai_id')->unique()->count();
        $sudahCheckOut = $mappedAttendances->where('has_checkout', true)->pluck('pegawai_id')->unique()->count();
        $wfoCount = $mappedAttendances->where('skema', 'WFO')->pluck('pegawai_id')->unique()->count();
        $wfhCount = $mappedAttendances->whereIn('skema', ['WFH', 'WFC'])->pluck('pegawai_id')->unique()->count();

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

        $belumHadir = max(0, $totalPegawai - $totalHadir - $sakitCount - $izinCount);

        // Group attendance by primary locations (Sulaksana vs Cikawao)
        $sulaksanaLocation = $branches->firstWhere('nama_kantor', 'Kantor Sulaksana') ?? $branches->firstWhere('lokasi_id', 1);
        $cikawaoLocation = $branches->firstWhere('nama_kantor', 'Kantor Cikawao') ?? $branches->firstWhere('lokasi_id', 2);

        $sulaksanaId = $sulaksanaLocation ? (string)$sulaksanaLocation->lokasi_id : '1';
        $cikawaoId = $cikawaoLocation ? (string)$cikawaoLocation->lokasi_id : '2';

        $sulaksanaList = $mappedAttendances->filter(function ($i) use ($sulaksanaId) {
            return (string)$i['cabang_id'] === $sulaksanaId || str_contains(strtolower($i['cabang_label']), 'sulaksana');
        })->values();

        $cikawaoList = $mappedAttendances->filter(function ($i) use ($cikawaoId) {
            return (string)$i['cabang_id'] === $cikawaoId || str_contains(strtolower($i['cabang_label']), 'cikawao');
        })->values();

        return [
            'branches' => $branches,
            'totalPegawai' => $totalPegawai,
            'totalHadir' => $totalHadir,
            'sedangBekerja' => $sedangBekerja,
            'sudahCheckOut' => $sudahCheckOut,
            'wfoCount' => $wfoCount,
            'wfhCount' => $wfhCount,
            'sakitCount' => $sakitCount,
            'izinCount' => $izinCount,
            'belumHadir' => $belumHadir,
            'liveCheckIns' => $mappedAttendances,
            'sulaksanaList' => $sulaksanaList,
            'cikawaoList' => $cikawaoList,
        ];
    }
}