<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('periode'); // contoh: 2025-Q4
            $table->enum('jenis', ['Penelitian','Pengabdian','Gabungan'])->default('Gabungan');
            $table->string('file_path')->nullable();
            $table->date('tanggal_publish')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('laporans');
    }
};
