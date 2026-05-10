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

        $this->productModel = $this->model('Product_model');
    }

    // List products (For Pelapak)
    public function index() {
        // Get products by seller
        $products = $this->productModel->getProductsBySeller($_SESSION['user_id']);

        $data = [
            'products' => $products
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
}
?>
