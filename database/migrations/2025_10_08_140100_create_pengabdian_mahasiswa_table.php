<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pengabdian_mahasiswa')) {
            return;
        }

        $pengabdianTable = Schema::hasTable('pengabdians')
            ? 'pengabdians'
            : (Schema::hasTable('pengabdian') ? 'pengabdian' : null);

        Schema::create('pengabdian_mahasiswa', function (Blueprint $table) use ($pengabdianTable) {
            $table->id();
            $table->unsignedBigInteger('pengabdian_id');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->string('peran')->default('Pendukung');
            $table->timestamps();

            $table->unique(['pengabdian_id', 'mahasiswa_id']);

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
        Schema::dropIfExists('pengabdian_mahasiswa');
    }
};
