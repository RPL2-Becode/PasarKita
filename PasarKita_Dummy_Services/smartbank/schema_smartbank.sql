CREATE DATABASE IF NOT EXISTS dummy_smartbank_db;
USE dummy_smartbank_db;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `account_number` VARCHAR(20) NOT NULL UNIQUE,
    `username` VARCHAR(50) NOT NULL,
    `balance` DECIMAL(15,2) DEFAULT 0
);

CREATE TABLE `ledgers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` VARCHAR(50) NOT NULL UNIQUE,
    `order_reference` VARCHAR(50),
    `account_number` VARCHAR(20),
    `amount_debited` DECIMAL(15,2),
    `fee_bank` DECIMAL(10,2),
    `fee_gateway` DECIMAL(10,2),
    `system_tax` DECIMAL(10,2),
    `marketplace_fee` DECIMAL(10,2),
    `status` ENUM('success', 'failed', 'pending') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Dummy Awal Sesuai Aturan: Saldo Awal Mahasiswa = 50.000
INSERT INTO `users` (`account_number`, `username`, `balance`) VALUES ('1234567890', 'mahasiswa_demo', 50000);
