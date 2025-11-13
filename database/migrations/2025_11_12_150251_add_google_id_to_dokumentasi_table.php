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
        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('gdrive_path');
            $table->string('google_url')->nullable()->after('google_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumentasi', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google_url']);
        });
    }
};
