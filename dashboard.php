# 📁 STRUKTUR FOLDER & SETUP PROJECT

## Panduan Lengkap Setup PhotoBooth di XAMPP

---

## 🎯 STEP 1: Buat Folder Project

### Lokasi Default XAMPP:
```
Windows:  C:\xampp\htdocs\photobooth
Mac:      /Applications/XAMPP/htdocs/photobooth
Linux:    /opt/lampp/htdocs/photobooth
```

### Struktur Folder Lengkap:
```
photobooth/
├── admin/                    # Folder admin pages
│   ├── dashboard.php        # Dashboard utama admin
│   ├── users.php            # Kelola user
│   ├── photos.php           # Kelola foto
│   ├── orders.php           # Pesanan online
│   └── offline-orders.php   # Cetak langsung
│
├── config/                   # Configuration files
│   ├── database.php         # Database connection
│   └── midtrans.php         # Payment gateway config
│
├── css/                      # Stylesheets
│   └── style.css            # Main CSS (modern aesthetic)
│
├── js/                       # JavaScript files
│   ├── camera.js            # Photobooth camera & filters
│   ├── editor.js            # Photo editor functionality
│   └── main.js              # General scripts
│
├── uploads/                  # Folder untuk menyimpan foto
│   └── (photos akan disimpan di sini)
│
├── includes/                 # Helper functions
│   └── functions.php        # Database & auth functions
│
├── auth-check.php           # Session validation
├── login.php                # Login page
├── register.php             # Register page
├── index.php                # Main photobooth page
├── photobooth.php           # Photobooth interface (sama dengan index.php)
├── editor.php               # Photo editor
├── album.php                # User album
├── checkout.php             # Checkout page
├── order-history.php        # Order history
├── offline-print.php        # Admin offline print
├── print-preview.php        # Print preview
├── get-photo.php            # API get photo
├── upload.php               # Upload handler
├── delete.php               # Delete handler
├── process-payment.php      # Payment processor
├── midtrans-callback.php    # Midtrans webhook
├── logout.php               # Logout handler
├── database.sql             # Database schema (import ini!)
├── README.md                # Installation guide
└── .htaccess                # URL rewriting (optional)
```

---

## 📋 STEP 2: File-File Yang Harus Di-Copy

Salin file-file berikut ke folder `photobooth/` dengan struktur yang tepat:

### A. CONFIG FILES
```
photobooth/config/
├── database.php          ← Dari config_database.php
└── midtrans.php          ← Dari config_midtrans.php
```

### B. HELPER FILES
```
photobooth/includes/
└── functions.php         ← Dari includes_functions.php
```

### C. CSS FILES
```
photobooth/css/
└── style.css             ← Dari css_style.css
```

### D. JAVASCRIPT FILES
```
photobooth/js/
├── camera.js             ← Dari js_camera.js
└── editor.js             ← Dari js_editor.js
```

### E. PHP FILES (Root Directory)
```
photobooth/
├── login.php             ← Dari login.php
├── register.php          ← Dari register.php
├── index.php             ← Dari index.php
├── album.php             ← Dari album.php
├── logout.php            ← Dari logout.php
├── upload.php            ← Dari upload.php
└── database.sql          ← Dari database.sql
```

### F. ADMIN FILES
```
photobooth/admin/
└── dashboard.php         ← Dari admin_dashboard.php
```

---

## 🗂️ MEMBUAT FOLDER UPLOADS

### Important: Create this folder!

```bash
# Linux/Mac
mkdir -p photobooth/uploads
chmod 755 photobooth/uploads

# Windows
# Buat folder manual di photobooth/uploads
# Klik kanan → New → Folder → uploads
```

---

## 🔧 STEP 3: Setup Database

### A. Buka phpMyAdmin
```
1. Buka XAMPP Control Panel
2. Klik "Admin" button pada MySQL
   (atau buka browser: http://localhost/phpmyadmin)
```

### B. Create Database
```
1. Klik "New" di sisi kiri
2. Ketik nama: photobooth
3. Collation: utf8mb4_unicode_ci
4. Klik "Create"
```

### C. Import SQL
```
1. Select database "photobooth"
2. Klik tab "Import"
3. Klik "Choose File"
4. Pilih file: database.sql
5. Klik "Go"
```

**Jika berhasil, Anda akan melihat:**
- ✅ Semua tabel berhasil dibuat
- ✅ Default user test sudah ada

---

## 🔑 STEP 4: Konfigurasi Files

### A. Edit config/database.php
Sesuaikan dengan setup XAMPP Anda:

```php
// XAMPP Default (biasanya tidak perlu diubah)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Kosong untuk XAMPP default
define('DB_NAME', 'photobooth');
```

### B. Edit config/midtrans.php
Jika ingin menggunakan payment gateway:

```php
// Ganti dengan credentials Anda dari Midtrans
define('MIDTRANS_SERVER_KEY', 'SB-Mid-XXXXXXXXXXXXXX');
define('MIDTRANS_CLIENT_KEY', 'SB-Cli-XXXXXXXXXXXXXX');

// Atau gunakan sandbox dulu untuk testing
define('MIDTRANS_IS_PRODUCTION', false);
```

---

## ▶️ STEP 5: Jalankan Aplikasi

### A. Start XAMPP
```
1. Buka XAMPP Control Panel
2. Start Apache
3. Start MySQL
```

### B. Akses Aplikasi
```
Browser: http://localhost/photobooth

Atau akses direct:
- Login:      http://localhost/photobooth/login.php
- Register:   http://localhost/photobooth/register.php
```

---

## 🧪 TEST ACCOUNTS

### User Account
```
Username: user123
Password: password123
```

### Admin Account
```
Username: admin
Password: admin123
```

---

## ⚠️ TROUBLESHOOTING

### Error: "Connection refused"
```
✓ Pastikan MySQL sudah running
✓ Check config/database.php
✓ Buka phpmyadmin dan verify database
```

### Error: "Webcam not working"
```
✓ Browser harus HTTPS atau localhost
✓ Beri izin akses kamera ke browser
✓ Gunakan Chrome, Firefox, Safari, atau Edge
```

### Error: "Cannot write to upload folder"
```
✓ Check folder permission:
  Linux/Mac: chmod 755 uploads/
  Windows: Klik kanan → Properties → Security → Full Control
```

### Error: "Database connection failed"
```
✓ Check localhost:3306 sudah aktif
✓ Verify username: root (default)
✓ Verify password: kosong (XAMPP default)
✓ Verify database name: photobooth
```

### Foto tidak tersimpan
```
✓ Check uploads/ folder permission
✓ Check disk space
✓ Check PHP upload_max_filesize di php.ini
```

---

## 📝 IMPORTANT NOTES

1. **Folder `uploads/` harus writable** ✅
   - Ini tempat foto disimpan
   - Harus chmod 755 (Linux/Mac)

2. **Database.sql harus di-import** ✅
   - Buat database dulu
   - Kemudian import SQL file

3. **Config files harus disesuaikan** ✅
   - database.php: sesuai MySQL Anda
   - midtrans.php: jika ingin payment

4. **All files must be UTF-8** ✅
   - Save semua .php dan .css dalam UTF-8

5. **Browser requirement** ✅
   - Chrome, Firefox, Safari, Edge
   - Support getUserMedia()
   - HTTPS atau localhost saja

---

## 🔐 SECURITY CHECKLIST

Sebelum production:

```
☐ Change default user passwords
☐ Update MIDTRANS_SERVER_KEY dengan production key
☐ Set MIDTRANS_IS_PRODUCTION = true
☐ Change database name dari "photobooth"
☐ Create new admin user
☐ Remove test accounts (user123, admin)
☐ Check file permissions (755 untuk folder)
☐ Enable HTTPS
☐ Setup .htaccess untuk security
☐ Regular database backup
```

---

## 📱 RESPONSIVE DESIGN

Aplikasi ini fully responsive:
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (< 768px)

Semua fitur bekerja di semua ukuran!

---

## 🎨 CUSTOMIZATION

### Ubah Color Scheme
Edit `css/style.css` bagian `:root`:

```css
:root {
    --primary-pink: #FFB6D9;      ← Ubah warna utama
    --primary-cream: #FFF8F0;     ← Ubah warna background
    --primary-lavender: #E8D5F2;  ← Ubah warna accent
}
```

### Ubah Logo/Brand Name
Edit `login.php`, `index.php`, dll bagian:
```php
<div class="navbar-brand">📸 PhotoBooth</div>
```

---

## 📞 QUICK REFERENCE

| Feature | File |
|---------|------|
| Login/Register | login.php, register.php |
| Photobooth | index.php |
| Photo Editor | editor.php |
| Album | album.php |
| Checkout | checkout.php |
| Admin Dashboard | admin/dashboard.php |
| Database | database.sql |
| Config | config/database.php |
| Styles | css/style.css |
| Camera JS | js/camera.js |
| Editor JS | js/editor.js |

---

## 🚀 DEPLOY KE PRODUCTION

### Untuk hosting online:
```
1. Upload semua file ke server
2. Import database.sql
3. Update config/database.php dengan server creds
4. Set PHP max_upload_filesize = 5MB+
5. Enable HTTPS
6. Change admin password
7. Update .env atau config files
8. Test semua fitur
```

---

## 💡 TIPS & TRICKS

- Gunakan Chrome DevTools untuk debug
- Check browser console untuk JavaScript errors
- Monitor uploads/ folder size
- Regular backup database
- Cache bust dengan: `style.css?v=1.0`
- Test payment di Midtrans Sandbox dulu

---

**Happy Photo Taking! 📸✨**

Version: 1.0
Last Updated: 2024
License: MIT
