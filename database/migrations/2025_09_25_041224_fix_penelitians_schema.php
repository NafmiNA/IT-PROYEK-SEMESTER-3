<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Jika tabel belum ada, buat lengkap
        if (!Schema::hasTable('penelitians')) {
            Schema::create('penelitians', function (Blueprint $table) {
                $table->id();
                $table->string('judul');                   // ← WAJIB
                $table->integer('tahun');                  // ← WAJIB
                $table->string('skema')->nullable();
                $table->string('sumber_dana')->nullable();
                $table->bigInteger('dana')->nullable();    // simpan sebagai integer (Rp)
                $table->string('status', 50)->default('Draft');
                $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
                $table->timestamps();
            });
            return;
        }

        // Jika tabel sudah ada, tambahkan kolom yang belum ada
        Schema::table('penelitians', function (Blueprint $table) {
            if (!Schema::hasColumn('penelitians', 'judul'))       $table->string('judul')->after('id');
            if (!Schema::hasColumn('penelitians', 'tahun'))       $table->integer('tahun')->after('judul');
            if (!Schema::hasColumn('penelitians', 'skema'))       $table->string('skema')->nullable()->after('tahun');
            if (!Schema::hasColumn('penelitians', 'sumber_dana')) $table->string('sumber_dana')->nullable()->after('skema');
            if (!Schema::hasColumn('penelitians', 'dana'))        $table->bigInteger('dana')->nullable()->after('sumber_dana');
            if (!Schema::hasColumn('penelitians', 'status'))      $table->string('status',50)->default('Draft')->after('dana');
            if (!Schema::hasColumn('penelitians', 'dosen_id'))    $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete()->after('status');
            // timestamps biasanya sudah ada, tapi jika tidak:
            if (!Schema::hasColumn('penelitians', 'created_at') && !Schema::hasColumn('penelitians', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        // Tidak perlu drop kolom satu-satu; biarkan kosong atau tulis rollback sesuai kebutuhan
    }
};

