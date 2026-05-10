<?php
/**
 * Marketplace Controller (Public Catalog)
 * Browse products, search, filter by category
 */
class Marketplace extends Controller {
    protected $productModel;

    public function __construct() {
        $this->productModel = $this->model('Product_model');
    }

    // Browse all products with search and category filter
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        $category_id = isset($_GET['category']) ? $_GET['category'] : '';
        
        if (!empty($keyword)) {
            $products = $this->productModel->searchProducts($keyword);
        } elseif (!empty($category_id)) {
            $products = $this->productModel->getProductsByCategory($category_id);
        } else {
            $products = $this->productModel->getProducts();
        }

        $categories = $this->productModel->getCategories();

        $data = [
            'title' => 'Katalog Produk - PasarKita',
            'products' => $products,
            'categories' => $categories,
            'search' => $keyword,
            'selected_category' => $category_id
        ];

        $this->view('marketplace/catalog', $data);
    }

    // Product Detail
    public function detail($id) {
        $product = $this->productModel->getProductById($id);
        $data = [
            'title' => ($product ? $product->name : 'Produk') . ' - PasarKita',
            'product' => $product
        ];
        $this->view('marketplace/detail', $data);
    }
}
?>
