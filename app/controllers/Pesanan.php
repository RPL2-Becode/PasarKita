<?php
/**
 * Pesanan Controller
 * Handles order history for consumers
 */
class Pesanan extends Controller {
    protected $orderModel;

    public function __construct() {
        // Redirect if not logged in
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }
        $this->orderModel = $this->model('Order_model');
    }

    // Display order history for the logged-in user
    public function index() {
        $orders = $this->orderModel->getOrdersByBuyer($_SESSION['user_id']);
        
        foreach($orders as $order) {
            $order->items = $this->orderModel->getOrderItems($order->id);
        }
        
        $data = [
            'title' => 'Pesanan Saya - PasarKita',
            'orders' => $orders
        ];

        $this->view('marketplace/pesanan', $data);
    }

    // Detail Pesanan
    public function detail($id) {
        $order = $this->orderModel->getOrderById($id);
        
        // Security check: Make sure order belongs to this user
        if (!$order || $order->buyer_id != $_SESSION['user_id']) {
            header('location: /pesanan');
            exit();
        }

        $items = $this->orderModel->getOrderItems($id);
        $reviewModel = $this->model('Review_model');

        foreach($items as $item) {
            $item->has_reviewed = $reviewModel->hasReviewed($order->id, $item->product_id, $_SESSION['user_id']);
        }

        $data = [
            'title' => 'Detail Pesanan ' . $order->id . ' - PasarKita',
            'order' => $order,
            'items' => $items
        ];

        $this->view('marketplace/pesanan_detail', $data);
    }
    // Request Cancellation
    public function cancel($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->getOrderById($id);
            $reason = trim($_POST['reason'] ?? '');
            
            if ($order && $order->buyer_id == $_SESSION['user_id'] && in_array($order->status, ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas'])) {
                if ($this->orderModel->updateStatusAndReason($id, 'Pengajuan Pembatalan', $reason)) {
                    flash('pesanan_message', 'Pengajuan pembatalan berhasil dikirim. Menunggu konfirmasi toko / admin.', 'bg-yellow-100 text-yellow-700 border-yellow-400 border');
                } else {
                    flash('pesanan_message', 'Gagal mengajukan pembatalan.', 'bg-red-100 text-red-700 border-red-400 border');
                }
            } else {
                flash('pesanan_message', 'Pesanan ini tidak dapat dibatalkan.', 'bg-red-100 text-red-700 border-red-400 border');
            }
        }
        header('location: /pesanan/detail/' . $id);
    }

    // Request Return (Pengajuan Pengembalian)
    public function return_order($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->getOrderById($id);
            $reason = trim($_POST['reason'] ?? '');
            
            if ($order && $order->buyer_id == $_SESSION['user_id'] && in_array($order->status, ['Dikirim', 'Selesai'])) {
                if ($this->orderModel->updateStatusAndReason($id, 'Pengajuan Pengembalian', $reason)) {
                    flash('pesanan_message', 'Pengajuan pengembalian produk berhasil dikirim. Menunggu konfirmasi admin.', 'bg-yellow-100 text-yellow-700 border-yellow-400 border');
                } else {
                    flash('pesanan_message', 'Gagal mengajukan pengembalian.', 'bg-red-100 text-red-700 border-red-400 border');
                }
            } else {
                flash('pesanan_message', 'Pesanan ini tidak dapat dikembalikan.', 'bg-red-100 text-red-700 border-red-400 border');
            }
        }
        header('location: /pesanan/detail/' . $id);
    }

    // Confirm Order Completed
    public function complete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->getOrderById($id);
            
            // Check if order belongs to user and is eligible for completion (Dikirim)
            if ($order && $order->buyer_id == $_SESSION['user_id'] && $order->status == 'Dikirim') {
                if ($this->orderModel->updateStatus($id, 'Selesai')) {
                    // Automated chat from seller to buyer
                    $items = $this->orderModel->getOrderItems($id);
                    if ($order && !empty($items)) {
                        $chatModel = $this->model('Chat_model');
                        $seller_id = $items[0]->seller_id;
                        $chatMsg = "Halo! Pesanan Anda (#" . $id . ") telah diselesaikan. Terima kasih telah berbelanja di toko kami! Jangan lupa berikan ulasan Anda ya.";
                        $chatData = [
                            'sender_id' => $seller_id,
                            'receiver_id' => $order->buyer_id,
                            'message' => $chatMsg,
                            'product_id' => null,
                            'order_id' => $id
                        ];
                        $chatModel->sendMessage($chatData);
                    }

                    // Distribusi uang ke pelapak
                    $userModel = $this->model('User_model');
                    
                    foreach ($items as $item) {
                        if (!empty($item->seller_id)) {
                            $item_total = $item->price_at_purchase * $item->quantity;
                            // Sesuai aturan: Fee Marketplace 2% dipotong dari penjualan
                            $seller_revenue = $item_total - ($item_total * 0.02);
                            $userModel->addBalance($item->seller_id, $seller_revenue);
                        }
                    }
                    
                    flash('pesanan_message', 'Pesanan telah dikonfirmasi selesai. Dana telah diteruskan ke pelapak (dikurangi fee 2%).', 'bg-green-100 text-green-700 border-green-400 border');
                } else {
                    flash('pesanan_message', 'Gagal mengonfirmasi pesanan selesai.', 'bg-red-100 text-red-700 border-red-400 border');
                }
            } else {
                flash('pesanan_message', 'Tindakan ini tidak dapat dilakukan.', 'bg-red-100 text-red-700 border-red-400 border');
            }
        }
        header('location: /pesanan/detail/' . $id);
    }
}
?>
