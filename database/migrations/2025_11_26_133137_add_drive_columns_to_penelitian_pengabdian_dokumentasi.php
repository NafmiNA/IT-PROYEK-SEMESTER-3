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
        // Add Google Drive columns to penelitian table (after laporan_path)
        Schema::table('penelitian', function (Blueprint $table) {
            $table->string('drive_file_id')->nullable()->after('laporan_path');
            $table->text('drive_file_url')->nullable()->after('drive_file_id');
            $table->boolean('uploaded_to_drive')->default(false)->after('drive_file_url');
        });

        // Add Google Drive columns to pengabdians table (pengabdians uses plural table name)
        Schema::table('pengabdians', function (Blueprint $table) {
            $table->string('drive_file_id')->nullable();
            $table->text('drive_file_url')->nullable();
            $table->boolean('uploaded_to_drive')->default(false);
        });

        // Add Google Drive columns to dokumentasi table (after gdrive_path)
        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->string('drive_file_id')->nullable()->after('gdrive_path');
            $table->text('drive_file_url')->nullable()->after('drive_file_id');
            $table->boolean('uploaded_to_drive')->default(false)->after('drive_file_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropColumn(['drive_file_id', 'drive_file_url', 'uploaded_to_drive']);
        });

        Schema::table('pengabdians', function (Blueprint $table) {
            $table->dropColumn(['drive_file_id', 'drive_file_url', 'uploaded_to_drive']);
        });

        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->dropColumn(['drive_file_id', 'drive_file_url', 'uploaded_to_drive']);
        });
    }
};
