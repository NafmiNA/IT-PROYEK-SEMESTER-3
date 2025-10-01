<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('laporan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->cascadeOnDelete();

            $table->foreignId('penelitian_id')->nullable()->constrained('penelitian')->nullOnDelete();
            $table->foreignId('pengabdian_id')->nullable()->constrained('pengabdian')->nullOnDelete();

            $table->string('judul_snapshot')->nullable();
            $table->integer('tahun_snapshot')->nullable();
            $table->decimal('dana_snapshot', 15, 2)->nullable();
            $table->string('status_snapshot')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('laporan_detail');
    }
};
