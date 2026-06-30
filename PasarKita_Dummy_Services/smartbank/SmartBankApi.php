<?php
// File: dummy_integration/php_api_dummy/smartbank_mock/SmartBankApi.php
header('Content-Type: application/json');

class SmartBankApi {
    private $pdo;

    public function __construct() {
        // Koneksi mandiri ke database SmartBank
        $host = 'localhost';
        $db   = 'dummy_smartbank_db';
        $user = 'root'; // Sesuaikan username DB Anda
        $pass = '';     // Sesuaikan password DB Anda

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(json_encode(["status" => "error", "message" => "SmartBank System Offline (DB Connection Failed)"]));
        }
    }

    public function process_payment() {
        // Data dari aplikasi eksternal (misal: PasarKita)
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '1234567890';
        $order_reference = isset($_POST['order_id']) ? $_POST['order_id'] : 'ORDER-' . time();
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;

        if ($amount <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid amount"]);
            return;
        }

        // Kalkulasi fee sesuai Aturan Keuangan
        $fee_bank = $amount * 0.01;
        $fee_gateway = $amount * 0.005;
        $system_tax = $amount * 0.02;
        $marketplace_fee = $amount * 0.02; // Asumsi dipotong dari penjual, tapi dicatat di ledger SmartBank

        $total_debit = $amount + $fee_bank + $fee_gateway + $system_tax;

        try {
            $this->pdo->beginTransaction();

            // Cek Saldo
            $stmt = $this->pdo->prepare("SELECT balance FROM users WHERE account_number = ? FOR UPDATE");
            $stmt->execute([$account_number]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || $user['balance'] < $total_debit) {
                $this->pdo->rollBack();
                echo json_encode(["status" => "error", "message" => "Saldo tidak mencukupi untuk menanggung transaksi dan fee terkait."]);
                return;
            }

            // Potong Saldo
            $new_balance = $user['balance'] - $total_debit;
            $stmtUpdate = $this->pdo->prepare("UPDATE users SET balance = ? WHERE account_number = ?");
            $stmtUpdate->execute([$new_balance, $account_number]);

            // Catat Ledger
            $transaction_id = "SB-" . uniqid();
            $stmtLedger = $this->pdo->prepare("INSERT INTO ledgers (transaction_id, order_reference, account_number, amount_debited, fee_bank, fee_gateway, system_tax, marketplace_fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'success')");
            $stmtLedger->execute([$transaction_id, $order_reference, $account_number, $amount, $fee_bank, $fee_gateway, $system_tax, $marketplace_fee]);

            $this->pdo->commit();

            echo json_encode([
                "status" => "success",
                "transaction_id" => $transaction_id,
                "message" => "Pembayaran berhasil. Saldo telah dipotong beserta fee (Bank: $fee_bank, Gateway: $fee_gateway, Tax: $system_tax)."
            ]);

        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(["status" => "error", "message" => "Terjadi kesalahan sistem: " . $e->getMessage()]);
        }
    }
}

// Router Sederhana
if (isset($_GET['action']) && $_GET['action'] == 'pay') {
    $api = new SmartBankApi();
    $api->process_payment();
}
