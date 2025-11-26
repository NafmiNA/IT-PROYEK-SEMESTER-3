# STRUKTUR FOLDER PENYIMPANAN FILE

## Root Folder: SIDOPPAN

Semua file dokumentasi dan laporan tersimpan dalam folder root **SIDOPPAN** di `/storage/app/public/SIDOPPAN/`

## Struktur Lengkap:

```
storage/app/public/SIDOPPAN/
├── Penelitian/
│   ├── {id}/
│   │   ├── laporan/
│   │   │   └── {unique_id}_{filename}.pdf
│   │   └── dokumentasi/
│   │       └── {unique_id}_{filename}.pdf
│   └── Dokumen/
│       └── {unique_id}_{filename}.pdf
│
├── Pengabdian/
│   └── {id}/
│       └── dokumentasi/
│           └── {unique_id}_{filename}.pdf
│
└── Dokumentasi/
    └── {unique_id}_{filename}.pdf
```

## Contoh Path:

### Penelitian
- Laporan: `SIDOPPAN/Penelitian/1/laporan/692716c0831be8_Laporan.pdf`
- Dokumentasi (Dosen): `SIDOPPAN/Penelitian/1/dokumentasi/692716c0831be8_file1.pdf`
- Dokumentasi (Mahasiswa): `SIDOPPAN/Penelitian/1/dokumentasi/692716c0831be9_file2.pdf`
- Dokumen Penelitian (standalone): `SIDOPPAN/Penelitian/Dokumen/692716c0831be8_dokumen.pdf`

### Pengabdian
- Dokumentasi: `SIDOPPAN/Pengabdian/4/dokumentasi/692716c0831be8_Bukti.png`

### Dokumentasi Standalone
- Dokumentasi Umum: `SIDOPPAN/Dokumentasi/692716c0831be8_dokumen.pdf`

## Controllers yang Menggunakan Struktur Ini:

1. **MahasiswaDokumentasiController** - Upload dokumentasi mahasiswa (terkait penelitian/pengabdian)
2. **MahasiswaDashboardController** - Upload dokumentasi standalone
3. **MahasiswaController** - Upload dokumentasi mahasiswa
4. **DosenDokumentasiController** - Upload dokumentasi dosen
5. **AdminDokumentasiController** - Upload dokumentasi admin
6. **DosenPenelitianController** - Upload laporan penelitian
7. **DosenPengabdianController** - Upload dokumentasi pengabdian
8. **AdminPenelitianController** - Upload laporan penelitian (admin)
9. **AdminPengabdianController** - Upload dokumentasi pengabdian (admin)
10. **PenelitianController** - Upload dokumen penelitian standalone

## Keuntungan Struktur Ini:

1. ✅ **Konsisten** - Semua role menggunakan struktur yang sama
2. ✅ **Terorganisir** - Mudah menemukan file berdasarkan ID penelitian/pengabdian
3. ✅ **Scalable** - Bisa menambah subfolder baru tanpa konflik
4. ✅ **Clean** - Tidak ada file berserakan di root folder
5. ✅ **Compatible** - Siap untuk integrasi Google Drive dengan struktur folder yang sama

## URL Akses File:

File dapat diakses via URL:
```
http://127.0.0.1:8000/storage/SIDOPPAN/Penelitian/1/laporan/filename.pdf
http://127.0.0.1:8000/storage/SIDOPPAN/Pengabdian/4/dokumentasi/filename.png
```

## Migrasi File Lama (Jika Ada):

Jika ada file yang tersimpan di struktur lama, Anda bisa memindahkannya secara manual atau membiarkannya (tidak akan konflik karena path berbeda).
