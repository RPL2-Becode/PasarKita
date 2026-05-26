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

    // Browse all products with search and advanced filters
    public function index() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category']) ? $_GET['category'] : '';
        $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
        $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
        $min_rating = isset($_GET['min_rating']) ? $_GET['min_rating'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';

        // Check if any filter is active
        $has_filters = !empty($keyword) || !empty($category_id) || $min_price !== '' || $max_price !== '' || !empty($min_rating) || $sort !== 'terbaru';

        if ($has_filters) {
            $filters = [
                'keyword' => $keyword,
                'category_id' => $category_id,
                'min_price' => $min_price,
                'max_price' => $max_price,
                'min_rating' => $min_rating,
                'sort' => $sort
            ];
            $products = $this->productModel->advancedSearch($filters);
        } else {
            $products = $this->productModel->getProducts();
        }

        $categories = $this->productModel->getCategories();

        // Check wishlist status if user is logged in
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = $this->model('Wishlist_model');
            foreach ($products as $p) {
                $p->in_wishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $p->id);
            }
        }

        $data = [
            'title' => 'Katalog Produk - PasarKita',
            'products' => $products,
            'categories' => $categories,
            'search' => $keyword,
            'selected_category' => $category_id,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'min_rating' => $min_rating,
            'sort' => $sort
        ];

        $this->view('marketplace/catalog', $data);
    }

    // Product Detail
    public function detail($id) {
        $product = $this->productModel->getProductById($id);
        $reviewModel = $this->model('Review_model');
        $userModel = $this->model('User_model');
        
        $seller = null;
        $seller_stats = ['total_products' => '--', 'avg_rating' => '--'];
        
        if ($product && !empty($product->seller_id)) {
            $seller = $userModel->getUserById($product->seller_id);
            
            // Calculate seller stats
            $seller_products = $this->productModel->getProductsBySeller($product->seller_id);
            $seller_stats['total_products'] = count($seller_products);
            
            $total_rating = 0;
            $rating_count = 0;
            foreach($seller_products as $sp) {
                if ($sp->avg_rating > 0) {
                    $total_rating += $sp->avg_rating;
                    $rating_count++;
                }
            }
            if ($rating_count > 0) {
                $seller_stats['avg_rating'] = number_format($total_rating / $rating_count, 1);
            }
        }
        
        $in_wishlist = false;
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = $this->model('Wishlist_model');
            $in_wishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $id);
        }
        
        $data = [
            'title' => ($product ? $product->name : 'Produk') . ' - PasarKita',
            'product' => $product,
            'seller' => $seller,
            'seller_stats' => $seller_stats,
            'in_wishlist' => $in_wishlist,
            'reviews' => $reviewModel->getReviewsByProductId($id),
            'ratingStats' => $reviewModel->getProductRatingStats($id)
        ];
        $this->view('marketplace/detail', $data);
    }
}
?>
