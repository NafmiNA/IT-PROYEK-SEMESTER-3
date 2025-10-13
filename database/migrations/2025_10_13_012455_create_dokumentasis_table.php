<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // Penelitian atau Pengabdian
            $table->string('ketua');
            $table->string('email');
            $table->string('status')->default('Menunggu');
            $table->year('tahun');
            $table->string('dokumen')->nullable(); // file upload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};