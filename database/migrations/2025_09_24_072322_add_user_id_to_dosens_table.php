<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // untuk DB yang sudah berisi data, buat nullable dulu
            if (!Schema::hasColumn('dosens', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable()                            // aman untuk data lama
                      ->constrained('users')                  // FK ke users.id
                      ->nullOnDelete();                       // jika user dihapus -> null
            }
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');  // hapus FK + kolom
            }
        });
    }
};
