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
            $query->whereHas('pegawai', function ($query) use ($search) {
                $query->where('nama_pegawai', 'ilike', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status !== 'Semua') {
                $query->where('status_kehadiran', $status);
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

    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $attendances = $this->attendanceQuery($request)
            ->orderByDesc('tanggal_absensi')
            ->orderBy('jam_checkin')
            ->paginate(10)
            ->withQueryString();

        $pegawaiList = Pegawai::orderBy('nama_pegawai')->get(['pegawai_id', 'nama_pegawai']);

        $statusOptions = Attendance::select('status_kehadiran')
            ->distinct()
            ->whereNotNull('status_kehadiran')
            ->orderBy('status_kehadiran')
            ->pluck('status_kehadiran')
            ->filter()
            ->values()
            ->all();

        array_unshift($statusOptions, 'Semua');

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
                return [
                    $attendance->pegawai?->nama_pegawai ?? '-',
                    $this->formatDate($attendance->tanggal_absensi),
                    $this->formatTime($attendance->jam_checkin),
                    $this->formatTime($attendance->jam_checkout),
                    $this->formatDuration($attendance->jam_checkin, $attendance->jam_checkout),
                    $attendance->skema_kerja ?? '-',
                    $this->formatLocation($attendance->latitude, $attendance->longitude),
                    $attendance->status_kehadiran ?? '-',
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
                return [
                    'nama' => $attendance->pegawai?->nama_pegawai ?? '-',
                    'tanggal' => $this->formatDate($attendance->tanggal_absensi),
                    'jam_masuk' => $this->formatTime($attendance->jam_checkin),
                    'jam_keluar' => $this->formatTime($attendance->jam_checkout),
                    'durasi' => $this->formatDuration($attendance->jam_checkin, $attendance->jam_checkout),
                    'mode' => $attendance->skema_kerja ?? '-',
                    'lokasi' => $this->formatLocation($attendance->latitude, $attendance->longitude),
                    'status' => $attendance->status_kehadiran ?? '-',
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
