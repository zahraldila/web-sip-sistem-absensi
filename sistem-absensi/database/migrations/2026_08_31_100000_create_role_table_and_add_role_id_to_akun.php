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
        // 1. Buat tabel master role jika belum ada
        if (! Schema::hasTable('role')) {
            Schema::create('role', function (Blueprint $table) {
                $table->id('role_id');
                $table->string('nama_role', 100)->unique();
                $table->timestamps();
            });
        }

        // 2. Seed 4 role awal standar
        $initialRoles = [
            'Super Admin',
            'HR / HRD',
            'Direktur',
            'Pegawai',
        ];

        foreach ($initialRoles as $namaRole) {
            DB::table('role')->updateOrInsert(
                ['nama_role' => $namaRole],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 3. Tambahkan kolom role_id ke tabel akun jika belum ada
        if (Schema::hasTable('akun')) {
            if (! Schema::hasColumn('akun', 'role_id')) {
                Schema::table('akun', function (Blueprint $table) {
                    $table->unsignedBigInteger('role_id')->nullable()->after('role');
                    $table->foreign('role_id')->references('role_id')->on('role')->nullOnDelete();
                });
            }

            // 4. Migrasi data role yang sudah ada di tabel akun ke role_id
            $roleSuperAdminId = DB::table('role')->where('nama_role', 'Super Admin')->value('role_id');
            $roleHrId = DB::table('role')->where('nama_role', 'HR / HRD')->value('role_id');
            $roleDirekturId = DB::table('role')->where('nama_role', 'Direktur')->value('role_id');
            $rolePegawaiId = DB::table('role')->where('nama_role', 'Pegawai')->value('role_id');

            // Map role 'admin' / 'super_admin' / 'Super Admin'
            DB::table('akun')
                ->whereRaw("LOWER(role) IN ('admin', 'super_admin', 'super admin')")
                ->update(['role_id' => $roleSuperAdminId]);

            // Map role 'hr' / 'hrd' / 'hr / hrd'
            DB::table('akun')
                ->whereRaw("LOWER(role) IN ('hr', 'hrd', 'hr / hrd')")
                ->update(['role_id' => $roleHrId]);

            // Map role 'direktur'
            DB::table('akun')
                ->whereRaw("LOWER(role) IN ('direktur', 'director')")
                ->update(['role_id' => $roleDirekturId]);

            // Map role 'pegawai' / 'karyawan' atau yang belum memiliki role_id
            DB::table('akun')
                ->whereNull('role_id')
                ->update(['role_id' => $rolePegawaiId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('akun') && Schema::hasColumn('akun', 'role_id')) {
            Schema::table('akun', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
        }

        Schema::dropIfExists('role');
    }
};
