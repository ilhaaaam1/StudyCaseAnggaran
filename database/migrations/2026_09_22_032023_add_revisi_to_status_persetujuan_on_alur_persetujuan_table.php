<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PRESENTASI: Memodifikasi tipe data ENUM pada tabel alur_persetujuan untuk menambahkan opsi 'Revisi'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE alur_persetujuan MODIFY COLUMN status_persetujuan ENUM('ACC', 'Ditolak', 'Revisi') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE alur_persetujuan MODIFY COLUMN status_persetujuan ENUM('ACC', 'Ditolak') NOT NULL");
        }
    }
};
