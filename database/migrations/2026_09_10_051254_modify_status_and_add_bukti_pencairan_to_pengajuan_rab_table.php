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
        // Add bukti_pencairan column
        Schema::table('pengajuan_rab', function (Blueprint $table) {
            $table->string('bukti_pencairan')->nullable()->after('status');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Menunggu Verifikasi Finance'");

            // Map old statuses to new statuses
            DB::table('pengajuan_rab')->where('status', 'Pending')->update(['status' => 'Menunggu Verifikasi Finance']);
            DB::table('pengajuan_rab')->where('status', 'ACC Finance')->update(['status' => 'Menunggu Persetujuan Pimpinan']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak Finance')->update(['status' => 'Revisi']);
            DB::table('pengajuan_rab')->where('status', 'ACC Final')->update(['status' => 'Proses Pencairan']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak Pimpinan')->update(['status' => 'Ditolak']);
            DB::table('pengajuan_rab')->where('status', 'ACC')->update(['status' => 'Proses Pencairan']);

        } else {
            Schema::table('pengajuan_rab', function (Blueprint $table) {
                $table->string('status', 50)->default('Menunggu Verifikasi Finance')->change();
            });
            DB::table('pengajuan_rab')->where('status', 'Pending')->update(['status' => 'Menunggu Verifikasi Finance']);
            DB::table('pengajuan_rab')->where('status', 'ACC Finance')->update(['status' => 'Menunggu Persetujuan Pimpinan']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak Finance')->update(['status' => 'Revisi']);
            DB::table('pengajuan_rab')->where('status', 'ACC Final')->update(['status' => 'Proses Pencairan']);
            DB::table('pengajuan_rab')->where('status', 'Ditolak Pimpinan')->update(['status' => 'Ditolak']);
            DB::table('pengajuan_rab')->where('status', 'ACC')->update(['status' => 'Proses Pencairan']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table) {
            $table->dropColumn('bukti_pencairan');
        });

        // Reverse status change if needed, simplified to basic Pending
        if (DB::getDriverName() === 'mysql') {
            DB::table('pengajuan_rab')->update(['status' => 'Pending']);
            DB::statement("ALTER TABLE `pengajuan_rab` MODIFY COLUMN `status` ENUM('Pending', 'ACC Finance', 'Ditolak Finance', 'ACC Final', 'Ditolak Pimpinan') NOT NULL DEFAULT 'Pending'");
        } else {
            DB::table('pengajuan_rab')->update(['status' => 'Pending']);
            Schema::table('pengajuan_rab', function (Blueprint $table) {
                $table->string('status', 50)->default('Pending')->change();
            });
        }
    }
};
