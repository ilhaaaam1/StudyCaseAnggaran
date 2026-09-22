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
     * Tambahkan index pada kolom-kolom yang sering difilter dan diurutkan
     * untuk mempercepat query dashboard dan perpindahan halaman pengajuan RAB.
     */
    public function up(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table): void {
            $table->index('status', 'idx_pengajuan_rab_status');
            $table->index(['id_pengguna', 'status'], 'idx_pengajuan_rab_user_status');
            $table->index('tanggal_pengajuan', 'idx_pengajuan_rab_tgl_pengajuan');
            $table->index(['id_divisi', 'status'], 'idx_pengajuan_rab_divisi_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table): void {
            $table->dropIndex('idx_pengajuan_rab_status');
            $table->dropIndex('idx_pengajuan_rab_user_status');
            $table->dropIndex('idx_pengajuan_rab_tgl_pengajuan');
            $table->dropIndex('idx_pengajuan_rab_divisi_status');
        });
    }
};
