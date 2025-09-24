<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: membuat tabel dosens.
     */
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->boolean('status_aktif')->default(true);

            // FK otomatis ke tabel users (id) + cascade on delete
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
