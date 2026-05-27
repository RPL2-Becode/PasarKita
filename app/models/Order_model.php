<?php
/**
 * Order Model
 */
class Order_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
        
        // Self-healing: ensure status enum has all values and cancellation_reason exists
        try {
            $this->db->query("ALTER TABLE orders MODIFY COLUMN status ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Diserahkan ke Kurir', 'Dikirim', 'Selesai', 'Dibatalkan', 'Pengajuan Pembatalan', 'Pengajuan Pengembalian', 'Dikembalikan') DEFAULT 'Menunggu Pembayaran'");
            $this->db->execute();
            $this->db->query("ALTER TABLE orders ADD COLUMN cancellation_reason TEXT DEFAULT NULL");
            $this->db->execute();
        } catch (Exception $e) { }
    }

    // Create Order
    public function createOrder($data) {
        $order_id = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $this->db->query('INSERT INTO orders (id, buyer_id, total_subtotal, fee_marketplace, fee_shipping, total_payment, status, shipping_service) VALUES(:id, :buyer_id, :subtotal, :fee_m, :fee_s, :total, :status, :shipping_service)');
        
        $this->db->bind(':id', $order_id);
        $this->db->bind(':buyer_id', $data['buyer_id']);
        $this->db->bind(':subtotal', $data['subtotal']);
        $this->db->bind(':fee_m', $data['fee_marketplace']);
        $this->db->bind(':fee_s', $data['fee_shipping']);
        $this->db->bind(':total', $data['total_payment']);
        $this->db->bind(':status', 'Menunggu Pembayaran');
        $this->db->bind(':shipping_service', $data['shipping_service'] ?? 'JNE');

        if ($this->db->execute()) {
            return $order_id;
        } else {
            return false;
        }
    }

    // Create Order Item
    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES(:order_id, :product_id, :qty, :price)');
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':qty', $quantity);
        $this->db->bind(':price', $price);

        return $this->db->execute();
    }

    // Update Order Status
    public function updateStatus($order_id, $status) {
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Update Order Status and Reason
    public function updateStatusAndReason($order_id, $status, $reason) {
        $this->db->query('UPDATE orders SET status = :status, cancellation_reason = :reason WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':reason', $reason);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Update SmartBank transaction ID
    public function updateSmartBankTrxId($order_id, $trx_id) {
        $this->db->query('UPDATE orders SET smartbank_trx_id = :trx_id WHERE id = :id');
        $this->db->bind(':trx_id', $trx_id);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Update Resi & Shipping Service (Phase 2)
    public function updateResi($order_id, $shipping_service, $resi_number) {
        $this->db->query('UPDATE orders SET shipping_service = :service, resi_number = :resi WHERE id = :id');
        $this->db->bind(':service', $shipping_service);
        $this->db->bind(':resi', $resi_number);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Get all orders (for admin/operator)
    public function getAllOrders() {
        $this->db->query('SELECT o.*, u.username as buyer_name FROM orders o LEFT JOIN users u ON o.buyer_id = u.id ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }

    // Get orders by status
    public function getOrdersByStatus($status) {
        $this->db->query('SELECT o.*, u.username as buyer_name FROM orders o LEFT JOIN users u ON o.buyer_id = u.id WHERE o.status = :status ORDER BY o.created_at DESC');
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    // Get order by ID
    public function getOrderById($order_id) {
        $this->db->query('SELECT o.*, u.username as buyer_name FROM orders o LEFT JOIN users u ON o.buyer_id = u.id WHERE o.id = :id');
        $this->db->bind(':id', $order_id);
        return $this->db->single();
    }

    // Get order items
    public function getOrderItems($order_id) {
        $this->db->query('SELECT oi.*, p.seller_id, p.name as product_name, p.image_url, u.store_name, u.username as seller_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id LEFT JOIN users u ON p.seller_id = u.id WHERE oi.order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }

    // Get orders by buyer
    public function getOrdersByBuyer($buyer_id) {
        $this->db->query('SELECT * FROM orders WHERE buyer_id = :buyer_id ORDER BY created_at DESC');
        $this->db->bind(':buyer_id', $buyer_id);
        return $this->db->resultSet();
    }

    // Dashboard statistics
    public function getTotalRevenue() {
        $this->db->query("SELECT SUM(total_payment) as total FROM orders WHERE status NOT IN ('Dibatalkan', 'Menunggu Pembayaran')");
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getTotalFees() {
        $this->db->query("SELECT SUM(fee_marketplace) as total FROM orders WHERE status NOT IN ('Dibatalkan', 'Menunggu Pembayaran')");
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getOrderCount() {
        $this->db->query('SELECT COUNT(*) as total FROM orders');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getOrderCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as total FROM orders WHERE status = :status');
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // --- Seller Insight Methods ---
    public function getSellerRevenue($seller_id) {
        $this->db->query("SELECT SUM(oi.price_at_purchase * oi.quantity) as total FROM order_items oi JOIN products p ON oi.product_id = p.id JOIN orders o ON oi.order_id = o.id WHERE p.seller_id = :seller_id AND o.status IN ('Selesai', 'Dikirim')");
        $this->db->bind(':seller_id', $seller_id);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getSellerOrderCount($seller_id) {
        $this->db->query("SELECT COUNT(DISTINCT o.id) as total FROM order_items oi JOIN products p ON oi.product_id = p.id JOIN orders o ON oi.order_id = o.id WHERE p.seller_id = :seller_id AND o.status != 'Dibatalkan' AND o.status != 'Menunggu Pembayaran'");
        $this->db->bind(':seller_id', $seller_id);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getSellerTopProducts($seller_id) {
        $this->db->query("SELECT p.name, p.image_url, SUM(oi.quantity) as sold_count, SUM(oi.price_at_purchase * oi.quantity) as revenue FROM order_items oi JOIN products p ON oi.product_id = p.id JOIN orders o ON oi.order_id = o.id WHERE p.seller_id = :seller_id AND o.status IN ('Selesai', 'Dikirim') GROUP BY p.id ORDER BY sold_count DESC LIMIT 5");
        $this->db->bind(':seller_id', $seller_id);
        return $this->db->resultSet();
    }

    // Get orders containing products belonging to a specific seller
    public function getOrdersBySeller($seller_id) {
        $this->db->query('SELECT DISTINCT o.*, u.username as buyer_name 
                          FROM orders o 
                          JOIN order_items oi ON o.id = oi.order_id 
                          JOIN products p ON oi.product_id = p.id 
                          LEFT JOIN users u ON o.buyer_id = u.id 
                          WHERE p.seller_id = :seller_id 
                          ORDER BY o.created_at DESC');
        $this->db->bind(':seller_id', $seller_id);
        return $this->db->resultSet();
    }

    // Get orders containing products belonging to a specific seller with a status filter
    public function getOrdersBySellerAndStatus($seller_id, $status) {
        $this->db->query('SELECT DISTINCT o.*, u.username as buyer_name 
                          FROM orders o 
                          JOIN order_items oi ON o.id = oi.order_id 
                          JOIN products p ON oi.product_id = p.id 
                          LEFT JOIN users u ON o.buyer_id = u.id 
                          WHERE p.seller_id = :seller_id AND o.status = :status 
                          ORDER BY o.created_at DESC');
        $this->db->bind(':seller_id', $seller_id);
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }
}
?>
