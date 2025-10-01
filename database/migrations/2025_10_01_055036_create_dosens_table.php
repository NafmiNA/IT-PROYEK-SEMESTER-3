<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->nullable()->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('jabatan_fungsional')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dosens');
    }
};
