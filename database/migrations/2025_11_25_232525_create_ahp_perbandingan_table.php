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
        Schema::create('ahp_perbandingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_a_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_b_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 10, 4); // Nilai perbandingan (1-9 atau pecahan)
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang terakhir update
            $table->timestamps();
            
            // Unique constraint: satu pasangan kriteria hanya bisa dibandingkan sekali
            $table->unique(['kriteria_a_id', 'kriteria_b_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_perbandingan');
    }
};
