# Rencana Implementasi Perubahan Aplikasi PasarKita (Migrasi Microservices)

Dokumen ini berisi *checklist* tahapan (roadmap) yang harus diikuti oleh tim developer (Backend & Frontend) untuk merombak aplikasi utama PasarKita agar terintegrasi penuh dengan ekosistem API Dummy (SmartBank & LogistikKita).

---

## TAHAP 1: Perombakan Database PasarKita (Database Migration)
*Fokus: Membuang wewenang manajemen finansial dari PasarKita dan menyiapkan penyimpanan data eksternal.*

- [ ] **Drop Kolom Saldo:** Eksekusi query SQL `ALTER TABLE users DROP COLUMN balance;` di database `pasarkita`.
- [ ] **Tambah Kolom Logistik pada `orders`:** 
  Eksekusi query penambahan kolom untuk logistik dan status:
  ```sql
  ALTER TABLE orders 
  ADD COLUMN shipping_courier VARCHAR(50),
  ADD COLUMN shipping_cost DECIMAL(10,2) DEFAULT 0,
  ADD COLUMN resi VARCHAR(50) NULL,
  ADD COLUMN payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending';
  ```

## TAHAP 2: Pembersihan *Legacy Code* (Refactoring)
*Fokus: Menghapus semua logika kode yang mengatur uang di backend.*

- [ ] **Pembersihan Model:** Cari file `User_model.php` (atau sejenisnya), hapus semua *method* yang berhubungan dengan uang, seperti `addBalance()`, `deductBalance()`, atau pengecekan saldo.
- [ ] **Pembersihan Session:** Pastikan tidak ada lagi penyimpanan `$_SESSION['balance']` pada saat proses autentikasi (Login).
- [ ] **Pembersihan Rute & UI:** Hapus halaman/controller untuk fitur "Top Up Saldo" di aplikasi, karena sekarang itu urusannya SmartBank.

## TAHAP 3: Integrasi API Checkout (Backend)
*Fokus: Menulis ulang logika Checkout Controller.*

- [ ] **Buat API Helper (Opsional):** Buat satu file helper/library khusus (misal: `Api_helper.php`) yang isinya konfigurasi cURL untuk menembak *Base URL* SmartBank dan LogistikKita agar rapi.
- [ ] **Refactor Fungsi Checkout:** Di dalam `Checkout.php`:
  1. Hapus logika cek `if (saldo_user < total_belanja)`.
  2. Implementasikan cURL POST ke `/smartbank/pembayaran_transaksi` (Bawa *amount* dan *account_number*).
  3. Buat validasi `if ($bank_response['status'] == 'success')`.
  4. Jika sukses, lanjutkan cURL POST ke `/logistikita/request_pengiriman` untuk mendapatkan `$resi`.
  5. Terakhir, jalankan `INSERT/UPDATE` ke tabel `orders` PasarKita, simpan data `$resi` dan status menjadi `paid`.

## TAHAP 4: Pembaruan UI/UX Halaman Keranjang & Pembayaran (Frontend)
*Fokus: Mengubah tampilan yang dilihat oleh pembeli untuk mengakomodasi pemilihan kurir.*

- [ ] **Dropdown Ongkir Dinamis:** Pada halaman keranjang, jangan hardcode pilihan ongkos kirim. Gunakan `fetch()` atau `$.ajax()` untuk memanggil API `/logistikita/cek_ongkir?amount=xx` dan tampilkan opsi pengiriman yang dikembalikan.
- [ ] **Form Rekening SmartBank:** Tambahkan sebuah input text untuk `account_number` di halaman Checkout. Minta pengguna memasukkan nomor rekening SmartBank mereka.
- [ ] **Rincian Tagihan:** Pastikan rincian tagihan menampilkan subtotal ditambah dengan `shipping_cost` yang dipilih dari *dropdown*.
- [ ] **Tampilan Resi:** Di halaman "Pesanan Saya", buat agar variabel `$resi` yang disimpan di database bisa ditampilkan kepada pengguna beserta opsi fitur "Lacak" (memanggil `/logistikita/lacak_resi`).

## TAHAP 5: Pengujian (Testing)
- [ ] **Skenario Berhasil:** Masukkan nomor rekening `1234567890` (yang punya saldo awal Rp 50.000). Beli barang seharga Rp 20.000. Pastikan transaksi sukses, resi muncul, dan uang terpotong.
- [ ] **Skenario Gagal:** Beli barang dengan total biaya di atas Rp 60.000 menggunakan rekening yang sama. Pastikan transaksi tertolak oleh API SmartBank dan muncul notifikasi "Saldo Tidak Mencukupi" di PasarKita.
