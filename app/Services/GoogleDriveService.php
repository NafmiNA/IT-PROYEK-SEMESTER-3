<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected $service;
    protected $folderId;
    protected $useGoogleDrive = true;

    public function __construct()
    {
        // Check if Google Drive credentials are configured
        if (empty(config('filesystems.disks.google.clientId')) || 
            empty(config('filesystems.disks.google.clientSecret')) || 
            empty(config('filesystems.disks.google.refreshToken'))) {
            $this->useGoogleDrive = false;
            \Log::warning('Google Drive credentials not configured, using local storage');
        } else {
            try {
                $client = new Client();
                $client->setClientId(config('filesystems.disks.google.clientId'));
                $client->setClientSecret(config('filesystems.disks.google.clientSecret'));
                $client->refreshToken(config('filesystems.disks.google.refreshToken'));
                
                $this->service = new Drive($client);
                $this->folderId = config('filesystems.disks.google.folder');
                
                \Log::info('Google Drive initialized successfully');
            } catch (\Exception $e) {
                $this->useGoogleDrive = false;
                \Log::error('Google Drive initialization failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Upload file to Google Drive
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array ['path' => string, 'url' => string, 'id' => string]
     */
    public function upload(UploadedFile $file, string $folder = '')
    {
        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
        
        if ($this->useGoogleDrive && $this->service) {
            try {
                // Create folder structure if needed
                $parentId = $this->folderId ?: null;
                
                if (!empty($folder)) {
                    $parentId = $this->createFolderPath($folder, $parentId);
                }
                
                // Prepare file metadata
                $fileMetadata = new DriveFile([
                    'name' => $filename,
                ]);
                
                if ($parentId) {
                    $fileMetadata->setParents([$parentId]);
                }
                
                // Upload file
                $content = file_get_contents($file->getRealPath());
                $uploadedFile = $this->service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => $file->getMimeType(),
                    'uploadType' => 'multipart',
                    'fields' => 'id,name,webViewLink,webContentLink',
                ]);
                
                $path = $folder ? "{$folder}/{$filename}" : $filename;
                
                \Log::info('File uploaded to Google Drive: ' . $uploadedFile->id);
                
                return [
                    'path' => $path,
                    'google_id' => $uploadedFile->id,
                    'url' => $uploadedFile->webViewLink ?? $uploadedFile->webContentLink,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            } catch (\Exception $e) {
                \Log::error('Google Drive upload failed, falling back to local: ' . $e->getMessage());
                $this->useGoogleDrive = false;
            }
        }
        
        // Local storage fallback
        $path = Storage::disk('public')->putFileAs($folder, $file, $filename);
        
        return [
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }
    
    /**
     * Create folder path in Google Drive
     */
    protected function createFolderPath(string $path, ?string $parentId = null): ?string
    {
        $folders = explode('/', trim($path, '/'));
        $currentParentId = $parentId;
        
        foreach ($folders as $folderName) {
            // Check if folder exists
            $query = "name='" . addslashes($folderName) . "' and mimeType='application/vnd.google-apps.folder' and trashed=false";
            
            if ($currentParentId) {
                $query .= " and '" . $currentParentId . "' in parents";
            }
            
            $results = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name)',
                'pageSize' => 1,
            ]);
            
            if (count($results->getFiles()) > 0) {
                // Folder exists
                $currentParentId = $results->getFiles()[0]->id;
            } else {
                // Create folder
                $folderMetadata = new DriveFile([
                    'name' => $folderName,
                    'mimeType' => 'application/vnd.google-apps.folder',
                ]);
                
                if ($currentParentId) {
                    $folderMetadata->setParents([$currentParentId]);
                }
                
                $folder = $this->service->files->create($folderMetadata, [
                    'fields' => 'id',
                ]);
                
                $currentParentId = $folder->id;
                \Log::info('Created folder in Google Drive: ' . $folderName . ' (ID: ' . $currentParentId . ')');
            }
        }
        
        return $currentParentId;
    }

    /**
     * Delete file from Google Drive
     *
     * @param string $pathOrId
     * @return bool
     */
    public function delete(string $pathOrId): bool
    {
        if (!$this->useGoogleDrive || !$this->service) {
            // Try local storage
            try {
                if (Storage::disk('public')->exists($pathOrId)) {
                    Storage::disk('public')->delete($pathOrId);
                    return true;
                }
            } catch (\Exception $e) {
                \Log::error('Local storage delete error: ' . $e->getMessage());
            }
            return false;
        }
        
        try {
            // Try to delete by Google Drive file ID
            $this->service->files->delete($pathOrId);
            \Log::info('File deleted from Google Drive: ' . $pathOrId);
            return true;
        } catch (\Exception $e) {
            \Log::error('GoogleDrive delete error: ' . $e->getMessage());
            
            // Fallback: try local storage
            try {
                if (Storage::disk('public')->exists($pathOrId)) {
                    Storage::disk('public')->delete($pathOrId);
                    return true;
                }
            } catch (\Exception $e2) {
                \Log::error('Local storage delete error: ' . $e2->getMessage());
            }
        }
        
        return false;
    }

    /**
     * Get file URL from Google Drive
     *
     * @param string $pathOrId
     * @return string|null
     */
    public function getUrl(string $pathOrId): ?string
    {
        if (!$this->useGoogleDrive || !$this->service) {
            // Fallback to local storage
            return Storage::disk('public')->url($pathOrId);
        }
        
        try {
            // Try to get file metadata by ID
            $file = $this->service->files->get($pathOrId, [
                'fields' => 'webViewLink,webContentLink',
            ]);
            
            return $file->webViewLink ?? $file->webContentLink;
        } catch (\Exception $e) {
            \Log::error('GoogleDrive getUrl error: ' . $e->getMessage());
            // Fallback to local storage
            return Storage::disk('public')->url($pathOrId);
        }
    }
}
