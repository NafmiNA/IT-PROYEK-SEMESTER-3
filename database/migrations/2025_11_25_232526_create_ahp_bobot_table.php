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
        Schema::create('ahp_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('bobot', 10, 6); // Bobot hasil AHP (0.000000 - 1.000000)
            $table->decimal('consistency_ratio', 10, 6)->nullable(); // CR untuk cek konsistensi
            $table->boolean('is_consistent')->default(true); // CR < 0.1
            $table->timestamp('calculated_at'); // Kapan dihitung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_bobot');
    }
};
