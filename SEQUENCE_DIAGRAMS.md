# Sequence Diagrams - SIDOPPAN (Sistem Dokumentasi Penelitian, Pengabdian, Prestasi)

## 1. Authentication & Role-Based Redirect Flow

```mermaid
sequenceDiagram
    actor User
    participant Browser
    participant AuthController
    participant Middleware
    participant Database
    participant Dashboard

    User->>Browser: Access /
    Browser->>AuthController: Redirect to /login
    User->>Browser: Submit credentials (email, password)
    Browser->>AuthController: POST /login
    AuthController->>Database: Verify credentials
    Database-->>AuthController: User data (id, email, role)
    
    alt Valid Credentials
        AuthController->>Browser: Set session & cookies
        Browser->>Middleware: GET /dashboard
        Middleware->>Database: Check user role
        
        alt Role = Admin
            Database-->>Middleware: role: admin
            Middleware->>Dashboard: Redirect to /admin/dashboard
            Dashboard-->>Browser: Admin Dashboard View
        else Role = Dosen
            Database-->>Middleware: role: dosen
            Middleware->>Dashboard: Redirect to /dosen/dashboard
            Dashboard-->>Browser: Dosen Dashboard View
        else Role = Mahasiswa
            Database-->>Middleware: role: mahasiswa
            Middleware->>Dashboard: Redirect to /mahasiswa/dashboard
            Dashboard-->>Browser: Mahasiswa Dashboard View
        else Unknown Role
            Middleware->>AuthController: Logout
            AuthController->>Browser: Redirect to /login with error
        end
    else Invalid Credentials
        AuthController-->>Browser: Login error message
    end
```

---

## 2. Dosen - Create Penelitian with Google Drive Upload

```mermaid
sequenceDiagram
    actor Dosen
    participant Browser
    participant PenelitianController
    participant GoogleDriveService
    participant GoogleDriveAPI
    participant Database

    Dosen->>Browser: Navigate to /dosen/penelitian/create
    Browser->>PenelitianController: GET create()
    PenelitianController->>Database: Fetch dosens & mahasiswa list
    Database-->>PenelitianController: Return data
    PenelitianController-->>Browser: Show create form

    Dosen->>Browser: Fill form (judul, tahun, skema, dana, ketua, anggota)
    Dosen->>Browser: Upload file (laporan_jurnal.pdf)
    Browser->>PenelitianController: POST store() with data & file
    
    PenelitianController->>PenelitianController: Validate request
    PenelitianController->>Database: Begin transaction
    
    PenelitianController->>Database: Create penelitian record
    Database-->>PenelitianController: penelitian_id
    
    PenelitianController->>Database: Sync anggota (pivot table)
    PenelitianController->>Database: Sync mahasiswa (pivot table)
    
    alt File uploaded
        PenelitianController->>GoogleDriveService: upload(file, folder='Penelitian/{id}')
        GoogleDriveService->>GoogleDriveService: Check if main folder exists
        GoogleDriveService->>GoogleDriveAPI: Create folder structure
        GoogleDriveAPI-->>GoogleDriveService: folder_id
        
        GoogleDriveService->>GoogleDriveAPI: Upload file to folder
        GoogleDriveAPI-->>GoogleDriveService: file_id, file_url
        
        GoogleDriveService-->>PenelitianController: Return upload result
        PenelitianController->>Database: Create dokumentasi record (gdrive_path, google_id, google_url)
    end
    
    PenelitianController->>Database: Commit transaction
    Database-->>PenelitianController: Success
    PenelitianController-->>Browser: Redirect to /dosen/penelitian with success message
    Browser-->>Dosen: Show penelitian list with new entry
```

---

## 3. Mahasiswa - Upload Dokumentasi

```mermaid
sequenceDiagram
    actor Mahasiswa
    participant Browser
    participant DokumentasiController
    participant GoogleDriveService
    participant GoogleDriveAPI
    participant Database

    Mahasiswa->>Browser: Navigate to /mahasiswa/dokumentasi
    Browser->>DokumentasiController: GET index()
    DokumentasiController->>Database: Fetch mahasiswa by email
    Database-->>DokumentasiController: mahasiswa_id
    
    DokumentasiController->>Database: Get penelitian & pengabdian IDs
    Database-->>DokumentasiController: penelitian_ids, pengabdian_ids
    
    DokumentasiController->>Database: Fetch dokumentasi list
    Database-->>DokumentasiController: dokumentasi records
    DokumentasiController-->>Browser: Show dokumentasi index

    Mahasiswa->>Browser: Click "Edit" on dokumentasi
    Browser->>DokumentasiController: GET edit(id)
    DokumentasiController->>Database: Find dokumentasi by id
    Database-->>DokumentasiController: dokumentasi record
    DokumentasiController->>DokumentasiController: Check ownership
    
    alt Authorized
        DokumentasiController-->>Browser: Show edit form
        
        Mahasiswa->>Browser: Upload new file & submit
        Browser->>DokumentasiController: PUT update(id) with file
        
        DokumentasiController->>DokumentasiController: Validate file
        
        alt Old file exists
            DokumentasiController->>GoogleDriveService: delete(old_google_id)
            GoogleDriveService->>GoogleDriveAPI: Delete old file
            GoogleDriveAPI-->>GoogleDriveService: Deleted
        end
        
        DokumentasiController->>GoogleDriveService: upload(new_file, folder)
        GoogleDriveService->>GoogleDriveAPI: Upload to Google Drive
        GoogleDriveAPI-->>GoogleDriveService: new_file_id, new_url
        
        GoogleDriveService-->>DokumentasiController: Upload result
        DokumentasiController->>Database: Update dokumentasi record
        Database-->>DokumentasiController: Updated
        
        DokumentasiController-->>Browser: Redirect with success message
        Browser-->>Mahasiswa: Show updated dokumentasi
    else Unauthorized
        DokumentasiController-->>Browser: 403 Forbidden
    end
```

---

## 4. Admin - Verify Penelitian Status

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant AdminPenelitianController
    participant Database
    participant EmailService

    Admin->>Browser: Navigate to /admin/penelitian
    Browser->>AdminPenelitianController: GET index()
    AdminPenelitianController->>Database: Fetch all penelitian with relations
    Database-->>AdminPenelitianController: penelitian list (with ketua, dosens, dokumentasi)
    AdminPenelitianController-->>Browser: Show penelitian table

    Admin->>Browser: Click "Verifikasi" button
    Browser->>AdminPenelitianController: PATCH updateStatus(penelitian_id)
    Browser->>AdminPenelitianController: Request with status: 'verified'
    
    AdminPenelitianController->>AdminPenelitianController: Validate status (pending/verified/rejected)
    AdminPenelitianController->>Database: Find penelitian by id
    Database-->>AdminPenelitianController: penelitian record
    
    AdminPenelitianController->>Database: Update status & catatan
    Database-->>AdminPenelitianController: Updated
    
    opt Send notification (if configured)
        AdminPenelitianController->>EmailService: Send status update email
        EmailService->>Database: Fetch ketua email
        Database-->>EmailService: ketua email
        EmailService-->>AdminPenelitianController: Email sent
    end
    
    AdminPenelitianController-->>Browser: Redirect with success message
    Browser-->>Admin: Show updated penelitian status
```

---

## 5. Google Drive Integration - Upload Flow

```mermaid
sequenceDiagram
    participant Controller
    participant GoogleDriveService
    participant GoogleClient
    participant GoogleDriveAPI
    participant Database

    Controller->>GoogleDriveService: upload(file, folder='Penelitian/123')
    
    GoogleDriveService->>GoogleDriveService: Check if credentials configured
    
    alt Credentials valid
        GoogleDriveService->>GoogleClient: Initialize with clientId, clientSecret, refreshToken
        GoogleClient->>GoogleDriveAPI: Authenticate
        GoogleDriveAPI-->>GoogleClient: Access token
        
        GoogleDriveService->>GoogleDriveService: getOrCreateMainFolder()
        GoogleDriveService->>GoogleDriveAPI: Search for "Integrasi Sistem PBL Drive"
        
        alt Folder exists
            GoogleDriveAPI-->>GoogleDriveService: main_folder_id
        else Folder not exists
            GoogleDriveService->>GoogleDriveAPI: Create main folder
            GoogleDriveAPI-->>GoogleDriveService: main_folder_id
        end
        
        GoogleDriveService->>GoogleDriveService: getOrCreateFolder(folder='Penelitian/123')
        GoogleDriveService->>GoogleDriveAPI: Search subfolder "Penelitian"
        
        alt Subfolder exists
            GoogleDriveAPI-->>GoogleDriveService: subfolder_id
        else Subfolder not exists
            GoogleDriveService->>GoogleDriveAPI: Create subfolder
            GoogleDriveAPI-->>GoogleDriveService: subfolder_id
        end
        
        GoogleDriveService->>GoogleDriveAPI: Upload file to subfolder
        GoogleDriveAPI-->>GoogleDriveService: file_id, webViewLink
        
        GoogleDriveService->>GoogleDriveService: Build result array
        GoogleDriveService-->>Controller: Return upload result
        note right of Controller: {<br/>  path: 'Penelitian/123/file.pdf',<br/>  google_id: 'abc123',<br/>  url: 'https://drive.google.com/...',<br/>  mime_type: 'application/pdf',<br/>  size: 1024<br/>}
        
        Controller->>Database: Save dokumentasi with google_id & url
        
    else Credentials invalid
        GoogleDriveService->>GoogleDriveService: Fallback to local storage
        GoogleDriveService->>Controller: Return local storage path
        note right of Controller: Falls back to /storage/app/uploads
    end
```

---

## 6. Admin - User Management (CRUD)

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant UserController
    participant Database
    participant PasswordHash

    Admin->>Browser: Navigate to /admin/users
    Browser->>UserController: GET index()
    UserController->>Database: Fetch all users with pagination
    Database-->>UserController: users list
    UserController-->>Browser: Show users table

    Admin->>Browser: Click "Create User"
    Browser->>UserController: GET create()
    UserController-->>Browser: Show user form

    Admin->>Browser: Fill form (name, email, password, role)
    Browser->>UserController: POST store()
    
    UserController->>UserController: Validate input
    UserController->>PasswordHash: Hash password
    PasswordHash-->>UserController: hashed_password
    
    UserController->>Database: Begin transaction
    UserController->>Database: Create user record
    Database-->>UserController: user_id
    
    alt Role = Dosen
        UserController->>Database: Create dosen record
    else Role = Mahasiswa
        UserController->>Database: Create mahasiswa record
    end
    
    UserController->>Database: Commit transaction
    Database-->>UserController: Success
    
    UserController-->>Browser: Redirect to /admin/users with success
    Browser-->>Admin: Show updated users list
```

---

## 7. Dosen Dashboard - Statistics Overview

```mermaid
sequenceDiagram
    actor Dosen
    participant Browser
    participant DashboardController
    participant Database

    Dosen->>Browser: Navigate to /dosen/dashboard
    Browser->>DashboardController: GET index()
    
    DashboardController->>Database: Get dosen by auth email
    Database-->>DashboardController: dosen record
    
    par Fetch Statistics
        DashboardController->>Database: Count penelitian
        Database-->>DashboardController: total_penelitian
    and
        DashboardController->>Database: Count pengabdian
        Database-->>DashboardController: total_pengabdian
    and
        DashboardController->>Database: Count prestasi
        Database-->>DashboardController: total_prestasi
    and
        DashboardController->>Database: Get recent penelitian (limit 5)
        Database-->>DashboardController: recent_penelitian
    and
        DashboardController->>Database: Get recent pengabdian (limit 5)
        Database-->>DashboardController: recent_pengabdian
    end
    
    DashboardController->>DashboardController: Compile statistics
    DashboardController-->>Browser: Render dashboard with stats
    Browser-->>Dosen: Display dashboard cards & charts
```

---

## 8. Export Pengabdian to Excel

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant AdminPengabdianController
    participant PengabdianExport
    participant Database
    participant MaatwebsiteExcel

    Admin->>Browser: Click "Export Excel"
    Browser->>AdminPengabdianController: GET /admin/pengabdian/export/excel
    
    AdminPengabdianController->>PengabdianExport: new PengabdianExport()
    AdminPengabdianController->>MaatwebsiteExcel: Excel::download(export, 'pengabdian.xlsx')
    
    MaatwebsiteExcel->>PengabdianExport: Call collection()
    PengabdianExport->>Database: Fetch all pengabdian with relations
    Database-->>PengabdianExport: pengabdian records
    
    PengabdianExport->>PengabdianExport: Transform data to array
    PengabdianExport-->>MaatwebsiteExcel: Return collection
    
    MaatwebsiteExcel->>MaatwebsiteExcel: Generate Excel file
    MaatwebsiteExcel-->>AdminPengabdianController: Excel file binary
    
    AdminPengabdianController-->>Browser: Download response (pengabdian.xlsx)
    Browser-->>Admin: File downloaded
```

---

## Keterangan Flow Utama

### Role-Based Access:
- **Admin**: Kelola users, verify penelitian/pengabdian, view all data, export Excel
- **Dosen**: CRUD penelitian/pengabdian/prestasi, upload dokumentasi
- **Mahasiswa**: View & upload dokumentasi terkait penelitian/pengabdian mereka

### Google Drive Integration:
- Otomatis membuat folder "Integrasi Sistem PBL Drive"
- Struktur: `Main Folder / [Penelitian|Pengabdian|Mahasiswa] / {ID} / files`
- Fallback ke local storage jika credentials tidak ada
- Menyimpan `google_id` dan `google_url` di database

### Database Relations:
- **User** → (has one) → **Dosen** atau **Mahasiswa**
- **Penelitian** → (belongs to) → **Dosen** (ketua)
- **Penelitian** → (many-to-many) → **Dosen** (anggota)
- **Penelitian** → (many-to-many) → **Mahasiswa**
- **Penelitian** → (has many) → **Dokumentasi**
- **Pengabdian** → (similar structure)
- **Dokumentasi** → (polymorphic / belongs to) → **Penelitian** atau **Pengabdian**

---

## Tools Used:
- **Mermaid.js** for sequence diagrams
- **Laravel 10+** framework
- **Google Drive API** for file storage
- **Maatwebsite/Laravel-Excel** for export
- **MySQL/MariaDB** database
