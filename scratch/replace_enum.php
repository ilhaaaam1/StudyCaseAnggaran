<?php

$files = [
    'app/Http/Controllers/StaffRabController.php',
    'app/Http/Controllers/FinanceController.php',
    'app/Http/Controllers/PimpinanController.php',
    'app/Http/Controllers/AdminRabController.php',
    'app/Http/Controllers/UserRabController.php',
];

$replacements = [
    'PengajuanRab::STATUS_MENUNGGU_FINANCE' => '\App\Enums\StatusPengajuan::MENUNGGU_FINANCE',
    'PengajuanRab::STATUS_REVISI' => '\App\Enums\StatusPengajuan::REVISI',
    'PengajuanRab::STATUS_MENUNGGU_PIMPINAN' => '\App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN',
    'PengajuanRab::STATUS_DITOLAK' => '\App\Enums\StatusPengajuan::DITOLAK',
    'PengajuanRab::STATUS_PROSES_PENCAIRAN' => '\App\Enums\StatusPengajuan::PROSES_PENCAIRAN',
    'PengajuanRab::STATUS_SELESAI' => '\App\Enums\StatusPengajuan::SELESAI',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }
    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
    echo "Updated $file\n";
}
