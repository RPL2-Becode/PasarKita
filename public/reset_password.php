<?php
require_once '../config/db.php';

try {
    $db = new Database();
    
    // Hash baru untuk 'admin123' yang terjamin cocok dengan server PHP saat ini
    $new_password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    $db->query('UPDATE users SET password = :password');
    $db->bind(':password', $new_password_hash);
    
    if ($db->execute()) {
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
        echo "<h1 style='color: green;'>✅ Password Berhasil Di-reset!</h1>";
        echo "<p>Semua akun (admin, juna, budi, pelapak1) sekarang menggunakan password: <strong>admin123</strong></p>";
        echo "<a href='/users/login' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #ee4d2d; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Kembali ke Login</a>";
        echo "</div>";
    } else {
        echo "Gagal mereset password.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
