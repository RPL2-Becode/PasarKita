-- Database: pasar_kita

CREATE DATABASE IF NOT EXISTS pasar_kita;
USE pasar_kita;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('consumen', 'admin', 'pelapak', 'operator') NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    store_name VARCHAR(100) DEFAULT NULL,
    store_description TEXT DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    store_banner VARCHAR(255) DEFAULT NULL,
    balance_id VARCHAR(100) DEFAULT NULL COMMENT 'SmartBank balance reference ID',
    balance DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(15,2) NOT NULL,
    stock INT NOT NULL,
    image_url VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(20) PRIMARY KEY,
    buyer_id INT,
    total_subtotal DECIMAL(15,2),
    fee_marketplace DECIMAL(15,2),
    fee_shipping DECIMAL(15,2),
    total_payment DECIMAL(15,2),
    status ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan', 'Pengajuan Pembatalan') DEFAULT 'Menunggu Pembayaran',
    smartbank_trx_id VARCHAR(100),
    shipping_service VARCHAR(50) DEFAULT NULL COMMENT 'e.g. JNE, J&T, SiCepat',
    resi_number VARCHAR(100) DEFAULT NULL COMMENT 'Nomor resi pengiriman',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20),
    product_id INT,
    quantity INT,
    price_at_purchase DECIMAL(15,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Initial Data
INSERT INTO categories (name) VALUES ('Makanan'), ('Minuman'), ('Pakaian'), ('Kerajinan');

-- Dummy User (Admin: admin123, Budi: budi123, Juna: juna123, RajaKentang: kentang123)
INSERT INTO users (username, password, role, balance) VALUES 
('admin', '$2y$10$S7x1hbrL4bavEMz2B7O4ceRIixBHHH7SliQ/VmQaIzlmYMu0LU3La', 'admin', 0),
('budi', '$2y$10$MHBBH5ioHp77Sp0CldFsruKXlLDqN04Xjtp5q/1Se43T5Shkpfoay', 'consumen', 2500000),
('RajaKentang', '$2y$10$/J3YcnlQl2EnvA08jtL2o.1/svrPMDFjVgOqnRUvkbQ0ek/NVzR3a', 'pelapak', 0),
('juna', '$2y$10$juKShCgEaSTiVhCMskzSh.dyDnBZFmZTcrivHdgZh3X1mobF1LnM6', 'operator', 0);

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    order_id VARCHAR(20),
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Wishlists Table
CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE(user_id, product_id)
);
