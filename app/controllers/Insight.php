<?php
/**
 * UMKM Insight Controller
 * Dashboard analytics for Pelapak
 */
class Insight extends Controller {
    private $orderModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || strtolower($_SESSION['user_role']) != 'pelapak') {
            header('location: /');
            exit;
        }
        $this->orderModel = $this->model('Order_model');
    }

    public function index() {
        $seller_id = $_SESSION['user_id'];
        
        $gross_revenue = $this->orderModel->getSellerRevenue($seller_id);
        $fee_deduction = $gross_revenue * 0.02;
        $net_revenue = $gross_revenue - $fee_deduction;
        
        $order_count = $this->orderModel->getSellerOrderCount($seller_id);
        $top_products = $this->orderModel->getSellerTopProducts($seller_id);

        $data = [
            'title' => 'UMKM Insight - PasarKita',
            'gross_revenue' => $gross_revenue,
            'fee_deduction' => $fee_deduction,
            'net_revenue' => $net_revenue,
            'order_count' => $order_count,
            'top_products' => $top_products
        ];

        $this->view('templates/header', $data);
        $this->view('marketplace/insight', $data);
        $this->view('templates/footer');
    }
}
?>
