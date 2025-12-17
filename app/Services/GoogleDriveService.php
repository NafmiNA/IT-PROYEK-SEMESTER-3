<?php

namespace App\Services;

use App\Models\CloudStorageSetting;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $driveService;
    protected $settings;

    public function __construct()
    {
        $this->settings = CloudStorageSetting::first();
        $this->initializeClient();
    }

    protected function initializeClient()
    {
        $this->client = new GoogleClient();
        $this->client->setApplicationName(config('app.name'));
        $this->client->setScopes([GoogleDrive::DRIVE_FILE]);
        
        // Build redirect URI safely
        $redirectUri = url('/admin/cloud-storage/callback');
        
        $this->client->setAuthConfig([
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uris' => [$redirectUri],
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        if ($this->settings && $this->settings->isConnected()) {
            $this->client->setAccessToken($this->settings->access_token);
            
            if ($this->settings->isTokenExpired()) {
                $this->refreshToken();
            }
        }

        $this->driveService = new GoogleDrive($this->client);
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate(string $code): bool
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new Exception($token['error']);
            }

            $accessToken = $token['access_token'];
            $refreshToken = $token['refresh_token'] ?? null;
            $expiresIn = $token['expires_in'] ?? 3600;
            
            $setting = CloudStorageSetting::firstOrNew();
            $setting->access_token = $accessToken;
            if ($refreshToken) {
                $setting->refresh_token = $refreshToken;
            }
            $setting->token_expires_at = now()->addSeconds($expiresIn);
            $setting->configured_by = auth()->id();
            $setting->save();

            $this->settings = $setting;
            $this->initializeClient();

            return true;
        } catch (Exception $e) {
            Log::error('Google Drive authentication error: ' . $e->getMessage());
            return false;
        }
    }

    public function refreshToken(): bool
    {
        try {
            if (!$this->settings || !$this->settings->refresh_token) {
                Log::warning('Cannot refresh token: No refresh token available');
                return false;
            }

            Log::info('Refreshing Google Drive access token...');
            
            $this->client->refreshToken($this->settings->refresh_token);
            $newToken = $this->client->getAccessToken();

            if (!isset($newToken['access_token'])) {
                throw new Exception('Failed to get new access token');
            }

            $this->settings->access_token = $newToken['access_token'];
            $this->settings->token_expires_at = now()->addSeconds($newToken['expires_in'] ?? 3600);
            $this->settings->save();

            // Update client with new token
            $this->client->setAccessToken($newToken['access_token']);

            Log::info('Google Drive token refreshed successfully');
            return true;
        } catch (Exception $e) {
            Log::error('Google Drive token refresh error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure token is valid before operations
     */
    protected function ensureValidToken(): bool
    {
        if (!$this->settings || !$this->settings->isConnected()) {
            Log::warning('Google Drive not connected');
            return false;
        }

        if ($this->settings->isTokenExpired()) {
            Log::info('Token expired, attempting to refresh...');
            
            if (!$this->refreshToken()) {
                Log::error('Failed to refresh expired token');
                return false;
            }
        }

        return true;
    }

    public function disconnect(): bool
    {
        try {
            if ($this->settings) {
                $this->client->revokeToken();
                $this->settings->update([
                    'access_token' => null,
                    'refresh_token' => null,
                    'token_expires_at' => null,
                    'is_configured' => false,
                ]);
            }
            return true;
        } catch (Exception $e) {
            Log::error('Google Drive disconnect error: ' . $e->getMessage());
            return false;
        }
    }

    public function findFolder(string $folderName, ?string $parentId = null): ?string
    {
        try {
            if (!$this->ensureValidToken()) {
                throw new Exception('Google Drive token is invalid');
            }

            $query = "mimeType='application/vnd.google-apps.folder' and name='" . $folderName . "' and trashed=false";
            if ($parentId) {
                $query .= " and '" . $parentId . "' in parents";
            }

            $response = $this->driveService->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name)',
            ]);

            if (count($response->files) > 0) {
                return $response->files[0]->id;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Google Drive find folder error: ' . $e->getMessage());
            return null;
        }
    }

    public function setupDefaultFolders(): array
    {
        try {
            // 1. Determine unique Main Folder Name
            $baseName = 'SIDEPAN';
            $mainFolderName = $baseName;
            $counter = 1;

            // Check if folder exists in Drive (not just in DB)
            // If main_folder_id is already set in DB, we should technically use that, 
            // but the request implies a "fresh" setup or re-setup where we want a new folder if collision exists.
            // To be safe: if db has id, check if it matches real folder. If so, use it. 
            // If not (broken link), or if we are setting up fresh, find unique name.
            
            // However, simplicity based on prompt: "jika di akun tersebut sudah ada folder yang namanya sideppan, maka... sidepan1"
            // This implies a check against Drive content.
            
            while ($this->findFolder($mainFolderName)) {
                $mainFolderName = $baseName . ' ' . $counter;
                $counter++;
            }

            // Create the folder with the unique name
            $mainFolderId = $this->createFolder($mainFolderName);

            if (!$mainFolderId) {
                throw new Exception('Failed to create main folder ' . $mainFolderName);
            }

            // 2. Create Subfolders
            $folders = [];
            $subFolders = ['Dokumentasi', 'Penelitian', 'Pengabdian'];
            $folderIds = [];

            foreach ($subFolders as $subFolder) {
                // Subfolders inside the NEW main folder don't need unique checks against global drive, 
                // just create them.
                $folderId = $this->createFolder($subFolder, $mainFolderId);
                
                $folders[$subFolder] = $folderId;
                
                // Map to db columns
                $key = strtolower($subFolder) . '_folder_id';
                $folderIds[$key] = $folderId;
            }

            // 3. Update Settings
            $this->settings->update(array_merge([
                'main_folder_id' => $mainFolderId,
                'main_folder_name' => $mainFolderName,
                'is_configured' => true,
            ], $folderIds));

            return array_merge(['main' => $mainFolderId], $folders);

        } catch (Exception $e) {
            Log::error('Google Drive setup default folders error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createSubFolder(string $folderName): ?string
    {
        try {
            // Get Main Folder ID from settings
            $mainFolderId = $this->settings->main_folder_id;
            
            if (!$mainFolderId) {
                throw new Exception('Main folder not configured');
            }

            return $this->createFolder($folderName, $mainFolderId);
        } catch (Exception $e) {
            Log::error('Google Drive create subfolder error: ' . $e->getMessage());
            return null;
        }
    }

    public function createFolder(string $folderName, ?string $parentId = null): ?string
    {
        try {
            // Ensure token is valid before operation
            if (!$this->ensureValidToken()) {
                throw new Exception('Google Drive token is invalid');
            }

            $fileMetadata = new DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

            if ($parentId) {
                $fileMetadata->setParents([$parentId]);
            }

            $folder = $this->driveService->files->create($fileMetadata, [
                'fields' => 'id, name, webViewLink'
            ]);

            return $folder->id;
        } catch (Exception $e) {
            Log::error('Google Drive create folder error: ' . $e->getMessage());
            return null;
        }
    }

    public function setupFolders(string $mainFolderId): array
    {
        try {
            $folders = [];

            $penelitianId = $this->createFolder('Penelitian', $mainFolderId);
            $pengabdianId = $this->createFolder('Pengabdian', $mainFolderId);
            $dokumentasiId = $this->createFolder('Dokumentasi', $mainFolderId);

            if ($penelitianId && $pengabdianId && $dokumentasiId) {
                $this->settings->update([
                    'main_folder_id' => $mainFolderId,
                    'penelitian_folder_id' => $penelitianId,
                    'pengabdian_folder_id' => $pengabdianId,
                    'dokumentasi_folder_id' => $dokumentasiId,
                    'is_configured' => true,
                ]);

                $folders = [
                    'main' => $mainFolderId,
                    'penelitian' => $penelitianId,
                    'pengabdian' => $pengabdianId,
                    'dokumentasi' => $dokumentasiId,
                ];
            }

            return $folders;
        } catch (Exception $e) {
            Log::error('Google Drive setup folders error: ' . $e->getMessage());
            return [];
        }
    }

    public function uploadFile(string $filePath, string $fileName, string $folderId): ?array
    {
        try {
            // Ensure token is valid before operation
            if (!$this->ensureValidToken()) {
                throw new Exception('Google Drive token is invalid');
            }

            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);

            $file = $this->driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, webContentLink'
            ]);

            Log::info('File uploaded to Google Drive successfully', [
                'file_id' => $file->id,
                'file_name' => $fileName,
            ]);

            return [
                'file_id' => $file->id,
                'file_url' => $file->webViewLink,
                'download_url' => $file->webContentLink,
            ];
        } catch (Exception $e) {
            Log::error('Google Drive upload error: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteFile(string $fileId): bool
    {
        try {
            // Ensure token is valid before operation
            if (!$this->ensureValidToken()) {
                throw new Exception('Google Drive token is invalid');
            }

            $this->driveService->files->delete($fileId);
            Log::info('File deleted from Google Drive successfully', ['file_id' => $fileId]);
            return true;
        } catch (Exception $e) {
            Log::error('Google Drive delete error: ' . $e->getMessage());
            return false;
        }
    }

    public function getFolderIdByType(string $type): ?string
    {
        if (!$this->settings) {
            return null;
        }

        return match($type) {
            'penelitian' => $this->settings->penelitian_folder_id,
            'pengabdian' => $this->settings->pengabdian_folder_id,
            'dokumentasi' => $this->settings->dokumentasi_folder_id,
            default => null,
        };
    }

    public function isConfigured(): bool
    {
        return $this->settings && 
               $this->settings->isConnected() && 
               $this->settings->isFolderConfigured();
    }

    public function getSettings(): ?CloudStorageSetting
    {
        return $this->settings;
    }
}
