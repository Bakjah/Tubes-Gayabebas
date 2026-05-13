╔═══════════════════════════════════════════════════════════════════════════╗
║                                                                           ║
║          📸 PHOTOBOOTH - INSTALASI CEPAT (5 MENIT) 📸                    ║
║                                                                           ║
╚═══════════════════════════════════════════════════════════════════════════╝

LANGKAH 1: EXTRACT FILE INI
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Extract folder "photobooth" ke:

📁 Windows:  C:\xampp\htdocs\photobooth
📁 Mac:      /Applications/XAMPP/htdocs/photobooth  
📁 Linux:    /opt/lampp/htdocs/photobooth

Struktur yang benar:
  htdocs/
  └── photobooth/
      ├── config/
      ├── css/
      ├── js/
      ├── includes/
      ├── uploads/
      ├── admin/
      ├── *.php files
      └── database.sql

LANGKAH 2: SETUP DATABASE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1. Start XAMPP (Apache + MySQL)
2. Buka: http://localhost/phpmyadmin
3. Klik "New" → Create database "photobooth"
4. Klik tab "Import"
5. Upload file: database.sql (ada di folder photobooth)
6. Klik "Go"

✓ Jika berhasil, tabel sudah dibuat dan data test sudah ada

LANGKAH 3: JALANKAN APLIKASI
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1. Buka browser
2. Ketik: http://localhost/photobooth/login.php

🔑 LOGIN DENGAN:
   Username: user123
   Password: password123

👨‍💼 ATAU ADMIN:
   Username: admin
   Password: admin123

LANGKAH 4: SELESAI! 🎉
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Aplikasi sudah siap digunakan!

Fitur yang tersedia:
✅ Ambil foto dengan webcam
✅ 6 live filters
✅ Photo editor
✅ User album
✅ Admin dashboard

TROUBLESHOOTING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
❌ Error: "Connection refused"
   → Pastikan MySQL sudah running
   → Check config/database.php

❌ Error: "Database error"  
   → Database belum di-import
   → Buka phpmyadmin dan import database.sql

❌ Webcam tidak bekerja
   → Browser harus HTTPS atau localhost
   → Beri izin akses kamera

❌ Foto tidak tersimpan
   → Check folder uploads/ permission
   → Chmod 755 uploads/ (Linux/Mac)

DOKUMENTASI LENGKAP
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Baca file ini untuk detail lebih lanjut:
- 000_MULAI_DARI_SINI.txt
- README.md
- SETUP_GUIDE.md
- 00_CHECKLIST_LENGKAP.md

PENTING! ⚠️
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1. Folder "uploads/" harus ada dan writable
   - Linux/Mac: chmod 755 uploads/
   - Windows: Set permission ke Full Control

2. Database HARUS di-import dengan benar
   - Bukan hanya create folder
   - Import file database.sql ke phpmyadmin

3. Config files sudah default untuk XAMPP
   - Jika password MySQL bukan kosong, edit config/database.php

SELESAI! 🎉
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Aplikasi Anda sudah 100% siap pakai!

Senang menggunakan PhotoBooth! 📸✨

Version: 1.0
Support: Lihat dokumentasi included
