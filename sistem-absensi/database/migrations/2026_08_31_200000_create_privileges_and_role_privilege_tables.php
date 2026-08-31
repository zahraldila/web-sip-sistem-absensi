<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel privilege
        if (! Schema::hasTable('privilege')) {
            Schema::create('privilege', function (Blueprint $table) {
                $table->id('privilege_id');
                $table->string('nama_privilege', 100)->unique();
                $table->string('label_privilege', 150);
                $table->string('kategori', 100);
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        // 2. Buat tabel pivot role_privilege
        if (! Schema::hasTable('role_privilege')) {
            Schema::create('role_privilege', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('privilege_id');
                $table->timestamps();

                $table->primary(['role_id', 'privilege_id']);
                $table->foreign('role_id')->references('role_id')->on('role')->cascadeOnDelete();
                $table->foreign('privilege_id')->references('privilege_id')->on('privilege')->cascadeOnDelete();
            });
        }

        // 3. Seed data master privilege berdasarkan fitur nyata project
        $privileges = [
            // Dashboard
            [
                'nama_privilege' => 'lihat_dashboard',
                'label_privilege' => 'Lihat Dashboard',
                'kategori' => 'Dashboard',
                'deskripsi' => 'Melihat ringkasan kehadiran harian, statistik, dan live check-in pegawai.',
            ],
            // Laporan Kehadiran
            [
                'nama_privilege' => 'lihat_laporan_kehadiran',
                'label_privilege' => 'Lihat Laporan Kehadiran',
                'kategori' => 'Laporan Kehadiran',
                'deskripsi' => 'Melihat data presensi harian seluruh pegawai beserta filter dan pencarian.',
            ],
            [
                'nama_privilege' => 'export_laporan_kehadiran',
                'label_privilege' => 'Export Laporan Kehadiran',
                'kategori' => 'Laporan Kehadiran',
                'deskripsi' => 'Mengekspor laporan data kehadiran ke format Excel, CSV, dan PDF.',
            ],
            // Manajemen Akun
            [
                'nama_privilege' => 'lihat_manajemen_akun',
                'label_privilege' => 'Lihat Manajemen Akun',
                'kategori' => 'Manajemen Akun',
                'deskripsi' => 'Melihat daftar akun dan informasi pegawai perusahaan.',
            ],
            [
                'nama_privilege' => 'tambah_pegawai',
                'label_privilege' => 'Tambah Pegawai',
                'kategori' => 'Manajemen Akun',
                'deskripsi' => 'Menambahkan data dan akun pegawai baru ke dalam sistem.',
            ],
            [
                'nama_privilege' => 'edit_pegawai',
                'label_privilege' => 'Edit Pegawai',
                'kategori' => 'Manajemen Akun',
                'deskripsi' => 'Memperbarui data profil, divisi, jabatan, status, dan role akun pegawai.',
            ],
            [
                'nama_privilege' => 'export_pegawai',
                'label_privilege' => 'Export Data Pegawai',
                'kategori' => 'Manajemen Akun',
                'deskripsi' => 'Mengekspor daftar akun karyawan ke format Excel, CSV, dan PDF.',
            ],
            // Persetujuan
            [
                'nama_privilege' => 'lihat_persetujuan',
                'label_privilege' => 'Lihat Persetujuan',
                'kategori' => 'Persetujuan',
                'deskripsi' => 'Melihat daftar pengajuan cuti, izin, sakit, dan lembur pegawai.',
            ],
            [
                'nama_privilege' => 'approve_pengajuan',
                'label_privilege' => 'Setujui Pengajuan',
                'kategori' => 'Persetujuan',
                'deskripsi' => 'Menyetujui pengajuan izin, cuti, atau lembur yang diajukan pegawai.',
            ],
            [
                'nama_privilege' => 'reject_pengajuan',
                'label_privilege' => 'Tolak Pengajuan',
                'kategori' => 'Persetujuan',
                'deskripsi' => 'Menolak pengajuan izin, cuti, atau lembur pegawai beserta alasannya.',
            ],
            // Log Aktivitas
            [
                'nama_privilege' => 'lihat_log_aktivitas',
                'label_privilege' => 'Lihat Log Aktivitas',
                'kategori' => 'Log Aktivitas',
                'deskripsi' => 'Melihat riwayat audit log aktivitas yang dilakukan di dalam sistem.',
            ],
            // Jadwal Kerja
            [
                'nama_privilege' => 'kelola_jadwal_kerja',
                'label_privilege' => 'Kelola Jadwal & Jam Kerja',
                'kategori' => 'Jadwal Kerja',
                'deskripsi' => 'Mengatur konfigurasi jam masuk, jam pulang, dan toleransi keterlambatan.',
            ],
            // Settings
            [
                'nama_privilege' => 'kelola_tampilan_branding',
                'label_privilege' => 'Kelola Tampilan & Branding',
                'kategori' => 'Settings',
                'deskripsi' => 'Mengatur logo perusahaan dan warna tema utama sistem.',
            ],
            [
                'nama_privilege' => 'kelola_lokasi_cabang',
                'label_privilege' => 'Kelola Lokasi & Cabang',
                'kategori' => 'Settings',
                'deskripsi' => 'Menambah, mengubah, dan menghapus titik lokasi GPS kantor serta radius presensi.',
            ],
            [
                'nama_privilege' => 'kelola_wifi_kantor',
                'label_privilege' => 'Kelola Jaringan Wi-Fi',
                'kategori' => 'Settings',
                'deskripsi' => 'Mengatur SSID dan BSSID jaringan Wi-Fi kantor untuk validasi presensi.',
            ],
            [
                'nama_privilege' => 'kelola_role_hak_akses',
                'label_privilege' => 'Kelola Role & Hak Akses',
                'kategori' => 'Settings',
                'deskripsi' => 'Mengatur hak akses (privilege) untuk setiap role pengguna dalam sistem.',
            ],
        ];

        foreach ($privileges as $p) {
            DB::table('privilege')->updateOrInsert(
                ['nama_privilege' => $p['nama_privilege']],
                [
                    'label_privilege' => $p['label_privilege'],
                    'kategori' => $p['kategori'],
                    'deskripsi' => $p['deskripsi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 4. Default seeding: Super Admin memiliki semua privilege
        $superAdminRoleId = DB::table('role')->where('nama_role', 'Super Admin')->value('role_id');
        if ($superAdminRoleId) {
            $allPrivilegeIds = DB::table('privilege')->pluck('privilege_id');
            foreach ($allPrivilegeIds as $privId) {
                DB::table('role_privilege')->updateOrInsert(
                    ['role_id' => $superAdminRoleId, 'privilege_id' => $privId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_privilege');
        Schema::dropIfExists('privilege');
    }
};
