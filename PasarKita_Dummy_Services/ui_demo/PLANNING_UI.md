# Perencanaan UI Dummy (Integrasi SmartBank & LogistikKita)

Fokus utama dari UI dummy ini adalah untuk mensimulasikan alur (flow) pengguna saat melakukan checkout, memilih pengiriman, dan melakukan pembayaran tanpa harus mengubah UI utama PasarKita terlebih dahulu.

## 1. Struktur Halaman yang Dibutuhkan
- **Halaman Keranjang/Checkout (`checkout.html`)**
  - **Pemilihan Pengiriman:** Dropdown atau list radio button untuk memilih layanan pengiriman (Reguler, Express) yang datanya diambil dari endpoint dummy LogistikKita.
  - **Ringkasan Biaya:** Subtotal harga barang + Ongkos kirim dari LogistikKita (5% atau flat Rp 5.000) + Fee Bank (1%) + Fee Gateway (0.5%) + Pajak Sistem (2%). Semua ini mengikuti Aturan Keuangan RPL.
  - **Metode Pembayaran:** Pilihan pembayaran menggunakan SmartBank.
  - **Tombol "Bayar Sekarang"**: Memicu proses pembuatan pesanan (Order) ke backend.
- **Halaman Simulasi SmartBank (`smartbank_payment.html`)**
  - Menampilkan antarmuka simulasi *payment gateway*.
  - Menampilkan jumlah tagihan.
  - Tombol "Konfirmasi Pembayaran" untuk mensimulasikan pembayaran berhasil.
- **Halaman Lacak Pengiriman (`tracking.html`)**
  - Form input nomor resi.
  - Menampilkan *timeline* status pengiriman (dikemas, dikirim kurir, diterima) berdasarkan respons dari endpoint LogistikKita.

## 2. Alur Interaksi (User Flow)
1. **User** masuk ke halaman Checkout.
2. Halaman melakukan *fetch* (AJAX/Fetch API) ke `/api/dummy/logistikkita/rates` untuk mendapatkan opsi ongkir.
3. **User** memilih ongkir dan klik Bayar.
4. UI mengarahkan (**redirect**) user ke halaman `smartbank_payment.html` dengan membawa ID Tagihan.
5. Di halaman SmartBank, user klik "Konfirmasi Pembayaran". UI menembak API `/api/dummy/smartbank/pay`.
6. Jika berhasil, user diarahkan kembali ke PasarKita dengan pesan "Pembayaran Berhasil".

## 3. Desain Endpoint Dummy yang Dibutuhkan UI
Untuk tahap ini, UI akan mengkonsumsi endpoint dummy (mock API) berikut:
- `GET /api/dummy/logistik/rates` -> Return JSON daftar harga ongkir.
- `POST /api/dummy/smartbank/pay` -> Return JSON `{ "status": "success", "message": "Pembayaran diterima" }`.
- `GET /api/dummy/logistik/track/{resi}` -> Return JSON `{ "status": "On Delivery", "history": [...] }`.
