<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class logHelpers {
    // Untuk mencatat aktivitas user ke tabel audit_log
    public static function record($akunId, $aktivitas) {
        DB::table('audit_log')->insert([
            'akun_id'   => $akunId,
            'aktivitas' => $aktivitas,
            'waktu_log' => Carbon::now(),
        ]);
    }
}