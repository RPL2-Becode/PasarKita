<?php
/**
 * Checkout Controller
 * Handles checkout processing and SmartBank payment integration
 */
class Checkout extends Controller {
    protected $orderModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }
        $this->orderModel = $this->model('Order_model');
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Calculate fees on server-side (Biaya Layanan - 2%)
            $subtotal = floatval($_POST['subtotal']);
            $fee_marketplace = $subtotal * 0.02;  // 2% fee as per planning
            $fee_shipping = 5000; // Flat rate as per planning
            $shipping_service = 'LogistikKita';
            $total_payment = $subtotal + $fee_marketplace + $fee_shipping;

            $data = [
                'buyer_id' => $_SESSION['user_id'],
                'subtotal' => $subtotal,
                'fee_marketplace' => $fee_marketplace,
                'fee_shipping' => $fee_shipping,
                'shipping_service' => $shipping_service,
                'total_payment' => $total_payment
            ];

            // 1. Create the order
            $order_id = $this->orderModel->createOrder($data);

            if ($order_id) {
                // 2. Add items to order_items
                foreach ($_SESSION['cart'] as $item) {
                    $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
                }

                // 3. Integration with SmartBank API
                $smartbank_result = $this->integrateSmartBankPayment($order_id, $total_payment);

                if ($smartbank_result['success']) {
                    // 4. Update status and SmartBank transaction ID
                    $this->orderModel->updateStatus($order_id, 'Menunggu Konfirmasi');
                    $this->orderModel->updateSmartBankTrxId($order_id, $smartbank_result['trx_id']);
                    
                    // 5. Reduce stock for each purchased product
                    $productModel = $this->model('Product_model');
                    foreach ($_SESSION['cart'] as $item) {
                        $productModel->reduceStock($item['id'], $item['quantity']);
                    }

                    // 6. Clear cart
                    $_SESSION['cart'] = [];
                    
                    $data['order_id'] = $order_id;
                    $data['smartbank_trx_id'] = $smartbank_result['trx_id'];
                    $this->view('marketplace/success', $data);
                } else {
                    // Payment failed - cancel order
                    $this->orderModel->updateStatus($order_id, 'Dibatalkan');
                    $error_msg = isset($smartbank_result['message']) ? $smartbank_result['message'] : 'Pembayaran gagal. Silakan coba lagi.';
                    flash('cart_message', $error_msg, 'bg-red-100 text-red-700');
                    header('location: /cart');
                }
            } else {
                die('Could not create order');
            }
        }
    }

    /**
     * SmartBank Payment Integration
     * 
     * In production: sends cURL request to SmartBank API endpoint
     * Currently: simulation that generates a mock transaction ID
     */
    private function integrateSmartBankPayment($order_id, $amount) {
        $userModel = $this->model('User_model');
        $user_id = $_SESSION['user_id'];
        
        // Attempt to deduct balance
        if ($userModel->deductBalance($user_id, $amount)) {
            $trx_id = 'SB-' . date('Ymd') . '-' . strtoupper(substr(md5($order_id . time()), 0, 8));
            return [
                'success' => true,
                'trx_id' => $trx_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Saldo SmartBank Anda tidak mencukupi untuk pembayaran ini.'
            ];
        }
    }
}
?>
