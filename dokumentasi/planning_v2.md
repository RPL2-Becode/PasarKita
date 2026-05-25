# Planning Pengembangan Lanjutan: PasarKita Marketplace V2

Karena fitur dasar (MVP - Minimum Viable Product) secara teknis sudah selesai, fase berikutnya berfokus pada **peningkatan skala (scaling)**, **retensi pengguna**, **kepercayaan (trust)**, dan **kenyamanan**.

Berikut adalah rancangan (planning) fitur-fitur untuk pengembangan versi 2.0 (V2) dari aplikasi PasarKita:

## 1. Fitur Interaksi & Komunitas (Social Features)
- **Sistem Rating & Ulasan (Review & Rating)**: Memungkinkan konsumen memberikan penilaian (bintang 1-5) dan ulasan tertulis beserta foto pada produk yang telah dibeli.
- **Fitur Chat/Pesan Langsung**: Sistem *real-time chat* antara Konsumen dan Pelapak untuk menanyakan detail produk, negosiasi, atau komplain.
- **Tanya Jawab Produk (Q&A)**: Bagian diskusi publik di halaman produk tempat konsumen bisa bertanya dan dijawab langsung oleh pelapak.

## 2. Peningkatan Pengalaman Pengguna (Advanced UX/UI)
- **Pencarian Lanjutan & Filter (Advanced Search)**: Filter produk berdasarkan rentang harga, lokasi pelapak, rating minimum, dan kategori spesifik.
- **Wishlist (Barang Favorit)**: Memungkinkan pengguna menyimpan produk yang ingin dibeli di masa mendatang.
- **Sistem Rekomendasi (Recommendation Engine)**: Menampilkan produk "Mungkin Anda Suka" berdasarkan riwayat penelusuran atau pembelian sebelumnya.

## 3. Logistik & Pengiriman Terintegrasi
- **Integrasi API Ekspedisi (Cek Ongkir Dinamis)**: Menggantikan ongkos kirim *flat* dengan perhitungan otomatis dari layanan kurir nyata (misal: integrasi API RajaOngkir untuk JNE, J&T, SiCepat).
- **Live Tracking Pengiriman**: Menampilkan status pengiriman barang dan nomor resi secara otomatis kepada konsumen.
- **Dukungan Kurir Instant (GoSend/GrabExpress)**: Opsi pengiriman di hari yang sama untuk pelapak dan konsumen dalam satu kota.

## 4. Keamanan & Kepercayaan (Security & Trust)
- **Verifikasi Pelapak (KYC - Know Your Customer)**: Sistem centang biru atau "Toko Terverifikasi" setelah pelapak mengunggah KTP dan verifikasi bisnis.
- **Sistem Resolusi Konflik (Pusat Bantuan / Retur)**: Alur yang jelas jika barang yang diterima rusak atau tidak sesuai, termasuk opsi *Refund* uang ke Saldo.
- **Autentikasi Dua Langkah (2FA)**: Untuk mengamankan akun pengguna dan pelapak dari peretasan.

## 5. Marketing & Penjualan (Promotions)
- **Sistem Kupon & Diskon**: Fitur bagi Pelapak untuk membuat voucher diskon (potongan harga atau persentase).
- **Flash Sale & Campaign Marketplace**: Admin dapat membuat *event* khusus di beranda untuk mendongkrak penjualan (misal: "Promo UMKM Merdeka").
- **Program Poin/Loyalitas**: Memberikan poin setiap kali konsumen berbelanja yang bisa ditukar dengan potongan harga di transaksi berikutnya.

## 6. Analitik & Laporan Lanjutan (Data Dashboard)
- **Dashboard Analitik Pelapak**: Grafik penjualan harian/bulanan, produk terlaris, dan jumlah pengunjung toko.
- **Laporan Performa UMKM (Admin)**: Admin dapat melihat data agregat pertumbuhan transaksi, tren produk, dan kesehatan ekonomi ekosistem aplikasi.

---

## Rencana Implementasi (Roadmap V2)

| Fase | Fokus Pengembangan | Estimasi Waktu |
|------|--------------------|----------------|
| **Fase 1** | Rating & Ulasan, Wishlist, Filter Pencarian Lanjutan | 2-3 Minggu |
| **Fase 2** | Integrasi API Cek Ongkir & Live Tracking Pengiriman | 2 Minggu |
| **Fase 3** | Fitur Chat Real-time & Sistem Diskon/Kupon | 3 Minggu |
| **Fase 4** | Verifikasi Pelapak, Sistem Retur, dan Keamanan | 2 Minggu |
| **Fase 5** | Dashboard Analitik & Fitur Marketing (Flash Sale) | 2 Minggu |

> **Saran Teknis:** Untuk fitur seperti Chat Real-time dan Notifikasi, disarankan mulai mempertimbangkan penggunaan teknologi seperti **WebSockets** (contoh: Socket.io dengan Node.js, atau Pusher untuk PHP) agar performa aplikasi tetap optimal.
