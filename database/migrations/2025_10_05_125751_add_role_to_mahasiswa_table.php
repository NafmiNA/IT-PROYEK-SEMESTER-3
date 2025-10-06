<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswa', 'role')) {
                $column = $table->string('role')->default('mahasiswa');

                if (Schema::hasColumn('mahasiswa', 'peran')) {
                    $column->after('peran');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswa', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
