<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ubah kolom role dari ENUM('admin','user') menjadi mendukung seluruh role RBAC:
     * staff, finance, pimpinan, admin_it, admin, user
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` ENUM('admin','user','staff','finance','pimpinan','admin_it') NOT NULL DEFAULT 'user'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
        }
    }
};
