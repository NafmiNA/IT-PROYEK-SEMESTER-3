<?php
/**
 * Google Drive Refresh Token Generator
 * 
 * Script untuk generate refresh token yang diperlukan untuk akses Google Drive API
 */

require 'vendor/autoload.php';

// ============================================
// KONFIGURASI - ISI DENGAN CREDENTIALS ANDA
// ============================================
$clientId = '124110049119-vdtgimfqjfa4vmaeftmjjugnclka49m5.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:8000'; // Sesuaikan dengan redirect URI yang didaftarkan

// ============================================
// JANGAN EDIT DIBAWAH INI
// ============================================

$client = new Google\Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope(Google\Service\Drive::DRIVE_FILE);
$client->addScope(Google\Service\Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setApprovalPrompt('force');

// Check if we have an authorization code from command line or GET parameter
$code = $_GET['code'] ?? $argv[1] ?? null;

if (!$code) {
    // Step 1: Generate authorization URL
    $authUrl = $client->createAuthUrl();
    
    echo "==============================================\n";
    echo "  GOOGLE DRIVE REFRESH TOKEN GENERATOR\n";
    echo "==============================================\n\n";
    echo "LANGKAH 1: Buka URL berikut di browser:\n\n";
    echo $authUrl . "\n\n";
    echo "LANGKAH 2: Login dengan akun Google yang akan digunakan\n";
    echo "LANGKAH 3: Izinkan akses aplikasi ke Google Drive\n";
    echo "LANGKAH 4: Anda akan melihat error 'This site can't be reached'\n";
    echo "           Ini NORMAL! Jangan tutup halaman tersebut.\n\n";
    echo "LANGKAH 5: Copy SELURUH URL dari address bar browser\n";
    echo "           (Contoh: http://localhost:9999/callback?code=KODE_PANJANG...)\n\n";
    echo "LANGKAH 6: Extract 'code' parameter dari URL tersebut\n";
    echo "           (Ambil bagian setelah 'code=' sampai sebelum '&' atau end)\n\n";
    echo "LANGKAH 7: Jalankan script ini lagi dengan code:\n";
    echo "           php generate_token.php PASTE_CODE_DISINI\n\n";
    echo "==============================================\n";
    exit;
}

// Step 2: Exchange authorization code for tokens
echo "==============================================\n";
echo "  PROCESSING...\n";
echo "==============================================\n\n";

try {
    $token = $client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($token['error'])) {
            echo "❌ ERROR: " . $token['error_description'] . "\n\n";
            echo "Kemungkinan penyebab:\n";
            echo "1. Authorization code sudah expired (hanya valid 10 menit)\n";
            echo "2. Authorization code sudah digunakan\n";
            echo "3. Client ID atau Client Secret salah\n\n";
            echo "Solusi: Jalankan script lagi dari awal untuk mendapatkan code baru\n\n";
            exit;
        }
        
        if (!isset($token['refresh_token'])) {
            echo "❌ ERROR: Refresh token tidak ditemukan\n\n";
            echo "Kemungkinan penyebab:\n";
            echo "1. Aplikasi sudah pernah di-authorize sebelumnya\n";
            echo "2. Perlu revoke access terlebih dahulu\n\n";
            echo "Solusi:\n";
            echo "1. Buka: https://myaccount.google.com/permissions\n";
            echo "2. Cari aplikasi Laravel P3M System (atau nama aplikasi Anda)\n";
            echo "3. Klik dan pilih 'Remove Access'\n";
            echo "4. Jalankan script ini lagi dari awal\n\n";
            exit;
        }
        
        echo "✅ SUCCESS! Refresh Token berhasil di-generate!\n\n";
        echo "==============================================\n";
        echo "  COPY REFRESH TOKEN BERIKUT KE .env FILE\n";
        echo "==============================================\n\n";
        echo "GOOGLE_DRIVE_REFRESH_TOKEN=" . $token['refresh_token'] . "\n\n";
        echo "==============================================\n\n";
        echo "LANGKAH SELANJUTNYA:\n";
        echo "1. Copy refresh token diatas\n";
        echo "2. Paste ke file .env sebagai GOOGLE_DRIVE_REFRESH_TOKEN\n";
        echo "3. Pastikan juga sudah mengisi:\n";
        echo "   - GOOGLE_DRIVE_CLIENT_ID\n";
        echo "   - GOOGLE_DRIVE_CLIENT_SECRET\n";
        echo "4. Jalankan: php artisan config:clear\n";
        echo "5. Test upload file!\n\n";
        echo "CATATAN:\n";
        echo "- Simpan refresh token dengan aman\n";
        echo "- JANGAN commit file .env ke Git\n";
        echo "- Refresh token tidak expired kecuali dicabut akses\n\n";
        echo "==============================================\n";
        
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
    echo "Periksa kembali Client ID dan Client Secret Anda\n\n";
    exit;
}
