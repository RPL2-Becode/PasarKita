# Dokumen Desain Perangkat Lunak (System Design Document)
**Nama Aplikasi:** PasarKita Marketplace
**Versi:** 1.0

---

## 1. Deskripsi Aplikasi
**PasarKita** adalah sebuah platform aplikasi *marketplace* (pasar digital) terintegrasi berbasis web yang dirancang khusus untuk memfasilitasi transaksi jual-beli antara pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) dengan konsumen. Aplikasi ini dibangun dengan tujuan mendigitalkan proses bisnis lokal, menyediakan etalase *online* yang mudah digunakan, serta mengelola transaksi secara transparan menggunakan sistem dompet digital (saldo internal) dan potongan *fee* otomatis.

---

## 2. Diagram & Deskripsi Use Case
Sistem PasarKita memiliki tiga aktor (pengguna) utama dengan hak akses dan skenario penggunaan (*Use Case*) yang berbeda:

1.  **Aktor: Pembeli (Konsumen)**
    *   Mencari dan melihat detail produk (*Catalog*).
    *   Melakukan pemesanan barang (*Checkout*).
    *   Berkomunikasi dengan penjual via *Live Chat*.
    *   Mengonfirmasi penerimaan barang (Pesanan Selesai).
    *   Mengajukan retur atau pembatalan pesanan dengan alasan spesifik.
2.  **Aktor: Penjual (Pelapak / UMKM)**
    *   Membuka toko digital (mengubah profil & *banner* toko).
    *   Menambahkan, mengedit, dan menghapus inventaris Produk (stok, harga, foto).
    *   Menerima order masuk dan mengubah status pesanan (misal: "Sedang Dikemas", "Dikirim").
    *   Menerima dana penjualan (saldo) secara otomatis setelah transaksi selesai.
3.  **Aktor: Admin System**
    *   Memantau seluruh transaksi yang terjadi di platform.
    *   Mengelola daftar pengguna (Blokir, Verifikasi).
    *   Menyetujui atau menolak proses pengembalian dana (*Refund*) akibat pembatalan.

---

## 3. Arsitektur Sistem
PasarKita dibangun menggunakan pendekatan **Monolithic Web App dengan dukungan API terpisah**.
*   **Pola Desain Utama:** Model-View-Controller (MVC) murni menggunakan PHP (tanpa *framework* eksternal yang membebani kinerja dasar).
*   **Frontend UI:** Menggunakan kombinasi HTML5, Vanilla JavaScript, dan *Tailwind CSS* melalui CDN untuk tampilan yang *responsive* dan modern.
*   **Backend Server:** PHP native berjalan di atas Apache/Nginx.
*   **Keamanan:** Sistem sesi (PHP `$_SESSION`), validasi *form input* (*backend-validation*), dan sanitasi kueri basis data untuk mencegah *SQL Injection*.
*   **Pencatatan (Logging):** Menyediakan mekanisme rekaman otomatis aktivitas akses API yang disimpan ke dalam *file log* (`api_request.log`) untuk tujuan audit.

---

## 4. Struktur Database (Schema)
Sistem menggunakan *Relational Database Management System* (MySQL / MariaDB) dengan tabel-tabel utama sebagai berikut:

*   **`users`**: Menyimpan data identitas (ID, username, password, email, role [admin/seller/buyer], balance/saldo, foto profil).
*   **`products`**: Menyimpan katalog dagangan (ID, seller_id (FK), name, description, price, stock, category_id, image_url).
*   **`categories`**: Menyusun kategori navigasi barang.
*   **`orders`**: Mencatat *header* transaksi (ID, buyer_id, total_price, status, reason_cancellation, created_at).
*   **`order_items`**: Mencatat detail barang per transaksi (order_id, product_id, seller_id, qty, price_at_purchase).
*   **`chat_messages`**: Menyimpan riwayat komunikasi (sender_id, receiver_id, message, product_id/order_id, created_at).

---

## 5. Spesifikasi API (REST API Endpoints)
Selain antarmuka *browser*, aplikasi mengekspos *endpoints* REST API tersentralisasi di `api/index.php` untuk melayani klien eksternal atau aplikasi *mobile* dengan format respons standar **JSON**.

| Method | Endpoint (*Query Params*) | Fungsi / Deskripsi |
| :--- | :--- | :--- |
| **GET** | `?endpoint=products` | Mengambil semua produk yang tersedia. |
| **POST** | `?endpoint=products` | Menambahkan produk baru (membutuhkan JSON *payload*). |
| **GET** | `?endpoint=categories` | Menampilkan seluruh opsi kategori toko. |
| **GET** | `?endpoint=orders` | Mendapatkan rekapan seluruh transaksi. |
| **PUT** | `?endpoint=orders&action=status` | Memperbarui status pesanan (misal: "Dikirim"). |
| **GET** | `?endpoint=users` | Melihat daftar pengguna dan *role* mereka. |

---

## 6. Mekanisme Logika Transaksi & Finansial
Mekanisme siklus transaksi uang dan barang menerapkan aturan bisnis internal PasarKita:

1.  **Checkout:** Pembeli memesan barang, total biaya (Harga Barang + Ongkos Kirim Tetap [LogistikKita: Rp 5.000]) dihitung. Saldo pembeli dikurangi. Status pesanan *"Menunggu Konfirmasi"*.
2.  **Pemrosesan:** Penjual mengonfirmasi pesanan, memotong kuantitas `stock` di tabel *products*. Status bergeser ke *"Sedang Dikemas"* lalu *"Dikirim"*.
3.  **Order Selesai (Revenue & Fee Marketplace):** 
    Ketika pembeli menekan tombol pesanan Selesai, sistem secara otomatis mengeksekusi perhitungan:
    *   Menghitung *total revenue* barang pesanan.
    *   Sistem memotong **Fee Platform (Biaya Marketplace) sebesar 2%** dari hasil penjualan.
    *   Hanya dana bersih (98%) yang didistribusikan masuk (ditambahkan) ke kolom `balance` (saldo) milik Penjual.
    *   Memicu modul *Chat* untuk mengirimkan pesan otomatis (Robot) ucapan terima kasih dari penjual kepada pembeli.
4.  **Pembatalan (Refund):** Jika pesanan dibatalkan atau di-retur (dengan alasan yang valid), Admin akan menekan tombol *Refund*. Sistem akan mengembalikan dana secara utuh ke saldo pembeli dan merestorasi *stock* barang penjual.
