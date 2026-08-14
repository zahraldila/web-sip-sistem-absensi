<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Akun;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== STARTING VERIFICATION TEST ===\n";

// 1. Get an admin user
$admin = Akun::whereRaw('LOWER(role) = ?', ['admin'])->first() ?? Akun::first();
if (!$admin) {
    echo "ERROR: Admin account not found!\n";
    exit(1);
}
Auth::login($admin);
echo "Logged in as Admin: {$admin->username} (akun_id: {$admin->akun_id})\n";

DB::beginTransaction();
try {
    // Create a dummy pending submission
    $pegawai = DB::table('pegawai')->first();
    $pegawaiId = $pegawai ? $pegawai->pegawai_id : 1;

    // Get max pengajuan_id if no sequence
    $nextPengajuanId = (DB::table('pengajuan')->max('pengajuan_id') ?? 0) + 1;
    $existingJenis = DB::table('pengajuan')->pluck('jenis_pengajuan')->unique()->values()->all();
    echo "Existing jenis_pengajuan in db: " . json_encode($existingJenis) . "\n";
    $chosenJenis = $existingJenis[0] ?? 'WFH';

    DB::table('pengajuan')->insert([
        'pengajuan_id' => $nextPengajuanId,
        'pegawai_id' => $pegawaiId,
        'jenis_pengajuan' => $chosenJenis,
        'tanggal_pengajuan' => now()->toDateString(),
        'keterangan' => 'Pengajuan cuti untuk pengujian otomatis',
        'status_pengajuan' => 'Pending',
    ]);
    echo "Created dummy submission ID: $nextPengajuanId (status: Pending)\n";

    $controller = new \App\Http\Controllers\ApprovalControllers();

    // TEST 1: Approve
    echo "\n--- Testing Approve --- \n";
    $req = new Request();
    $response = $controller->approve($req, $nextPengajuanId);
    $data = json_decode($response->getContent(), true);
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response body: " . json_encode($data) . "\n";

    $updatedPengajuan = DB::table('pengajuan')->where('pengajuan_id', $nextPengajuanId)->first();
    echo "Updated pengajuan status: {$updatedPengajuan->status_pengajuan}\n";
    assert($updatedPengajuan->status_pengajuan === 'Disetujui', "Status should be Disetujui");

    $approvalRecord = DB::table('approval')->where('pengajuan_id', $nextPengajuanId)->first();
    echo "Approval record: " . json_encode($approvalRecord) . "\n";
    assert($approvalRecord && $approvalRecord->status_approval === 'Disetujui', "Approval record should exist with Disetujui");

    $notifRecord = DB::table('notifikasi')->where('pegawai_id', $pegawaiId)->orderByDesc('notifikasi_id')->first();
    echo "Notifikasi record: " . json_encode($notifRecord) . "\n";
    assert($notifRecord && strpos($notifRecord->judul, 'Disetujui') !== false, "Notification should exist for approved submission");

    // TEST 2: Attempting to approve again should fail
    echo "\n--- Testing Double Approve Protection --- \n";
    $doubleResponse = $controller->approve($req, $nextPengajuanId);
    echo "Double response code: " . $doubleResponse->getStatusCode() . " (Expected: 422)\n";
    assert($doubleResponse->getStatusCode() === 422, "Double approval should be rejected");

    // TEST 3: Create second pending submission for Reject test
    echo "\n--- Testing Reject --- \n";
    $nextPengajuanId2 = $nextPengajuanId + 1;
    DB::table('pengajuan')->insert([
        'pengajuan_id' => $nextPengajuanId2,
        'pegawai_id' => $pegawaiId,
        'jenis_pengajuan' => 'Izin',
        'tanggal_pengajuan' => now()->toDateString(),
        'keterangan' => 'Pengajuan izin untuk pengujian tolak',
        'status_pengajuan' => 'Pending',
    ]);

    $rejectReq = new Request([
        'catatan_admin' => 'Dokumen pendukung kurang lengkap.',
    ]);
    $rejectResponse = $controller->reject($rejectReq, $nextPengajuanId2);
    $rejectData = json_decode($rejectResponse->getContent(), true);
    echo "Reject response status: " . $rejectResponse->getStatusCode() . "\n";
    echo "Reject response body: " . json_encode($rejectData) . "\n";

    $updatedPengajuan2 = DB::table('pengajuan')->where('pengajuan_id', $nextPengajuanId2)->first();
    echo "Updated pengajuan2 status: {$updatedPengajuan2->status_pengajuan}\n";
    assert($updatedPengajuan2->status_pengajuan === 'Ditolak', "Status should be Ditolak");

    $approvalRecord2 = DB::table('approval')->where('pengajuan_id', $nextPengajuanId2)->first();
    echo "Approval record 2: " . json_encode($approvalRecord2) . "\n";
    assert($approvalRecord2 && $approvalRecord2->status_approval === 'Ditolak', "Approval record should be Ditolak");
    assert($approvalRecord2->catatan_admin === 'Dokumen pendukung kurang lengkap.', "Catatan admin should match");

    $notifRecord2 = DB::table('notifikasi')->where('pegawai_id', $pegawaiId)->orderByDesc('notifikasi_id')->first();
    echo "Notifikasi record 2: " . json_encode($notifRecord2) . "\n";
    assert($notifRecord2 && strpos($notifRecord2->judul, 'Ditolak') !== false, "Notification should mention Ditolak");
    assert(strpos($notifRecord2->isi_pesan, 'Dokumen pendukung kurang lengkap.') !== false, "Notification message should include reason");

    echo "\n=== ALL VERIFICATION TESTS PASSED SUCCESSFULLY! ===\n";
} catch (\Throwable $e) {
    echo "TEST FAILED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "\nRolled back test transaction.\n";
}
