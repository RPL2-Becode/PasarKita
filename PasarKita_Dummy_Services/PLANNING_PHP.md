# Perencanaan Perubahan PHP (Integrasi API Terpisah)

Perencanaan ini berfokus pada arsitektur di mana **SmartBank** dan **LogistikKita** disimulasikan sebagai entitas independen (microservices) dengan **database mandiri yang terpisah dari PasarKita**. Hal ini memastikan API dapat dites selayaknya API dari layanan pihak ketiga yang sesungguhnya.

## 1. Arsitektur Database Terpisah (Microservices Mock)
Alih-alih menggunakan database `pasarkita`, kita akan membuat dua database baru khusus untuk *dummy system*:
1. **Database `dummy_smartbank_db`**
   - **Tabel `users`**: Menyimpan data saldo pengguna (contoh: *account_number*, *balance*). Sesuai aturan, saldo awal simulasi adalah Rp 50.000.
   - **Tabel `ledgers`**: Menyimpan histori transaksi dan pemotongan fee (Bank 1%, Gateway 0.5%, Pajak 2%, Marketplace 2%).
2. **Database `dummy_logistik_db`**
   - **Tabel `shipments`**: Menyimpan detail pengiriman, harga (*shipping_cost*), dan status.
   - **Tabel `tracking_history`**: Menyimpan riwayat perjalanan paket (*resi*).

## 2. Pembuatan API Eksternal (Dummy API Endpoint)
Setiap layanan akan memiliki Controller dan koneksi DB sendiri, di-hosting dalam satu folder gabungan (misalnya `ExternalServices_Mock_API`) untuk menghemat *slot hosting*:

- **SmartBank API (`smartbank/SmartBankApi.php`)**
  - Terkoneksi ke `dummy_smartbank_db`.
  - Endpoint `process_payment`: Melakukan pengecekan saldo, memotong saldo, mencatat fee ke dalam *ledger*, dan memastikan transaksi valid (Atomic Transaction menggunakan PDO `beginTransaction`).

- **LogistikKita API (`logistik/LogistikKitaApi.php`)**
  - Terkoneksi ke `dummy_logistik_db`.
  - Endpoint `get_shipping_rates`: Mengembalikan tarif flat Rp 5.000 atau 5% dari total transaksi.
  - Endpoint `create_shipment`: Menyimpan resi baru ke tabel `shipments`.
  - Endpoint `track_resi`: Mengambil riwayat dari tabel `tracking_history`.

## 3. Modifikasi Database PasarKita Utama
Karena *SmartBank* bertindak sebagai *Single Source of Truth* untuk keuangan, **PasarKita tidak boleh menyimpan saldo sama sekali**. 
**Penghapusan (Drop/Hapus) di PasarKita:**
- Hapus kolom `balance` dan `balance_id` dari tabel `users`.
- Hapus semua metode terkait keuangan pada model lokal, seperti `User_model->addBalance()` dan `User_model->deductBalance()`.
- Hapus `$_SESSION['user_balance']` dari aplikasi.

**Penambahan di PasarKita:**
Meskipun transaksi diproses di luar, PasarKita tetap butuh pencatatan lokal pesanan di tabel `orders`:
- `shipping_courier` (VARCHAR)
- `shipping_cost` (INT)
- `tracking_number` / `resi` (VARCHAR)
- `payment_method` (VARCHAR)
- `payment_status` (ENUM) : 'pending', 'paid', 'failed'

## 4. Modifikasi Controller Utama PasarKita (`Checkout.php`)
- Saat Checkout, PasarKita akan melempar *HTTP Request* (cURL/Guzzle) ke URL API Eksternal (misal: `http://localhost/.../SmartBankApi.php?action=pay`).
- **Hitung Grand Total** = Total Harga Barang + Ongkos Kirim (LogistikKita) + Fee Bank (1%) + Fee Gateway (0.5%) + Pajak Sistem (2%).
- Jika respons dari SmartBank `status: "success"`, maka update `payment_status = 'paid'` di database PasarKita.
