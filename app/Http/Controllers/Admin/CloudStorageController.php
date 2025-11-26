<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GoogleDriveService;
use App\Models\CloudStorageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CloudStorageController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function settings()
    {
        $settings = $this->driveService->getSettings();
        
        return view('admin.cloud-storage.settings', [
            'settings' => $settings,
            'isConnected' => $settings && $settings->isConnected(),
            'isConfigured' => $this->driveService->isConfigured(),
        ]);
    }

    public function connect()
    {
        try {
            $authUrl = $this->driveService->getAuthUrl();
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Cloud storage connect error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghubungkan ke Google Drive: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        try {
            $code = $request->get('code');
            
            if (!$code) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Kode autentikasi tidak ditemukan');
            }

            $success = $this->driveService->authenticate($code);
            
            if ($success) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil terhubung ke Google Drive!');
            } else {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Gagal menghubungkan ke Google Drive');
            }
        } catch (\Exception $e) {
            Log::error('Cloud storage callback error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        try {
            $success = $this->driveService->disconnect();
            
            if ($success) {
                return back()->with('success', 'Berhasil memutuskan koneksi Google Drive');
            } else {
                return back()->with('error', 'Gagal memutuskan koneksi');
            }
        } catch (\Exception $e) {
            Log::error('Cloud storage disconnect error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function saveFolders(Request $request)
    {
        try {
            $request->validate([
                'main_folder_id' => 'required|string',
                'main_folder_name' => 'required|string',
            ]);

            $mainFolderId = $request->main_folder_id;
            $mainFolderName = $request->main_folder_name;

            // Setup subfolders
            $folders = $this->driveService->setupFolders($mainFolderId);

            if (empty($folders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat folder'
                ], 500);
            }

            // Update main folder name
            $settings = CloudStorageSetting::first();
            $settings->main_folder_name = $mainFolderName;
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Folder berhasil dikonfigurasi',
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            Log::error('Cloud storage save folders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatus()
    {
        try {
            $settings = $this->driveService->getSettings();
            
            return response()->json([
                'success' => true,
                'isConnected' => $settings && $settings->isConnected(),
                'isConfigured' => $this->driveService->isConfigured(),
                'settings' => $settings ? [
                    'email' => $settings->email ?? 'N/A',
                    'main_folder_name' => $settings->main_folder_name,
                    'is_configured' => $settings->is_configured,
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
