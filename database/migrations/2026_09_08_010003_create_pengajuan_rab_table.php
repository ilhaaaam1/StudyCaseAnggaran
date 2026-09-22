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
        Schema::create('pengajuan_rab', function (Blueprint $table): void {
            $table->increments('id_pengajuan');
            $table->unsignedInteger('id_pengguna');
            $table->unsignedInteger('id_divisi');
            $table->string('no_rab', 50)->unique();
            $table->string('judul_pengajuan', 255);
            $table->string('periode_penggunaan', 255)->nullable();
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->text('latar_belakang');
            $table->decimal('estimasi_total', 15, 2)->default(0);
            $table->enum('status', ['Pending', 'ACC', 'Ditolak'])->default('Pending');
            $table->dateTime('tanggal_pengajuan');
            $table->timestamps();

            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('restrict');

            $table->foreign('id_divisi')
                ->references('id_divisi')
                ->on('divisi')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_rab');
    }
};
