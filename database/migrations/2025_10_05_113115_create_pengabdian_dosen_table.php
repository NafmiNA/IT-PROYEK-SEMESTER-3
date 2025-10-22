<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pengabdian_dosen')) {
            return;
        }

        $pengabdianTable = Schema::hasTable('pengabdians')
            ? 'pengabdians'
            : (Schema::hasTable('pengabdian') ? 'pengabdian' : null);

        Schema::create('pengabdian_dosen', function (Blueprint $table) use ($pengabdianTable) {
            $table->id();

            // FK ke tabel pengabdian (plural atau legacy singular) dan tabel dosen
            $table->unsignedBigInteger('pengabdian_id');
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();

            // peran di tim
            $table->string('peran')->default('Anggota'); // Ketua | Anggota

            // hindari duplikat anggota di satu pengabdian
            $table->unique(['pengabdian_id', 'dosen_id']);

            $table->timestamps();

            if ($pengabdianTable) {
                $table->foreign('pengabdian_id')
                    ->references('id')
                    ->on($pengabdianTable)
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengabdian_dosen');
    }
};
