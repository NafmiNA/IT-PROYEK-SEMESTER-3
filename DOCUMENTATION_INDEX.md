# 📚 SIDOPPAN - System Documentation Index

## 📖 Daftar Dokumentasi

Dokumentasi lengkap sistem **SIDOPPAN** (Sistem Dokumentasi Penelitian, Pengabdian, dan Prestasi) mencakup:

### 1. [SEQUENCE_DIAGRAMS.md](./SEQUENCE_DIAGRAMS.md) - **Sequence Diagrams**
Menjelaskan alur interaksi antar komponen sistem untuk setiap use case utama.

**Diagram yang tersedia:**
- ✅ Authentication & Role-Based Redirect Flow
- ✅ Dosen - Create Penelitian with Google Drive Upload
- ✅ Mahasiswa - Upload Dokumentasi
- ✅ Admin - Verify Penelitian Status
- ✅ Google Drive Integration - Upload Flow
- ✅ Admin - User Management (CRUD)
- ✅ Dosen Dashboard - Statistics Overview
- ✅ Export Pengabdian to Excel

**Format**: Mermaid.js Sequence Diagram  
**Cara Melihat**: Buka file di GitHub/GitLab atau gunakan Mermaid Live Editor

---

### 2. [DATABASE_DIAGRAM.md](./DATABASE_DIAGRAM.md) - **Database Schema & ERD**
Menjelaskan struktur database, relasi antar tabel, dan storage architecture.

**Isi:**
- ✅ Entity Relationship Diagram (ERD)
- ✅ Database Indexes & Foreign Keys
- ✅ Data Flow Summary
- ✅ Google Drive Storage Structure
- ✅ Sample Data Examples

**Tables:**
- `users` - User accounts (admin, dosen, mahasiswa)
- `dosens` - Lecturer profiles
- `mahasiswa` - Student profiles
- `penelitian` - Research projects
- `pengabdian` - Community service projects
- `prestasi_dosen` - Lecturer achievements
- `dokumentasi` - File documentation (links to Google Drive)
- Pivot tables: `dosen_penelitian`, `dosen_pengabdian`, `penelitian_mahasiswa`, `pengabdian_mahasiswa`

---

### 3. [USE_CASE_DIAGRAM.md](./USE_CASE_DIAGRAM.md) - **Use Case Diagrams**
Menjelaskan fungsi-fungsi sistem yang dapat diakses oleh setiap role.

**Isi:**
- ✅ Complete System Use Case Diagram (Mermaid.js)
- ✅ Detailed Use Case Specifications
- ✅ Use Case Relationships (Include/Extend/Generalization)
- ✅ Actor Descriptions

**Actors:**
- **Admin** - 17 use cases (user management, verification, export)
- **Dosen** - 13 use cases (penelitian, pengabdian, prestasi)
- **Mahasiswa** - 7 use cases (dokumentasi, view involvement)
- **System Actors**: Google Drive API, Email Service

---

## 🚀 Cara Menggunakan Dokumentasi

### Untuk Developers:
1. **Pahami Alur Sistem**: Baca `SEQUENCE_DIAGRAMS.md` untuk memahami flow antar komponen
2. **Pahami Database**: Baca `DATABASE_DIAGRAM.md` untuk memahami struktur data
3. **Pahami Fitur**: Baca `USE_CASE_DIAGRAM.md` untuk memahami requirement fungsional

### Untuk Project Manager:
1. **Review Use Cases**: Gunakan `USE_CASE_DIAGRAM.md` untuk tracking feature development
2. **Verify Data Model**: Gunakan `DATABASE_DIAGRAM.md` untuk validasi business logic
3. **Check Integration**: Gunakan `SEQUENCE_DIAGRAMS.md` untuk memahami third-party integration

### Untuk Stakeholders:
1. **Overview System**: Lihat use case diagram untuk memahami apa yang bisa dilakukan sistem
2. **Data Security**: Review database diagram untuk memahami penyimpanan data
3. **User Experience**: Lihat sequence diagram untuk memahami alur interaksi user

---

## 🔧 Tools & Technologies

### Backend:
- **Laravel 10+** - PHP Framework
- **MySQL/MariaDB** - Relational Database
- **Google Drive API** - Cloud Storage
- **Maatwebsite/Laravel-Excel** - Excel Export

### Frontend:
- **Blade Templates** - Laravel Templating Engine
- **Alpine.js** - Lightweight JavaScript Framework
- **Tailwind CSS** - Utility-first CSS Framework

### Authentication:
- **Laravel Breeze** - Authentication Scaffolding
- **Google OAuth 2.0** - Social Login (SSO)

### Diagram Tools:
- **Mermaid.js** - Diagram Generation

---

## 📊 Key System Features

### 1. Role-Based Access Control (RBAC)
- **Admin**: Full system access, user management, verification
- **Dosen**: Manage penelitian/pengabdian/prestasi
- **Mahasiswa**: Upload dokumentasi, view involvement

### 2. Google Drive Integration
- Auto upload files to Google Drive
- Auto folder creation with organized structure
- Fallback to local storage if credentials missing
- Store `google_id` and `google_url` in database

### 3. Collaborative Research Management
- Multiple dosens as team members (many-to-many)
- Multiple mahasiswa participants (many-to-many)
- Track ketua (leader) vs anggota (members)

### 4. Document Management
- Support multiple file formats (PDF, DOC, JPG, PNG)
- File size validation (max 5MB for dosen, 4MB for mahasiswa)
- Version control via update/delete functions
- Direct link to Google Drive files

### 5. Verification Workflow
- Admin can verify or reject penelitian/pengabdian
- Status tracking: `pending` → `verified` / `rejected`
- Add notes (catatan) for feedback
- Optional email notifications

### 6. Export & Reporting
- Export pengabdian to Excel format
- Include all relations (ketua, anggota, mahasiswa, dokumentasi)
- Timestamp-based file naming

---

## 📁 Folder Structure

```
/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Dosen/          # Dosen controllers
│   │   │   ├── Mahasiswa/      # Mahasiswa controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   └── Middleware/
│   ├── Models/                 # Eloquent models
│   └── Services/
│       └── GoogleDriveService.php
├── resources/
│   └── views/
│       ├── admin/              # Admin views
│       ├── dosen/              # Dosen views
│       ├── mahasiswa/          # Mahasiswa views
│       └── layouts/
│           ├── app.blade.php   # Dosen & Admin layout
│           └── mahasiswa.blade.php
├── routes/
│   ├── web.php                 # Main routes
│   └── auth.php                # Authentication routes
├── database/
│   └── migrations/             # Database migrations
└── Documentation/
    ├── SEQUENCE_DIAGRAMS.md
    ├── DATABASE_DIAGRAM.md
    ├── USE_CASE_DIAGRAM.md
    └── DOCUMENTATION_INDEX.md  # This file
```

---

## 🔐 Security Features

1. **Authentication**: Laravel Breeze with email verification
2. **Authorization**: Policy-based access control per resource
3. **CSRF Protection**: All forms protected with CSRF tokens
4. **Password Hashing**: Bcrypt password hashing
5. **SQL Injection Prevention**: Eloquent ORM parameterized queries
6. **File Upload Validation**: MIME type and size validation
7. **Role-based Middleware**: Prevent unauthorized access to routes

---

## 🌐 Google Drive Configuration

### Required Environment Variables:
```env
GOOGLE_DRIVE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=your-client-secret
GOOGLE_DRIVE_REFRESH_TOKEN=your-refresh-token
GOOGLE_DRIVE_FOLDER_ID=main-folder-id  # Optional, auto-created
```

### Folder Naming Convention:
```
Integrasi Sistem PBL Drive/
  ├── Penelitian/{penelitian_id}/
  ├── Pengabdian/{pengabdian_id}/
  └── Mahasiswa/
      ├── Penelitian/{penelitian_id}/
      └── Pengabdian/{pengabdian_id}/
```

---

## 📝 Code Conventions

### Controllers:
- Use resource controllers for CRUD operations
- Inject dependencies via constructor
- Use form request validation when complex
- Return views or redirect with flash messages

### Models:
- Define relationships explicitly
- Use casts for data type conversion
- Implement soft deletes where appropriate
- Add fillable/guarded properties

### Views:
- Use Blade components for reusability
- Follow Tailwind utility-first approach
- Implement Alpine.js for interactivity
- Keep logic minimal in views

### Database:
- Use migrations for all schema changes
- Add indexes for foreign keys and frequently queried columns
- Use meaningful column names
- Follow Laravel naming conventions

---

## 🐛 Troubleshooting

### Google Drive Upload Fails:
1. Check credentials in `.env`
2. Verify refresh token is valid
3. Check Google Drive API quota
4. Review logs in `storage/logs/laravel.log`

### Authentication Issues:
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Clear sessions: `php artisan session:flush`
4. Check database migrations

### File Upload Issues:
1. Check `php.ini` `upload_max_filesize` and `post_max_size`
2. Verify storage permissions: `storage/app` must be writable
3. Check file validation rules in controller

---

## 📞 Support & Maintenance

### For Developers:
- Read sequence diagrams before making changes
- Follow existing code patterns
- Write migrations for database changes
- Test file uploads with Google Drive integration

### For Database Admin:
- Backup database regularly
- Monitor Google Drive quota usage
- Review slow queries and add indexes
- Keep documentation updated

---

## 🎯 Future Enhancements

Potential features untuk pengembangan selanjutnya:
- [ ] Real-time notifications dengan WebSocket
- [ ] Advanced search & filtering
- [ ] Data visualization dashboard
- [ ] Mobile app integration
- [ ] Bulk import from Excel
- [ ] Automatic backup to multiple cloud storage
- [ ] Version control untuk dokumentasi
- [ ] Collaborative editing untuk laporan

---

## 📚 References

- [Laravel Documentation](https://laravel.com/docs)
- [Google Drive API Documentation](https://developers.google.com/drive)
- [Mermaid.js Documentation](https://mermaid.js.org/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)
- [Alpine.js Documentation](https://alpinejs.dev/)

---

**Last Updated**: 2025-11-19  
**Version**: 1.0  
**Maintainer**: Development Team
