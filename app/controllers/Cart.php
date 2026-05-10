<?php
/**
 * Cart Controller
 */
class Cart extends Controller {
    protected $productModel;

    public function __construct() {
        $this->productModel = $this->model('Product_model');
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index() {
        $data = [
            'cart' => $_SESSION['cart']
        ];
        $this->view('marketplace/cart', $data);
    }

    public function add($id) {
        $product = $this->productModel->getProductById($id);
        
        if ($product) {
            // Check if product already in cart
            $item_exists = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity']++;
                    $item_exists = true;
                    break;
                }
            }

            if (!$item_exists) {
                $_SESSION['cart'][] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => $product->image_url,
                    'quantity' => 1
                ];
            }

            flash('cart_message', 'Produk ditambahkan ke keranjang', 'bg-green-100 text-green-700');
            header('location: /marketplace');
        }
    }

    public function remove($id) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        header('location: /cart');
    }

    public function clear() {
        $_SESSION['cart'] = [];
        header('location: /cart');
    }
}
?>
