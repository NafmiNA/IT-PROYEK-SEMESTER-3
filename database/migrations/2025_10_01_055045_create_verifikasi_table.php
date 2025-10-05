<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal')->nullable();
            $table->enum('status', ['Menunggu','Disetujui','Ditolak'])->default('Menunggu');
            $table->text('catatan')->nullable();

            $table->foreignId('admin_p3m_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('penelitian_id')->nullable()->constrained('penelitian')->nullOnDelete();
            $table->foreignId('pengabdian_id')->nullable()->constrained('pengabdians')->nullOnDelete(); // <-- plural

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('verifikasi');
    }
};
