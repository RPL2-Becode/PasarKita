# 🛒 PasarKita - UMKM Marketplace Application

PasarKita adalah aplikasi marketplace berbasis web yang dibangun dengan arsitektur **MVC (Model-View-Controller)** menggunakan **PHP Native** dan **MySQL**. Aplikasi ini difokuskan untuk memberdayakan UMKM lokal (Pelapak) agar dapat menjual produk mereka secara digital langsung kepada konsumen.

Aplikasi ini merupakan bagian dari **Tugas Besar Rekayasa Perangkat Lunak (RPL)**.

---

## 🚀 Fitur Utama Berdasarkan Hak Akses (Role)

Aplikasi PasarKita memiliki 4 (empat) peran utama dengan tingkat otorisasi yang berbeda-beda:

### 1. 🛍️ Consumen (Pembeli)
* **Katalog Produk:** Mencari produk berdasarkan kata kunci atau filter kategori.
* **Keranjang Belanja:** Menambah barang ke keranjang sebelum *checkout*.
* **Checkout:** Simulasi pembayaran terintegrasi API (Mock) **SmartBank**, termasuk perhitungan pajak layanan (2%) dan ongkos kirim.
* **Pesanan Saya:** Memantau riwayat transaksi, melihat detail barang yang dibeli, dan melacak status pesanan secara real-time.

### 2. 🏪 Pelapak (Seller)
* **Manajemen Produk:** Fitur *Create, Read, Update, Delete* (CRUD) untuk produk jualan mereka sendiri.
* **Kategori & Stok:** Menentukan jumlah stok yang tersedia dan kategori barang (Makanan, Minuman, Pakaian, dll).

### 3. 👨‍💻 Operator
* **Monitoring Transaksi:** Memantau seluruh pesanan yang masuk ke aplikasi.
* **Update Status:** Dapat memperbarui status pesanan dari "Menunggu Konfirmasi" ➔ "Dikemas" ➔ "Dikirim" ➔ "Selesai".

### 4. 👑 Admin
* **Dashboard Finansial:** Memiliki hak akses khusus untuk melihat statistik pendapatan, *fee marketplace*, dan tren transaksi.
* **Manajemen User:** Dapat menghapus, mengubah *role*, atau mengelola semua pengguna yang terdaftar di dalam platform.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP 8+ (Arsitektur MVC Custom)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, Tailwind CSS (via CDN), FontAwesome (Icons)
* **Konsep API:** Simulasi REST API dengan integrasi pembayaran SmartBank.

---

## ⚙️ Cara Instalasi & Penggunaan (Local Development)

### Prasyarat:
Pastikan kamu telah menginstal environment server lokal seperti **FlyEnv**, **XAMPP**, **MAMP**, atau **Laragon**.

### Langkah-langkah:
1. **Clone Repository**
   ```bash
   git clone https://github.com/RPL2-Becode/PasarKita.git
   ```

2. **Setup Database**
   * Buka aplikasi database manager kamu (phpMyAdmin, TablePlus, dll).
   * Buat database baru (kosong) bernama `pasar_kita`.
   * Jalankan file script SQL yang berada di `config/init.sql` untuk membuat semua tabel dan memasukkan data dummy (seeder).

3. **Konfigurasi Database**
   * Buka file `config/db.php`.
   * Sesuaikan `DB_HOST`, `DB_USER`, dan `DB_PASS` dengan environment lokal kamu.
   * *Catatan:* Jika kamu menggunakan FlyEnv, pastikan host mengarah ke `127.0.0.1` dan menggunakan password database bawaan FlyEnv.

4. **Jalankan Aplikasi**
   * Tempatkan folder ini di dalam document root server lokal kamu (`htdocs` atau `www`).
   * Buka browser dan jalankan aplikasinya (misal: `http://localhost/PasarKita/public`).

---

## 🔑 Akun Pengujian (Dummy Data)

Semua akun di bawah ini menggunakan kata sandi (password) default yang sama yaitu: **`admin123`**

| Role (Peran) | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `admin123` |
| **Operator** | `juna` | `admin123` |
| **Pelapak** | `pelapak1` | `admin123` |
| **Consumen** | `budi` | `admin123` |

*(Note: Sistem dilengkapi fitur Lupa Password bawaan yang terkoneksi langsung ke database).*

---

## 📁 Struktur Direktori Utama

```
PasarKita/
├── api/             # Endpoint REST API (Products, Users, Orders)
├── app/             # Inti MVC Aplikasi
│   ├── controllers/ # Logika bisnis (Marketplace, Cart, Checkout, Admin, dll)
│   ├── core/        # Router & Controller inti (App.php)
│   ├── models/      # Query dan struktur database
│   └── views/       # Tampilan antarmuka (UI/HTML)
├── config/          # Konfigurasi Database dan Init SQL
└── public/          # Entry point (.htaccess, index.php, assets, gambar)
```

---
*Dibuat untuk Tugas Kuliah Rekayasa Perangkat Lunak (RPL).*