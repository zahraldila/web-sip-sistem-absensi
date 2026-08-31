<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat kolom username di tabel akun bisa nullable,
     * agar pegawai yang login via email bisa tidak memiliki username.
     */
    public function up(): void
    {
        Schema::table('akun', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Kembalikan ke NOT NULL (perhatikan: bisa gagal jika ada row dengan username null)
        Schema::table('akun', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }
};
