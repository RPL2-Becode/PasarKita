<?php
require_once '../app/models/Order_model.php';
require_once '../app/services/RevenueService.php';
require_once '../app/services/OrderNotificationService.php';

class OrderService {
    private $orderModel;
    private $revenueService;
    private $notificationService;

    public function __construct() {
        $this->orderModel = new Order_model();
        $this->revenueService = new RevenueService();
        $this->notificationService = new OrderNotificationService();
    }

    public function processAutoUpdates(): void {
        // 1. Auto Cancel (Status 'Menunggu Konfirmasi' > 2 days)
        $this->orderModel->processAutoCancel(2);

        // 2. Auto Complete (Status 'Dikirim' > 2 days)
        $ordersToComplete = $this->orderModel->findAutoCompletable(2);
        
        foreach ($ordersToComplete as $order) {
            if ($this->orderModel->updateStatus($order->id, 'Selesai')) {
                $items = $this->orderModel->getOrderItems($order->id);
                if (!empty($items)) {
                    $seller_id = $items[0]->seller_id;
                    $this->notificationService->notifyAutoCompletion($order, $seller_id);
                    $this->revenueService->distributeSellerRevenue($items);
                }
            }
        }
    }
    
    // Complete an order manually
    public function completeOrder($orderId, $userId) {
        $order = $this->orderModel->getOrderById($orderId);
        
        if (!$order) {
            return (object) ['success' => false, 'message' => 'Pesanan tidak ditemukan.'];
        }

        // Check if user is the buyer
        if ($order->buyer_id != $userId) {
            return (object) ['success' => false, 'message' => 'Anda tidak memiliki akses.'];
        }

        if ($order->status == 'Dikirim') {
            if ($this->orderModel->updateStatus($orderId, 'Selesai')) {
                $items = $this->orderModel->getOrderItems($orderId);
                
                if (!empty($items)) {
                    $seller_id = $items[0]->seller_id;
                    $this->notificationService->notifyCompletion($order, $seller_id);
                    $this->revenueService->distributeSellerRevenue($items);
                }
                
                return (object) ['success' => true, 'message' => 'Pesanan berhasil diselesaikan.'];
            } else {
                return (object) ['success' => false, 'message' => 'Gagal menyelesaikan pesanan.'];
            }
        } else {
            return (object) ['success' => false, 'message' => 'Pesanan belum bisa diselesaikan.'];
        }
    }
}
?>
