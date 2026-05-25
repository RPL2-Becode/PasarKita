<?php
/**
 * Wishlist Model
 */
class Wishlist_model {
    private $table = 'wishlists';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get user wishlist
    public function getUserWishlist($user_id) {
        $this->db->query('
            SELECT w.id as wishlist_id, p.*, c.name as category_name
            FROM ' . $this->table . ' w
            JOIN products p ON w.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE w.user_id = :user_id
            ORDER BY w.created_at DESC
        ');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Check if product is in user wishlist
    public function isInWishlist($user_id, $product_id) {
        $this->db->query('SELECT id FROM ' . $this->table . ' WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Add to wishlist
    public function add($user_id, $product_id) {
        if ($this->isInWishlist($user_id, $product_id)) {
            return false;
        }

        $this->db->query('INSERT INTO ' . $this->table . ' (user_id, product_id) VALUES(:user_id, :product_id)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);

        return $this->db->execute();
    }

    // Remove from wishlist
    public function remove($user_id, $product_id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }
}
?>
