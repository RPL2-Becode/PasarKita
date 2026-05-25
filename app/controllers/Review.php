<?php

class Review extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'product_id' => $_POST['product_id'],
                'user_id' => $_SESSION['user_id'],
                'order_id' => $_POST['order_id'],
                'rating' => $_POST['rating'],
                'comment' => htmlspecialchars($_POST['comment'])
            ];

            // Verify order belongs to user and is completed
            $orderModel = $this->model('Order_model');
            $order = $orderModel->getOrderById($data['order_id']);

            if (!$order || $order->buyer_id != $_SESSION['user_id'] || $order->status != 'Selesai') {
                flash('pesanan_message', 'Tidak dapat memberikan ulasan untuk pesanan ini.', 'bg-red-100 text-red-700');
                header('location: /pesanan/detail/' . $data['order_id']);
                exit();
            }

            $reviewModel = $this->model('Review_model');
            if ($reviewModel->hasReviewed($data['order_id'], $data['product_id'], $_SESSION['user_id'])) {
                flash('pesanan_message', 'Anda sudah mengulas produk ini di pesanan yang sama.', 'bg-yellow-100 text-yellow-700');
            } else {
                if ($reviewModel->addReview($data)) {
                    flash('pesanan_message', 'Terima kasih! Ulasan Anda berhasil disimpan.', 'bg-green-100 text-green-700');
                } else {
                    flash('pesanan_message', 'Gagal menyimpan ulasan.', 'bg-red-100 text-red-700');
                }
            }

            header('location: /pesanan/detail/' . $data['order_id']);
            exit;
        }
    }
}
