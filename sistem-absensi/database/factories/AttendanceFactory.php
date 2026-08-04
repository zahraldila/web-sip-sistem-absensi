<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'pegawai_id' => 1,
            'tanggal_absensi' => now()->toDateString(),
            'jam_checkin' => now(),
            'jam_checkout' => now()->addHours(8),
            'skema_kerja' => 'WFO',
            'status_kehadiran' => 'Hadir'
        ];
    }
}
