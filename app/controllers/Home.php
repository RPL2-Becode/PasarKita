<?php
/**
 * Default Home Controller
 */
class Home extends Controller {
    protected $productModel;

    public function __construct() {
        $this->productModel = $this->model('Product_model');
    }

    public function index() {
        $products = $this->productModel->getProducts();

        $data = [
            'title' => 'PasarKita - Marketplace UMKM',
            'products' => $products
        ];
        $this->view('home/index', $data);
    }
}
?>
