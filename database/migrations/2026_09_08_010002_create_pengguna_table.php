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
        Schema::create('pengguna', function (Blueprint $table): void {
            $table->increments('id_pengguna');
            $table->unsignedInteger('id_divisi');
            $table->string('nama_lengkap', 150);
            $table->string('jabatan', 100);
            $table->string('email', 150)->unique();
            $table->string('password')->default('$2y$12$eA3.kG9a0V4l6VbWJq1j8uWbWf7h5I/J4t/j4uWbWf7h5I/J4t/j4');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();

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
        Schema::dropIfExists('pengguna');
    }
};
