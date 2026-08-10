<?php
require __DIR__ . '/sistem-absensi/vendor/autoload.php';
$app = require __DIR__ . '/sistem-absensi/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$migrations = Illuminate\Support\Facades\DB::select('SELECT * FROM migrations ORDER BY batch, migration');
foreach ($migrations as $migration) {
    echo $migration->id . ' | ' . $migration->migration . ' | ' . $migration->batch . PHP_EOL;
}
$tables = Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = current_schema() AND table_name IN ('pegawai', 'akun', 'migrations') ORDER BY table_name");
foreach ($tables as $table) {
    echo 'table: ' . $table->table_name . PHP_EOL;
}
