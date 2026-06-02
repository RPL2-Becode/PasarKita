# Dokumentasi Konteks Sistem - PasarKita (Codex Standard)

## 1. Pendahuluan
Dokumen ini disusun berdasarkan standar Codex untuk memetakan arsitektur, prinsip dasar, dan fitur-fitur operasional pada platform PasarKita. Disiplin dalam pencatatan pengembangan (Documentation as Code) adalah landasan dari stabilitas sistem.

## 2. Prinsip Pengembangan (Study of Principles)
Sistem PasarKita dirancang menggunakan prinsip **MVC (Model-View-Controller)** dan menerapkan konsep **SOLID**:
- **Single Responsibility:** Setiap controller (`Admin.php`, `Pesanan.php`) hanya menangani domain yang relevan dengan namanya (misalnya, `Admin` khusus mengurus logik manajemen, dashboard, dan monitoring).
- **Open/Closed Principle:** Sistem pembukuan (Fee 2%) dibangun dengan logika yang dapat diperluas tanpa harus memodifikasi core transaksi utama.
- **MVC Separation:** Pemisahan ketat antara View (UI berbasis HTML/CSS) dan Controller (PHP backend), memastikan bahwa perubahan antarmuka pengguna tidak merusak integritas database.

## 3. Fitur Utama & Logika Bisnis
### A. Dashboard Admin & Pembukuan Keuntungan (Marketplace Profit)
Dashboard Admin kini tidak lagi hanya menampilkan tabel statis, melainkan **Grafik Transaksi Terbaru (Chart.js)** untuk visualisasi data yang lebih informatif.
- **Pembukuan Keuntungan (Admin):** Sistem secara otomatis mengambil `Fee Marketplace (2%)` dari setiap transaksi yang berhasil (`status = 'Selesai'`). Total keuntungan PasarKita (fee admin) direkam dan divisualisasikan pada chart bergaris biru, memisahkan nilai omset kotor dan laba bersih perusahaan.

### B. Fitur Pembatalan & Pengembalian Pesanan
- **Tombol Ajukan Pembatalan:** Berfungsi secara dinamis hanya saat pesanan belum dikirim (`Menunggu Pembayaran`, `Menunggu Konfirmasi`, `Sedang Dikemas`).
- **Tombol Ajukan Pengembalian (Kembali):** Berfungsi hanya setelah pesanan dikirimkan (`Dikirim`, `Selesai`).
Sistem memastikan saldo pengguna (`user_balance`) dikembalikan secara tepat dikurangi biaya yang tidak dapat di-refund jika validasi backend terpenuhi.

### C. Fitur Hubungi Penjual (Chat)
Tombol "Hubungi Penjual / Chat Penjual" diaktifkan dan terhubung secara otomatis ke modul `Chat.php`. Ini mengirimkan `product_id`, `order_id`, dan `seller_id` dalam parameternya sehingga penjual langsung memahami konteks pesan dari pembeli tanpa harus bertanya nomor pesanan.

## 4. Analisis Kendala & Mitigasi (Potential Constraints)
Seiring perubahan arsitektur pada dashboard, berikut adalah kendala yang berpotensi terjadi dan status penanganannya:

| Kendala (Constraint) | Dampak | Mitigasi / Solusi yang Diterapkan |
| :--- | :--- | :--- |
| **Beban Render Grafik (Client-Side)** | Jika data transaksi ratusan ribu, browser admin akan menjadi lambat merender grafik (lag). | Data yang dilempar ke grafik dibatasi (`LIMIT 15` transaksi terbaru). Backend hanya merender subset data. |
| **Ketergantungan CDN Chart.js** | Grafik tidak akan muncul jika server/komputer admin tidak memiliki koneksi internet keluar (Offline intranet). | Menggunakan fallback data atau menyediakan pustaka `chart.js` secara lokal di dalam folder `public/js/` di masa mendatang. |
| **Inkonsistensi Status Pembatalan** | Pengguna menekan tombol pembatalan berulang kali sebelum halaman termuat, membuat multiple entry. | Memisahkan view logic dengan route backend (`/pesanan/cancel`) yang melakukan validasi ketat status order. Jika status bukan `Menunggu`, query update ditolak. |
| **Laporan Pembukuan Fee 2% Meleset** | Terjadi jika pembulatan nilai desimal (floating point) dikonversi paksa. | Backend (Order_model) melakukan kalkulasi dasar (`amount * 0.02`) dan View hanya berfungsi sebagai pemformat (`number_format`). |

---
*Dokumen ini bersifat dinamis dan harus terus diperbarui seiring dengan berjalannya versi baru dari PasarKita.*
