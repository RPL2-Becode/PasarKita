# PasarKita V2: Phase 3 Implementation Plan

Berdasarkan `planning_v2.md`, **Fase 3** berfokus pada:
1. **Fitur Chat Real-time**
2. **Sistem Diskon/Kupon** (Belum Diimplementasikan)

---

## 1. Fitur Chat Real-time (Selesai) ✅

Fitur obrolan telah sepenuhnya diselesaikan dan diintegrasikan ke seluruh bagian aplikasi PasarKita.

### A. Struktur Database (Selesai)
- Tabel `messages` telah ditambahkan ke skema (terdapat di `init.sql`).
- Mendukung pesan antara pembeli dan pelapak.
- Menyimpan konteks produk (`product_id`) dan konteks pesanan (`order_id`) jika diskusi berasal dari halaman spesifik.
- Memiliki flag `is_read` untuk indikator notifikasi pesan belum dibaca.

### B. Controller & Model (Selesai)
- `Chat.php` (Controller): Menangani rute `/chat`, menampilkan daftar obrolan, halaman detail obrolan, dan fungsi `/chat/send` untuk mengirim pesan.
- `Chat_model.php` (Model): Menangani logika untuk mengambil riwayat percakapan (`getConversations`), mendapatkan pesan antar pengguna (`getMessages`), perhitungan pesan belum dibaca (`getUnreadCount`), serta memperbarui status baca (`markAsRead`).

### C. Tampilan & UI/UX (Selesai)
- Tampilan chat (`app/views/chat/index.php`) bergaya modern (seperti UI desktop Tokopedia) lengkap dengan daftar percakapan di sebelah kiri dan detail obrolan di sebelah kanan.
- Fitur auto-scroll ke bawah saat halaman chat dimuat.
- Desain *embedded* yang memunculkan kartu informasi produk atau pesanan ke dalam obrolan jika pelanggan bertanya langsung dari suatu produk atau pesanan.

### D. Integrasi UI/Tombol Chat (Selesai)
- **Header Navbar:** Ikon chat yang otomatis menampilkan *badge* merah berisi angka jika terdapat pesan masuk yang belum dibaca.
- **Halaman Detail Produk (`detail.php`):** Tombol "Chat Sekarang" di-redirect ke `/chat/index/[id_seller]?product_id=[id_produk]`.
- **Halaman Detail Pesanan Pembeli (`pesanan_detail.php`):** Tombol "Chat Penjual" di-redirect ke `/chat/index/[id_seller]?order_id=[id_order]`.
- **Halaman Dasbor Pesanan Pelapak (`products/orders.php`):** Ikon chat di sebelah nama pembeli untuk bertanya mengenai alamat atau detail pesanan (`/chat/index/[id_buyer]?order_id=[id_order]`).
- **Halaman Profil Toko (`toko/index.php`):** Tombol "Chat" di-redirect ke `/chat/index/[id_seller]`.

---

## 2. Status Fase 3: SEBAGIAN SELESAI

**Langkah berikutnya:** Mengerjakan **Sistem Diskon/Kupon** untuk Fase 3, lalu dilanjutkan ke Fase 4 (Sistem Retur, dsb).
