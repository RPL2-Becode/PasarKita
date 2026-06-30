<?php
// Script untuk meng-update database 'pasar_kita' 
// Menyesuaikan struktur dengan kebutuhan arsitektur microservices (Dummy API)

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pasar_kita'; // Sesuai gambar screenshot dari Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Memulai Migrasi Database: $db</h3>";

    // 1. Modifikasi Tabel 'users' (Menghapus kolom balance)
    try {
        $pdo->exec("ALTER TABLE users DROP COLUMN balance");
        echo "<p style='color:green;'>✅ Berhasil: Kolom 'balance' dihapus dari tabel 'users'. Wewenang finansial sekarang 100% ada di SmartBank.</p>";
    } catch (PDOException $e) {
        // Abaikan jika kolom tidak ada (sudah terhapus)
        if (strpos($e->getMessage(), "check that column/key exists") !== false) {
            echo "<p style='color:orange;'>⚠️ Info: Kolom 'balance' sudah tidak ada di tabel 'users'. Melewati proses ini.</p>";
        } else {
            throw $e;
        }
    }

    // 2. Modifikasi Tabel 'orders' (Menambahkan kolom logistik dan status)
    try {
        $query = "ALTER TABLE orders 
                  ADD COLUMN shipping_courier VARCHAR(50) NULL,
                  ADD COLUMN shipping_cost DECIMAL(10,2) DEFAULT 0,
                  ADD COLUMN resi VARCHAR(50) NULL,
                  ADD COLUMN payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending'";
        
        $pdo->exec($query);
        echo "<p style='color:green;'>✅ Berhasil: Kolom Logistik dan Pembayaran (shipping_courier, shipping_cost, resi, payment_status) berhasil ditambahkan ke tabel 'orders'.</p>";
    } catch (PDOException $e) {
        // Abaikan jika kolom sudah ada
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
            echo "<p style='color:orange;'>⚠️ Info: Kolom untuk Logistik/Pembayaran sudah ada di tabel 'orders'. Melewati proses ini.</p>";
        } else {
            throw $e;
        }
    }

    echo "<h3>🎉 Migrasi Selesai! Struktur Database telah siap.</h3>";
    echo "<p>Sekarang Anda bisa menghapus file script ini (migrate_db.php) demi keamanan.</p>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>❌ Terjadi Kesalahan Database</h3>";
    echo "Error: " . $e->getMessage();
}
?>
