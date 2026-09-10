<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alur_persetujuan', function (Blueprint $table): void {
            $table->increments('id_persetujuan');
            $table->unsignedInteger('id_pengajuan');
            $table->unsignedInteger('id_reviewer');
            $table->integer('level_persetujuan')->default(1);
            $table->enum('status_persetujuan', ['ACC', 'Ditolak']);
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_proses');
            $table->timestamps();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_rab')
                ->onDelete('cascade');

            $table->foreign('id_reviewer')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alur_persetujuan');
    }
};
