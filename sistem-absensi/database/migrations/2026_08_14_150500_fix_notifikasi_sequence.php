<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the notifikasi_id sequence is set to max(id) + 1 to prevent duplicate key errors.
        DB::statement(
            "SELECT setval(pg_get_serial_sequence('notifikasi', 'notifikasi_id'), (SELECT COALESCE(MAX(notifikasi_id), 0) FROM notifikasi) + 1, false)"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed; sequence will remain at its current value.
    }
};
?>
