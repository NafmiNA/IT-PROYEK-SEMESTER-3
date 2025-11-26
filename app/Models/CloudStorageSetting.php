<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CloudStorageSetting extends Model
{
    protected $fillable = [
        'provider',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'main_folder_id',
        'main_folder_name',
        'penelitian_folder_id',
        'pengabdian_folder_id',
        'dokumentasi_folder_id',
        'is_active',
        'is_configured',
        'total_files',
        'migrated_files',
        'migration_status',
        'last_migration_at',
        'configured_by',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_configured' => 'boolean',
        'total_files' => 'integer',
        'migrated_files' => 'integer',
        'last_migration_at' => 'datetime',
    ];

    public function configuredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'configured_by');
    }

    public function isConnected(): bool
    {
        return !empty($this->access_token) && !empty($this->refresh_token);
    }

    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return true;
        }
        return now()->gte($this->token_expires_at);
    }

    public function isFolderConfigured(): bool
    {
        return !empty($this->main_folder_id) && 
               !empty($this->penelitian_folder_id) && 
               !empty($this->pengabdian_folder_id) && 
               !empty($this->dokumentasi_folder_id);
    }
}
