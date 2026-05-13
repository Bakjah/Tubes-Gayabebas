# 📸 PHOTOBOOTH APLIKASI - Panduan Instalasi

Aplikasi photobooth modern dengan sistem pembayaran QRIS, admin dashboard, dan cetak foto online/offline.

## 🚀 Instalasi Cepat di XAMPP

### 1. Setup Folder
```bash
# Letakkan folder 'photobooth' di:
# Windows: C:\xampp\htdocs\photobooth
# Mac: /Applications/XAMPP/htdocs/photobooth
# Linux: /opt/lampp/htdocs/photobooth
```

### 2. Aktifkan XAMPP
- Buka XAMPP Control Panel
- Start Apache & MySQL

### 3. Import Database
```bash
# Buka http://localhost/phpmyadmin
# Create database baru: nama 'photobooth'
# Import file 'database.sql' ke database tersebut
```

### 4. Konfigurasi Midtrans (Opsional)
Edit file `config/midtrans.php`:
```php
// Ganti dengan Server Key Anda
define('MIDTRANS_SERVER_KEY', 'SB-Mid-xxxxxxxxxxxxx');
define('MIDTRANS_CLIENT_KEY', 'SB-Cli-xxxxxxxxxxxxx');
```

Dapatkan key dari: https://dashboard.midtrans.com

### 5. Jalankan Aplikasi
```
http://localhost/photobooth
```

## 🔑 Akun Default

**User Test:**
- Username: user123
- Password: password123

**Admin Test:**
- Username: admin
- Password: admin123

## 📁 Struktur Folder

```
photobooth/
├── config/                 # Konfigurasi database & midtrans
├── css/                    # Stylesheet modern
├── js/                     # JavaScript photobooth & editor
├── uploads/                # Folder hasil foto user
├── admin/                  # Dashboard admin
├── includes/               # Helper functions
├── login.php              # Halaman login
├── register.php           # Halaman register
├── photobooth.php         # Main photobooth interface
├── editor.php             # Photo editor
├── album.php              # User album
├── checkout.php           # Checkout online order
├── offline-print.php      # Admin offline print
├── order-history.php      # Riwayat pesanan user
├── print-preview.php      # Print preview
├── process-payment.php    # Backend pembayaran
├── midtrans-callback.php  # Webhook Midtrans
├── logout.php             # Logout
└── database.sql           # Schema database
```

## ✨ Fitur Utama

✅ **Authentication**
- Register & Login dengan session
- Role: User & Admin
- Password hashing aman

✅ **Photobooth**
- Webcam real-time dengan getUserMedia()
- Countdown 3 detik otomatis
- 6 live filter (Normal, B&W, Sepia, Vintage, Bright, Cool Tone)
- Layout: Photostrip, Grid, Polaroid

✅ **Photo Editor**
- Ganti filter
- Pilih frame (Birthday, Polaroid, Simple, Kawaii)
- Tambah teks custom
- Tambah sticker
- Crop & rotate

✅ **User Album**
- Simpan semua hasil foto
- Download PNG/JPG
- Pesan cetak online

✅ **Pembayaran**
- QRIS Midtrans integration
- Status tracking
- Webhook callback

✅ **Admin Dashboard**
- Statistik real-time
- Kelola pesanan online & offline
- Print langsung di tempat
- Kelola user & foto

✅ **UI Modern**
- Pastel color palette (pink, cream, lavender)
- Glassmorphism effect
- Responsive mobile & desktop
- Dark mode friendly
- Smooth animations

## 🔐 Keamanan

- Password hashing dengan `password_hash()`
- Prepared statement untuk semua query
- Session protection
- Role checking di setiap halaman
- Validasi upload file
- CSRF protection siap implementasi

## 📝 Notes

- Folder `uploads/` harus writable
- PHP 7.4+ required
- MySQL 5.7+ required
- Browser dengan support getUserMedia() (Chrome, Firefox, Safari, Edge)

## 🆘 Troubleshooting

**Error: "Connection refused"**
- Pastikan MySQL sudah running
- Check `config/database.php`

**Webcam tidak bekerja**
- Gunakan HTTPS atau localhost
- Browser beri izin akses kamera

**Foto tidak tersimpan**
- Check folder `uploads/` permission (chmod 755)
- Check PHP upload_max_filesize di php.ini

## 📧 Support

Untuk pertanyaan atau masalah, cek log di folder `uploads/errors.log`

---

**Happy Photo Taking! 📸✨**
