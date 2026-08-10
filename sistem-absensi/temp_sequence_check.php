<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'pgsql',
    'host' => 'aws-0-ap-southeast-1.pooler.supabase.com',
    'port' => '5432',
    'database' => 'postgres',
    'username' => 'postgres.fxovkmcrdeezrotwqjhb',
    'password' => 'Selada@2026',
    'charset' => 'utf8',
    'prefix' => '',
    'schema' => 'public',
    'sslmode' => 'require',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$connection = $capsule->getConnection();
$tables = ['pegawai','akun'];
foreach ($tables as $table) {
    echo "\n=== $table ===\n";
    $col = $connection->selectOne("SELECT column_name, data_type, is_nullable, column_default, identity_generation FROM information_schema.columns WHERE table_schema = 'public' AND table_name = ? AND column_name = ?", [$table, $table . '_id']);
    if ($col) {
        echo "column_name: {$col->column_name}\n";
        echo "data_type: {$col->data_type}\n";
        echo "is_nullable: {$col->is_nullable}\n";
        echo "column_default: {$col->column_default}\n";
        echo "identity_generation: {$col->identity_generation}\n";
    }
    $seq = $connection->selectOne("SELECT pg_get_serial_sequence(?, ?) AS seq", [$table, $table . '_id']);
    echo "seq: " . ($seq->seq ?? 'NULL') . "\n";
    if ($seq && $seq->seq) {
        $parts = explode('.', $seq->seq);
        if (count($parts) === 2) {
            list($schema, $seqName) = $parts;
        } else {
            $schema = 'public';
            $seqName = $parts[0];
        }
        echo "schema: $schema, seqName: $seqName\n";
        try {
            $row = $connection->selectOne("SELECT last_value, is_called FROM \"$schema\".\"$seqName\"");
            echo "last_value: {$row->last_value}, is_called: {$row->is_called}\n";
        } catch (Exception $e) {
            echo "seq query failed: " . $e->getMessage() . "\n";
        }
    }
    $max = $connection->selectOne("SELECT MAX(\"$table\".{$table}_id) AS max_id FROM \"$table\"");
    echo "max_id: " . ($max->max_id ?? 'NULL') . "\n";
}
