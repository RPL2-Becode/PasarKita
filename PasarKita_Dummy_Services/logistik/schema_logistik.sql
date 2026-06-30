CREATE DATABASE IF NOT EXISTS dummy_logistik_db;
USE dummy_logistik_db;

CREATE TABLE `shipments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `resi` VARCHAR(50) NOT NULL UNIQUE,
    `order_reference` VARCHAR(50),
    `service_type` VARCHAR(20),
    `shipping_cost` DECIMAL(15,2),
    `status` VARCHAR(50) DEFAULT 'Menunggu Pickup',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `tracking_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `resi` VARCHAR(50) NOT NULL,
    `status_update` VARCHAR(255),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`resi`) REFERENCES `shipments`(`resi`) ON DELETE CASCADE
);
