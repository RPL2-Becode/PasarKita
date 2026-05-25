# PasarKita V2: Phase 2 Implementation Plan

Berdasarkan `planning_v2.md`, **Fase 2** berfokus pada:
1. **Integrasi Cek Ongkir (Simulasi Multi-Ekspedisi)**
2. **Input Nomor Resi oleh Operator/Admin**
3. **Live Tracking Info untuk Konsumen**

---

## 1. Persiapan Database (Selesai)

Dua kolom baru ditambahkan ke tabel `orders`:

```sql
-- Jalankan query ini di phpMyAdmin jika database sudah ada:
ALTER TABLE orders 
    ADD COLUMN shipping_service VARCHAR(50) DEFAULT NULL COMMENT 'e.g. JNE, J&T, SiCepat' AFTER smartbank_trx_id,
    ADD COLUMN resi_number VARCHAR(100) DEFAULT NULL COMMENT 'Nomor resi pengiriman' AFTER shipping_service;
```

> **Catatan:** Jika menggunakan `init.sql` baru (fresh import), kolom ini sudah otomatis ada.

---

## 2. Fitur yang Diimplementasikan

### A. Pilihan Ekspedisi di Keranjang ✅
- Ekspedisi di set secara default ke **LogistikKita** sesuai dengan aturan keuangan.
- Ongkir adalah *flat rate* sebesar Rp 5.000.
- Total tagihan dihitung berdasarkan subtotal + biaya layanan 2% + ongkir LogistikKita.
- Ekspedisi disimpan ke kolom `shipping_service` di tabel `orders` sebagai "LogistikKita".

**File yang dimodifikasi:**
- `app/views/marketplace/cart.php` — UI ekspedisi LogistikKita.
- `app/controllers/Checkout.php` — Set `shipping_service` ke LogistikKita dan fee 5000.
- `app/models/Order_model.php` — `createOrder()` menyimpan `shipping_service`.

### B. Input Nomor Resi oleh Operator ✅
- Di halaman Admin Orders, muncul tombol **"Input Resi"** untuk order berstatus `Sedang Dikemas` atau `Menunggu Konfirmasi`
- Operator memasukkan nomor resi (Jasa pengiriman otomatis diset ke LogistikKita).
- Setelah submit, status otomatis berubah ke **"Dikirim"** dan resi tersimpan

**File yang dimodifikasi:**
- `app/views/admin/orders.php` — kolom resi + form toggle input resi dengan opsi LogistikKita.
- `app/controllers/Admin.php` — method `updateresi()` baru
- `app/models/Order_model.php` — method `updateResi()` baru

### C. Info Tracking untuk Konsumen ✅
- Di halaman **Detail Pesanan**, jika status "Dikirim" atau "Selesai" dan resi sudah ada:
  - Nama jasa pengiriman (LogistikKita) ditampilkan
  - Nomor resi ditampilkan lengkap dengan tombol **salin**

**File yang dimodifikasi:**
- `app/views/marketplace/pesanan_detail.php` — card tracking info dengan kurir LogistikKita.

---

## 3. Status Fase 2: ✅ SELESAI

**Langkah berikutnya: Fase 3** (Fitur Chat Real-time & Sistem Diskon/Kupon)
