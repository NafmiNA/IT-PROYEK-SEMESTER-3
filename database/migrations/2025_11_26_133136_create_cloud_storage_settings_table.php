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
        Schema::create('cloud_storage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->default('google_drive');
            
            // OAuth tokens
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            
            // Folder settings
            $table->string('main_folder_id')->nullable();
            $table->string('main_folder_name')->nullable();
            $table->string('penelitian_folder_id')->nullable();
            $table->string('pengabdian_folder_id')->nullable();
            $table->string('dokumentasi_folder_id')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_configured')->default(false);
            
            // Migration tracking
            $table->integer('total_files')->default(0);
            $table->integer('migrated_files')->default(0);
            $table->enum('migration_status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->timestamp('last_migration_at')->nullable();
            
            // User who configured
            $table->foreignId('configured_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloud_storage_settings');
    }
};
