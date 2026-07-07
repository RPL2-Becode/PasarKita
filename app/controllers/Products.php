<?php
/**
 * Products Controller
 * CRUD for Pelapak (Seller) product management
 */
class Products extends Controller {
    protected $productModel;

    public function __construct() {
        // Redirect if not logged in
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }

        // Restrict to pelapak
        if (strtolower($_SESSION['user_role']) != 'pelapak') {
            header('location: /home');
            exit();
        }

        $this->productModel = $this->model('Product_model');
    }

    // List products (For Pelapak)
    public function index() {
        // Get products by seller
        $products = $this->productModel->getProductsBySeller($_SESSION['user_id']);
        $userModel = $this->model('User_model');
        $seller = $userModel->getUserById($_SESSION['user_id']);

        $data = [
            'title' => 'Dashboard Pelapak - PasarKita',
            'products' => $products,
            'seller' => $seller
        ];

        $this->view('products/index', $data);
    }

    // Add Product
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'seller_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'image_url' => '',
                'name_err' => '',
                'price_err' => '',
                'stock_err' => '',
                'categories' => $this->productModel->getCategories()
            ];

            // Validation
            if (empty($data['name'])) $data['name_err'] = 'Masukkan nama produk';
            if (empty($data['price'])) $data['price_err'] = 'Masukkan harga';
            if (empty($data['stock'])) $data['stock_err'] = 'Masukkan stok';

            // Handle File Upload
            if (!empty($_FILES['image']['name'])) {
                $target_dir = "uploads/";
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $data['image_url'] = '/uploads/' . $file_name;
                }
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['stock_err'])) {
                if ($this->productModel->addProduct($data)) {
                    flash('product_message', 'Produk berhasil ditambahkan', 'bg-green-100 text-green-700');
                    header('location: /products');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('products/add', $data);
            }

        } else {
            $data = [
                'name' => '',
                'description' => '',
                'price' => '',
                'stock' => '',
                'category_id' => '',
                'name_err' => '',
                'price_err' => '',
                'stock_err' => '',
                'categories' => $this->productModel->getCategories()
            ];
            $this->view('products/add', $data);
        }
    }

    // Edit Product
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $product = $this->productModel->getProductById($id);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'image_url' => $product->image_url,
                'name_err' => '',
                'price_err' => '',
                'stock_err' => '',
                'categories' => $this->productModel->getCategories()
            ];

            // Handle File Upload if new image
            if (!empty($_FILES['image']['name'])) {
                $target_dir = "uploads/";
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $data['image_url'] = '/uploads/' . $file_name;
                }
            }

            // Validation
            if (empty($data['name'])) $data['name_err'] = 'Masukkan nama produk';
            if (empty($data['price'])) $data['price_err'] = 'Masukkan harga';
            if (empty($data['stock'])) $data['stock_err'] = 'Masukkan stok';

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['stock_err'])) {
                if ($this->productModel->updateProduct($data)) {
                    flash('product_message', 'Produk berhasil diperbarui', 'bg-green-100 text-green-700');
                    header('location: /products');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('products/edit', $data);
            }
        } else {
            $product = $this->productModel->getProductById($id);
            // Check ownership
            if ($product->seller_id != $_SESSION['user_id']) {
                header('location: /products');
                exit();
            }

            $data = [
                'id' => $id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'image_url' => $product->image_url,
                'category_id' => $product->category_id,
                'name_err' => '',
                'price_err' => '',
                'stock_err' => '',
                'categories' => $this->productModel->getCategories()
            ];
            $this->view('products/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->productModel->getProductById($id);
            if ($product->seller_id == $_SESSION['user_id']) {
                if ($this->productModel->deleteProduct($id)) {
                    flash('product_message', 'Produk berhasil dihapus');
                    header('location: /products');
                }
            }
        }
    }

    // List incoming orders containing products owned by the logged-in pelapak
    public function orders() {
        $orderModel = $this->model('Order_model');
        $userModel = $this->model('User_model');
        
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
        
        if (!empty($status_filter)) {
            $orders = $orderModel->getOrdersBySellerAndStatus($_SESSION['user_id'], $status_filter);
        } else {
            $orders = $orderModel->getOrdersBySeller($_SESSION['user_id']);
        }
        
        // Fetch items and filter them to only show the seller's own products
        foreach($orders as $order) {
            $allItems = $orderModel->getOrderItems($order->id);
            $order->items = array_filter($allItems, function($item) {
                return $item->seller_id == $_SESSION['user_id'];
            });
        }
        
        $seller = $userModel->getUserById($_SESSION['user_id']);

        $data = [
            'title' => 'Kelola Pesanan Masuk - PasarKita',
            'orders' => $orders,
            'status_filter' => $status_filter,
            'seller' => $seller
        ];

        $this->view('products/orders', $data);
    }

    // Update order status (Specifically "Sedang Dikemas" and "Diserahkan ke Kurir") for pelapak
    public function update_order_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $order_id = trim($_POST['order_id']);
            $new_status = trim($_POST['status']);
            
            $orderModel = $this->model('Order_model');
            $items = $orderModel->getOrderItems($order_id);
            
            // Security verification: Check if order contains products belonging to this seller
            $belongsToSeller = false;
            foreach ($items as $item) {
                if ($item->seller_id == $_SESSION['user_id']) {
                    $belongsToSeller = true;
                    break;
                }
            }

            if (!$belongsToSeller) {
                flash('order_message', 'Anda tidak berwenang mengelola pesanan ini', 'bg-red-100 text-red-700 border-red-400 border');
                header('location: /products/orders');
                exit();
            }

            $valid_statuses = ['Sedang Dikemas', 'Diserahkan ke Kurir'];
            
            if (in_array($new_status, $valid_statuses)) {
                if ($orderModel->updateStatus($order_id, $new_status)) {
                    $msg = $new_status == 'Sedang Dikemas' 
                        ? 'Pesanan ' . $order_id . ' berhasil dikonfirmasi dan status diperbarui ke "' . $new_status . '"!'
                        : 'Pesanan ' . $order_id . ' berhasil diserahkan ke jasa pengiriman!';
                    flash('order_message', $msg, 'bg-green-100 text-green-700 border-green-400 border');
                    
                    // Automated chat update
                    $order = $orderModel->getOrderById($order_id);
                    if ($order) {
                        $notificationService = $this->service('OrderNotificationService');
                        $notificationService->notifyStatusChange($order, $_SESSION['user_id'], $new_status);
                    }
                } else {
                    flash('order_message', 'Gagal memperbarui status pesanan.', 'bg-red-100 text-red-700 border-red-400 border');
                }
            } else {
                flash('order_message', 'Status pembaruan tidak valid.', 'bg-red-100 text-red-700 border-red-400 border');
            }

            header('location: /products/orders');
        }
    }
}
?>
