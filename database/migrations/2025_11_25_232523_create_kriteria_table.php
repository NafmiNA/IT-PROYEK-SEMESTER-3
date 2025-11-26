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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // K1, K2, K3, dst
            $table->string('nama', 100); // Nama kriteria
            $table->text('deskripsi')->nullable(); // Penjelasan kriteria
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit'); // Benefit (semakin besar semakin baik) atau Cost (semakin kecil semakin baik)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
