<?php
// File: PasarKita_Dummy_Services/logistik/LogistikKitaApi.php
header('Content-Type: application/json');

class LogistikKitaApi {
    private $pdo;

    public function __construct() {
        // Koneksi mandiri ke database LogistikKita
        $host = 'localhost';
        $db   = 'dummy_logistik_db';
        $user = 'root'; // Sesuaikan
        $pass = '';     // Sesuaikan

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(json_encode(["status" => "error", "message" => "Logistik System Offline (DB Connection Failed)"]));
        }
    }

    public function get_shipping_rates() {
        $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
        $fee_percent = $amount * 0.05; // 5%

        $rates = [
            [
                "service_code" => "FLAT",
                "service_name" => "LogistikKita (Flat Rate)",
                "price" => 5000,
                "estimated_days" => "2-3 Hari"
            ],
            [
                "service_code" => "PCT",
                "service_name" => "LogistikKita (5% Transaksi)",
                "price" => $fee_percent > 0 ? $fee_percent : 5000,
                "estimated_days" => "1-2 Hari"
            ]
        ];

        echo json_encode([
            "status" => "success",
            "data" => $rates
        ]);
    }

    public function create_shipment() {
        $order_reference = isset($_POST['order_id']) ? $_POST['order_id'] : 'ORDER-' . time();
        $service_type = isset($_POST['service_type']) ? $_POST['service_type'] : 'FLAT';
        $shipping_cost = isset($_POST['shipping_cost']) ? (float)$_POST['shipping_cost'] : 5000;

        $resi = "LOG-" . strtoupper(uniqid());

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO shipments (resi, order_reference, service_type, shipping_cost) VALUES (?, ?, ?, ?)");
            $stmt->execute([$resi, $order_reference, $service_type, $shipping_cost]);

            $stmtHistory = $this->pdo->prepare("INSERT INTO tracking_history (resi, status_update) VALUES (?, ?)");
            $stmtHistory->execute([$resi, "Data pengiriman diterima, menunggu pickup kurir."]);

            $this->pdo->commit();

            echo json_encode([
                "status" => "success",
                "resi" => $resi,
                "message" => "Shipment created successfully."
            ]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function track_resi() {
        $resi = isset($_GET['resi']) ? $_GET['resi'] : '';

        if (!$resi) {
            echo json_encode(["status" => "error", "message" => "Nomor resi dibutuhkan"]);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM shipments WHERE resi = ?");
            $stmt->execute([$resi]);
            $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$shipment) {
                echo json_encode(["status" => "error", "message" => "Resi tidak ditemukan"]);
                return;
            }

            $stmtHistory = $this->pdo->prepare("SELECT status_update, updated_at FROM tracking_history WHERE resi = ? ORDER BY updated_at DESC");
            $stmtHistory->execute([$resi]);
            $history = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "status" => "success",
                "resi" => $resi,
                "current_status" => $shipment['status'],
                "history" => $history
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}

// Router
if (isset($_GET['action'])) {
    $api = new LogistikKitaApi();
    if ($_GET['action'] == 'rates') {
        $api->get_shipping_rates();
    } elseif ($_GET['action'] == 'create') {
        $api->create_shipment();
    } elseif ($_GET['action'] == 'track') {
        $api->track_resi();
    }
}
