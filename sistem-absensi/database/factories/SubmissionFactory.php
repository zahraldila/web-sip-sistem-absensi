<?php

namespace Database\Factories;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        return [
            'pegawai_id' => 1,
            'jenis_pengajuan' => 'Izin',
            'tanggal_pengajuan' => now()->toDateString(),
            'keterangan' => fake()->sentence(),
            'status_pengajuan' => 'Pending'
        ];
    }
}
