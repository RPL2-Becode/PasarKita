<?php
/**
 * Admin Controller
 * Dashboard Finansial, Monitoring Transaksi, Manajemen User
 * Accessible by: admin, operator
 */
class Admin extends Controller {
    protected $orderModel;
    protected $productModel;
    protected $userModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }

        // Only admin and operator can access
        if (!in_array($_SESSION['user_role'], ['admin', 'operator'])) {
            header('location: /home');
            exit();
        }

        $this->orderModel = $this->model('Order_model');
        $this->productModel = $this->model('Product_model');
        $this->userModel = $this->model('User_model');
    }

    /**
     * Dashboard Finansial (Admin only)
     */
    public function dashboard() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('location: /admin/orders');
            exit();
        }

        $data = [
            'title' => 'Dashboard Admin - PasarKita',
            'total_revenue' => $this->orderModel->getTotalRevenue(),
            'total_fees' => $this->orderModel->getTotalFees(),
            'total_orders' => $this->orderModel->getOrderCount(),
            'pending_orders' => $this->orderModel->getOrderCountByStatus('Menunggu Konfirmasi'),
            'shipped_orders' => $this->orderModel->getOrderCountByStatus('Dikirim'),
            'completed_orders' => $this->orderModel->getOrderCountByStatus('Selesai'),
            'cancelled_orders' => $this->orderModel->getOrderCountByStatus('Dibatalkan'),
            'recent_orders' => $this->orderModel->getAllOrders(),
            'total_users' => $this->userModel->getUserCount(),
            'total_products' => $this->productModel->getProductCount()
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Monitoring Transaksi (Admin & Operator)
     */
    public function orders() {
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';

        if (!empty($status_filter)) {
            $orders = $this->orderModel->getOrdersByStatus($status_filter);
        } else {
            $orders = $this->orderModel->getAllOrders();
        }

        $data = [
            'title' => 'Monitoring Transaksi - PasarKita',
            'orders' => $orders,
            'status_filter' => $status_filter
        ];

        $this->view('admin/orders', $data);
    }

    /**
     * Update Order Status (POST)
     */
    public function updatestatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $order_id = trim($_POST['order_id']);
            $new_status = trim($_POST['status']);

            $valid_statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan'];

            if (in_array($new_status, $valid_statuses)) {
                if ($this->orderModel->updateStatus($order_id, $new_status)) {
                    // Restore stock if order is cancelled
                    if ($new_status === 'Dibatalkan') {
                        $items = $this->orderModel->getOrderItems($order_id);
                        foreach ($items as $item) {
                            $this->productModel->increaseStock($item->product_id, $item->quantity);
                        }
                    }
                    flash('order_message', 'Status order ' . $order_id . ' berhasil diperbarui ke "' . $new_status . '"', 'bg-green-100 text-green-700');
                } else {
                    flash('order_message', 'Gagal memperbarui status order', 'bg-red-100 text-red-700');
                }
            }

            header('location: /admin/orders');
        }
    }

    /**
     * Update Resi Number (POST) - Phase 2
     */
    public function updateresi() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $order_id = trim($_POST['order_id']);
            $shipping_service = trim($_POST['shipping_service']);
            $resi_number = trim($_POST['resi_number']);

            if (!empty($resi_number) && !empty($shipping_service)) {
                $this->orderModel->updateResi($order_id, $shipping_service, $resi_number);
                $this->orderModel->updateStatus($order_id, 'Dikirim');
                flash('order_message', 'Resi ' . $shipping_service . ' ' . $resi_number . ' berhasil disimpan untuk order ' . $order_id, 'bg-green-100 text-green-700');
            } else {
                flash('order_message', 'Nomor resi dan jasa pengiriman tidak boleh kosong!', 'bg-red-100 text-red-700');
            }

            header('location: /admin/orders');
        }
    }

    /**
     * Order Detail
     */
    public function orderdetail($order_id) {
        $order = $this->orderModel->getOrderById($order_id);
        $items = $this->orderModel->getOrderItems($order_id);

        $data = [
            'title' => 'Detail Order ' . $order_id . ' - PasarKita',
            'order' => $order,
            'items' => $items
        ];

        $this->view('admin/order_detail', $data);
    }

    /**
     * Manajemen User (Admin only)
     */
    public function users() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('location: /admin/orders');
            exit();
        }

        $users = $this->userModel->getAllUsers();

        $data = [
            'title' => 'Manajemen User - PasarKita',
            'users' => $users
        ];

        $this->view('admin/users', $data);
    }

    /**
     * Delete User (Admin only, POST)
     */
    public function deleteuser($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            header('location: /admin/orders');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Prevent deleting self
            if ($id == $_SESSION['user_id']) {
                flash('user_message', 'Tidak bisa menghapus akun sendiri', 'bg-red-100 text-red-700');
            } else {
                if ($this->userModel->deleteUser($id)) {
                    flash('user_message', 'User berhasil dihapus', 'bg-green-100 text-green-700');
                } else {
                    flash('user_message', 'Gagal menghapus user', 'bg-red-100 text-red-700');
                }
            }
            header('location: /admin/users');
        }
    }

    /**
     * Update User Role (Admin only, POST)
     */
    public function updaterole() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('location: /admin/orders');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $user_id = trim($_POST['user_id']);
            $new_role = trim($_POST['role']);

            $valid_roles = ['consumen', 'admin', 'pelapak', 'operator'];

            if (in_array($new_role, $valid_roles)) {
                if ($this->userModel->updateRole($user_id, $new_role)) {
                    flash('user_message', 'Role user berhasil diperbarui', 'bg-green-100 text-green-700');
                } else {
                    flash('user_message', 'Gagal memperbarui role', 'bg-red-100 text-red-700');
                }
            }
            header('location: /admin/users');
        }
    }
}
?>
