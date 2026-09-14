<?php

function getDirContents($dir, &$results = [])
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if (! is_dir($path)) {
            if (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                $results[] = $path;
            }
        } elseif ($value != '.' && $value != '..') {
            getDirContents($path, $results);
        }
    }

    return $results;
}

$files = getDirContents('resources/views');

$replacements = [
    '\App\Models\PengajuanRab::STATUS_MENUNGGU_FINANCE' => '\App\Enums\StatusPengajuan::MENUNGGU_FINANCE',
    '\App\Models\PengajuanRab::STATUS_REVISI' => '\App\Enums\StatusPengajuan::REVISI',
    '\App\Models\PengajuanRab::STATUS_MENUNGGU_PIMPINAN' => '\App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN',
    '\App\Models\PengajuanRab::STATUS_DITOLAK' => '\App\Enums\StatusPengajuan::DITOLAK',
    '\App\Models\PengajuanRab::STATUS_PROSES_PENCAIRAN' => '\App\Enums\StatusPengajuan::PROSES_PENCAIRAN',
    '\App\Models\PengajuanRab::STATUS_SELESAI' => '\App\Enums\StatusPengajuan::SELESAI',
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
    $original = $content;
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
