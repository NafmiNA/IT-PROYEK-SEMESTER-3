<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->enum('jenis', ['foto','video','pdf'])->default('pdf');
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();

            // relasi opsional ke salah satu kegiatan
            $table->foreignId('penelitian_id')->nullable()->constrained('penelitian')->nullOnDelete();
            $table->foreignId('pengabdian_id')->nullable()->constrained('pengabdian')->nullOnDelete();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dokumentasi');
    }
};
