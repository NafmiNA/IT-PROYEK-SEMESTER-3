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
            // Setup default folders (SIDEPAN -> subfolders)
            $folders = $this->driveService->setupDefaultFolders();

            return redirect()->back()->with('success', 'Folder SIDEPAN dan sub-folder berhasil dibuat/dikonfigurasi!');
        } catch (\Exception $e) {
            Log::error('Cloud storage save folders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal konfigurasi folder: ' . $e->getMessage());
        }
    }

    public function createCustomFolder(Request $request)
    {
        try {
            $request->validate([
                'folder_name' => 'required|string|max:255',
            ]);

            $folderId = $this->driveService->createSubFolder($request->folder_name);

            if ($folderId) {
                return redirect()->back()->with('success', 'Folder "' . $request->folder_name . '" berhasil dibuat di dalam SIDEPAN.');
            } else {
                return redirect()->back()->with('error', 'Gagal membuat folder.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
