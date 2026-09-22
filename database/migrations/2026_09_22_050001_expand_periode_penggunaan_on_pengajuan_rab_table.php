<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Perbesar panjang kolom periode_penggunaan menjadi 255 karakter dan nullable
     * agar dapat menampung nama periode anggaran sekolah otomatis seperti:
     * 'BOS Reguler Tahap 1 (Januari – Juni) (2026/2027 - Semester Ganjil)'
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `pengajuan_rab` MODIFY COLUMN `periode_penggunaan` VARCHAR(255) NULL');
        } else {
            Schema::table('pengajuan_rab', function (Blueprint $table): void {
                $table->string('periode_penggunaan', 255)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `pengajuan_rab` MODIFY COLUMN `periode_penggunaan` VARCHAR(50) NOT NULL');
        } else {
            Schema::table('pengajuan_rab', function (Blueprint $table): void {
                $table->string('periode_penggunaan', 50)->nullable(false)->change();
            });
        }
    }
};
