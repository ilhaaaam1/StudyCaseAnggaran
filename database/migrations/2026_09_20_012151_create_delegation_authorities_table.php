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
        Schema::create('delegation_authorities', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // Pimpinan
            $table->unsignedInteger('delegate_to_user_id'); // Pengganti (Wakasek, dll)
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['Aktif', 'Selesai', 'Dibatalkan'])->default('Aktif');
            $table->timestamps();

            // Foreign keys pointing to pengguna table using id_pengguna
            $table->foreign('user_id')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->foreign('delegate_to_user_id')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegation_authorities');
    }
};
