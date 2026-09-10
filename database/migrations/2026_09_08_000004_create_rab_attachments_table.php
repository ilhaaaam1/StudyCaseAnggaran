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
        Schema::create('rab_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rabs')->cascadeOnDelete();
            $table->string('file_name'); // Nama asli file, contoh: Spesifikasi Teknis.xlsx
            $table->string('file_path'); // Path penyimpanan di storage
            $table->string('file_size')->nullable(); // Contoh: 245 KB
            $table->string('file_type')->nullable(); // Contoh: pdf, xlsx
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_attachments');
    }
};
