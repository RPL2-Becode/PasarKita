# 📌 Dokumentasi REST API PasarKita v1.0

Selamat datang di Dokumentasi REST API untuk **PasarKita Marketplace**. API ini dirancang untuk memudahkan integrasi data produk, kategori, pesanan, dan pengguna dengan format respons **JSON** yang standar.

---

## 🚀 Informasi Umum & Base URL

Secara default, semua request API diarahkan ke file entry point `api/index.php` pada server lokal atau production Anda.

* **Base URL (Localhost):** `http://localhost/pasar-kita/api/index.php`
* **Format Respons:** JSON (`Content-Type: application/json; charset=UTF-8`)
* **Metode HTTP yang Didukung:** `GET`, `POST`, `PUT`, `DELETE`, `OPTIONS`
* **CORS:** Diaktifkan secara penuh (`Access-Control-Allow-Origin: *`)

### Struktur Respons Standar

Setiap respons dari API akan dibungkus dengan format berikut:

```json
{
  "status": "success" | "error",
  "data": [ ... ] | { ... },
  "timestamp": "YYYY-MM-DD HH:MM:SS"
}
```

---

## 🛣️ Daftar Endpoint API (API Endpoints)

| Metode | Endpoint | Parameter Query | Deskripsi |
|:---|:---|:---|:---|
| **GET** | `?endpoint=products` | - | Mengambil semua daftar produk beserta nama kategorinya. |
| **POST** | `?endpoint=products` | - | Menambahkan produk baru ke marketplace. |
| **GET** | `?endpoint=categories` | - | Mengambil daftar semua kategori produk. |
| **GET** | `?endpoint=orders` | - | Mengambil semua daftar pesanan/order dari pelanggan. |
| **PUT** | `?endpoint=orders&action=status` | `action=status` | Memperbarui status pesanan (e.g., "Dikemas", "Dikirim", "Selesai"). |
| **GET** | `?endpoint=users` | - | Mengambil daftar pengguna terdaftar (aman dari informasi sensitif). |

---

## 🛠️ Detail Endpoint & Contoh Penggunaan

### 1. Products API (`?endpoint=products`)

#### A. Mengambil Daftar Produk (GET)
Mengambil semua produk yang aktif di marketplace diurutkan dari yang terbaru.

* **Request:**
  ```http
  GET /api/index.php?endpoint=products HTTP/1.1
  Host: localhost
  Accept: application/json
  ```

* **Contoh Respons Sukses (200 OK):**
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": 1,
        "seller_id": 2,
        "name": "Beras Pandan Wangi Premium 5kg",
        "description": "Beras organik berkualitas tinggi langsung dari petani lokal.",
        "price": 75000,
        "stock": 50,
        "image_url": "uploads/beras.jpg",
        "category_id": 3,
        "created_at": "2026-06-01 12:00:00",
        "category_name": "Bahan Pokok"
      }
    ],
    "timestamp": "2026-06-01 19:45:12"
  }
  ```

#### B. Menambahkan Produk Baru (POST)
Menambahkan produk baru yang dijual oleh pelapak/seller.

* **Request Headers:** `Content-Type: application/json`
* **Request Body (JSON):**
  ```json
  {
    "seller_id": 2,
    "name": "Minyak Goreng SunCo 2L",
    "description": "Minyak goreng berkualitas tinggi, jernih, dan hemat.",
    "price": 38000,
    "stock": 100,
    "image_url": "uploads/minyak.jpg",
    "category_id": 3
  }
  ```

* **Contoh Respons Sukses (201 Created):**
  ```json
  {
    "status": "success",
    "data": {
      "message": "Product created"
    },
    "timestamp": "2026-06-01 19:46:00"
  }
  ```

---

### 2. Categories API (`?endpoint=categories`)

#### Mengambil Daftar Kategori (GET)
Mengambil seluruh kategori produk untuk filter atau navigasi menu.

* **Request:**
  ```http
  GET /api/index.php?endpoint=categories HTTP/1.1
  Host: localhost
  ```

* **Contoh Respons Sukses (200 OK):**
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": 1,
        "name": "Alat Tulis",
        "created_at": "2026-05-20 10:00:00"
      },
      {
        "id": 3,
        "name": "Bahan Pokok",
        "created_at": "2026-05-20 10:00:00"
      }
    ],
    "timestamp": "2026-06-01 19:47:05"
  }
  ```

---

### 3. Orders API (`?endpoint=orders`)

#### A. Mengambil Daftar Semua Pesanan (GET)
Mengambil daftar transaksi/pesanan lengkap dengan nama pembeli.

* **Request:**
  ```http
  GET /api/index.php?endpoint=orders HTTP/1.1
  Host: localhost
  ```

* **Contoh Respons Sukses (200 OK):**
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": 10,
        "buyer_id": 5,
        "total_price": 81500,
        "status": "Pending",
        "smartbank_trx_id": "TX9921827A",
        "shipping_service": "LogistikKita",
        "resi_number": null,
        "created_at": "2026-06-01 15:30:00",
        "buyer_name": "Andi_Pratama"
      }
    ],
    "timestamp": "2026-06-01 19:48:10"
  }
  ```

#### B. Memperbarui Status Pesanan (PUT)
Memperbarui status pengiriman/proses dari sebuah order.

* **Request URL:** `api/index.php?endpoint=orders&action=status`
* **Request Headers:** `Content-Type: application/json`
* **Request Body (JSON):**
  ```json
  {
    "order_id": 10,
    "status": "Dikirim"
  }
  ```

* **Contoh Respons Sukses (200 OK):**
  ```json
  {
    "status": "success",
    "data": {
      "message": "Order status updated"
    },
    "timestamp": "2026-06-01 19:49:15"
  }
  ```

---

### 4. Users API (`?endpoint=users`)

#### Mengambil Daftar Pengguna (GET)
Mengambil semua daftar user terdaftar beserta perannya (Admin/Pelapak/Konsumen) dan saldo dompet digital mereka tanpa membocorkan data password atau data sensitif lainnya.

* **Request:**
  ```http
  GET /api/index.php?endpoint=users HTTP/1.1
  Host: localhost
  ```

* **Contoh Respons Sukses (200 OK):**
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": 1,
        "username": "admin_pasarkita",
        "role": "Admin",
        "balance": 1500000,
        "created_at": "2026-04-01 08:00:00"
      },
      {
        "id": 2,
        "username": "toko_makmur",
        "role": "Pelapak",
        "balance": 345000,
        "created_at": "2026-04-10 09:15:30"
      }
    ],
    "timestamp": "2026-06-01 19:50:00"
  }
  ```

---

## 🧪 Cara Pengujian API (API Testing)

Anda dapat menguji API ini dengan mudah menggunakan **Postman**, **Thunder Client** (di VS Code), atau perintah **cURL** di terminal.

### Contoh cURL untuk Mengambil Produk:
```bash
curl -X GET "http://localhost/pasar-kita/api/index.php?endpoint=products"
```

### Contoh cURL untuk Menambahkan Produk:
```bash
curl -X POST "http://localhost/pasar-kita/api/index.php?endpoint=products" \
     -H "Content-Type: application/json" \
     -d '{"seller_id": 2, "name": "Produk Baru", "description": "Deskripsi baru", "price": 10000, "stock": 10, "image_url": "", "category_id": 1}'
```
