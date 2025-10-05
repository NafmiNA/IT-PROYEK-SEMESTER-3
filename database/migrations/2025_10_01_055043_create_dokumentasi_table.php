<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id('dokumentasi_id');
            $table->unsignedBigInteger('penelitian_id')->nullable();
            $table->unsignedBigInteger('pengabdian_id')->nullable();
            $table->string('file_name');
            $table->string('mime')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('gdrive_path')->nullable();
            $table->timestamps();
        });

        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->foreign('penelitian_id')
                  ->references('id')->on('penelitian')
                  ->onDelete('cascade');

            $table->foreign('pengabdian_id')
                  ->references('id')->on('pengabdians') // <-- plural
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->dropForeign(['penelitian_id']);
            $table->dropForeign(['pengabdian_id']);
        });
        Schema::dropIfExists('dokumentasi');
    }
};
