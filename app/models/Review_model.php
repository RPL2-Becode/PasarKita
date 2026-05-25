<?php
/**
 * Review Model
 */
class Review_model {
    private $table = 'reviews';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get reviews for a product
    public function getReviewsByProductId($product_id) {
        $this->db->query('
            SELECT r.*, u.username 
            FROM ' . $this->table . ' r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = :product_id
            ORDER BY r.created_at DESC
        ');
        $this->db->bind(':product_id', $product_id);
        return $this->db->resultSet();
    }

    // Add a new review
    public function addReview($data) {
        $this->db->query('INSERT INTO ' . $this->table . ' (product_id, user_id, order_id, rating, comment) VALUES(:product_id, :user_id, :order_id, :rating, :comment)');
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':order_id', $data['order_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);

        return $this->db->execute();
    }

    // Check if user has already reviewed an order
    public function hasReviewed($order_id, $product_id, $user_id) {
        $this->db->query('SELECT id FROM ' . $this->table . ' WHERE order_id = :order_id AND product_id = :product_id AND user_id = :user_id');
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':user_id', $user_id);
        
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Get average rating and count for a product
    public function getProductRatingStats($product_id) {
        $this->db->query('SELECT AVG(rating) as avg_rating, COUNT(id) as review_count FROM ' . $this->table . ' WHERE product_id = :product_id');
        $this->db->bind(':product_id', $product_id);
        $result = $this->db->single();
        
        return [
            'avg_rating' => $result->avg_rating ? round($result->avg_rating, 1) : 0,
            'review_count' => $result->review_count ?? 0
        ];
    }
}
?>
