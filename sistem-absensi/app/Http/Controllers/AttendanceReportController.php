<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceReportExport;
use App\Models\Attendance;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceReportController extends Controller
{
    private function attendanceQuery(Request $request)
    {
        $query = Attendance::with('pegawai');

        if ($search = trim((string) $request->query('search', ''))) {
            $searchTerm = "%{$search}%";

            $query->where(function ($query) use ($search, $searchTerm) {
                $query->whereHas('pegawai', function ($query) use ($searchTerm) {
                    $query->where('nama_pegawai', 'ilike', $searchTerm);
                })
                ->orWhere('skema_kerja', 'ilike', $searchTerm)
                ->orWhere('status_kehadiran', 'ilike', $searchTerm)
                ->orWhereRaw("to_char(tanggal_absensi, 'FMDD TMMonth YYYY') ILIKE ?", [$searchTerm])
                ->orWhereRaw("CAST(tanggal_absensi AS TEXT) ILIKE ?", [$searchTerm])
                ->orWhereRaw("COALESCE(to_char(jam_checkin, 'HH24:MI'), '') ILIKE ?", [$searchTerm])
                ->orWhereRaw("COALESCE(to_char(jam_checkout, 'HH24:MI'), '') ILIKE ?", [$searchTerm])
                ->orWhereRaw("(
                    CASE
                        WHEN jam_checkin IS NULL OR jam_checkout IS NULL OR jam_checkout < jam_checkin THEN ''
                        ELSE (
                            CASE WHEN FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 3600) > 0
                                THEN FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 3600)::TEXT || ' Jam'
                                ELSE ''
                            END
                            || CASE WHEN MOD(FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 60), 60) > 0
                                THEN CASE WHEN FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 3600) > 0 THEN ' ' ELSE '' END
                                     || MOD(FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 60), 60)::TEXT || ' Menit'
                                ELSE ''
                            END
                            || CASE WHEN FLOOR(EXTRACT(EPOCH FROM (jam_checkout - jam_checkin)) / 60) = 0 THEN '0 Menit' ELSE '' END
                        )
                    END
                ) ILIKE ?", [$searchTerm])
                ->orWhereRaw("CAST(latitude AS TEXT) ILIKE ? OR CAST(longitude AS TEXT) ILIKE ? OR (CAST(latitude AS TEXT) || ', ' || CAST(longitude AS TEXT)) ILIKE ?", [$searchTerm, $searchTerm, $searchTerm]);

                if ($date = $this->parseSearchDate($search)) {
                    $query->orWhereDate('tanggal_absensi', '=', $date);
                }
            });
        }

        if ($status = $request->query('status')) {
            if ($status !== 'Semua') {
                if ($status === 'Tepat Waktu') {
                    $query->whereRaw("CAST(jam_checkin AS TIME) <= (
                        SELECT jam_masuk FROM jadwal_kerja WHERE jadwal_kerja.jadwal_id = absensi.jadwal_id LIMIT 1
                    )");
                } elseif ($status === 'Terlambat') {
                    $query->whereRaw("CAST(jam_checkin AS TIME) > (
                        SELECT jam_masuk FROM jadwal_kerja WHERE jadwal_kerja.jadwal_id = absensi.jadwal_id LIMIT 1
                    )");
                } elseif ($status === 'Hadir') {
                    $query->whereNotNull('jam_checkin');
                } else {
                    $query->where('status_kehadiran', $status);
                }
            }
        }

        if ($startDate = $request->query('start_date')) {
            $query->whereDate('tanggal_absensi', '>=', $startDate);
        }

        if ($endDate = $request->query('end_date')) {
            $query->whereDate('tanggal_absensi', '<=', $endDate);
        }

        if ($modeKerja = $request->query('mode_kerja')) {
            if ($modeKerja !== 'Semua') {
                $query->where('skema_kerja', $modeKerja);
            }
        }

        return $query;
    }

    private function formatDuration(?string $checkin, ?string $checkout): string
    {
        if (empty($checkin) || empty($checkout)) {
            return '-';
        }

        $start = Carbon::parse($checkin);
        $end = Carbon::parse($checkout);

        if ($end->lt($start)) {
            return '-';
        }

        $minutes = $start->diffInMinutes($end);
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];

        if ($hours > 0) {
            $parts[] = "{$hours} Jam";
        }

        if ($remainingMinutes > 0) {
            $parts[] = "{$remainingMinutes} Menit";
        }

        return $parts ? implode(' ', $parts) : '0 Menit';
    }

    private function formatTime(?string $value): string
    {
        if (empty($value)) {
            return '-';
        }

        return Carbon::parse($value)->format('H:i');
    }

    private function formatDate(?string $value): string
    {
        if (empty($value)) {
            return '-';
        }

        return Carbon::parse($value)->translatedFormat('d F Y');
    }

    private function formatLocation($latitude, $longitude): string
    {
        if ($latitude !== null && $longitude !== null) {
            return sprintf('%s, %s', trim((string) $latitude), trim((string) $longitude));
        }

        return '-';
    }

    private function parseSearchDate(string $search): ?string
    {
        $search = trim(strtolower($search));

        if ($search === '') {
            return null;
        }

        $months = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
        ];

        $normalized = preg_replace_callback('/\b(' . implode('|', array_keys($months)) . ')\b/i', function ($matches) use ($months) {
            return $months[strtolower($matches[1])];
        }, $search);

        $formats = ['d F Y', 'd M Y', 'Y-m-d', 'd-m-Y', 'd/m/Y'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $normalized);
                if ($date !== false) {
                    return $date->toDateString();
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $attendances = $this->attendanceQuery($request)
            ->orderByDesc('tanggal_absensi')
            ->orderBy('jam_checkin')
            ->paginate(5)
            ->withQueryString();

        $attendances->getCollection()->transform(function ($attendance) {
            if ($attendance->jam_checkin) {
                $schedule = \DB::table('jadwal_kerja')
                    ->where('jadwal_id', $attendance->jadwal_id)
                    ->first();
                if ($schedule && $schedule->jam_masuk) {
                    $checkInTime = Carbon::parse($attendance->jam_checkin)->format('H:i:s');
                    $jamMasukTime = Carbon::parse($schedule->jam_masuk)->format('H:i:s');
                    $attendance->status_kehadiran = ($checkInTime > $jamMasukTime) ? 'Terlambat' : 'Hadir';
                }
            }
            return $attendance;
        });

        $pegawaiList = Pegawai::orderBy('nama_pegawai')->get(['pegawai_id', 'nama_pegawai']);

        $statusOptions = ['Semua', 'Hadir', 'Tepat Waktu', 'Terlambat', 'Tidak Hadir'];

        return view('admin.laporan-kehadiran', [
            'attendances' => $attendances,
            'pegawaiList' => $pegawaiList,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function exportExcel(Request $request)
    {
        Carbon::setLocale('id');

        $rows = $this->attendanceQuery($request)
            ->get()
            ->map(function (Attendance $attendance) {
                $status = $attendance->status_kehadiran ?? 'Hadir';
                if ($attendance->jam_checkin) {
                    $schedule = \DB::table('jadwal_kerja')
                        ->where('jadwal_id', $attendance->jadwal_id)
                        ->first();
                    if ($schedule && $schedule->jam_masuk) {
                        $checkInTime = Carbon::parse($attendance->jam_checkin)->format('H:i:s');
                        $jamMasukTime = Carbon::parse($schedule->jam_masuk)->format('H:i:s');
                        $status = ($checkInTime > $jamMasukTime) ? 'Terlambat' : 'Hadir';
                    }
                }
                return [
                    $attendance->pegawai?->nama_pegawai ?? '-',
                    $this->formatDate($attendance->tanggal_absensi),
                    $this->formatTime($attendance->jam_checkin),
                    $this->formatTime($attendance->jam_checkout),
                    $this->formatDuration($attendance->jam_checkin, $attendance->jam_checkout),
                    $attendance->skema_kerja ?? '-',
                    $this->formatLocation($attendance->latitude, $attendance->longitude),
                    $status,
                ];
            });

        $filename = 'laporan-kehadiran-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new AttendanceReportExport($rows), $filename);
    }

    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');

        $rows = $this->attendanceQuery($request)
            ->get()
            ->map(function (Attendance $attendance) {
                $status = $attendance->status_kehadiran ?? 'Hadir';
                if ($attendance->jam_checkin) {
                    $schedule = \DB::table('jadwal_kerja')
                        ->where('jadwal_id', $attendance->jadwal_id)
                        ->first();
                    if ($schedule && $schedule->jam_masuk) {
                        $checkInTime = Carbon::parse($attendance->jam_checkin)->format('H:i:s');
                        $jamMasukTime = Carbon::parse($schedule->jam_masuk)->format('H:i:s');
                        $status = ($checkInTime > $jamMasukTime) ? 'Terlambat' : 'Hadir';
                    }
                }
                return [
                    'nama' => $attendance->pegawai?->nama_pegawai ?? '-',
                    'tanggal' => $this->formatDate($attendance->tanggal_absensi),
                    'jam_masuk' => $this->formatTime($attendance->jam_checkin),
                    'jam_keluar' => $this->formatTime($attendance->jam_checkout),
                    'durasi' => $this->formatDuration($attendance->jam_checkin, $attendance->jam_checkout),
                    'mode' => $attendance->skema_kerja ?? '-',
                    'lokasi' => $this->formatLocation($attendance->latitude, $attendance->longitude),
                    'status' => $status,
                ];
            });

        $pdf = Pdf::loadView('admin.laporan-kehadiran-pdf', [
            'rows' => $rows,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ]);

        $filename = 'laporan-kehadiran-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
