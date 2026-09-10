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
        Schema::create('rab_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rabs')->cascadeOnDelete();
            $table->string('description'); // Uraian Kegiatan / Barang
            $table->string('unit'); // Satuan (Unit, Set, Tahun, dll)
            $table->integer('quantity')->default(1); // Volume
            $table->decimal('unit_price', 15, 2)->default(0); // Harga Satuan
            $table->decimal('total_price', 15, 2)->default(0); // Subtotal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_items');
    }
};
