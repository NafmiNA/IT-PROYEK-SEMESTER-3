# AUTO-REFRESH TOKEN - GOOGLE DRIVE

## ✅ Fitur Auto-Refresh Token Sudah Diimplementasikan!

### **Bagaimana Cara Kerjanya:**

1. **Setiap kali ada operasi ke Google Drive** (upload, delete, create folder):
   - System otomatis cek apakah token masih valid
   - Jika token sudah expired, otomatis refresh menggunakan refresh_token
   - Setelah refresh, token baru disimpan ke database
   - Operasi dilanjutkan dengan token baru

2. **Flow Auto-Refresh:**
```
User Upload File
    ↓
GoogleDriveService::uploadFile()
    ↓
ensureValidToken() ← Cek token expired?
    ↓
    ├── Token valid → Lanjut upload
    │
    └── Token expired → refreshToken()
            ↓
        Get new access token dari Google
            ↓
        Simpan token baru ke database
            ↓
        Update client dengan token baru
            ↓
        Lanjut upload dengan token baru
```

---

## 🔧 Method yang Dilengkapi Auto-Refresh:

1. **uploadFile()** - Upload file ke Google Drive
2. **deleteFile()** - Hapus file dari Google Drive
3. **createFolder()** - Buat folder baru di Google Drive

### **Method Helper:**

- **ensureValidToken()** - Cek dan refresh token jika perlu (dipanggil otomatis)
- **refreshToken()** - Refresh access token menggunakan refresh token (dipanggil otomatis)

---

## ⚙️ Cara Setup Awal:

### **1. Pastikan OAuth Redirect URI Sudah Dikonfigurasi:**

Di Google Cloud Console:
```
Authorized redirect URIs:
http://127.0.0.1:8000/admin/cloud-storage/callback
```

### **2. Connect Google Drive (Pertama Kali):**

```
1. Login sebagai Admin
2. Dashboard Admin → "Pengaturan Cloud Storage"
3. Click "Connect Google Drive"
4. Login dengan Gmail
5. Authorize aplikasi
```

**PENTING:** Saat pertama kali connect, pastikan aplikasi mendapat `refresh_token` dari Google. Ini hanya diberikan saat pertama kali user authorize ATAU saat menggunakan `prompt=consent`.

---

## 🔍 Cara Cek Status Token:

### **Via Command Line:**
```bash
cd /home/muhammadnaufalnijami/Github/IT-PROYEK-SEMESTER-3
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();
\$settings = \App\Models\CloudStorageSetting::first();
if (\$settings) {
    echo 'Access Token: ' . (\$settings->access_token ? 'EXISTS' : 'NULL') . PHP_EOL;
    echo 'Refresh Token: ' . (\$settings->refresh_token ? 'EXISTS' : 'NULL') . PHP_EOL;
    echo 'Token Expires At: ' . \$settings->token_expires_at . PHP_EOL;
    echo 'Is Expired: ' . (\$settings->isTokenExpired() ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'Not configured' . PHP_EOL;
}
"
```

---

## 📋 Logs untuk Monitoring:

Auto-refresh akan menulis log ke `storage/logs/laravel.log`:

```
[2025-11-26 15:00:00] local.INFO: Token expired, attempting to refresh...
[2025-11-26 15:00:01] local.INFO: Refreshing Google Drive access token...
[2025-11-26 15:00:02] local.INFO: Google Drive token refreshed successfully
[2025-11-26 15:00:03] local.INFO: File uploaded to Google Drive successfully {"file_id":"xxx","file_name":"test.pdf"}
```

### **Log Error (Jika Gagal):**
```
[2025-11-26 15:00:00] local.WARNING: Cannot refresh token: No refresh token available
[2025-11-26 15:00:01] local.ERROR: Google Drive token refresh error: Invalid refresh token
```

---

## 🚨 Troubleshooting:

### **1. Token Tidak Bisa Di-Refresh:**
```
Error: Cannot refresh token: No refresh token available
```

**Solusi:**
- Disconnect Google Drive dari dashboard
- Reconnect lagi (pastikan mendapat refresh_token baru)

**Kenapa terjadi:**
- Refresh token tidak tersimpan saat connect pertama kali
- User sudah pernah authorize, Google tidak memberikan refresh_token lagi

### **2. Invalid Refresh Token:**
```
Error: Google Drive token refresh error: Invalid refresh token
```

**Solusi:**
- Revoke akses aplikasi di Google Account Settings
- Reconnect dari dashboard (akan dapat refresh_token baru)

### **3. File Tidak Upload ke Google Drive:**
```
Cek log: storage/logs/laravel.log
```

Kemungkinan:
- Token expired dan refresh gagal → Reconnect
- Folder ID tidak ditemukan → Setup folder di dashboard
- Network error → Cek koneksi internet

---

## ✅ Testing Auto-Refresh:

### **Test Manual:**

1. **Expire token secara manual:**
```bash
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();
\$settings = \App\Models\CloudStorageSetting::first();
if (\$settings) {
    \$settings->update(['token_expires_at' => now()->subHour()]);
    echo 'Token expired manually for testing' . PHP_EOL;
}
"
```

2. **Upload file:**
   - Login sebagai Dosen/Mahasiswa
   - Upload dokumentasi baru
   - System akan otomatis refresh token
   - Cek log: `storage/logs/laravel.log`

3. **Cek Google Drive:**
   - File seharusnya muncul di Google Drive
   - Token di database sudah ter-update

---

## 📊 Database Schema:

Table: `cloud_storage_settings`

| Column | Type | Description |
|--------|------|-------------|
| access_token | text | OAuth access token (expires in 1 hour) |
| refresh_token | text | OAuth refresh token (permanent) |
| token_expires_at | timestamp | Waktu token akan expired |
| main_folder_id | string | ID folder SIDOPPAN di Google Drive |
| penelitian_folder_id | string | ID subfolder Penelitian |
| pengabdian_folder_id | string | ID subfolder Pengabdian |
| dokumentasi_folder_id | string | ID subfolder Dokumentasi |

---

## 🎯 Best Practices:

1. **Jangan hapus refresh_token** dari database
2. **Monitor logs** untuk memastikan auto-refresh bekerja
3. **Reconnect setiap 6 bulan** (refresh token Google bisa expired setelah 6 bulan tidak digunakan)
4. **Backup folder IDs** jika butuh setup ulang

---

## 📝 Summary:

✅ Auto-refresh token sudah diimplementasikan
✅ Token otomatis di-refresh saat expired
✅ User tidak perlu manual reconnect lagi
✅ Semua operasi Google Drive dilindungi dengan auto-refresh
✅ Logs lengkap untuk monitoring
✅ Error handling yang robust

**Sistem sekarang production-ready untuk Google Drive integration!** 🚀
