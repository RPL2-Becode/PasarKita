<?php
require_once '../app/models/Chat_model.php';

class OrderNotificationService {
    private $chatModel;

    public function __construct() {
        $this->chatModel = new Chat_model();
    }

    public function notifyCompletion($order, $sellerId) {
        $message = "Halo! Pesanan Anda (#{$order->id}) telah diselesaikan. Terima kasih!";
        $this->send($sellerId, $order->buyer_id, $order->id, $message);
    }

    public function notifyAutoCompletion($order, $sellerId) {
        $message = "Halo! Pesanan Anda (#{$order->id}) telah diselesaikan secara otomatis karena telah melewati batas waktu 2 hari sejak dikirim. Silakan berikan nilai/ulasan untuk produk kami!";
        $this->send($sellerId, $order->buyer_id, $order->id, $message);
    }

    public function notifyStatusChange($order, $sellerId, string $newStatus) {
        $message = "Halo! Status pesanan Anda (#{$order->id}) diperbarui: {$newStatus}.";
        $this->send($sellerId, $order->buyer_id, $order->id, $message);
    }

    public function notifyCancellation($order, $sellerId, string $reason) {
        $message = "Halo! Pesanan Anda (#{$order->id}) telah dibatalkan. Alasan: {$reason}";
        $this->send($sellerId, $order->buyer_id, $order->id, $message);
    }

    private function send($senderId, $receiverId, $orderId, string $message) {
        $this->chatModel->sendMessage([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'product_id' => null,
            'order_id' => $orderId,
        ]);
    }
}
?>
