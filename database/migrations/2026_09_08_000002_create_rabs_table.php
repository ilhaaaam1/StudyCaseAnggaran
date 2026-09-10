<?php

use App\Enums\RabPriority;
use App\Enums\RabStatus;
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
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // Contoh: RAB-2026-001
            $table->string('title');
            $table->string('division'); // Divisi / Unit Kerja
            $table->string('period'); // Periode, contoh: Q3 2026, Q4 2026
            $table->string('priority')->default(RabPriority::SEDANG->value);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('justification')->nullable(); // Latar Belakang & Justifikasi
            $table->string('status')->default(RabStatus::DIAJUKAN->value);

            // Kolom Persetujuan / Catatan Review
            $table->text('admin_note')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};
