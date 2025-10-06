<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penelitian_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penelitian_id')->constrained('penelitian')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->string('peran')->default('Anggota');
            $table->timestamps();

            $table->unique(['penelitian_id', 'dosen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitian_dosen');
    }
};
