# PasarKita V2: Phase 1 Implementation Plan

Berdasarkan `planning_v2.md`, **Fase 1** berfokus pada:
1. **Sistem Rating & Ulasan (Review & Rating)**
2. **Wishlist (Barang Favorit)**
3. **Pencarian Lanjutan & Filter (Advanced Search)**

Berikut adalah rencana implementasi detail untuk menyelesaikan Fase 1 ini.

---

## 1. Persiapan Database (Selesai)
Kita telah menambahkan struktur tabel baru ke dalam `config/init.sql`:
- **Tabel `reviews`**: Menyimpan data ulasan produk, rating (1-5), komentar, beserta relasi ke produk, user, dan order.
- **Tabel `wishlists`**: Menyimpan relasi produk-produk favorit yang disimpan oleh konsumen.

> **Catatan:** Jika Anda belum memperbarui database Anda, pastikan untuk mengeksekusi *query* pembuatan tabel `reviews` dan `wishlists` ke database MySQL `pasar_kita` Anda, atau Anda dapat mengimpor ulang `config/init.sql`.

## 2. Pembuatan Models (Selesai)
Kita telah membuat model untuk berinteraksi dengan tabel-tabel baru tersebut:
- `app/models/Review_model.php`: Mengurus pengambilan review berdasarkan produk, menambahkan review, serta menghitung rata-rata (average) rating.
- `app/models/Wishlist_model.php`: Mengurus penambahan, penghapusan, dan pengambilan daftar wishlist seorang user.

## 3. Langkah Selanjutnya (Action Items)

### A. Fitur Rating & Ulasan
- [x] Membuat / Update **Orders Controller** untuk menangani _submit_ ulasan (ketika pesanan Selesai).
- [x] Menambahkan UI form ulasan di riwayat pesanan (`app/views/orders/index.php` atau sejenisnya).
- [x] Menampilkan daftar ulasan dan rata-rata rating di Halaman Detail Produk (`app/views/products/detail.php`).

### B. Fitur Wishlist
- [x] Membuat **Wishlist Controller** (`app/controllers/Wishlist.php`) untuk menangani aksi _Add_ dan _Remove_.
- [x] Menambahkan tombol "❤️" (Add to Wishlist) pada card produk dan halaman detail.
- [x] Membuat halaman **Daftar Wishlist** (`app/views/wishlist/index.php`) untuk melihat barang yang difavoritkan.

### C. Pencarian Lanjutan & Filter
- [x] Memodifikasi `app/models/Product_model.php` untuk mendukung parameter pencarian dinamis (kategori, min/max harga, rating minimum).
- [x] Memodifikasi controller `Marketplace.php` untuk menerima semua parameter filter dari GET.
- [x] Membuat UI _Sidebar Filter_ pada halaman katalog produk (keyword, kategori, rentang harga, rating minimum, pengurutan).

---

## 4. Status Fase 1: ✅ SELESAI

Semua fitur Fase 1 telah berhasil diimplementasikan:
- ⭐ Sistem Rating & Ulasan — Konsumen dapat memberi ulasan pada pesanan yang sudah "Selesai"
- ❤️ Wishlist — Konsumen dapat menyimpan & menghapus produk favorit
- 🔍 Pencarian Lanjutan — Sidebar filter dengan keyword, kategori, harga, rating, dan urutan

**Langkah berikutnya: Fase 2** (Integrasi API Cek Ongkir & Live Tracking Pengiriman)
