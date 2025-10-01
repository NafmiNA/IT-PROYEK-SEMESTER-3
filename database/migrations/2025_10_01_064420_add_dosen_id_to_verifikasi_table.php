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
    Schema::table('verifikasi', function (Blueprint $table) {
        $table->unsignedBigInteger('dosen_id')->after('id');

        // relasi ke tabel dosens
        $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('verifikasi', function (Blueprint $table) {
        $table->dropForeign(['dosen_id']);
        $table->dropColumn('dosen_id');
    });
}

};
