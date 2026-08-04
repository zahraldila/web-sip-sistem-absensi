<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('PGW####'),
            'nama_pegawai' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'no_handphone' => '0812' . fake()->numerify('#######'),
            'jabatan' => 'Staff',
            'divisi' => 'IT',
            'status' => 'Aktif'
        ];
    }
}
