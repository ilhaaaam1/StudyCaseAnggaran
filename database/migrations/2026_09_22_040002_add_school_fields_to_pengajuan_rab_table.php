<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambahkan kolom pendukung pelaporan SPJ, pencairan dana, dan jadwal kegiatan sekolah:
     * - tahun_ajaran (string, contoh: '2026/2027')
     * - semester (string, contoh: 'Ganjil' atau 'Genap')
     * - tahun_ajaran_semester (string, contoh: '2026/2027 - Semester Ganjil')
     * - tahap_bos (string, contoh: 'BOS Reguler Tahap 1', 'BOS Reguler Tahap 2')
     * - tanggal_mulai (date)
     * - tanggal_selesai (date)
     */
    public function up(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table): void {
            if (! Schema::hasColumn('pengajuan_rab', 'tahun_ajaran')) {
                $table->string('tahun_ajaran', 20)->nullable()->after('judul_pengajuan');
            }
            if (! Schema::hasColumn('pengajuan_rab', 'semester')) {
                $table->string('semester', 20)->nullable()->after('tahun_ajaran');
            }
            if (! Schema::hasColumn('pengajuan_rab', 'tahun_ajaran_semester')) {
                $table->string('tahun_ajaran_semester', 50)->nullable()->after('semester');
            }
            if (! Schema::hasColumn('pengajuan_rab', 'tahap_bos')) {
                $table->string('tahap_bos', 50)->nullable()->after('tahun_ajaran_semester');
            }
            if (! Schema::hasColumn('pengajuan_rab', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('tahap_bos');
            }
            if (! Schema::hasColumn('pengajuan_rab', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table): void {
            $columns = [
                'tahun_ajaran',
                'semester',
                'tahun_ajaran_semester',
                'tahap_bos',
                'tanggal_mulai',
                'tanggal_selesai',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('pengajuan_rab', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
