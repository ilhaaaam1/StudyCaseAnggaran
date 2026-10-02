<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRESENTASI: Dipisah menjadi seeder mandiri sesuai instruksi materi
        $unitTU = Divisi::where('nama_divisi', 'Tata Usaha & Operasional (TU)')->first();
        $unitKurikulum = Divisi::where('nama_divisi', 'Kurikulum & Pembelajaran')->first();
        $unitSarpras = Divisi::where('nama_divisi', 'Sarana & Prasarana (Sarpras)')->first();
        $unitKesiswaan = Divisi::where('nama_divisi', 'Kesiswaan & Ekstrakurikuler')->first();

        // 2. Seed Default Essential Accounts (Tabel Pengguna - Authenticatable)
        // Password default: 'password'
        $defaultPassword = Hash::make('password');

        if (! $unitTU || ! $unitKurikulum || ! $unitSarpras || ! $unitKesiswaan) {
            return; // Kembalikan jika divisi tidak lengkap
        }

        // Admin / Kepala TU
        Pengguna::updateOrCreate(
            ['email' => 'arif@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Drs. Arif Rachman',
                'jabatan' => 'Kepala Tata Usaha',
                'password' => $defaultPassword,
                'role' => 'admin',
            ]
        );

        // Staf Kurikulum
        Pengguna::updateOrCreate(
            ['email' => 'sari@sirab.local'],
            [
                'id_divisi' => $unitKurikulum->id_divisi,
                'nama_lengkap' => 'Sari Dewi',
                'jabatan' => 'Koordinator Kurikulum',
                'password' => $defaultPassword,
                'role' => 'user',
            ]
        );

        // Staf Sarpras
        Pengguna::updateOrCreate(
            ['email' => 'admin@sirab.local'],
            [
                'id_divisi' => $unitSarpras->id_divisi,
                'nama_lengkap' => 'admin',
                'jabatan' => 'Staf Sarana & Prasarana',
                'password' => $defaultPassword,
                'role' => 'admin',
            ]
        );

        // Staf Kesiswaan
        Pengguna::updateOrCreate(
            ['email' => 'nanda@sirab.local'],
            [
                'id_divisi' => $unitKesiswaan->id_divisi,
                'nama_lengkap' => 'Nanda',
                'jabatan' => 'Pembina Kesiswaan & Ekskul',
                'password' => $defaultPassword,
                'role' => 'finance',
            ]
        );

        // Finance / Bendahara BOS
        Pengguna::updateOrCreate(
            ['email' => 'finance@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Akun Finance',
                'jabatan' => 'Bendahara BOS',
                'password' => $defaultPassword,
                'role' => 'finance',
            ]
        );

        // Pimpinan / Kepala Sekolah
        Pengguna::updateOrCreate(
            ['email' => 'pimpinan@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Akun Pimpinan',
                'jabatan' => 'Kepala Sekolah',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );

        // Pimpinan / Wakil Kepala sekolah
        Pengguna::updateOrCreate(
            ['email' => 'wakakepsek@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Radhit',
                'jabatan' => 'Koordinator Kurikulum',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );

        // Pimpinan / Wakil Kepala sekolah
        Pengguna::updateOrCreate(
            ['email' => 'wina@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Wina',
                'jabatan' => 'Koordinator Kurikulum',
                'password' => $defaultPassword,
                'role' => 'finance',
            ]
        );
        // Pimpinan / Wakil Kepala sekolah
        Pengguna::updateOrCreate(
            ['email' => 'nabila@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'nabila',
                'jabatan' => 'Koordinator kesiswaan',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );
        // Pimpinan / Wakil Kepala sekolah
        Pengguna::updateOrCreate(
            ['email' => 'fajar@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'fajar',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'user',
            ]
        );
        Pengguna::updateOrCreate(
            ['email' => 'afid@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Afid',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'admin',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'mey@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Mey',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'finance',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'ilham@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Ilham',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'budi@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Budi',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'user',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'citra@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Citra',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'finance',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'dian@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Dian',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'eko@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Eko',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'user',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'fitri@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Fitri',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'admin',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'gilang@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Gilang',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'user',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'hendra@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Hendra',
                'jabatan' => 'Koordinator laboratorium',
                'password' => $defaultPassword,
                'role' => 'pimpinan',
            ]
        );
    }
}
