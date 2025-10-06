<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengabdian_dosen', function (Blueprint $table) {
            $table->id();

            // FK ke tabel 'pengabdians' dan 'dosens'
            $table->foreignId('pengabdian_id')->constrained('pengabdians')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();

            // peran di tim
            $table->string('peran')->default('Anggota'); // Ketua | Anggota

            // hindari duplikat anggota di satu pengabdian
            $table->unique(['pengabdian_id','dosen_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengabdian_dosen');
    }
};
