<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SchoolRabSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisiKurikulum;

    protected Divisi $divisiSarpras;

    protected Pengguna $staff;

    protected Pengguna $finance;

    protected Pengguna $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisiKurikulum = Divisi::create([
            'nama_divisi' => 'Kurikulum & Pembelajaran',
        ]);

        $this->divisiSarpras = Divisi::create([
            'nama_divisi' => 'Sarana & Prasarana (Sarpras)',
        ]);

        $this->staff = Pengguna::create([
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'nama_lengkap' => 'Ahmad Guru',
            'jabatan' => 'Koordinator Kurikulum',
            'email' => 'ahmad.guru@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'nama_lengkap' => 'Ibu Bendahara BOS',
            'jabatan' => 'Bendahara BOS',
            'email' => 'bendahara@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'finance',
        ]);

        $this->pimpinan = Pengguna::create([
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'nama_lengkap' => 'Bapak Kepala Sekolah',
            'jabatan' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);
    }

    public function test_can_submit_school_rab_with_period_and_date_range(): void
    {
        $payload = [
            'judul_pengajuan' => 'Pengadaan Modul Belajar dan Alat Peraga Kelas 5',
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'kategori_anggaran' => 'Pengembangan Perpustakaan & Literasi',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'latar_belakang' => 'Penyediaan modul cetak ajar siswa untuk persiapan Asesmen Nasional.',
            'action' => 'send',
            'items' => [
                [
                    'uraian_barang' => 'Buku Modul Literasi Siswa Kelas 5',
                    'satuan' => 'Eksemplar',
                    'volume' => 50,
                    'harga_satuan' => 45000,
                ],
                [
                    'uraian_barang' => 'Peta Dinding Indonesia & Dunia',
                    'satuan' => 'Lembar',
                    'volume' => 2,
                    'harga_satuan' => 125000,
                ],
            ],
        ];

        $response = $this->actingAs($this->staff)
            ->post(route('staff.rab.store'), $payload);

        $response->assertRedirect(route('staff.riwayat'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengajuan_rab', [
            'judul_pengajuan' => 'Pengadaan Modul Belajar dan Alat Peraga Kelas 5',
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'tahun_ajaran' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
        ]);

        $created = PengajuanRab::where('judul_pengajuan', 'Pengadaan Modul Belajar dan Alat Peraga Kelas 5')->first();
        $this->assertNotNull($created);
        $this->assertEquals('2026-08-01', $created->tanggal_mulai->format('Y-m-d'));
        $this->assertEquals('2026-08-05', $created->tanggal_selesai->format('Y-m-d'));
        $this->assertEquals(5, $created->durasi_hari);
        $this->assertStringContainsString('01 Aug 2026 s/d 05 Aug 2026', $created->rentang_tanggal_formatted);
        $this->assertEquals('BOS Reguler Tahap 1 (Januari – Juni) (2026/2027 - Semester Ganjil)', $created->periode_penggunaan);
    }

    public function test_fails_validation_when_tanggal_selesai_is_before_tanggal_mulai(): void
    {
        $payload = [
            'judul_pengajuan' => 'Perbaikan Pintu Ruang Kelas',
            'id_divisi' => $this->divisiSarpras->id_divisi,
            'kategori_anggaran' => 'Pemeliharaan Sarana & Prasarana',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-10',
            'tanggal_selesai' => '2026-08-05', // invalid: before tanggal_mulai
            'latar_belakang' => 'Perbaikan mendesak pintu kelas 3A.',
            'action' => 'send',
            'items' => [
                [
                    'uraian_barang' => 'Gagang Pintu & Engsel Stainless',
                    'satuan' => 'Set',
                    'volume' => 2,
                    'harga_satuan' => 75000,
                ],
            ],
        ];

        $response = $this->actingAs($this->staff)
            ->from(route('staff.rab.create'))
            ->post(route('staff.rab.store'), $payload);

        $response->assertRedirect(route('staff.rab.create'));
        $response->assertSessionHasErrors(['tanggal_selesai']);
    }

    public function test_fails_validation_when_id_divisi_is_missing(): void
    {
        $payload = [
            'judul_pengajuan' => 'Pengadaan Kertas Ujian',
            'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
            'latar_belakang' => 'Kertas untuk asesmen sumatif.',
            'action' => 'send',
            'items' => [
                [
                    'uraian_barang' => 'Kertas HVS F4 70gr',
                    'satuan' => 'Rim',
                    'volume' => 10,
                    'harga_satuan' => 52000,
                ],
            ],
        ];

        $response = $this->actingAs($this->staff)
            ->from(route('staff.rab.create'))
            ->post(route('staff.rab.store'), $payload);

        $response->assertSessionHasErrors(['id_divisi']);
    }

    public function test_staff_can_edit_and_update_school_rab_with_new_dates(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-901',
            'judul_pengajuan' => 'Kegiatan Pramuka Gugus Depan',
            'tahun_ajaran' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-14',
            'tanggal_selesai' => '2026-08-15',
            'periode_penggunaan' => 'BOS Reguler Tahap 1',
            'kategori_anggaran' => 'Kegiatan Kesiswaan & Lomba',
            'latar_belakang' => 'Pelaksanaan perkemahan Jumat-Sabtu siswa pramuka.',
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'estimasi_total' => 500000,
            'tanggal_pengajuan' => now(),
        ]);

        RincianItem::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'Tali Pramuka & Tongkat',
            'satuan' => 'Set',
            'volume' => 10,
            'harga_satuan' => 50000,
            'total_harga' => 500000,
        ]);

        // Access edit page
        $editResponse = $this->actingAs($this->staff)
            ->get(route('staff.rab.edit', $pengajuan->id_pengajuan));
        $editResponse->assertOk();
        $editResponse->assertSee('Kegiatan Pramuka Gugus Depan');
        $editResponse->assertSee('2026/2027 - Semester Ganjil');

        // Update with new dates
        $updatePayload = [
            'judul_pengajuan' => 'Kegiatan Pramuka Gugus Depan Revisi Tanggal',
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'kategori_anggaran' => 'Kegiatan Kesiswaan & Lomba',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-21',
            'tanggal_selesai' => '2026-08-23',
            'latar_belakang' => 'Jadwal diundur mengikuti kalender pendidikan dinas.',
            'action' => 'send',
            'items' => [
                [
                    'uraian_barang' => 'Tali Pramuka & Tongkat',
                    'satuan' => 'Set',
                    'volume' => 15,
                    'harga_satuan' => 50000,
                ],
            ],
        ];

        $updateResponse = $this->actingAs($this->staff)
            ->put(route('staff.rab.update', $pengajuan->id_pengajuan), $updatePayload);

        $updateResponse->assertRedirect(route('staff.riwayat'));

        $this->assertDatabaseHas('pengajuan_rab', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'judul_pengajuan' => 'Kegiatan Pramuka Gugus Depan Revisi Tanggal',
        ]);

        $pengajuan->refresh();
        $this->assertEquals('2026-08-21', $pengajuan->tanggal_mulai->format('Y-m-d'));
        $this->assertEquals('2026-08-23', $pengajuan->tanggal_selesai->format('Y-m-d'));
    }

    public function test_detail_and_reviewer_views_display_school_operational_card(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-902',
            'judul_pengajuan' => 'Pengadaan Laptop Asesmen Nasional',
            'tahun_ajaran' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-07',
            'periode_penggunaan' => 'BOS Reguler Tahap 1',
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'latar_belakang' => 'Penambahan unit laptop laboratorium untuk kelancaran ANBK.',
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'estimasi_total' => 15000000,
            'tanggal_pengajuan' => now(),
        ]);

        RincianItem::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'Laptop Chromebook Siswa',
            'satuan' => 'Unit',
            'volume' => 3,
            'harga_satuan' => 5000000,
            'total_harga' => 15000000,
        ]);

        // Staff show view
        $staffShow = $this->actingAs($this->staff)->get(route('staff.rab.show', $pengajuan->id_pengajuan));
        $staffShow->assertOk();
        $staffShow->assertSee('Informasi Operasional Sekolah');
        $staffShow->assertSee('Kurikulum &amp; Pembelajaran', false);
        $staffShow->assertSee('01 Sep 2026 s/d 07 Sep 2026');
        $staffShow->assertSee('7 Hari Pelaksanaan');

        // Finance show view
        $financeShow = $this->actingAs($this->finance)->get(route('finance.show', $pengajuan->id_pengajuan));
        $financeShow->assertOk();
        $financeShow->assertSee('Informasi Operasional Sekolah');
        $financeShow->assertSee('Kurikulum &amp; Pembelajaran', false);
        $financeShow->assertSee('01 Sep 2026 s/d 07 Sep 2026');

        // Advance to MENUNGGU_PIMPINAN for Pimpinan view
        $pengajuan->update(['status' => StatusPengajuan::MENUNGGU_PIMPINAN]);
        $pimpinanShow = $this->actingAs($this->pimpinan)->get(route('pimpinan.show', $pengajuan->id_pengajuan));
        $pimpinanShow->assertOk();
        $pimpinanShow->assertSee('Informasi Operasional Sekolah');
        $pimpinanShow->assertSee('Kurikulum &amp; Pembelajaran', false);
        $pimpinanShow->assertSee('01 Sep 2026 s/d 07 Sep 2026');
    }

    public function test_can_update_school_rab_with_long_auto_period_string(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-099',
            'judul_pengajuan' => 'Pengadaan Alat Praktik Lab IPA',
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-05',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni) (2026/2027 - Semester Ganjil)',
            'latar_belakang' => 'Kebutuhan praktikum siswa kelas 7-9.',
            'estimasi_total' => 5000000,
            'status' => StatusPengajuan::DRAFT,
            'tanggal_pengajuan' => now(),
        ]);

        $updatePayload = [
            'judul_pengajuan' => 'Pengadaan Mikroskop Digital & Set Preparat IPA',
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 2 (Juli – Desember)',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-07',
            'latar_belakang' => 'Update spesifikasi alat praktikum digital.',
            'action' => 'send',
            'items' => [
                [
                    'uraian_barang' => 'Mikroskop Digital USB',
                    'satuan' => 'Unit',
                    'volume' => 3,
                    'harga_satuan' => 2000000,
                ],
            ],
        ];

        $response = $this->actingAs($this->staff)
            ->put(route('staff.rab.update', $pengajuan->id_pengajuan), $updatePayload);

        $response->assertRedirect(route('staff.riwayat'));
        $response->assertSessionHas('success');

        $fresh = $pengajuan->fresh();
        $this->assertEquals(StatusPengajuan::MENUNGGU_FINANCE, $fresh->status);
        $this->assertEquals('BOS Reguler Tahap 2 (Juli – Desember) (2026/2027 - Semester Ganjil)', $fresh->periode_penggunaan);
        $this->assertEquals(7, $fresh->durasi_hari);
        $this->assertEquals(6000000, (float) $fresh->estimasi_total);
    }

    public function test_staff_riwayat_metrics_and_contextual_school_filters(): void
    {
        // 1. Buat 4 pengajuan dengan berbagai status
        $rab1 = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-101',
            'judul_pengajuan' => 'Pengadaan Buku Guru Kurikulum Merdeka',
            'kategori_anggaran' => 'Pengembangan Perpustakaan & Literasi',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-04',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'latar_belakang' => 'Buku pegangan ajar guru.',
            'estimasi_total' => 2500000,
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'tanggal_pengajuan' => now()->subDays(3),
        ]);

        $rab2 = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-102',
            'judul_pengajuan' => 'Pelatihan Workshop Asesmen Nasional',
            'kategori_anggaran' => 'Peningkatan Kompetensi Guru (SDM)',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-10',
            'tanggal_selesai' => '2026-08-12',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'latar_belakang' => 'Peningkatan kapasitas pendidik.',
            'estimasi_total' => 3000000,
            'status' => StatusPengajuan::MENUNGGU_PIMPINAN,
            'tanggal_pengajuan' => now()->subDays(2),
        ]);

        $rab3 = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiSarpras->id_divisi,
            'no_rab' => 'RAB-2026-103',
            'judul_pengajuan' => 'Renovasi Sanitasi Toilet Siswa',
            'kategori_anggaran' => 'Pemeliharaan Sarana & Prasarana',
            'tahun_ajaran_semester' => '2026/2027 - Semester Genap',
            'tahap_bos' => 'BOS Reguler Tahap 2 (Juli – Desember)',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-05',
            'periode_penggunaan' => 'BOS Reguler Tahap 2 (Juli – Desember)',
            'latar_belakang' => 'Perbaikan sanitasi darurat.',
            'estimasi_total' => 4500000,
            'status' => StatusPengajuan::REVISI,
            'tanggal_pengajuan' => now()->subDay(),
        ]);

        $rab4 = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-104',
            'judul_pengajuan' => 'Langganan Internet Indihome Sekolah',
            'kategori_anggaran' => 'Langganan Daya & Jasa',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-07-31',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'latar_belakang' => 'Konektivitas internet lab dan TU.',
            'estimasi_total' => 1200000,
            'status' => StatusPengajuan::PROSES_PENCAIRAN,
            'tanggal_pengajuan' => now()->subDays(5),
        ]);

        // 2. Akses halaman riwayat tanpa filter
        $response = $this->actingAs($this->staff)->get(route('staff.riwayat'));
        $response->assertOk();
        $response->assertSee('TOTAL PENGAJUAN');
        $response->assertSee('SEDANG DIPROSES');
        $response->assertSee('PERLU REVISI');
        $response->assertSee('DANA DISETUJUI / CAIR');
        $response->assertSee('RAB-2026-101');
        $response->assertSee('RAB-2026-102');
        $response->assertSee('RAB-2026-103');
        $response->assertSee('RAB-2026-104');

        // Pastikan nilai metrik tercermin
        $response->assertViewHas('metrics', function ($m) {
            return $m['total_pengajuan'] === 4
                && $m['total_diproses'] === 2
                && $m['total_revisi'] === 1
                && $m['total_disetujui'] == 1200000;
        });

        // 3. Filter tab status Revisi
        $revisiResponse = $this->actingAs($this->staff)->get(route('staff.riwayat', ['status' => StatusPengajuan::REVISI->value]));
        $revisiResponse->assertOk();
        $revisiResponse->assertSee('RAB-2026-103');
        $revisiResponse->assertDontSee('RAB-2026-101');
        $revisiResponse->assertDontSee('RAB-2026-102');

        // 4. Filter kontekstual sekolah: tahap_bos
        $bosResponse = $this->actingAs($this->staff)->get(route('staff.riwayat', [
            'tahap_bos' => 'BOS Reguler Tahap 2 (Juli – Desember)',
        ]));
        $bosResponse->assertOk();
        $bosResponse->assertSee('RAB-2026-103');
        $bosResponse->assertDontSee('RAB-2026-101');
    }

    public function test_staff_show_displays_school_stepper_and_revision_alert_box(): void
    {
        $rab = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-REV',
            'judul_pengajuan' => 'Pengadaan Alat Peraga Pembelajaran Tematik',
            'kategori_anggaran' => 'Pengembangan Perpustakaan & Literasi',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'latar_belakang' => 'Peraga tematik kelas 1-3.',
            'estimasi_total' => 2000000,
            'status' => StatusPengajuan::REVISI,
            'tanggal_pengajuan' => now(),
        ]);

        AlurPersetujuan::create([
            'id_pengajuan' => $rab->id_pengajuan,
            'id_reviewer' => $this->finance->id_pengguna,
            'level_persetujuan' => 1,
            'status_persetujuan' => 'Revisi',
            'catatan' => 'Mohon sertakan brosur katalog harga resmi dan rincian per kelas.',
            'tanggal_proses' => now(),
        ]);

        $response = $this->actingAs($this->staff)->get(route('staff.rab.show', $rab->id_pengajuan));
        $response->assertOk();

        // Cek Banner Peringatan Revisi
        $response->assertSee('Perhatian: Berkas Pengajuan Memerlukan Perbaikan / Revisi');
        $response->assertSee('Mohon sertakan brosur katalog harga resmi dan rincian per kelas.');
        $response->assertSee('Perbaiki Pengajuan Sekarang');

        // Cek Visual Stepper 4 Tahap Sekolah
        $response->assertSee('Alur Persetujuan &amp; Posisi Berkas Sekolah', false);
        $response->assertSee('1. Berkas Diajukan');
        $response->assertSee('2. Bendahara BOS');
        $response->assertSee('3. Kepala Sekolah');
        $response->assertSee('4. Pencairan &amp; SPJ', false);
    }

    public function test_staff_draft_page_displays_matching_action_buttons(): void
    {
        $draft = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisiKurikulum->id_divisi,
            'no_rab' => 'RAB-2026-DRAFT-01',
            'judul_pengajuan' => 'Draft Pengadaan ATK Penilaian',
            'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'latar_belakang' => 'Draft pembelian kertas dan tinta.',
            'estimasi_total' => 1500000,
            'status' => StatusPengajuan::DRAFT,
            'tanggal_pengajuan' => now(),
        ]);

        $response = $this->actingAs($this->staff)->get(route('staff.draft'));

        $response->assertOk();
        $response->assertSee('Draft Pengajuan RAB');
        $response->assertSee('Draft Pengadaan ATK Penilaian');
        $response->assertSee(route('staff.rab.show', $draft->id_pengajuan));
        $response->assertSee(route('staff.rab.edit', $draft->id_pengajuan));
        $response->assertSee(route('staff.rab.destroy', $draft->id_pengajuan));
        $response->assertSee('fa-regular fa-eye', false);
        $response->assertSee('fa-solid fa-pen-to-square', false);
        $response->assertSee('fa-regular fa-trash-can', false);
    }
}
