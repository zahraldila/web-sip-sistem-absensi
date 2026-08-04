<?php

namespace Database\Factories;

use App\Models\Approval;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalFactory extends Factory
{
    protected $model = Approval::class;

    public function definition(): array
    {
        return [
            'pengajuan_id' => 1,
            'akun_id' => 1,
            'status_approval' => 'Disetujui',
            'catatan_admin' => null
        ];
    }
}
