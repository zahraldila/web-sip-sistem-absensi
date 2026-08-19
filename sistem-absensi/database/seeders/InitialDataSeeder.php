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
        $divisiId = DB::table('master_divisi')->where('nama_divisi', 'HR')->value('divisi_id');
        $jabatanId = DB::table('master_jabatan')->where('nama_jabatan', 'HR Manager')->value('jabatan_id');

        $pegawai = Pegawai::updateOrCreate([
            'pegawai_id' => 5,
        ], [
            'nama_pegawai' => 'Admin HR',
            'email' => 'admin@selada.id',
            'divisi_id' => $divisiId,
            'jabatan_id' => $jabatanId,
            'status' => 'Aktif',
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

