<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use App\Models\Akun;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed data pegawai id = 5 sesuai spesifikasi
        $pegawai = Pegawai::updateOrCreate([
            'id' => 5,
        ], [
            'nama' => 'Admin HR',
            'email' => 'admin@selada.id',
            'jabatan' => 'HR Manager',
            'divisi' => 'HR',
        ]);

        // Seed data akun admin terhubung ke pegawai_id = 5
        Akun::updateOrCreate([
            'username' => 'admin',
        ], [
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'pegawai_id' => $pegawai->id,
        ]);
    }
}

