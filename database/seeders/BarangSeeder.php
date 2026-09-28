<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $user = Pengguna::first();
        $divisi = Divisi::firstOrCreate(['nama_divisi' => 'Kategori Seed Master']);

        $pengajuan = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-SEED-20'],
            [
                'id_pengguna' => $user->id_pengguna ?? 1,
                'id_divisi' => $divisi->id_divisi,
                'judul_pengajuan' => 'Pengadaan Barang Bulk Seed',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Ganjil',
                'tahap_bos' => 'Tahap 1',
                'tanggal_mulai' => now()->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(30)->format('Y-m-d'),
                'periode_penggunaan' => 'Semester Ganjil',
                'kategori_anggaran' => 'Pengadaan Aset',
                'latar_belakang' => 'Kebutuhan barang untuk tugas seed 20',
                'tanggal_pengajuan' => now(),
                'estimasi_total' => 0,
                'status' => 'Draft',
            ]
        );

        $totalEstimasi = 0;

        for ($i = 1; $i <= 20; $i++) {
            $harga = $faker->numberBetween(100, 1000) * 1000;
            $volume = $faker->numberBetween(1, 10);
            $total = $harga * $volume;

            RincianItem::updateOrCreate(
                [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'uraian_barang' => "Barang Seed {$i} - ".$faker->words(2, true),
                ],
                [
                    'satuan' => 'Unit',
                    'volume' => $volume,
                    'harga_satuan' => $harga,
                    'total_harga' => $total,
                ]
            );
            $totalEstimasi += $total;
        }

        $pengajuan->update(['estimasi_total' => $totalEstimasi]);
    }
}
