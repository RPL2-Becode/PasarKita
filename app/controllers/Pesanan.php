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

        $data = [
            'title' => 'Detail Pesanan ' . $order->id . ' - PasarKita',
            'order' => $order,
            'items' => $items
        ];

        $this->view('marketplace/pesanan_detail', $data);
    }
}
?>
