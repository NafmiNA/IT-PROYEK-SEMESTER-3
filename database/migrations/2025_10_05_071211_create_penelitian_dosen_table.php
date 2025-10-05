<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penelitian_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penelitian_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('peran')->default('Anggota'); // Ketua atau Anggota
            $table->timestamps();

            $table->foreign('penelitian_id')->references('id')->on('penelitian')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');

            $table->unique(['penelitian_id','dosen_id']); // supaya tidak dobel
        });
    }

    public function down(): void {
        Schema::dropIfExists('penelitian_dosen');
    }
};
