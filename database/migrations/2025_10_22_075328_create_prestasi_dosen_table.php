<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestasi_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id');
            $table->year('tahun');
            $table->integer('publikasi')->default(0)->comment('Jumlah publikasi');
            $table->bigInteger('hibah')->default(0)->comment('Total hibah dalam Rupiah');
            $table->integer('skor_sinta')->default(0)->comment('Skor SINTA');
            $table->integer('buku')->default(0)->comment('Jumlah buku');
            $table->timestamps();
            
            // Index for faster queries
            $table->index('dosen_id');
            $table->index('tahun');
            
            // Unique constraint: satu dosen hanya punya satu record per tahun
            $table->unique(['dosen_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_dosen');
    }
};
