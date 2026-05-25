<?php

class Wishlist extends Controller {
    public function __construct() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /users/login');
            exit;
        }
    }

    public function index() {
        $data['title'] = 'Wishlist Saya';
        $data['user'] = $this->model('User_model')->findUserByUsername($_SESSION['user_username']);
        $data['wishlist'] = $this->model('Wishlist_model')->getUserWishlist($_SESSION['user_id']);

        $this->view('templates/header', $data);
        $this->view('wishlist/index', $data);
        $this->view('templates/footer');
    }

    public function add($product_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SERVER['HTTP_REFERER'])) {
            $user_id = $_SESSION['user_id'];
            
            if ($this->model('Wishlist_model')->add($user_id, $product_id)) {
                flash('wishlist_message', 'Produk berhasil ditambahkan ke Wishlist', 'bg-green-100 text-green-700');
            } else {
                flash('wishlist_message', 'Produk sudah ada di Wishlist', 'bg-yellow-100 text-yellow-700');
            }
            
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function remove($product_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SERVER['HTTP_REFERER'])) {
            $user_id = $_SESSION['user_id'];
            
            if ($this->model('Wishlist_model')->remove($user_id, $product_id)) {
                flash('wishlist_message', 'Produk berhasil dihapus dari Wishlist', 'bg-green-100 text-green-700');
            } else {
                flash('wishlist_message', 'Produk gagal dihapus dari Wishlist', 'bg-red-100 text-red-700');
            }
            
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
