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
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Ubah role pada tabel pengguna
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'staff'");
            DB::table('pengguna')->where('role', 'admin')->update(['role' => 'admin_it']);
            DB::table('pengguna')->where('role', 'user')->update(['role' => 'staff']);
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` ENUM('staff', 'admin_it', 'finance', 'pimpinan') NOT NULL DEFAULT 'staff'");

            // 2. Ubah status pada tabel pengajuan_rab
            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Pending'");
            DB::table('pengajuan_rab')->where('status', 'ACC')->update(['status' => 'ACC Final']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak')->update(['status' => 'Ditolak Pimpinan']);
            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` ENUM('Pending', 'ACC Finance', 'Ditolak Finance', 'ACC Final', 'Ditolak Pimpinan') NOT NULL DEFAULT 'Pending'");
        } else {
            // SQLite (Testing Environment)
            Schema::table('pengguna', function (Blueprint $table): void {
                $table->string('role', 50)->default('staff')->change();
            });
            DB::table('pengguna')->where('role', 'admin')->update(['role' => 'admin_it']);
            DB::table('pengguna')->where('role', 'user')->update(['role' => 'staff']);

            Schema::table('pengajuan_rab', function (Blueprint $table): void {
                $table->string('status', 50)->default('Pending')->change();
            });
            DB::table('pengajuan_rab')->where('status', 'ACC')->update(['status' => 'ACC Final']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak')->update(['status' => 'Ditolak Pimpinan']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'user'");
            DB::table('pengguna')->where('role', 'admin_it')->update(['role' => 'admin']);
            DB::table('pengguna')->whereIn('role', ['staff', 'finance', 'pimpinan'])->update(['role' => 'user']);
            DB::statement("ALTER TABLE `pengguna` MODIFY COLUMN `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user'");

            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Pending'");
            DB::table('pengajuan_rab')->where('status', 'ACC Final')->update(['status' => 'ACC']);
            DB::table('pengajuan_rab')->whereIn('status', ['Ditolak Finance', 'Ditolak Pimpinan'])->update(['status' => 'Ditolak']);
            DB::table('pengajuan_rab')->where('status', 'ACC Finance')->update(['status' => 'Pending']);
            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` ENUM('Pending', 'ACC', 'Ditolak') NOT NULL DEFAULT 'Pending'");
        } else {
            Schema::table('pengguna', function (Blueprint $table): void {
                $table->string('role', 50)->default('user')->change();
            });
            DB::table('pengguna')->where('role', 'admin_it')->update(['role' => 'admin']);
            DB::table('pengguna')->whereIn('role', ['staff', 'finance', 'pimpinan'])->update(['role' => 'user']);

            Schema::table('pengajuan_rab', function (Blueprint $table): void {
                $table->string('status', 50)->default('Pending')->change();
            });
            DB::table('pengajuan_rab')->where('status', 'ACC Final')->update(['status' => 'ACC']);
            DB::table('pengajuan_rab')->whereIn('status', ['Ditolak Finance', 'Ditolak Pimpinan'])->update(['status' => 'Ditolak']);
            DB::table('pengajuan_rab')->where('status', 'ACC Finance')->update(['status' => 'Pending']);
        }
    }
};
