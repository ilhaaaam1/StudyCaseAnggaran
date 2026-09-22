<?php

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
        Schema::table('pengajuan_rab', function (Blueprint $table) {
            // PRESENTASI: Mengganti kolom prioritas menjadi kategori_anggaran
            // Kolom prioritas dihapus karena dianggap subjektif, dan diganti
            // dengan kategori_anggaran yang lebih objektif untuk analisis Finance.
            $table->dropColumn('prioritas');
            $table->string('kategori_anggaran')->after('periode_penggunaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_rab', function (Blueprint $table) {
            $table->dropColumn('kategori_anggaran');
            $table->string('prioritas')->after('periode_penggunaan')->nullable();
        });
    }
};
