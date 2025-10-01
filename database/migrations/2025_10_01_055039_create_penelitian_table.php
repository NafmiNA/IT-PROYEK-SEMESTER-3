<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penelitian', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('tahun');
            $table->string('skema')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->decimal('dana', 15, 2)->nullable();
            $table->enum('status', ['Draft','Menunggu','Disetujui','Ditolak'])->default('Draft');
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('penelitian');
    }
};
