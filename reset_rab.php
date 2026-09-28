<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PengajuanRab;
use App\Models\AlurPersetujuan;
use App\Enums\StatusPengajuan;

$pengajuan = PengajuanRab::find(1);
if ($pengajuan) {
    $pengajuan->update([
        'status' => StatusPengajuan::MENUNGGU_FINANCE
    ]);
    AlurPersetujuan::where('id_pengajuan', 1)->delete();
    echo "RAB #1 has been reset successfully to Menunggu Verifikasi Finance.\n";
} else {
    echo "RAB #1 not found.\n";
}
