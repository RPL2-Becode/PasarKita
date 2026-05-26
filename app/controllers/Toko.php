<?php
class Toko extends Controller {
    protected $userModel;
    protected $productModel;
    protected $reviewModel;

    public function __construct() {
        $this->userModel = $this->model('User_model');
        $this->productModel = $this->model('Product_model');
        $this->reviewModel = $this->model('Review_model');
    }

    public function index($username = '') {
        if (empty($username)) {
            header('location: /marketplace');
            exit;
        }

        $seller = $this->userModel->findUserByUsername($username);

        if (!$seller || $seller->role != 'pelapak') {
            header('location: /marketplace');
            exit;
        }

        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'semua';
        $sort = ($tab == 'terlaris') ? 'terlaris' : 'terbaru';
        
        $products = $this->productModel->getProductsBySeller($seller->id, $sort);
        $total_products = count($products);
        
        $total_rating = 0;
        $rating_count = 0;
        $top_sold_product_id = null;
        $max_sold = -1;
        
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = $this->model('Wishlist_model');
        }
        
        foreach ($products as $p) {
            if (isset($_SESSION['user_id'])) {
                $p->in_wishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $p->id);
            }
            if ($p->avg_rating > 0) {
                $total_rating += $p->avg_rating;
                $rating_count++;
            }
            if ($p->sold_count > $max_sold) {
                $max_sold = $p->sold_count;
                $top_sold_product_id = $p->id;
            }
        }
        
        // If max_sold is 0, no product is really "Terlaris" yet
        if ($max_sold == 0) {
            $top_sold_product_id = null;
        }

        $store_avg_rating = $rating_count > 0 ? number_format($total_rating / $rating_count, 1) : '--';

        $data = [
            'title' => ($seller->store_name ?? $seller->username) . ' - PasarKita',
            'seller' => $seller,
            'products' => $products,
            'total_products' => $total_products,
            'store_avg_rating' => $store_avg_rating,
            'top_sold_product_id' => $top_sold_product_id,
            'active_tab' => $tab
        ];

        $this->view('templates/header', $data);
        $this->view('toko/index', $data);
        $this->view('templates/footer');
    }
}
?>
