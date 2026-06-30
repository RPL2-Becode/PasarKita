<?php
// Script untuk membuat dan mengimpor tabel ke dummy_smartbank_db & dummy_logistik_db secara otomatis

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. SETUP SMARTBANK DB
    echo "<h3>Setting up SmartBank Database...</h3>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dummy_smartbank_db");
    $pdo->exec("USE dummy_smartbank_db");
    
    $smartbank_sql = file_get_contents(__DIR__ . '/smartbank/schema_smartbank.sql');
    if ($smartbank_sql) {
        $pdo->exec($smartbank_sql);
        echo "<p style='color:green;'>Berhasil membuat tabel di dummy_smartbank_db dan mengisi saldo awal Rp 50.000 (Akun: 1234567890).</p>";
    } else {
        echo "<p style='color:red;'>Gagal membaca schema_smartbank.sql</p>";
    }

    // 2. SETUP LOGISTIK DB
    echo "<h3>Setting up LogistikKita Database...</h3>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dummy_logistik_db");
    $pdo->exec("USE dummy_logistik_db");
    
    $logistik_sql = file_get_contents(__DIR__ . '/logistik/schema_logistik.sql');
    if ($logistik_sql) {
        $pdo->exec($logistik_sql);
        echo "<p style='color:green;'>Berhasil membuat tabel di dummy_logistik_db.</p>";
    } else {
        echo "<p style='color:red;'>Gagal membaca schema_logistik.sql</p>";
    }

    echo "<h3>✅ Setup Selesai!</h3>";
    echo "<p>Silakan coba kembali tombol <strong>Cek Ongkir</strong> dan <strong>Bayar Sekarang</strong> pada UI Demo.</p>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Koneksi Database Gagal</h3>";
    echo "Pastikan server MySQL (XAMPP/Laragon) Anda sudah menyala dan user root tidak menggunakan password.<br>";
    echo "Error: " . $e->getMessage();
}
?>
