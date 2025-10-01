<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('pengabdians', function (Blueprint $t) {
      $t->id();
      $t->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete(); // sesuaikan nama tabel dosenmu
      $t->string('judul');
      $t->year('tahun');
      $t->string('skema')->nullable();
      $t->string('sumber_dana')->nullable();
      $t->decimal('dana', 15, 2)->nullable();
      $t->string('status')->default('Menunggu'); // Menunggu | Disetujui | Ditolak
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('pengabdians');
  }
};
