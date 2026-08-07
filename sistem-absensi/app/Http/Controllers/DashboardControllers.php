<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardControllers extends Controller
{
    public function admin(Request $request)
    {
        $today = now()->toDateString();

        $totalPegawai = Pegawai::count();

        $hadirHariIni = Attendance::whereDate('tanggal_absensi', $today)
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        $wfoCount = Attendance::whereDate('tanggal_absensi', $today)
            ->where('skema_kerja', 'WFO')
            ->count();

        $wfhWfcCount = Attendance::whereDate('tanggal_absensi', $today)
            ->whereIn('skema_kerja', ['WFH', 'WFC'])
            ->count();

        $liveCheckIns = Attendance::whereDate('tanggal_absensi', $today)
            ->whereNotNull('jam_checkin')
            ->with('pegawai')
            ->orderByDesc('jam_checkin')
            ->limit(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'nama' => $attendance->pegawai?->nama_pegawai ?? 'Unknown',
                    'status' => $attendance->skema_kerja ?? 'WFO',
                    'jam' => $attendance->jam_checkin ? substr($attendance->jam_checkin, 0, 5) . ' WIB' : '-',
                ];
            });

        $pendingApprovals = Approval::where('status_pengajuan', 'Pending')
            ->with('pegawai')
            ->orderByDesc('tanggal_pengajuan')
            ->limit(4)
            ->get()
            ->map(function ($approval) {
                return [
                    'nama' => $approval->pegawai?->nama_pegawai ?? 'Unknown',
                    'jenis' => $approval->jenis_pengajuan ?? '-',
                    'tanggal' => $approval->tanggal_pengajuan ?
                        Carbon::parse($approval->tanggal_pengajuan)->translatedFormat('d F Y') : '-',
                    'status' => $approval->status_pengajuan ?? 'Pending',
                ];
            });

        $activities = AuditLog::query()
            ->with('akun.pegawai')
            ->orderByDesc('waktu_log')
            ->limit(4)
            ->get()
            ->map(function ($log) {
                $namaPegawai = $log->akun?->pegawai?->nama_pegawai;
                $aktivitas = $log->aktivitas ?? 'Aktivitas terbaru';

                return [
                    'title' => $namaPegawai ? $namaPegawai . ' - ' . $aktivitas : $aktivitas,
                    'time' => $log->waktu_log ? Carbon::parse($log->waktu_log)->diffForHumans() : '-',
                    'color' => 'green',
                ];
            });

        if ($activities->isEmpty()) {
            $activities = collect([
                [
                    'title' => 'Belum ada aktivitas',
                    'time' => '-',
                    'color' => 'blue',
                ],
            ]);
        }

        return view('admin.index', compact(
            'totalPegawai',
            'hadirHariIni',
            'wfoCount',
            'wfhWfcCount',
            'liveCheckIns',
            'pendingApprovals',
            'activities'
        ));
    }
}
