<?php
require __DIR__ . '/sistem-absensi/vendor/autoload.php';
$app = require __DIR__ . '/sistem-absensi/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$columns = Illuminate\Support\Facades\DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position", ['pegawai']);
foreach ($columns as $col) {
    echo $col->column_name . ' : ' . $col->data_type . PHP_EOL;
}
echo '---' . PHP_EOL;
$rows = Illuminate\Support\Facades\DB::select("SELECT COUNT(*) AS total, COUNT(divisi_id) FILTER (WHERE divisi_id IS NOT NULL) AS has_divisi_id, COUNT(jabatan_id) FILTER (WHERE jabatan_id IS NOT NULL) AS has_jabatan_id, COUNT(divisi) FILTER (WHERE divisi IS NOT NULL) AS has_divisi, COUNT(jabatan) FILTER (WHERE jabatan IS NOT NULL) AS has_jabatan FROM pegawai");
foreach ($rows as $row) {
    echo 'total: ' . $row->total . PHP_EOL;
    echo 'has_divisi_id: ' . $row->has_divisi_id . PHP_EOL;
    echo 'has_jabatan_id: ' . $row->has_jabatan_id . PHP_EOL;
    echo 'has_divisi: ' . $row->has_divisi . PHP_EOL;
    echo 'has_jabatan: ' . $row->has_jabatan . PHP_EOL;
}
echo '---' . PHP_EOL;
$sample = Illuminate\Support\Facades\DB::select("SELECT pegawai_id, divisi_id, jabatan_id, divisi, jabatan FROM pegawai WHERE divisi IS NOT NULL OR jabatan IS NOT NULL ORDER BY pegawai_id LIMIT 10");
foreach ($sample as $row) {
    echo json_encode($row) . PHP_EOL;
}
