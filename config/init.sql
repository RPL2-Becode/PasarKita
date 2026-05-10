-- Database: pasar_kita

CREATE DATABASE IF NOT EXISTS pasar_kita;
USE pasar_kita;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('consumen', 'admin', 'pelapak', 'operator') NOT NULL,
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
    status ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan') DEFAULT 'Menunggu Pembayaran',
    smartbank_trx_id VARCHAR(100),
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

-- Dummy User (Password: admin123)
INSERT INTO users (username, password, role, balance) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0),
('budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'consumen', 5000000),
('pelapak1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pelapak', 0),
('juna', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operator', 0);
