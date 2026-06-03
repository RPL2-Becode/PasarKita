# Laporan Implementasi Sistem: Analisis Pola Input-Process-Output (IPO)
**Platform Marketplace: PasarKita**

---

## 1. Pendahuluan
Dokumen ini disusun untuk memberikan bukti nyata (evidence-based report) mengenai cara kerja sistem **PasarKita**. Setiap fitur utama dianalisis menggunakan pola **Input - Proses - Output (IPO)** untuk mendemonstrasikan alur logika perangkat lunak secara terstruktur mulai dari interaksi pengguna, pemrosesan di sisi *backend* (Controller/Model), hingga hasil akhirnya.

---

## 2. Pembahasan Fitur Berdasarkan Pola IPO

Berikut adalah analisis fitur-fitur krusial dalam sistem PasarKita:

### 2.1. Fitur Penambahan Produk oleh Pelapak (UMKM)
Fitur ini memungkinkan penjual mendaftarkan barang baru ke dalam katalog *marketplace*.

*   **Input (Masukan):**
    Pengguna (Penjual) mengisi *form* penambahan produk yang berada pada *View* (`add.php`). Data yang dimasukkan berupa:
    *   `name`: Nama Produk (contoh: Kripik Tempe Pedas)
    *   `description`: Deskripsi detail produk
    *   `price`: Harga barang
    *   `stock`: Jumlah stok awal
    *   `category_id`: Pilihan kategori barang
    *   `image`: Berkas foto produk (.png, .jpg)

*   **Process (Proses):**
    Sistem akan mengirim *request POST* ke rute `/products/add`. *Controller Product* melakukan pemrosesan sebagai berikut:
    1. Memvalidasi seluruh masukan untuk memastikan tidak ada kolom yang kosong.
    2. Mengunggah dan memindahkan *file* gambar ke folder statis di server.
    3. Memanggil *Model* (`Product_model->addProduct($data)`) untuk menjalankan perintah `INSERT INTO` ke dalam tabel database.

*   **Output (Keluaran):**
    *   Data produk tersimpan di *database*.
    *   Sistem menampilkan notifikasi sukses/gagal (*flash message*).
    *   Produk baru langsung dirender ke dalam etalase toko dan halaman *Catalog* utama.

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Tampilan form penambahan produk yang sudah terisi)*
> *   *(Screenshot: Tampilan halaman katalog yang menunjukkan produk berhasil ditambahkan)*
> *   *(Opsional Snippet Code: Tunjukkan bagian form input atau Controller pemrosesan)*

---

### 2.2. Fitur Penyelesaian Pesanan & Distribusi Dana (Order Completion)
Fitur ini dijalankan oleh Pembeli untuk mengonfirmasi bahwa barang telah diterima, yang memicu distribusi uang ke pihak penjual.

*   **Input (Masukan):**
    Pengguna menekan tombol "Selesai" pada halaman detail pesanan. Sistem mengirimkan *request POST* berupa ID pesanan (`$id`) ke *Controller* `Pesanan::complete($id)`.

*   **Process (Proses):**
    Sistem melakukan verifikasi dan kalkulasi finansial di sisi *server*:
    1. Memastikan status pesanan saat ini adalah "Dikirim" dan milik pengguna yang sedang *login*.
    2. Mengubah status pesanan di database menjadi **"Selesai"**.
    3. **Automated Chat:** Sistem secara otomatis mengirimkan pesan *chat* dari Pelapak ke Pembeli berupa ucapan terima kasih via `Chat_model`.
    4. **Distribusi Finansial (Sesuai Aturan Sistem):** Menghitung total harga produk, kemudian memotong **biaya platform (fee marketplace) sebesar 2%**.
    5. Menambahkan saldo bersih (*revenue*) ke akun Penjual melalui `User_model->addBalance()`.

*   **Output (Keluaran):**
    *   Status *Order* pada antarmuka berubah menjadi "Selesai".
    *   Notifikasi: *"Pesanan telah dikonfirmasi selesai. Dana telah diteruskan ke pelapak (dikurangi fee 2%)."*
    *   Saldo akun Penjual (di dashboard) bertambah.

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Pesanan dengan status 'Dikirim' yang memiliki tombol 'Selesai')*
> *   *(Screenshot: Pesan sukses berwarna hijau (flash message) dan status berubah menjadi Selesai)*
> *   *(Screenshot: Tampilan history saldo penjual yang bertambah)*
> *   *(Opsional Snippet Code: Bagian pemotongan fee 2% dan Chat Otomatis di `Pesanan.php` baris 104-132)*

---

### 2.3. Fitur Pengajuan Pembatalan atau Retur Pesanan
Mekanisme pengajuan jika pembeli ingin membatalkan atau mengembalikan barang.

*   **Input (Masukan):**
    Pembeli membuka modal pop-up "Batalkan Pesanan" atau "Retur" dan mengisi kolom *textarea* berupa Alasan (`reason`). Form dikirim (*POST*) ke `Pesanan::cancel($id)` atau `Pesanan::return_order($id)`.

*   **Process (Proses):**
    1. *Controller* memeriksa kecocokan status pesanan (misal pembatalan hanya bisa jika status *Menunggu Pembayaran/Konfirmasi*).
    2. Menggunakan fungsi *Model* `updateStatusAndReason($id, 'Pengajuan Pembatalan', $reason)`.
    3. Sistem mencatat waktu dan alasan pembatalan untuk dievaluasi oleh Admin atau Toko terkait.

*   **Output (Keluaran):**
    *   Status Pesanan berubah menjadi "Pengajuan Pembatalan" atau "Pengajuan Pengembalian" (dengan warna peringatan).
    *   Muncul notifikasi: *"Pengajuan berhasil dikirim. Menunggu konfirmasi..."*

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Modal pop-up pengisian alasan retur/pembatalan)*
> *   *(Screenshot: Detail pesanan dengan status 'Pengajuan Pembatalan' beserta teks alasannya di layar)*

---

### 2.4. Akses Data Melalui REST API (Endpoint Jelas dan Konsisten)
Sistem PasarKita juga menyediakan layanan web service (API) yang memiliki endpoint yang terstruktur dan konsisten, dipusatkan pada `api/index.php`. 

*   **Input (Masukan):**
    Aplikasi *client* (seperti Postman atau aplikasi pihak ketiga) mengirimkan HTTP Request (GET/POST/PUT) ke *base URL* dengan menggunakan parameter `endpoint`. Contoh:
    *   `GET ?endpoint=products` (Mendapatkan data semua produk)
    *   `POST ?endpoint=products` (Menambah produk baru melalui payload JSON)
    *   `GET ?endpoint=orders` (Mendapatkan daftar pesanan)
    *   `PUT ?endpoint=orders&action=status` (Memperbarui status pesanan)

*   **Process (Proses):**
    Sistem API bertindak sebagai *router* yang menangkap *method* HTTP dan rute `endpoint`. *Controller* API kemudian berinteraksi dengan Model terkait (seperti `Product_model` atau `Order_model`) untuk mengeksekusi *query* ke database dengan logika dan aturan yang sama.

*   **Output (Keluaran):**
    Sistem mengembalikan hasil balasan berupa struktur data JSON (*JavaScript Object Notation*) yang rapi dan konsisten, memudahkan interaksi antar-sistem.

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Aplikasi Postman yang sedang melakukan request `GET ?endpoint=products` dan menampilkan balasan data berformat JSON)*
> *   *(Screenshot: Tampilan kumpulan Endpoint di dalam Postman Collection)*

---

### 2.5. Keamanan & Integritas Data: Penerapan Validasi Input
Untuk mencegah data *error* masuk ke dalam sistem, PasarKita menerapkan mekanisme validasi yang ketat pada tahapan *Input* dan *Process*.

*   **Input (Masukan):**
    Pengguna mencoba mengirimkan formulir (misalnya *form* Tambah Produk) dengan data yang tidak lengkap, sengaja dikosongkan, atau formatnya tidak sesuai (contoh: memasukkan nilai negatif pada kolom harga).

*   **Process (Proses):**
    Di sisi *backend*, *Controller* (contoh: fungsi penambahan produk) tidak akan langsung memanggil fungsi *Model* untuk menyimpan ke *database*. Sistem menjalankan pemeriksaan validasi (*conditional check*). Apabila masukan terdeteksi tidak valid, sistem membatalkan alur eksekusi *database* dan menyimpan pesan kesalahan (misalnya `$data['name_err'] = 'Nama produk wajib diisi'`).

*   **Output (Keluaran):**
    Sistem merender ulang halaman *form* tersebut, namun memberikan *Output* visual berupa *border* berwarna merah dan pesan teks *error* yang jelas tepat di bawah kolom *input* yang bermasalah. Hal ini membantu memandu pengguna untuk memperbaiki data yang salah.

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Tampilan antarmuka (UI) Form Tambah Produk saat sengaja dikosongkan lalu tombol submit ditekan, sehingga muncul pesan peringatan/error berwarna merah)*
> *   *(Opsional Snippet Code: Bukti potongan *code* dari *View* atau *Controller* yang menampilkan variabel pesan error)*

---

### 2.6. Pencatatan Sistem (System Logging) untuk Request dan Transaksi
Untuk kebutuhan audit dan pemantauan sistem, PasarKita memiliki mekanisme *logging* otomatis yang merekam setiap aktivitas *request* pada sistem API.

*   **Input (Masukan):**
    Setiap kali ada akses *HTTP Request* ke layanan API PasarKita, server secara implisit menerima informasi latar belakang seperti Waktu, Alamat IP pengakses (*User/App*), *Method*, dan rute *Endpoint*.

*   **Process (Proses):**
    Sebelum sistem mengirimkan respons ke klien, fungsi internal `logApiRequest()` di dalam file `api/index.php` akan mengekstrak data koneksi (menggunakan variabel global `$_SERVER`). Data tersebut kemudian diformat ke dalam sebuah *string* terstruktur: `[WAKTU] [IP ADDRESS] METHOD ENDPOINT | STATUS | ERROR_MSG`.

*   **Output (Keluaran):**
    Sistem menyalin (*append*) *string* tersebut dan menyimpannya secara fisik ke dalam *file* berekstensi `.log` (yaitu `api/api_request.log`). *File* ini akan terus bertambah setiap kali ada interaksi, sehingga merekam secara akurat: waktu, endpoint, user/app, status, dan pesan error jika transaksi gagal.

> **Bukti (Lampirkan Tangkapan Layar di bawah ini):**
> *   *(Screenshot: Tampilan isi file `api/api_request.log` di dalam VSCode yang menampilkan daftar baris log aktivitas sistem)*
> *   *(Opsional Snippet Code: Tunjukkan fungsi `logApiRequest()` di dalam `api/index.php`)*

---

## 3. Kesimpulan
Aplikasi PasarKita telah mengimplementasikan alur logika yang kokoh berdasarkan prinsip **IPO (Input-Proses-Output)**. Setiap aksi dari pengguna (*Input*) divalidasi dan diproses melalui logika bisnis MVC di *backend* (*Proses*) — seperti kalkulasi potongan fee 2% dan pengiriman notifikasi/chat otomatis — yang pada akhirnya disajikan kembali dalam bentuk pembaruan UI dan modifikasi *database* (*Output*) secara presisi.

---
*Catatan: Pastikan untuk mengganti teks di dalam tanda kurung seperti `*(Screenshot: ...)*` dengan gambar asli (hasil screenshot) dari aplikasi Anda saat dijalankan (di browser).*
